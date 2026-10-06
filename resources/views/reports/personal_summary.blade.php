@extends('layouts.app')

@section('title', 'ANGKASA PURA - PERSONAL SUMMARY REPORT')

@section('content')
<!-- KPI Summary Cards for Personal Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #f57c00;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Ekstensi Aktif Berbicara</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #f57c00; margin-top: 4px;">
            {{ number_format($totalStats->active_ext_count ?? 0) }} Ext
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Dari total {{ $departments->count() }} Ekstensi PABX</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #0288d1;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Panggilan</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #0288d1; margin-top: 4px;">
            {{ number_format($totalStats->total_call ?? 0) }} Calls
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Akumulasi panggilan keluar</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #2e7d32;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Durasi Bicara</div>
        @php
            $totSec = $totalStats->total_sec ?? 0;
            $hrs = floor($totSec / 3600);
            $mins = floor(($totSec % 3600) / 60);
            $secs = $totSec % 60;
        @endphp
        <div style="font-size: 1.5rem; font-weight: 800; color: #2e7d32; margin-top: 4px;">
            {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">{{ round($totSec / 60, 1) }} Menit</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #d32f2f;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Tagihan (Charge)</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #d32f2f; margin-top: 4px;">
            Rp {{ number_format($totalStats->total_charge ?? 0, 0, ',', '.') }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Termasuk PPN 11%</div>
    </div>
</div>

<!-- Main Table Card -->
<div class="report-card">
    <div class="report-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <span>PERSONAL SUMMARY REPORT</span>
            @if(isset($dateMin) && isset($dateMax))
                <div style="font-size: 0.725rem; font-weight: normal; color: #64748b; margin-top: 2px;">
                    <i class="fa-solid fa-database text-success"></i> Periode Data: <strong>{{ \Carbon\Carbon::parse($dateMin)->format('d/m/Y') }} &ndash; {{ \Carbon\Carbon::parse($dateMax)->format('d/m/Y') }}</strong> ({{ number_format($totalStats->total_call ?? 0) }} Panggilan)
                </div>
            @endif
        </div>
        <button type="button" onclick="window.print()" class="btn-export" style="font-size: 0.75rem; padding: 0 12px; height: 32px;">
            <i class="fa-solid fa-print"></i> Cetak / Export
        </button>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.personal-summary') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        
        <div class="filter-group">
            <label class="filter-label">Phone ID / Extension</label>
            <select name="phone_id" class="select-box" style="min-width: 220px;">
                <option value="">Semua Ekstensi (All Phone-ID)</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $phoneId == $dept->id ? 'selected' : '' }}>
                        {{ $dept->extcode }} - {{ $dept->extname }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">From Date</label>
            <input type="date" name="from_date" class="input-text" value="{{ $fromDate }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">To Date</label>
            <input type="date" name="to_date" class="input-text" value="{{ $toDate }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">Cari Ext / Nama</label>
            <input type="text" name="search" class="input-text" placeholder="No Ext / Nama..." value="{{ $search }}">
        </div>

        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Search Data
        </button>

        <a href="{{ route('reports.personal-summary') }}" class="btn-export" style="background: #64748b;" title="Reset Filter">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>

    <!-- Quick Presets -->
    <div style="padding: 0.5rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.775rem;">
        <span style="color: #64748b; font-weight: 600;">Filter Cepat:</span>
        <a href="{{ route('reports.personal-summary') }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ !$fromDate && !$search ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Semua Data</a>
        <a href="{{ route('reports.personal-summary', ['from_date' => '2026-08-01', 'to_date' => '2026-08-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ $fromDate == '2026-08-01' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Bulan Terakhir (Agu 2026)</a>
        <a href="{{ route('reports.personal-summary', ['from_date' => '2026-01-01', 'to_date' => '2026-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ $fromDate == '2026-01-01' && $toDate == '2026-12-31' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2026</a>
        <a href="{{ route('reports.personal-summary', ['from_date' => '2025-01-01', 'to_date' => '2025-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ $fromDate == '2025-01-01' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2025</a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table">
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>Phone ID (Ext)</th>
                    <th>User / Department</th>
                    <th>Division</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">IDD Cost</th>
                    <th class="text-right">NDD Cost</th>
                    <th class="text-right">CELL Cost</th>
                    <th class="text-right">LDD Cost</th>
                    <th class="text-right">Total Cost</th>
                    <th class="text-right">Total Charge</th>
                </tr>
            </thead>
            <tbody>
                @php $no = $reportData->firstItem() ?: 1; @endphp
                @forelse($reportData as $row)
                    @php
                        $sec = $row->total_sec ?: 0;
                        $h = floor($sec / 3600);
                        $m = floor(($sec % 3600) / 60);
                        $s = $sec % 60;
                        $durFormatted = sprintf('%02d:%02d:%02d', $h, $m, $s);
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <strong style="color: #0288d1; font-family: 'JetBrains Mono', monospace; font-size: 0.9rem;">
                                {{ $row->ext_pemanggil }}
                            </strong>
                        </td>
                        <td>{{ $row->user_name }}</td>
                        <td><span style="font-size: 0.75rem; background: #f1f5f9; padding: 2px 6px; border-radius: 3px; color: #475569;">{{ $row->division }}</span></td>
                        <td class="text-right" style="font-weight: 700;">{{ number_format($row->total_call) }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $durFormatted }}</td>
                        <td class="text-right">{{ number_format($row->idd_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row->ndd_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row->cell_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row->ldd_cost, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: 600;">{{ number_format($row->total_cost, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: 700; color: #2e7d32; font-size: 0.9rem;">
                            Rp {{ number_format($row->total_charge, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center" style="padding: 2.5rem 1rem; color: #64748b;">
                            <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"><i class="fa-solid fa-inbox"></i></div>
                            <strong style="color: #334155; font-size: 0.95rem;">Tidak ada data personal summary yang cocok dengan filter.</strong>
                            @if($fromDate && isset($dateMax) && $fromDate > $dateMax)
                                <div style="font-size: 0.825rem; color: #e11d48; margin-top: 6px;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Tanggal pencarian Anda ({{ $fromDate }}) melebihi tanggal data terakhir di database ({{ \Carbon\Carbon::parse($dateMax)->format('d-m-Y') }}).
                                </div>
                            @endif
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('reports.personal-summary') }}" class="btn-search" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; padding: 6px 14px; background: #0288d1;">
                                    <i class="fa-solid fa-rotate-left"></i> Tampilkan Semua Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f8fafc; font-weight: 700;">
                    <td colspan="4" style="text-align: right; text-transform: uppercase;">GRAND TOTAL:</td>
                    <td class="text-right">{{ number_format($totalStats->total_call ?? 0) }}</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                        {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
                    </td>
                    <td class="text-right">{{ number_format($totalStats->idd_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalStats->ndd_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalStats->cell_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalStats->ldd_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalStats->total_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #2e7d32; font-size: 0.95rem;">
                        Rp {{ number_format($totalStats->total_charge ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($reportData->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #e2e8f0; background: #ffffff;">
            {{ $reportData->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
@endsection
