@extends('layouts.app')

@section('title', 'ANGKASA PURA - DIVISION SUMMARY REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>DIVISION SUMMARY REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.division-summary') }}" method="GET" class="filter-section">
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

        <button type="button" onclick="exportTableToCSV('divisionTable', 'division_summary_report.csv')" class="btn-export">
            <i class="fa-solid fa-file-excel"></i> Export Data
        </button>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table" id="divisionTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Division</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">IDD Cost</th>
                    <th class="text-right">NDD Cost</th>
                    <th class="text-right">CELL Cost</th>
                    <th class="text-right">LDD Cost</th>
                    <th class="text-right">Total Cost</th>
                    <th class="text-right">Total Charge</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $row)
                    <tr>
                        <td>{{ $row['no'] }}</td>
                        <td><strong>{{ $row['division'] }}</strong></td>
                        <td class="text-right">{{ number_format($row['call']) }}</td>
                        <td class="text-right">{{ number_format($row['idd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['ndd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['cell_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['ldd_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['total_cost'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($row['total_charge'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>1</td>
                        <td><strong>AP</strong></td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
                        <td class="text-right">0</td>
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
                    <td class="text-right">{{ number_format($totalAll['charge'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
