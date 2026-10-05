@extends('layouts.app')

@section('title', 'ANGKASA PURA - PEAK TIME REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PEAK TIME REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.peak-time') }}" method="GET" class="filter-section">
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
                @foreach($hourlyData as $row)
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
                @endforeach
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
