@extends('layouts.app')

@section('title', 'ANGKASA PURA - PHONE-ID SUMMARY REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PHONE-ID SUMMARY REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.phone-id-summary') }}" method="GET" class="filter-section">
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

        <button type="button" onclick="exportTableToCSV('phoneIdSummaryTable', 'phone_id_summary.csv')" class="btn-export">
            <i class="fa-solid fa-file-excel"></i> Export Data
        </button>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table" id="phoneIdSummaryTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Phone ID</th>
                    <th>User Name</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">Total Charge</th>
                </tr>
            </thead>
            <tbody>
                @php $no = $rows instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rows->firstItem() : 1; @endphp
                @forelse($rows as $row)
                    @php
                        $sec = (int)$row->duration_sec;
                        $h = floor($sec / 3600);
                        $m = floor(($sec % 3600) / 60);
                        $s = $sec % 60;
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $row->phone_id }}</strong></td>
                        <td>{{ $row->user_name }}</td>
                        <td class="text-right">{{ number_format($row->call_count) }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ sprintf('%d:%02d:%02d', $h, $m, $s) }}</td>
                        <td class="text-right"><strong>{{ number_format($row->total_charge, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 1.5rem; color: #64748b;">
                            {{ $isSearched ? 'Tidak ada data rekap Phone ID untuk kriteria ini.' : 'Tentukan rentang tanggal dan klik Search Data.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">TOTAL</td>
                    <td class="text-right">{{ number_format($totalAll['call']) }}</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                        @php
                            $sec = (int)($totalAll['duration_sec'] ?? 0);
                            $h = floor($sec / 3600);
                            $m = floor(($sec % 3600) / 60);
                            $s = $sec % 60;
                        @endphp
                        {{ sprintf('%d:%02d:%02d', $h, $m, $s) }}
                    </td>
                    <td class="text-right">{{ number_format($totalAll['charge'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->hasPages())
        <div style="padding: 1rem 1.25rem;">
            {{ $rows->links() }}
        </div>
    @endif
</div>
@endsection
