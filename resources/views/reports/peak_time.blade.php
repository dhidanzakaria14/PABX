@extends('layouts.app')

@section('title', 'ANGKASA PURA - PEAK TIME REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PEAK TIME REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.peak-time') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        <div class="filter-group">
            <label class="filter-label">From Date</label>
            <input type="date" name="from_date" class="input-text" value="{{ $fromDate }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">To</label>
            <input type="date" name="to_date" class="input-text" value="{{ $toDate }}">
        </div>

        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Search Data
        </button>

        <button type="button" onclick="window.print()" class="btn-export">
            <i class="fa-solid fa-file-excel"></i> Export Data
        </button>
    </form>

    <!-- Table with 24 Hours -->
    <div class="table-responsive">
        <table class="ap-table">
            <thead>
                <tr>
                    <th style="width: 80px;">HH</th>
                    <th class="text-right">IDD Cost</th>
                    <th class="text-right">NDD Cost</th>
                    <th class="text-right">CELL Cost</th>
                    <th class="text-right">LDD Cost</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hourlyData as $row)
                    <tr>
                        <td><strong>{{ $row['hh'] }}</strong></td>
                        <td class="text-right">{{ number_format($row['idd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['ndd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['cell_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['ldd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                            @php
                                $s = $row['duration_sec'];
                                $h = floor($s / 3600);
                                $m = floor(($s % 3600) / 60);
                                $sec = $s % 60;
                            @endphp
                            {{ sprintf('%d:%02d:%02d', $h, $m, $sec) }}
                        </td>
                        <td class="text-right" style="font-weight: 700; color: #2e7d32;">
                            {{ number_format($row['total_cost'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 2.5rem 1rem; color: #64748b;">
                            @if(!($isSearched ?? false))
                                <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"><i class="fa-solid fa-magnifying-glass"></i></div>
                                <strong style="color: #475569; font-size: 0.95rem;">Halaman Siap untuk Pencarian</strong>
                                <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">Silakan tentukan periode tanggal di atas dan klik <strong>"Search Data"</strong> untuk menampilkan data peak time.</div>
                            @else
                                <div style="font-size: 1.75rem; color: #e2e8f0; margin-bottom: 0.5rem;"><i class="fa-solid fa-inbox"></i></div>
                                Tidak ada data untuk periode ini.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td>TOTAL</td>
                    <td class="text-right">{{ number_format($totalAll['idd'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['ndd'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['cell'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['ldd'], 0, ',', '.') }}</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                        @php
                            $s = $totalAll['duration_sec'];
                            $h = floor($s / 3600);
                            $m = floor(($s % 3600) / 60);
                            $sec = $s % 60;
                        @endphp
                        {{ sprintf('%d:%02d:%02d', $h, $m, $sec) }}
                    </td>
                    <td class="text-right" style="color: #2e7d32;">{{ number_format($totalAll['cost'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
