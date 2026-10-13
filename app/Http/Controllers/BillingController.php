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
    // Home / Dashboard (Modern Executive Analytics & Charts)
    public function index(Request $request)
    {
        $profile = TbmProfil::first();
        $departments = TbmDepartment::with('group')->orderBy('extcode')->get();
        $zones = TbmZona::all();
        $settings = TbmSetting::all();
        $tarifs = TbmTarif::with('rates')->get();

        // 1. High-Level KPIs
        $totalCalls = TbmDataMasuk::count();
        $totalCost = TbmDataMasuk::sum('subtotal') ?: 0;
        $totalDurationSec = TbmDataMasuk::sum('durasi_detik') ?: 0;
        $totalExtensions = TbmDepartment::count();
        $calculatedCalls = TbmDataMasuk::where('hitung', '>=', 1)->count();
        $uncalculatedCalls = $totalCalls - $calculatedCalls;

        $avgCostPerCall = $totalCalls > 0 ? round($totalCost / $totalCalls) : 0;
        $avgDurationSec = $totalCalls > 0 ? round($totalDurationSec / $totalCalls) : 0;

        // Formatted Duration (HH:MM:SS)
        $hours = floor($totalDurationSec / 3600);
        $minutes = floor(($totalDurationSec % 3600) / 60);
        $seconds = $totalDurationSec % 60;
        $formattedTotalDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $avgMin = floor($avgDurationSec / 60);
        $avgSec = $avgDurationSec % 60;
        $formattedAvgDuration = sprintf('%02d:%02d', $avgMin, $avgSec);

        // Date Range of System Data
        $dateMin = TbmDataMasuk::min('tglmasuk');
        $dateMax = TbmDataMasuk::max('tglmasuk');

        // 2. Monthly Trend (Volume Calls & Cost) - 12 Bulan Terakhir
        $monthlyQuery = DB::table('tbm_data_masuk')
            ->selectRaw('SUBSTRING(tglmasuk, 1, 7) as ym, COUNT(*) as calls, SUM(subtotal) as cost, SUM(durasi_detik) as duration')
            ->whereNotNull('tglmasuk')
            ->where('tglmasuk', '!=', '')
            ->groupBy('ym')
            ->orderBy('ym', 'desc')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        $monthLabels = [];
        $monthCalls = [];
        $monthCosts = [];
        $monthCostsInK = [];

        foreach ($monthlyQuery as $item) {
            $monthLabels[] = date('M Y', strtotime($item->ym . '-01'));
            $monthCalls[] = (int)$item->calls;
            $monthCosts[] = (int)$item->cost;
            $monthCostsInK[] = round($item->cost / 1000, 1);
        }

        // 3. Category Distribution (Lokal, Interlokal NDD, Seluler GSM, Internasional IDD, Internal)
        $categoryQuery = DB::table('tbm_data_masuk')
            ->selectRaw("
                CASE 
                    WHEN UPPER(cell) = 'CELL' OR UPPER(idd) = 'CELL' THEN 'Seluler (GSM)'
                    WHEN UPPER(idd) = 'NDD' THEN 'Interlokal (NDD/SLJJ)'
                    WHEN UPPER(idd) = 'LDD' THEN 'Lokal (LDD)'
                    WHEN UPPER(idd) = 'IDD' THEN 'Internasional (IDD/SLI)'
                    ELSE 'Internal / Direct'
                END as cat_name,
                COUNT(*) as calls,
                SUM(subtotal) as cost
            ")
            ->groupBy('cat_name')
            ->orderByDesc('calls')
            ->get();

        $catLabels = [];
        $catCalls = [];
        $catCosts = [];
        $catColors = [
            'Lokal (LDD)' => '#0288d1',
            'Internal / Direct' => '#64748b',
            'Interlokal (NDD/SLJJ)' => '#f59e0b',
            'Seluler (GSM)' => '#10b981',
            'Internasional (IDD/SLI)' => '#8b5cf6',
        ];
        $chartCatColors = [];

        foreach ($categoryQuery as $cat) {
            $catLabels[] = $cat->cat_name;
            $catCalls[] = (int)$cat->calls;
            $catCosts[] = (int)$cat->cost;
            $chartCatColors[] = $catColors[$cat->cat_name] ?? '#94a3b8';
        }

        // 4. Hourly Peak Traffic (24 Jam 00-23)
        $hourlyRaw = DB::table('tbm_data_masuk')
            ->selectRaw('SUBSTRING(jammasuk, 1, 2) as hour, COUNT(*) as calls')
            ->whereNotNull('jammasuk')
            ->where('jammasuk', '!=', '')
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->pluck('calls', 'hour')
            ->toArray();

        $hourLabels = [];
        $hourCalls = [];
        for ($h = 0; $h < 24; $h++) {
            $hKey = sprintf('%02d', $h);
            $hourLabels[] = $hKey . ':00';
            $hourCalls[] = (int)($hourlyRaw[$hKey] ?? 0);
        }

        // 5. Top 5 Most Active Extensions
        $topExtensions = DB::table('tbm_data_masuk')
            ->selectRaw('ext_pemanggil, namadepartment, COUNT(*) as total_calls, SUM(durasi_detik) as total_duration, SUM(subtotal) as total_cost')
            ->whereNotNull('ext_pemanggil')
            ->where('ext_pemanggil', '!=', '')
            ->groupBy('ext_pemanggil', 'namadepartment')
            ->orderByDesc('total_calls')
            ->limit(5)
            ->get();

        // 6. Top 5 Cost Spender Extensions
        $topCostExtensions = DB::table('tbm_data_masuk')
            ->selectRaw('ext_pemanggil, namadepartment, COUNT(*) as total_calls, SUM(durasi_detik) as total_duration, SUM(subtotal) as total_cost')
            ->whereNotNull('ext_pemanggil')
            ->where('ext_pemanggil', '!=', '')
            ->groupBy('ext_pemanggil', 'namadepartment')
            ->orderByDesc('total_cost')
            ->limit(5)
            ->get();

        // 7. Recent 10 Calls (Live Monitor CDR)
        $recentCalls = TbmDataMasuk::with(['department'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('billing.index', compact(
            'profile',
            'departments',
            'zones',
            'settings',
            'tarifs',
            'totalCalls',
            'totalCost',
            'totalDurationSec',
            'formattedTotalDuration',
            'totalExtensions',
            'calculatedCalls',
            'uncalculatedCalls',
            'avgCostPerCall',
            'avgDurationSec',
            'formattedAvgDuration',
            'dateMin',
            'dateMax',
            'monthLabels',
            'monthCalls',
            'monthCosts',
            'monthCostsInK',
            'catLabels',
            'catCalls',
            'catCosts',
            'chartCatColors',
            'categoryQuery',
            'hourLabels',
            'hourCalls',
            'topExtensions',
            'topCostExtensions',
            'recentCalls'
        ));
    }

    // 1. Division Summary Report
    public function divisionSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        // Fetch groups/divisions from database
        $groups = DB::table('tbm_group_department')
            ->where(function($q) {
                $q->whereNull('tgldelete')->orWhere('st', 1);
            })
            ->orderBy('idgroup', 'desc')
            ->get();

        if ($groups->isEmpty()) {
            $groups = collect([
                (object)['namagroup' => 'AP', 'namadivisi' => 'AP', 'idgroup' => 16],
                (object)['namagroup' => 'PABX', 'namadivisi' => 'PABX', 'idgroup' => 15],
            ]);
        }

        $reportData = [];
        $totalAll = [
            'call' => 0, 'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'cost' => 0, 'charge' => 0
        ];

        $no = 1;
        foreach ($groups as $group) {
            $divName = $group->namagroup ?: ($group->namadivisi ?: 'AP');

            $query = DB::table('tbm_data_masuk as dm')
                ->leftJoin('tbm_department as d', 'dm.iddepartment', '=', 'd.id');

            if ($divName === 'AP') {
                $query->where(function($q) {
                    $q->where('d.divisi', 'AP')
                      ->orWhere('dm.iddepartment', '>', 0);
                });
            } else {
                $query->where(function($q) use ($divName) {
                    $q->where('d.divisi', $divName)
                      ->orWhereNull('dm.iddepartment')
                      ->orWhere('dm.iddepartment', '<=', 0);
                });
            }

            if ($fromDate) $query->where('dm.tglmasuk', '>=', $fromDate);
            if ($toDate) $query->where('dm.tglmasuk', '<=', $toDate);

            $agg = $query->select(
                DB::raw('COUNT(*) as total_calls'),
                DB::raw('SUM(CASE WHEN dm.idd = "IDD" THEN dm.subtotal ELSE 0 END) as idd_cost'),
                DB::raw('SUM(CASE WHEN dm.idd = "NDD" THEN dm.subtotal ELSE 0 END) as ndd_cost'),
                DB::raw('SUM(CASE WHEN dm.cell = "CELL" OR dm.idd = "CELL" THEN dm.subtotal ELSE 0 END) as cell_cost'),
                DB::raw('SUM(CASE WHEN dm.idd = "LDD" OR dm.idd = "LOCAL" THEN dm.subtotal ELSE 0 END) as ldd_cost'),
                DB::raw('SUM(dm.subtotal) as total_cost')
            )->first();

            $calls = $agg ? (int)$agg->total_calls : 0;
            $idd = $agg ? (float)$agg->idd_cost : 0;
            $ndd = $agg ? (float)$agg->ndd_cost : 0;
            $cell = $agg ? (float)$agg->cell_cost : 0;
            $ldd = $agg ? (float)$agg->ldd_cost : 0;
            $cost = $agg ? (float)$agg->total_cost : 0;

            $reportData[] = [
                'no' => $no++,
                'division' => $divName,
                'call' => $calls,
                'idd_cost' => $idd,
                'ndd_cost' => $ndd,
                'cell_cost' => $cell,
                'ldd_cost' => $ldd,
                'total_cost' => $cost,
                'total_charge' => $cost,
            ];

            $totalAll['call'] += $calls;
            $totalAll['idd'] += $idd;
            $totalAll['ndd'] += $ndd;
            $totalAll['cell'] += $cell;
            $totalAll['ldd'] += $ldd;
            $totalAll['cost'] += $cost;
            $totalAll['charge'] += $cost;
        }

        return view('reports.division_summary', compact('reportData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 2. Favourite Area Report
    public function favouriteArea(Request $request)
    {
        $areaCodeId = $request->input('area_code_id', 'Any');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') 
            || ($request->filled('area_code_id') && $areaCodeId !== 'Any') 
            || $request->filled('from_date') 
            || $request->filled('to_date');

        $distinctAreas = DB::table('tbm_data_masuk')
            ->whereNotNull('kodearea')
            ->where('kodearea', '!=', '')
            ->select('kodearea', DB::raw('MAX(ketarea) as ketarea'))
            ->groupBy('kodearea')
            ->orderBy('kodearea', 'asc')
            ->get();

        $query = DB::table('tbm_data_masuk');
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);
        if ($areaCodeId && $areaCodeId !== 'Any') {
            $query->where('kodearea', $areaCodeId);
        }

        $rawItems = $query->select(
            'kodearea',
            DB::raw('MAX(ketarea) as ketarea'),
            DB::raw('COUNT(*) as call_count'),
            DB::raw('SUM(durasi_detik) as duration_sec'),
            DB::raw('SUM(subtotal) as total_charge')
        )
        ->groupBy('kodearea')
        ->orderByDesc('call_count')
        ->limit(100)
        ->get();

        $grouped = $rawItems->map(function($item) {
            $sec = (int) $item->duration_sec;
            $h = floor($sec / 3600);
            $m = floor(($sec % 3600) / 60);
            $s = $sec % 60;
            return [
                'area_code' => $item->kodearea ?: '-',
                'area_name' => $item->ketarea ?: 'Internal Call',
                'call' => $item->call_count,
                'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
                'total_charge' => $item->total_charge
            ];
        });

        $totalCalls = $rawItems->sum('call_count');
        $totalDurationSec = $rawItems->sum('duration_sec');
        $totalCharge = $rawItems->sum('total_charge');

        $h = floor($totalDurationSec / 3600);
        $m = floor(($totalDurationSec % 3600) / 60);
        $s = $totalDurationSec % 60;

        $totalAll = [
            'call' => $totalCalls,
            'duration_sec' => $totalDurationSec,
            'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
            'total_charge' => $totalCharge
        ];

        return view('reports.favourite_area', compact('distinctAreas', 'grouped', 'totalAll', 'areaCodeId', 'fromDate', 'toDate', 'isSearched'));
    }

    // 3. Favourite Business Report
    public function favouriteBusiness(Request $request)
    {
        $businessName = $request->input('business_name', 'Any');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') 
            || ($request->filled('business_name') && $businessName !== 'Any') 
            || $request->filled('from_date') 
            || $request->filled('to_date');

        $distinctBusinesses = TbmBisnis::whereNull('tgldelete')->get();

        $query = DB::table('tbm_data_masuk');
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);
        
        if ($businessName && $businessName !== 'Any') {
            $biz = TbmBisnis::where('nama', $businessName)->first();
            $phone = $biz ? $biz->notelp : null;
            $query->where(function($q) use ($businessName, $phone) {
                if ($phone) {
                    $q->where('no_tujuan', $phone)
                      ->orWhere('no_tujuan', 'like', "%{$phone}%");
                }
                $q->orWhere('namaperusahaan', $businessName);
            });
        }

        $rawItems = $query->select(
            'no_tujuan',
            DB::raw('MAX(ketarea) as ketarea'),
            DB::raw('MAX(kodearea) as kodearea'),
            DB::raw('COUNT(*) as call_count'),
            DB::raw('SUM(durasi_detik) as duration_sec'),
            DB::raw('SUM(subtotal) as total_cost')
        )
        ->groupBy('no_tujuan')
        ->orderByDesc('call_count')
        ->limit(100)
        ->get();

        $grouped = $rawItems->map(function($item) {
            $sec = (int) $item->duration_sec;
            $h = floor($sec / 3600);
            $m = floor(($sec % 3600) / 60);
            $s = $sec % 60;
            return [
                'destination' => $item->no_tujuan ?: '-',
                'area_name' => $item->ketarea ?: 'Internal Call',
                'dist' => $item->kodearea ?: '-',
                'call' => $item->call_count,
                'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
                'total_cost' => $item->total_cost
            ];
        });

        $totalCalls = $rawItems->sum('call_count');
        $totalDurationSec = $rawItems->sum('duration_sec');
        $totalCost = $rawItems->sum('total_cost');

        $h = floor($totalDurationSec / 3600);
        $m = floor(($totalDurationSec % 3600) / 60);
        $s = $totalDurationSec % 60;

        $totalAll = [
            'call' => $totalCalls,
            'duration_sec' => $totalDurationSec,
            'duration' => sprintf('%d:%02d:%02d', $h, $m, $s),
            'total_cost' => $totalCost
        ];

        return view('reports.favourite_business', compact('distinctBusinesses', 'grouped', 'totalAll', 'businessName', 'fromDate', 'toDate', 'isSearched'));
    }

    // 4. Peak Time Report (24 Hours)
    public function peakTime(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $query = \Illuminate\Support\Facades\DB::table('tbm_data_masuk');
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $hourlyRecords = $query->select(
            \Illuminate\Support\Facades\DB::raw('SUBSTRING(jammasuk, 1, 2) as hour_prefix'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN idd = "IDD" THEN subtotal ELSE 0 END) as idd_cost'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN idd = "NDD" THEN subtotal ELSE 0 END) as ndd_cost'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN cell = "CELL" OR idd = "CELL" THEN subtotal ELSE 0 END) as cell_cost'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN idd = "LDD" OR idd = "LOCAL" THEN subtotal ELSE 0 END) as ldd_cost'),
            \Illuminate\Support\Facades\DB::raw('SUM(durasi_detik) as duration_sec'),
            \Illuminate\Support\Facades\DB::raw('SUM(subtotal) as total_cost')
        )
        ->whereNotNull('jammasuk')
        ->where('jammasuk', '!=', '')
        ->groupBy('hour_prefix')
        ->get()
        ->keyBy('hour_prefix');

        $hourlyMap = $hourlyRecords->toArray();

        $hourlyData = [];
        $totalAll = [
            'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'duration_sec' => 0, 'cost' => 0
        ];

        for ($h = 0; $h < 24; $h++) {
            $hourStr = sprintf('%02d', $h);
            $hourLabel = "{$hourStr}:00";

            $record = $hourlyMap[$hourStr] ?? null;

            $iddCost = $record ? (float)$record->idd_cost : 0;
            $nddCost = $record ? (float)$record->ndd_cost : 0;
            $cellCost = $record ? (float)$record->cell_cost : 0;
            $lddCost = $record ? (float)$record->ldd_cost : 0;
            $durSec = $record ? (int)$record->duration_sec : 0;
            $cost = $record ? (float)$record->total_cost : 0;

            $hourlyData[] = [
                'hh' => $hourLabel,
                'idd_cost' => $iddCost,
                'ndd_cost' => $nddCost,
                'cell_cost' => $cellCost,
                'ldd_cost' => $lddCost,
                'duration_sec' => $durSec,
                'total_cost' => $cost,
            ];

            $totalAll['idd'] += $iddCost;
            $totalAll['ndd'] += $nddCost;
            $totalAll['cell'] += $cellCost;
            $totalAll['ldd'] += $lddCost;
            $totalAll['duration_sec'] += $durSec;
            $totalAll['cost'] += $cost;
        }

        return view('reports.peak_time', compact('hourlyData', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 5. Personal Favorite Dialed Number
    public function personalFavoriteDialed(Request $request)
    {
        $phoneId = $request->input('phone_id');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $departments = TbmDepartment::where(function($q) {
            $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
        })->orderBy('extcode')->get();

        if (!$phoneId && $departments->isNotEmpty()) {
            $defaultDept = $departments->firstWhere('extcode', '569') ?: ($departments->firstWhere('extcode', '308') ?: $departments->first());
            $phoneId = $defaultDept ? $defaultDept->extcode : null;
        }

        $selectedDept = $departments->firstWhere('extcode', $phoneId);

        // Fetch distinct dialed numbers for this Phone ID from history
        $distinctItems = DB::table('tbm_data_masuk')
            ->where('ext_pemanggil', $phoneId)
            ->whereNotNull('no_tujuan')
            ->where('no_tujuan', '!=', '')
            ->select(
                'no_tujuan as dialed',
                DB::raw('MAX(ketarea) as area_name'),
                DB::raw('MAX(kodearea) as dist')
            )
            ->groupBy('no_tujuan')
            ->orderBy('no_tujuan')
            ->get();

        // Aggregate calls for this Phone ID (filtered by dates if provided, or all-time if empty)
        $q = DB::table('tbm_data_masuk')->where('ext_pemanggil', $phoneId);
        if ($fromDate) $q->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $q->where('tglmasuk', '<=', $toDate);

        $aggMap = $q->select(
            'no_tujuan',
            DB::raw('COUNT(*) as call_count'),
            DB::raw('SUM(durasi_detik) as duration_sec'),
            DB::raw('SUM(subtotal) as cost')
        )
        ->groupBy('no_tujuan')
        ->get()
        ->keyBy('no_tujuan');

        $rows = $distinctItems->map(function($item) use ($aggMap) {
            $dist = $item->dist ?: '-';
            $area = $item->area_name ?: 'Internal Call';
            if (stripos($area, 'Internal') !== false) $dist = 'X';
            elseif (stripos($area, 'Jakarta') !== false) $dist = 'C3';
            elseif (stripos($area, 'Surabaya') !== false || stripos($area, 'Denpasar') !== false) $dist = 'C2';
            elseif ($item->dialed == '1500800' || $item->dialed == '1500803') $dist = 'L1';

            $agg = $aggMap[$item->dialed] ?? null;
            $call = $agg ? (int)$agg->call_count : 0;
            $dur = $agg ? (int)$agg->duration_sec : 0;
            $cost = $agg ? (float)$agg->cost : 0;

            return [
                'dialed' => $item->dialed,
                'area_name' => $area,
                'dist' => $dist,
                'call' => $call,
                'duration' => $dur,
                'cost' => $cost
            ];
        });

        $totalAll = [
            'call' => $rows->sum('call'),
            'duration' => $rows->sum('duration'),
            'cost' => $rows->sum('cost')
        ];

        return view('reports.personal_favorite_dialed', compact(
            'departments', 'selectedDept', 'rows', 'totalAll',
            'phoneId', 'fromDate', 'toDate', 'isSearched'
        ));
    }

    // 6. Personal Summary Report
    public function personalSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        // Fetch paginated departments for smooth performance
        $departments = TbmDepartment::where(function($q) {
            $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
        })->orderBy('extcode')->paginate(30)->withQueryString();

        // Get overall last call date per extension (independent of filter dates, matching photo)
        $extCodes = $departments->pluck('extcode')->toArray();
        $lastCallMap = DB::table('tbm_data_masuk')
            ->whereIn('ext_pemanggil', $extCodes)
            ->select('ext_pemanggil', DB::raw('MAX(CONCAT(tglmasuk, " ", jammasuk)) as last_dt'))
            ->groupBy('ext_pemanggil')
            ->pluck('last_dt', 'ext_pemanggil');

        // Aggregations within the selected date range
        $query = DB::table('tbm_data_masuk')->whereIn('ext_pemanggil', $extCodes);
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $aggMap = $query->select(
            'ext_pemanggil',
            DB::raw('COUNT(*) as total_call'),
            DB::raw('SUM(durasi_detik) as total_dur'),
            DB::raw('SUM(CASE WHEN idd = "IDD" THEN subtotal ELSE 0 END) as idd_cost'),
            DB::raw('SUM(CASE WHEN idd = "NDD" THEN subtotal ELSE 0 END) as ndd_cost'),
            DB::raw('SUM(CASE WHEN cell = "CELL" OR idd = "CELL" THEN subtotal ELSE 0 END) as cell_cost'),
            DB::raw('SUM(CASE WHEN idd = "LDD" OR idd = "LOCAL" THEN subtotal ELSE 0 END) as ldd_cost'),
            DB::raw('SUM(subtotal) as total_cost')
        )
        ->groupBy('ext_pemanggil')
        ->get()
        ->keyBy('ext_pemanggil');

        $rows = [];
        $totalAll = [
            'call' => 0, 'dur' => 0, 'idd' => 0, 'ndd' => 0, 'cell' => 0, 'ldd' => 0, 'cost' => 0
        ];

        $startNo = $departments->firstItem() ?: 1;
        $no = $startNo;
        foreach ($departments as $dept) {
            $agg = $aggMap[$dept->extcode] ?? null;

            $call = $agg ? (int)$agg->total_call : 0;
            $dur = $agg ? (int)$agg->total_dur : 0;
            $idd = $agg ? (float)$agg->idd_cost : 0;
            $ndd = $agg ? (float)$agg->ndd_cost : 0;
            $cell = $agg ? (float)$agg->cell_cost : 0;
            $ldd = $agg ? (float)$agg->ldd_cost : 0;
            $cost = $agg ? (float)$agg->total_cost : 0;
            $lastCall = $lastCallMap[$dept->extcode] ?? '';

            $rows[] = [
                'no' => $no++,
                'ext' => $dept->extcode,
                'nama' => $dept->extname,
                'dept' => $dept->divisi ?: 'AP',
                'call' => $call,
                'dur' => $dur,
                'idd' => $idd,
                'ndd' => $ndd,
                'cell' => $cell,
                'ldd' => $ldd,
                'cost' => $cost,
                'last_call' => $lastCall,
            ];

            $totalAll['call'] += $call;
            $totalAll['dur'] += $dur;
            $totalAll['idd'] += $idd;
            $totalAll['ndd'] += $ndd;
            $totalAll['cell'] += $cell;
            $totalAll['ldd'] += $ldd;
            $totalAll['cost'] += $cost;
        }

        return view('reports.personal_summary', compact('departments', 'rows', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 7. Personal Detail Report
    public function personalDetail(Request $request)
    {
        $phoneId = $request->input('phone_id', 'Any');
        $zoneCode = $request->input('zone_code');
        $destNo = $request->input('destination_no');
        $areaCode = $request->input('area_code');
        $areaName = $request->input('area_name');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $allDepartments = TbmDepartment::where(function($q) {
            $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
        })->orderBy('extcode')->get();

        if ($phoneId && $phoneId !== 'Any') {
            $phoneGroups = TbmDepartment::where('extcode', $phoneId)->paginate(15)->withQueryString();
        } else {
            $phoneGroups = TbmDepartment::where(function($q) {
                $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
            })->orderBy('extcode')->paginate(15)->withQueryString();
        }

        // Single batch query for calls matching all extensions on this page
        $extCodes = $phoneGroups->pluck('extcode')->toArray();
        $q = DB::table('tbm_data_masuk')->whereIn('ext_pemanggil', $extCodes);
        if ($fromDate) $q->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $q->where('tglmasuk', '<=', $toDate);
        if ($zoneCode) $q->where('zona', 'like', "%{$zoneCode}%");
        if ($destNo) $q->where('no_tujuan', 'like', "%{$destNo}%");
        if ($areaCode) $q->where('kodearea', 'like', "%{$areaCode}%");
        if ($areaName) $q->where('ketarea', 'like', "%{$areaName}%");

        $allCalls = $q->orderBy('tglmasuk', 'desc')->orderBy('jammasuk', 'desc')->get()->groupBy('ext_pemanggil');

        $reportGroups = [];
        foreach ($phoneGroups as $dept) {
            $calls = $allCalls->get($dept->extcode, collect());
            $totCalls = $calls->count();
            $durSec = $calls->sum('durasi_detik');
            $totCost = $calls->sum('subtotal');

            $h = floor($durSec / 3600);
            $m = floor(($durSec % 3600) / 60);
            $s = $durSec % 60;
            $totDur = $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s);

            $reportGroups[] = [
                'dept' => $dept,
                'calls' => $calls,
                'tot_calls' => $totCalls,
                'tot_dur' => $totDur,
                'tot_cost' => $totCost
            ];
        }

        return view('reports.personal_detail', compact(
            'allDepartments', 'phoneGroups', 'reportGroups',
            'phoneId', 'zoneCode', 'destNo', 'areaCode', 'areaName',
            'fromDate', 'toDate', 'isSearched'
        ));
    }

    // 8. Personal Favorite Area Report
    public function personalFavoriteArea(Request $request)
    {
        $phoneId = $request->input('phone_id', 'Any');
        $areaCode = $request->input('area_code');
        $areaName = $request->input('area_name');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $allDepartments = TbmDepartment::where(function($q) {
            $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
        })->orderBy('extcode')->get();

        if ($phoneId && $phoneId !== 'Any') {
            $phoneGroups = TbmDepartment::where('extcode', $phoneId)->paginate(15)->withQueryString();
        } else {
            $phoneGroups = TbmDepartment::where(function($q) {
                $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
            })->orderBy('extcode')->paginate(15)->withQueryString();
        }

        // Single batch aggregation for areas per extension
        $extCodes = $phoneGroups->pluck('extcode')->toArray();
        $q = DB::table('tbm_data_masuk')->whereIn('ext_pemanggil', $extCodes);
        if ($fromDate) $q->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $q->where('tglmasuk', '<=', $toDate);
        if ($areaCode) $q->where('kodearea', 'like', "%{$areaCode}%");
        if ($areaName) $q->where('ketarea', 'like', "%{$areaName}%");

        $allAreas = $q->select(
            'ext_pemanggil',
            DB::raw('COALESCE(NULLIF(ketarea, ""), "Internal Call") as area'),
            DB::raw('COALESCE(NULLIF(kodearea, ""), "-") as distrik'),
            DB::raw('COUNT(*) as call_count'),
            DB::raw('SUM(durasi_detik) as duration_sec'),
            DB::raw('SUM(subtotal) as total_cost')
        )
        ->groupBy('ext_pemanggil', 'area', 'distrik')
        ->orderByDesc('call_count')
        ->get()
        ->groupBy('ext_pemanggil');

        $reportGroups = [];
        foreach ($phoneGroups as $dept) {
            $areas = $allAreas->get($dept->extcode, collect());
            $totCalls = $areas->sum('call_count');
            $durSec = $areas->sum('duration_sec');
            $totCost = $areas->sum('total_cost');

            $h = floor($durSec / 3600);
            $m = floor(($durSec % 3600) / 60);
            $s = $durSec % 60;
            $totDur = $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s);

            $reportGroups[] = [
                'dept' => $dept,
                'areas' => $areas,
                'tot_calls' => $totCalls,
                'tot_dur' => $totDur,
                'tot_cost' => $totCost
            ];
        }

        return view('reports.personal_favorite_area', compact(
            'allDepartments', 'phoneGroups', 'reportGroups',
            'phoneId', 'areaCode', 'areaName', 'fromDate', 'toDate', 'isSearched'
        ));
    }

    // 9. Phone-ID Detail Report
    public function phoneIdDetail(Request $request)
    {
        $department = $request->input('department', 'Any');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $distinctDepts = DB::table('tbm_group_department')
            ->where(function($q) {
                $q->whereNull('tgldelete')->orWhere('st', 1);
            })->get();

        $deptQuery = TbmDepartment::where(function($q) {
            $q->whereNull('tgldelete')->orWhere('st', '0')->orWhere('st', '1');
        });

        if ($department && $department !== 'Any') {
            $deptQuery->where('divisi', $department);
        }

        $departments = $deptQuery->orderBy('extcode')->paginate(30)->withQueryString();

        // Batch aggregate costs for the departments on this page
        $extCodes = $departments->pluck('extcode')->toArray();
        $q = DB::table('tbm_data_masuk')->whereIn('ext_pemanggil', $extCodes);
        if ($fromDate) $q->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $q->where('tglmasuk', '<=', $toDate);

        $aggMap = $q->select(
            'ext_pemanggil',
            DB::raw('COUNT(*) as total_call'),
            DB::raw('SUM(durasi_detik) as duration_sec'),
            DB::raw('SUM(subtotal) as total_cost')
        )
        ->groupBy('ext_pemanggil')
        ->get()
        ->keyBy('ext_pemanggil');

        $phoneList = [];
        $pageTotalCost = 0;
        foreach ($departments as $dept) {
            $agg = $aggMap[$dept->extcode] ?? null;
            $cost = $agg ? (float)$agg->total_cost : 0;
            $pageTotalCost += $cost;

            $phoneList[] = [
                'ext' => $dept->extcode,
                'name' => $dept->extname,
                'cost' => $cost
            ];
        }

        $divName = 'AP';
        $deptName = 'AP';
        $unitName = 'AP';

        return view('reports.phone_id_detail', compact(
            'distinctDepts', 'departments', 'phoneList', 'pageTotalCost',
            'department', 'divName', 'deptName', 'unitName',
            'fromDate', 'toDate', 'isSearched'
        ));
    }

    // 10. Phone-ID Summary Report
    public function phoneIdSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $query = DB::table('tbm_data_masuk as dm')
            ->leftJoin('tbm_department as d', 'd.extcode', '=', 'dm.ext_pemanggil')
            ->select(
                'dm.ext_pemanggil as phone_id',
                DB::raw('COALESCE(MAX(d.extname), MAX(dm.namadepartment), "-") as user_name'),
                DB::raw('COUNT(*) as call_count'),
                DB::raw('SUM(dm.durasi_detik) as duration_sec'),
                DB::raw('SUM(dm.subtotal) as total_charge')
            )
            ->groupBy('dm.ext_pemanggil');

        if ($fromDate) $query->where('dm.tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('dm.tglmasuk', '<=', $toDate);

        $rows = $query->orderByDesc('total_charge')->paginate(30)->withQueryString();
        
        $totalStats = DB::table('tbm_data_masuk as dm')
            ->when($fromDate, fn($q) => $q->where('dm.tglmasuk', '>=', $fromDate))
            ->when($toDate, fn($q) => $q->where('dm.tglmasuk', '<=', $toDate))
            ->select(
                DB::raw('COUNT(*) as call_count'),
                DB::raw('SUM(dm.durasi_detik) as duration_sec'),
                DB::raw('SUM(dm.subtotal) as total_charge')
            )
            ->first();

        $totalAll = [
            'call' => $totalStats->call_count ?? 0,
            'duration_sec' => $totalStats->duration_sec ?? 0,
            'charge' => $totalStats->total_charge ?? 0
        ];

        return view('reports.phone_id_summary', compact('rows', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // 11. Unit Summary Report
    public function unitSummary(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $query = DB::table('tbm_data_masuk as dm')
            ->select(
                DB::raw('COALESCE(NULLIF(dm.namadepartment, ""), "General") as unit_name'),
                DB::raw('COUNT(*) as total_call'),
                DB::raw('SUM(CASE WHEN dm.idd = "IDD" THEN dm.subtotal ELSE 0 END) as idd_cost'),
                DB::raw('SUM(CASE WHEN dm.idd = "NDD" THEN dm.subtotal ELSE 0 END) as ndd_cost'),
                DB::raw('SUM(CASE WHEN dm.cell = "CELL" OR dm.idd = "CELL" THEN dm.subtotal ELSE 0 END) as cell_cost'),
                DB::raw('SUM(CASE WHEN dm.idd = "LDD" OR dm.idd = "LOCAL" THEN dm.subtotal ELSE 0 END) as ldd_cost'),
                DB::raw('SUM(dm.subtotal) as total_cost')
            )
            ->groupBy('unit_name');

        if ($fromDate) $query->where('dm.tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('dm.tglmasuk', '<=', $toDate);

        $units = $query->orderByDesc('total_cost')->get();
        $totalAll = [
            'call' => $units->sum('total_call'),
            'idd' => $units->sum('idd_cost'),
            'ndd' => $units->sum('ndd_cost'),
            'cell' => $units->sum('cell_cost'),
            'ldd' => $units->sum('ldd_cost'),
            'cost' => $units->sum('total_cost'),
        ];

        return view('reports.unit_summary', compact('units', 'totalAll', 'fromDate', 'toDate', 'isSearched'));
    }

    // AJAX Billing Simulator (Support both Modal formats)
    public function simulate(Request $request)
    {
        $destinationInput = $request->input('destination') ?? $request->input('dialed');
        $durationInput = $request->input('duration_sec') ?? $request->input('durasi_detik', 60);
        $extInput = $request->input('ext_id') ?? $request->input('ext');
        $callTime = $request->input('call_time') ?? $request->input('jam') ?? date('H:i:s');
        $callDate = $request->input('call_date') ?? $request->input('tgl') ?? date('Y-m-d');

        if (!$destinationInput) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Nomor tujuan harus diisi.',
            ], 422);
        }

        $destination = preg_replace('/[^0-9]/', '', (string)$destinationInput);
        $durationSec = max(1, (int)$durationInput);

        // Find department by ID or extcode
        $extCode = null;
        $deptName = '-';
        $extName = '-';
        if ($extInput) {
            $dept = TbmDepartment::with('group')
                ->where('id', $extInput)
                ->orWhere('extcode', (string)$extInput)
                ->first();
            if ($dept) {
                $extCode = $dept->extcode;
                $extName = $dept->extname;
                $deptName = $dept->group ? $dept->group->namagroup : ($dept->divisi ?: $dept->namadepartment);
            } else {
                $extCode = (string)$extInput;
            }
        }

        $calc = \App\Services\CallRateCalculator::calculate($destination, $durationSec, $callDate, $callTime, $extCode);

        return response()->json([
            'status' => 'success',
            'success' => true,
            'ext' => $extCode ?: '-',
            'ext_name' => $extName,
            'department' => $deptName,
            'destination' => $calc['destination'],
            'prefix' => $calc['prefix_code'],
            'prefix_ket' => $calc['ketarea'],
            'area' => $calc['ketarea'],
            'zone' => $calc['zona'] ?: 'Lokal',
            'zona' => $calc['zona'] ?: 'Lokal',
            'rate_name' => $calc['namarate'] ?: '-',
            'namarate' => $calc['namarate'] ?: '-',
            'tarif_name' => $calc['namarate'] ?: '-',
            'unit_rate' => $calc['formularate'],
            'duration_sec' => $durationSec,
            'pulsa' => $calc['pulsa'],
            'formula' => $calc['formularate'] . ' (' . $calc['pulsa'] . ' x interval)',
            'biaya' => $calc['biaya'],
            'tambahan' => $calc['tambahan'],
            'subtotal' => $calc['subtotal'],
            'subtotal_formatted' => $calc['subtotal_formatted'],
            'tier' => $calc['tier'],
            'idd' => $calc['idd'],
            'cell' => $calc['cell'],
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
        $callDate = $now->format('Y-m-d');
        $callTime = $now->format('H:i:s');

        $calc = \App\Services\CallRateCalculator::calculate($destination, $durationSec, $callDate, $callTime, $dept->extcode);

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
            'tglmasuk' => $callDate,
            'jammasuk' => $callTime,
            'durasi' => $durasiStr,
            'durasi_detik' => $durationSec,
            'kodeakses' => '9',
            'no_tujuan' => $destination,
            'no_trunk' => 'TRK-01',
            'idmesin' => 1,
            'namamesin' => $calc['namamesin'],
            'idzone' => null,
            'zona' => $calc['zona'],
            'idprefix' => null,
            'kodearea' => $calc['kodearea'],
            'ketarea' => $calc['ketarea'],
            'idrate' => $calc['idrate'],
            'namarate' => $calc['namarate'],
            'formularate' => $calc['formularate'],
            'biaya' => $calc['biaya'],
            'tambahan' => $calc['tambahan'],
            'subtotal' => $calc['subtotal'],
            'hitung' => 1,
            'st' => '1',
            'tglinsert' => $callDate,
            'idperusahaan' => $calc['idperusahaan'] ?: 1,
            'namaperusahaan' => $calc['namaperusahaan'] ?: 'ANGKASA PURA',
            'pulsa' => (string)$calc['pulsa'],
            'idd' => $calc['idd'],
            'iddname' => $calc['iddname'],
            'cell' => $calc['cell'],
            'gabung' => $now->format('Y/m/d H:i:s') . " {$dept->extcode} 01 {$destination} {$durasiStr} {$nourut}",
        ]);

        return back()->with('success', "Panggilan baru {$nourut} berhasil dicatat & dihitung biayanya (Total: {$calc['subtotal_formatted']})!");
    }

    // Recalculate Call Records using Legacy Formula (Re Calculate Data)
    public function recalculate(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $onlyUnhit = $request->boolean('only_uncounted', false);

        $query = DB::table('tbm_data_masuk');
        if ($onlyUnhit) {
            $query->where(function($q) {
                $q->where('hitung', '<', 1)->orWhereNull('hitung');
            });
        }
        if ($fromDate) {
            $query->where('tglmasuk', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('tglmasuk', '<=', $toDate);
        }

        $records = $query->select('id', 'ext_pemanggil', 'no_tujuan', 'durasi', 'durasi_detik', 'tglmasuk', 'jammasuk')->limit(2000)->get();

        $updatedCount = 0;
        $totalCost = 0;

        foreach ($records as $r) {
            $durSec = $r->durasi_detik;
            if (!$durSec && $r->durasi) {
                $parts = explode(':', $r->durasi);
                if (count($parts) === 3) {
                    $durSec = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
                }
            }

            $calc = \App\Services\CallRateCalculator::calculate(
                $r->no_tujuan,
                $durSec ?: 0,
                $r->tglmasuk,
                $r->jammasuk,
                $r->ext_pemanggil
            );

            DB::table('tbm_data_masuk')->where('id', $r->id)->update([
                'iddepartment' => $calc['department_id'],
                'namadepartment' => $calc['department_name'],
                'namamesin' => $calc['namamesin'],
                'kodearea' => $calc['kodearea'],
                'zona' => $calc['zona'],
                'ketarea' => $calc['ketarea'],
                'idrate' => $calc['idrate'],
                'namarate' => $calc['namarate'],
                'biaya' => $calc['biaya'],
                'tambahan' => $calc['tambahan'],
                'subtotal' => $calc['subtotal'],
                'formularate' => $calc['formularate'],
                'hitung' => 1,
                'idd' => $calc['idd'],
                'iddname' => $calc['iddname'],
                'cell' => $calc['cell'],
                'idperusahaan' => $calc['idperusahaan'],
                'namaperusahaan' => $calc['namaperusahaan'],
                'durasi_detik' => $calc['duration_sec'],
            ]);

            $updatedCount++;
            $totalCost += $calc['subtotal'];
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'updated_count' => $updatedCount,
                'total_cost' => $totalCost,
                'total_cost_formatted' => 'Rp ' . number_format($totalCost, 0, ',', '.'),
                'message' => "Berhasil menghitung ulang {$updatedCount} data panggilan (Total: Rp " . number_format($totalCost, 0, ',', '.') . ")",
            ]);
        }

        return back()->with('success', "Berhasil menghitung ulang {$updatedCount} data panggilan!");
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
