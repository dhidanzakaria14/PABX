@extends('layouts.app')

@section('title', 'ANGKASA PURA - UNIT SUMMARY REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>UNIT SUMMARY REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.unit-summary') }}" method="GET" class="filter-section">
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

        <button type="button" onclick="exportTableToCSV('unitSummaryTable', 'unit_summary.csv')" class="btn-export">
            <i class="fa-solid fa-file-excel"></i> Export Data
        </button>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table" id="unitSummaryTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Unit / Department</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">IDD Cost</th>
                    <th class="text-right">NDD Cost</th>
                    <th class="text-right">CELL Cost</th>
                    <th class="text-right">LDD Cost</th>
                    <th class="text-right">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($units as $unit)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $unit->unit_name }}</strong></td>
                        <td class="text-right">{{ number_format($unit->total_call) }}</td>
                        <td class="text-right">{{ number_format($unit->idd_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($unit->ndd_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($unit->cell_cost, 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($unit->ldd_cost, 0, ',', '.') }}</td>
                        <td class="text-right"><strong>{{ number_format($unit->total_cost, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 1.5rem; color: #64748b;">
                            {{ $isSearched ? 'Tidak ada data unit untuk kriteria ini.' : 'Tentukan rentang tanggal dan klik Search Data.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">TOTAL</td>
                    <td class="text-right">{{ number_format($totalAll['call']) }}</td>
                    <td class="text-right">{{ number_format($totalAll['idd'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['ndd'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['cell'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['ldd'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($totalAll['cost'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
