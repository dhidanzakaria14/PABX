<?php

namespace App\Http\Controllers;

use App\Models\TbmArea;
use App\Models\TbmBisnis;
use App\Models\TbmDataMasuk;
use App\Models\TbmDepartment;
use App\Models\TbmGroupDepartment;
use App\Models\TbmPrefix;
use App\Models\TbmProfil;
use App\Models\TbmSetting;
use App\Models\TbmTarif;
use App\Models\TbmZona;
use App\Models\TbdRate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    // Home / Dashboard
    // Home / Dashboard
    public function index(Request $request)
    {
        $profile = TbmProfil::first();
        $departments = TbmDepartment::with('group')->orderBy('extcode')->get();
        $zones = TbmZona::all();
        $settings = TbmSetting::all();
        $tarifs = TbmTarif::with('rates')->get();

        $dateMin = TbmDataMasuk::min('tglmasuk');
        $dateMax = TbmDataMasuk::max('tglmasuk');

        $isFiltered = $request->filled('start_date') 
            || $request->filled('end_date') 
            || $request->filled('ext') 
            || $request->filled('department_id') 
            || $request->filled('zone_id') 
            || $request->filled('search');

        $query = TbmDataMasuk::with(['department.group', 'setting', 'zonaRel', 'prefixRel', 'tarifRel']);

        if ($request->filled('start_date')) {
            $query->where('tglmasuk', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tglmasuk', '<=', $request->end_date);
        }
        if ($request->filled('ext')) {
            $query->where('ext_pemanggil', $request->ext);
        }
        if ($request->filled('department_id')) {
            $query->where('iddepartment', $request->department_id);
        }
        if ($request->filled('zone_id')) {
            $query->where('idzone', $request->zone_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_tujuan', 'like', "%{$search}%")
                  ->orWhere('ext_pemanggil', 'like', "%{$search}%")
                  ->orWhere('namadepartment', 'like', "%{$search}%")
                  ->orWhere('nourut', 'like', "%{$search}%");
            });
        }

        // Fast SQL aggregates without loading 50,000 Eloquent objects into memory
        $totalCalls = (clone $query)->count();
        $totalCost = (clone $query)->sum('subtotal') ?: 0;
        $totalDurationSec = (clone $query)->sum('durasi_detik') ?: 0;

        $records = (clone $query)->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $isSearched = true;

        return view('billing.index', compact(
            'profile',
            'departments',
            'zones',
            'settings',
            'tarifs',
            'records',
            'totalCalls',
            'totalCost',
            'totalDurationSec',
            'isSearched',
            'isFiltered',
            'dateMin',
            'dateMax'
        ));
    }

    // 1. Division Summary Report
    public function divisionSummary(Request $request)
    {
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        if (!$isSearched) {
            $reportData = [];
            $totalAll = [
                'call' => 0, 'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'cost' => 0, 'charge' => 0
            ];
            return view('reports.division_summary', compact('reportData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
        }

        $query = TbmDataMasuk::query();
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $calls = $query->get();

        // Group by division
        $divisions = TbmGroupDepartment::with(['departments.dataMasuk'])->get();

        $reportData = [];
        $no = 1;
        $totalAll = [
            'call' => 0, 'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'cost' => 0, 'charge' => 0
        ];

        foreach ($divisions as $div) {
            $deptIds = $div->departments->pluck('id')->toArray();
            $divCalls = $calls->whereIn('iddepartment', $deptIds);

            if ($divCalls->count() == 0 && $div->namagroup !== 'AP') {
                continue;
            }

            $iddCost = $divCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Internasional') || str_contains($c->zona ?? '', 'IDD'))->sum('subtotal');
            $nddCost = $divCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Interlokal') || str_contains($c->zona ?? '', 'SLJJ') || str_contains($c->zona ?? '', 'NDD'))->sum('subtotal');
            $cellCost = $divCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Seluler') || str_contains($c->zona ?? '', 'CELL'))->sum('subtotal');
            $lddCost = $divCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Lokal') || str_contains($c->zona ?? '', 'LOCAL'))->sum('subtotal');
            $totalCost = $divCalls->sum('subtotal');
            $totalCharge = $totalCost; // or with markup

            $reportData[] = [
                'no' => $no++,
                'division' => $div->namagroup,
                'call' => $divCalls->count(),
                'idd_cost' => $iddCost,
                'ndd_cost' => $nddCost,
                'cell_cost' => $cellCost,
                'ldd_cost' => $lddCost,
                'total_cost' => $totalCost,
                'total_charge' => $totalCharge,
            ];

            $totalAll['call'] += $divCalls->count();
            $totalAll['idd'] += $iddCost;
            $totalAll['ndd'] += $nddCost;
            $totalAll['cell'] += $cellCost;
            $totalAll['ldd'] += $lddCost;
            $totalAll['cost'] += $totalCost;
            $totalAll['charge'] += $totalCharge;
        }

        return view('reports.division_summary', compact('reportData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 2. Favourite Area Report
    public function favouriteArea(Request $request)
    {
        $areaCodeId = $request->input('area_code_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') 
            || ($request->filled('area_code_id') && $request->input('area_code_id') !== 'Any') 
            || $request->filled('from_date') 
            || $request->filled('to_date');

        $areas = TbmArea::all();

        if (!$isSearched) {
            $grouped = collect();
            $totalAll = [
                'call' => 0,
                'duration_sec' => 0,
                'total_charge' => 0,
                'duration' => '0:00:00'
            ];
            return view('reports.favourite_area', compact('areas', 'grouped', 'totalAll', 'areaCodeId', 'fromDate', 'toDate', 'isSearched'));
        }

        $query = TbmDataMasuk::query();
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);
        if ($areaCodeId && $areaCodeId !== 'Any') {
            $query->where('kodearea', $areaCodeId);
        }

        $calls = $query->get();

        $grouped = $calls->groupBy('kodearea')->map(function($items, $code) {
            $first = $items->first();
            $sec = $items->sum('durasi_detik');
            $h = floor($sec / 3600);
            $m = floor(($sec % 3600) / 60);
            $s = $sec % 60;
            return [
                'area_code' => $code ?: '021',
                'area_name' => $first->ketarea ?: ($first->zona ?: 'Lokal'),
                'call' => $items->count(),
                'duration_sec' => $sec,
                'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
                'total_charge' => $items->sum('subtotal')
            ];
        })->sortByDesc('call');

        $totalAll = [
            'call' => $calls->count(),
            'duration_sec' => $calls->sum('durasi_detik'),
            'total_charge' => $calls->sum('subtotal')
        ];
        $h = floor($totalAll['duration_sec'] / 3600);
        $m = floor(($totalAll['duration_sec'] % 3600) / 60);
        $s = $totalAll['duration_sec'] % 60;
        $totalAll['duration'] = sprintf('%d:%02d:%02d', $h, $m, $s);

        return view('reports.favourite_area', compact('areas', 'grouped', 'totalAll', 'areaCodeId', 'fromDate', 'toDate'));
    }

    // 3. Favourite Business Report
    public function favouriteBusiness(Request $request)
    {
        $businessName = $request->input('business_name');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') 
            || ($request->filled('business_name') && $request->input('business_name') !== 'Any') 
            || $request->filled('from_date') 
            || $request->filled('to_date');

        $businesses = TbmBisnis::all();

        if (!$isSearched) {
            $grouped = collect();
            $totalAll = [
                'call' => 0,
                'duration_sec' => 0,
                'total_cost' => 0,
                'duration' => '0:00:00'
            ];
            return view('reports.favourite_business', compact('businesses', 'grouped', 'totalAll', 'businessName', 'fromDate', 'toDate', 'isSearched'));
        }

        $query = TbmDataMasuk::query();
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $calls = $query->get();

        $grouped = $calls->groupBy('no_tujuan')->map(function($items, $dest) {
            $first = $items->first();
            $sec = $items->sum('durasi_detik');
            $h = floor($sec / 3600);
            $m = floor(($sec % 3600) / 60);
            $s = $sec % 60;
            return [
                'destination' => $dest,
                'area_name' => $first->ketarea ?: ($first->zona ?: 'Lokal'),
                'dist' => $first->kodearea ?: 'L1',
                'call' => $items->count(),
                'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
                'total_cost' => $items->sum('subtotal')
            ];
        })->sortByDesc('call');

        $totalAll = [
            'call' => $calls->count(),
            'duration_sec' => $calls->sum('durasi_detik'),
            'total_cost' => $calls->sum('subtotal')
        ];
        $h = floor($totalAll['duration_sec'] / 3600);
        $m = floor(($totalAll['duration_sec'] % 3600) / 60);
        $s = $totalAll['duration_sec'] % 60;
        $totalAll['duration'] = sprintf('%d:%02d:%02d', $h, $m, $s);

        return view('reports.favourite_business', compact('businesses', 'grouped', 'totalAll', 'businessName', 'fromDate', 'toDate', 'isSearched'));
    }

    // 4. Peak Time Report (24 Hours)
    public function peakTime(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        if (!$isSearched) {
            $hourlyData = [];
            $totalAll = [
                'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'duration_sec' => 0, 'cost' => 0
            ];
            return view('reports.peak_time', compact('hourlyData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
        }

        $query = TbmDataMasuk::query();
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $calls = $query->get();

        $hourlyData = [];
        $totalAll = [
            'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'duration_sec' => 0, 'cost' => 0
        ];

        for ($h = 0; $h < 24; $h++) {
            $hourStr = sprintf('%02d', $h);
            $hourLabel = "{$hourStr}:00";

            $hourCalls = $calls->filter(function($c) use ($hourStr) {
                return str_starts_with($c->jammasuk ?? '', $hourStr);
            });

            $iddCost = $hourCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Internasional') || str_contains($c->zona ?? '', 'IDD'))->sum('subtotal');
            $nddCost = $hourCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Interlokal') || str_contains($c->zona ?? '', 'SLJJ') || str_contains($c->zona ?? '', 'NDD'))->sum('subtotal');
            $cellCost = $hourCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Seluler') || str_contains($c->zona ?? '', 'CELL'))->sum('subtotal');
            $lddCost = $hourCalls->filter(fn($c) => str_contains($c->zona ?? '', 'Lokal') || str_contains($c->zona ?? '', 'LOCAL'))->sum('subtotal');
            $durationSec = $hourCalls->sum('durasi_detik');
            $totalCost = $hourCalls->sum('subtotal');

            $hourlyData[] = [
                'hh' => $hourLabel,
                'idd_cost' => $iddCost,
                'ndd_cost' => $nddCost,
                'cell_cost' => $cellCost,
                'ldd_cost' => $lddCost,
                'duration_sec' => $durationSec,
                'total_cost' => $totalCost,
            ];

            $totalAll['idd'] += $iddCost;
            $totalAll['ndd'] += $nddCost;
            $totalAll['cell'] += $cellCost;
            $totalAll['ldd'] += $lddCost;
            $totalAll['duration_sec'] += $durationSec;
            $totalAll['cost'] += $totalCost;
        }

        return view('reports.peak_time', compact('hourlyData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 5. Personal Favorite Dialed Number
    public function personalFavoriteDialed(Request $request)
    {
        $phoneId = $request->input('phone_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched');

        $departments = TbmDepartment::orderBy('extcode')->get();
        $selectedDept = $phoneId ? TbmDepartment::find($phoneId) : null;

        if (!$isSearched) {
            $grouped = collect();
            $totalAll = [
                'call' => 0,
                'cost' => 0
            ];
            return view('reports.personal_favorite_dialed', compact('departments', 'selectedDept', 'grouped', 'totalAll', 'phoneId', 'fromDate', 'toDate', 'isSearched'));
        }

        $query = TbmDataMasuk::query();
        if ($selectedDept) {
            $query->where('iddepartment', $selectedDept->id);
        }
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $calls = $query->get();

        $grouped = $calls->groupBy('no_tujuan')->map(function($items, $dialed) {
            $first = $items->first();
            $sec = $items->sum('durasi_detik');
            $h = floor($sec / 3600);
            $m = floor(($sec % 3600) / 60);
            $s = $sec % 60;

            // Map dist code matching screenshot
            $dist = 'L1';
            if (str_contains($first->ketarea ?? '', 'Internal')) $dist = 'X';
            elseif (str_contains($first->zona ?? '', 'Seluler')) $dist = 'C2';
            elseif (str_contains($first->zona ?? '', 'SLJJ')) $dist = 'C3';

            return [
                'dialed' => $dialed,
                'area_name' => $first->ketarea ?: ($first->zona ?: 'Lokal'),
                'dist' => $dist,
                'call' => $items->count(),
                'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
                'cost' => $items->sum('subtotal')
            ];
        })->sortByDesc('call');

        $totalAll = [
            'call' => $calls->count(),
            'cost' => $calls->sum('subtotal')
        ];

        return view('reports.personal_favorite_dialed', compact('departments', 'selectedDept', 'grouped', 'totalAll', 'phoneId', 'fromDate', 'toDate'));
    }

    // 6. Personal Summary Report
    public function personalSummary(Request $request)
    {
        $phoneId = $request->input('phone_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $search = $request->input('search');

        $departments = TbmDepartment::orderBy('extcode')->get();
        $selectedDept = $phoneId ? TbmDepartment::find($phoneId) : null;

        $dateMin = TbmDataMasuk::min('tglmasuk');
        $dateMax = TbmDataMasuk::max('tglmasuk');

        // Base query grouped per extension
        $query = DB::table('tbm_data_masuk as dm')
            ->leftJoin('tbm_department as d', 'd.extcode', '=', 'dm.ext_pemanggil')
            ->select(
                'dm.ext_pemanggil',
                DB::raw('COALESCE(MAX(d.extname), MAX(dm.namadepartment), "-") as user_name'),
                DB::raw('COALESCE(MAX(d.divisi), "-") as division'),
                DB::raw('COUNT(*) as total_call'),
                DB::raw('SUM(dm.durasi_detik) as total_sec'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%IDD%" OR dm.zona LIKE "%Internasional%" THEN dm.subtotal ELSE 0 END) as idd_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%NDD%" OR dm.zona LIKE "%Interlokal%" OR dm.zona LIKE "%SLJJ%" THEN dm.subtotal ELSE 0 END) as ndd_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%CELL%" OR dm.zona LIKE "%Seluler%" THEN dm.subtotal ELSE 0 END) as cell_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%LOCAL%" OR dm.zona LIKE "%Lokal%" THEN dm.subtotal ELSE 0 END) as ldd_cost'),
                DB::raw('SUM(dm.biaya) as total_cost'),
                DB::raw('SUM(dm.subtotal) as total_charge')
            )
            ->groupBy('dm.ext_pemanggil');

        if ($selectedDept) {
            $query->where(function($q) use ($selectedDept) {
                $q->where('dm.iddepartment', $selectedDept->id)
                  ->orWhere('dm.ext_pemanggil', $selectedDept->extcode);
            });
        }
        if ($fromDate) {
            $query->where('dm.tglmasuk', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('dm.tglmasuk', '<=', $toDate);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('dm.ext_pemanggil', 'like', "%{$search}%")
                  ->orWhere('dm.namadepartment', 'like', "%{$search}%");
            });
        }

        // Summary KPI / Totals for the entire filtered dataset
        $totalStats = DB::table('tbm_data_masuk as dm')
            ->when($selectedDept, function($q) use ($selectedDept) {
                $q->where(function($sub) use ($selectedDept) {
                    $sub->where('dm.iddepartment', $selectedDept->id)
                        ->orWhere('dm.ext_pemanggil', $selectedDept->extcode);
                });
            })
            ->when($fromDate, fn($q) => $q->where('dm.tglmasuk', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->where('dm.tglmasuk', '<=', $toDate))
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('dm.ext_pemanggil', 'like', "%{$search}%")
                        ->orWhere('dm.namadepartment', 'like', "%{$search}%");
                });
            })
            ->select(
                DB::raw('COUNT(DISTINCT dm.ext_pemanggil) as active_ext_count'),
                DB::raw('COUNT(*) as total_call'),
                DB::raw('SUM(dm.durasi_detik) as total_sec'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%IDD%" OR dm.zona LIKE "%Internasional%" THEN dm.subtotal ELSE 0 END) as idd_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%NDD%" OR dm.zona LIKE "%Interlokal%" OR dm.zona LIKE "%SLJJ%" THEN dm.subtotal ELSE 0 END) as ndd_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%CELL%" OR dm.zona LIKE "%Seluler%" THEN dm.subtotal ELSE 0 END) as cell_cost'),
                DB::raw('SUM(CASE WHEN dm.zona LIKE "%LOCAL%" OR dm.zona LIKE "%Lokal%" THEN dm.subtotal ELSE 0 END) as ldd_cost'),
                DB::raw('SUM(dm.biaya) as total_cost'),
                DB::raw('SUM(dm.subtotal) as total_charge')
            )
            ->first();

        $reportData = $query->orderByDesc('total_charge')->paginate(20)->withQueryString();

        return view('reports.personal_summary', compact(
            'departments',
            'selectedDept',
            'reportData',
            'totalStats',
            'phoneId',
            'fromDate',
            'toDate',
            'search',
            'dateMin',
            'dateMax'
        ));
    }

    // AJAX Billing Simulator
    public function simulate(Request $request)
    {
        $validated = $request->validate([
            'ext_id' => 'required|exists:tbm_department,id',
            'destination' => 'required|string',
            'duration_sec' => 'required|integer|min:1',
            'call_time' => 'nullable|string',
        ]);

        $dept = TbmDepartment::with('group')->findOrFail($validated['ext_id']);
        $destination = preg_replace('/[^0-9]/', '', $validated['destination']);
        $durationSec = (int)$validated['duration_sec'];
        $callTime = $validated['call_time'] ?? date('H:i:s');

        $prefixes = TbmPrefix::with('zona')->get();
        $matchedPrefix = null;
        $matchedLength = 0;

        foreach ($prefixes as $p) {
            if (str_starts_with($destination, $p->kodeprefix)) {
                if (strlen($p->kodeprefix) > $matchedLength) {
                    $matchedPrefix = $p;
                    $matchedLength = strlen($p->kodeprefix);
                }
            }
        }

        if (!$matchedPrefix) {
            $matchedPrefix = TbmPrefix::with('zona')->first();
        }

        $zone = $matchedPrefix->zona;
        $tarif = null;
        if ($zone) {
            if ($zone->kelompok == 'CELL') {
                $tarif = TbmTarif::where('ratecode', 'like', '%SELULER%')->first();
            } elseif ($zone->kelompok == 'LOCAL') {
                $tarif = TbmTarif::where('ratecode', 'like', '%LOKAL%')->first();
            } elseif ($zone->kelompok == 'IDD') {
                $tarif = TbmTarif::where('ratecode', 'like', '%INTERNASIONAL%')->first();
            } else {
                $tarif = TbmTarif::where('ratecode', 'like', '%SLJJ%')->first();
            }
        }
        if (!$tarif) {
            $tarif = TbmTarif::first();
        }

        $rate = TbdRate::where('idrate', $tarif->idtarif)
            ->where('mulai', '<=', $callTime)
            ->where('selesai', '>=', $callTime)
            ->first() ?? TbdRate::where('idrate', $tarif->idtarif)->first();

        $unitRate = $rate ? (float)$rate->tarifnormal : 500;
        $minDurasi = $tarif->mindurasi ?: 60;
        $pulsa = ceil($durationSec / $minDurasi);
        $biaya = $pulsa * $unitRate;
        $tambahan = round($biaya * 0.11, 2);
        $subtotal = $biaya + $tambahan;

        return response()->json([
            'success' => true,
            'ext' => $dept->extcode,
            'ext_name' => $dept->extname,
            'department' => $dept->group ? $dept->group->namagroup : $dept->divisi,
            'destination' => $destination,
            'prefix' => $matchedPrefix->kodeprefix,
            'prefix_ket' => $matchedPrefix->keterangan,
            'zone' => $zone ? $zone->namazone : 'Lokal',
            'tarif_name' => $tarif->ratecode,
            'unit_rate' => $unitRate,
            'duration_sec' => $durationSec,
            'pulsa' => $pulsa,
            'formula' => "{$pulsa} x " . number_format($unitRate, 0, ',', '.'),
            'biaya' => $biaya,
            'tambahan' => $tambahan,
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
        ]);
    }

    // Manual CDR Call Record Store
    public function storeCall(Request $request)
    {
        $validated = $request->validate([
            'ext_id' => 'required|exists:tbm_department,id',
            'destination' => 'required|string',
            'duration_sec' => 'required|integer|min:1',
        ]);

        $dept = TbmDepartment::with('group')->findOrFail($validated['ext_id']);
        $destination = preg_replace('/[^0-9]/', '', $validated['destination']);
        $durationSec = (int)$validated['duration_sec'];
        $now = Carbon::now();

        $prefixes = TbmPrefix::with('zona')->get();
        $matchedPrefix = null;
        $matchedLength = 0;
        foreach ($prefixes as $p) {
            if (str_starts_with($destination, $p->kodeprefix)) {
                if (strlen($p->kodeprefix) > $matchedLength) {
                    $matchedPrefix = $p;
                    $matchedLength = strlen($p->kodeprefix);
                }
            }
        }
        if (!$matchedPrefix) {
            $matchedPrefix = TbmPrefix::with('zona')->first();
        }
        $zone = $matchedPrefix->zona;

        $tarif = null;
        if ($zone) {
            if ($zone->kelompok == 'CELL') {
                $tarif = TbmTarif::where('ratecode', 'like', '%SELULER%')->first();
            } elseif ($zone->kelompok == 'LOCAL') {
                $tarif = TbmTarif::where('ratecode', 'like', '%LOKAL%')->first();
            } elseif ($zone->kelompok == 'IDD') {
                $tarif = TbmTarif::where('ratecode', 'like', '%INTERNASIONAL%')->first();
            } else {
                $tarif = TbmTarif::where('ratecode', 'like', '%SLJJ%')->first();
            }
        }
        if (!$tarif) {
            $tarif = TbmTarif::first();
        }

        $callTime = $now->format('H:i:s');
        $rate = TbdRate::where('idrate', $tarif->idtarif)
            ->where('mulai', '<=', $callTime)
            ->where('selesai', '>=', $callTime)
            ->first() ?? TbdRate::where('idrate', $tarif->idtarif)->first();

        $unitRate = $rate ? (float)$rate->tarifnormal : 500;
        $minDurasi = $tarif->mindurasi ?: 60;
        $pulsa = ceil($durationSec / $minDurasi);
        $biaya = $pulsa * $unitRate;
        $tambahan = round($biaya * 0.11, 2);
        $subtotal = $biaya + $tambahan;

        $h = floor($durationSec / 3600);
        $m = floor(($durationSec % 3600) / 60);
        $s = $durationSec % 60;
        $durasiStr = sprintf('%02d:%02d:%02d', $h, $m, $s);

        $nourut = 'CDR-' . str_pad((TbmDataMasuk::max('id') + 1), 4, '0', STR_PAD_LEFT);

        TbmDataMasuk::create([
            'kodeclient' => 'CLI-001',
            'kodetelp' => '01',
            'nourut' => $nourut,
            'ext_pemanggil' => $dept->extcode,
            'iddepartment' => $dept->id,
            'namadepartment' => $dept->extname,
            'noline' => 'CO-01',
            'tglmasuk' => $now->format('Y-m-d'),
            'jammasuk' => $now->format('H:i:s'),
            'durasi' => $durasiStr,
            'durasi_detik' => $durationSec,
            'kodeakses' => '9',
            'no_tujuan' => $destination,
            'no_trunk' => 'TRK-01',
            'idmesin' => 1,
            'namamesin' => 'Panasonic KX-TDE600 Server Utama',
            'idzone' => $zone ? $zone->idzone : null,
            'zona' => $zone ? $zone->namazone : 'Lokal',
            'idprefix' => $matchedPrefix->idprefix,
            'kodearea' => $matchedPrefix->kodeprefix,
            'ketarea' => $matchedPrefix->keterangan,
            'idrate' => $tarif->idtarif,
            'namarate' => $tarif->ratecode,
            'formularate' => "{$pulsa} x {$unitRate}",
            'biaya' => $biaya,
            'tambahan' => $tambahan,
            'subtotal' => $subtotal,
            'hitung' => '1',
            'st' => '1',
            'tglinsert' => $now->format('Y-m-d'),
            'idperusahaan' => 1,
            'namaperusahaan' => 'ANGKASA PURA',
            'pulsa' => (string)$pulsa,
            'gabung' => $now->format('Y/m/d H:i:s') . " {$dept->extcode} 01 {$destination} {$durasiStr} {$nourut}",
        ]);

        return back()->with('success', "Panggilan baru {$nourut} berhasil dicatat & dihitung biayanya (Total: Rp " . number_format($subtotal, 0, ',', '.') . ")!");
    }

    // 25 Tables Schema Viewer
    public function schema()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.mysql.database', 'pabx');
        $keyName = "Tables_in_{$dbName}";

        $foreignKeys = DB::select("
            SELECT 
                TABLE_NAME, 
                COLUMN_NAME, 
                CONSTRAINT_NAME, 
                REFERENCED_TABLE_NAME, 
                REFERENCED_COLUMN_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = ? AND REFERENCED_TABLE_NAME IS NOT NULL
            ORDER BY TABLE_NAME
        ", [$dbName]);

        $tableStats = [];
        foreach ($tables as $t) {
            $tableName = $t->$keyName ?? array_values((array)$t)[0];
            $count = DB::table($tableName)->count();
            $columns = DB::select("DESCRIBE `{$tableName}`");
            $tableStats[$tableName] = [
                'count' => $count,
                'columns' => $columns,
            ];
        }

        return view('billing.schema', compact('foreignKeys', 'tableStats', 'dbName'));
    }
}
