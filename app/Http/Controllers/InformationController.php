<?php

namespace App\Http\Controllers;

use App\Models\TbmDataMasuk;
use App\Models\TbmDepartment;
use App\Models\TbmProfil;
use App\Models\TbmSetting;
use App\Models\TbmTarif;
use App\Models\TbmZona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InformationController extends Controller
{
    // 1. Call History (Full CDR Records & Filters)
    public function callHistory(Request $request)
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

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = min($request->start_date, $request->end_date);
            $endDate = max($request->start_date, $request->end_date);
            $query->whereBetween('tglmasuk', [$startDate, $endDate]);
        } elseif ($request->filled('start_date')) {
            $query->where('tglmasuk', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
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
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->where('no_tujuan', 'like', "%{$search}%")
                  ->orWhere('ext_pemanggil', 'like', "%{$search}%")
                  ->orWhere('namadepartment', 'like', "%{$search}%")
                  ->orWhere('nourut', 'like', "%{$search}%")
                  ->orWhere('noline', 'like', "%{$search}%")
                  ->orWhere('no_trunk', 'like', "%{$search}%")
                  ->orWhere('ketarea', 'like', "%{$search}%")
                  ->orWhere('zona', 'like', "%{$search}%");
            });
        }

        // Fast SQL aggregates
        $totalCalls = (clone $query)->count();
        $totalCost = (clone $query)->sum('subtotal') ?: 0;
        $totalDurationSec = (clone $query)->sum('durasi_detik') ?: 0;

        $records = (clone $query)->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $isSearched = true;

        return view('information.call_history', compact(
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

    // 2. Recalculating Call
    public function recalculatingCall(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $isSearched = $request->has('searched') || $request->filled('from_date') || $request->filled('to_date');

        $query = DB::table('tbm_data_masuk');
        if ($fromDate) $query->where('tglmasuk', '>=', $fromDate);
        if ($toDate) $query->where('tglmasuk', '<=', $toDate);

        $uncountedCount = DB::table('tbm_data_masuk')
            ->where(function($q) {
                $q->where('hitung', '<', 1)->orWhereNull('hitung');
            })->count();

        $records = $isSearched ? (clone $query)->orderByDesc('id')->paginate(25)->withQueryString() : collect();

        $stats = [
            'total' => (clone $query)->count(),
            'total_cost' => (clone $query)->sum('subtotal') ?: 0,
            'uncounted' => $uncountedCount,
        ];

        return view('information.recalculating_call', compact('records', 'stats', 'fromDate', 'toDate', 'isSearched'));
    }

    // 3. Listening Port
    public function monitoringPort()
    {
        $settings = TbmSetting::all();
        $activeSetting = TbmSetting::first();

        // Check if socket port is reachable locally
        $portOpen = false;
        if ($activeSetting && $activeSetting->port) {
            $connection = @fsockopen('127.0.0.1', (int)$activeSetting->port, $errno, $errstr, 0.5);
            if (is_resource($connection)) {
                $portOpen = true;
                fclose($connection);
            }
        }

        $recentRaw = DB::table('tbm_data_masuk')
            ->whereNotNull('gabung')
            ->where('gabung', '!=', '')
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        return view('information.monitoring_port', compact('settings', 'activeSetting', 'portOpen', 'recentRaw'));
    }
}
