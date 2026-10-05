<?php

namespace App\Http\Controllers;

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
    public function index(Request $request)
    {
        $profile = TbmProfil::first();
        $departments = TbmDepartment::with('group')->orderBy('extcode')->get();
        $zones = TbmZona::all();
        $settings = TbmSetting::all();
        $tarifs = TbmTarif::with('rates')->get();

        // Query Billing Records
        $query = TbmDataMasuk::with(['department.group', 'setting', 'zonaRel', 'prefixRel', 'tarifRel']);

        // Filters
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

        $records = (clone $query)->orderBy('id', 'desc')->paginate(15)->withQueryString();

        // KPI Summary
        $allMatching = (clone $query)->get();
        $totalCalls = $allMatching->count();
        $totalCost = $allMatching->sum('subtotal');
        $totalDurationSec = $allMatching->sum('durasi_detik');

        // Department breakdown
        $deptSummary = $allMatching->groupBy('iddepartment')->map(function($items, $deptId) {
            $first = $items->first();
            return [
                'id' => $deptId,
                'name' => $first->namadepartment ?? 'Unknown',
                'ext' => $first->ext_pemanggil ?? '-',
                'calls' => $items->count(),
                'duration_sec' => $items->sum('durasi_detik'),
                'total_cost' => $items->sum('subtotal'),
            ];
        })->sortByDesc('total_cost');

        // Zone breakdown
        $zoneSummary = $allMatching->groupBy('zona')->map(function($items, $zonaName) {
            return [
                'name' => $zonaName ?: 'Lain-lain',
                'calls' => $items->count(),
                'total_cost' => $items->sum('subtotal')
            ];
        });

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
            'deptSummary',
            'zoneSummary'
        ));
    }

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

        // Determine prefix and zone
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
            // Default to local if no prefix match
            $matchedPrefix = TbmPrefix::with('zona')->first();
        }

        $zone = $matchedPrefix->zona;

        // Find tariff and matching rate
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

        // Calculate rate based on time of day
        $rate = TbdRate::where('idrate', $tarif->idtarif)
            ->where('mulai', '<=', $callTime)
            ->where('selesai', '>=', $callTime)
            ->first();

        if (!$rate) {
            $rate = TbdRate::where('idrate', $tarif->idtarif)->first();
        }

        $unitRate = $rate ? (float)$rate->tarifnormal : 500;
        $minDurasi = $tarif->mindurasi ?: 60;

        // Pulsa calculation (rounding up to nearest interval)
        $pulsa = ceil($durationSec / $minDurasi);
        $biaya = $pulsa * $unitRate;
        $ppnRate = 0.11; // 11% PPN
        $tambahan = round($biaya * $ppnRate, 2);
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

        // Match Prefix & Zone
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

        // Match Tarif
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

        $call = TbmDataMasuk::create([
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
            'namaperusahaan' => 'PT Telekomunikasi Solusindo Corp',
            'pulsa' => (string)$pulsa,
            'gabung' => $now->format('Y/m/d H:i:s') . " {$dept->extcode} 01 {$destination} {$durasiStr} {$nourut}",
        ]);

        return redirect()->route('billing.index')->with('success', "Panggilan baru {$nourut} berhasil dicatat & dihitung biayanya (Total: Rp " . number_format($subtotal, 0, ',', '.') . ")!");
    }

    public function schema()
    {
        // Get all tables and their foreign keys directly from database
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
