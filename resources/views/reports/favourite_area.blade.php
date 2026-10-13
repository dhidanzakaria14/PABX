@extends('layouts.app')

@section('title', 'ANGKASA PURA - FAVOURITE AREA REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>FAVOURITE AREA REPORT</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.favourite-area') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        <div class="filter-group">
            <label class="filter-label">Area Code ID</label>
            <select name="area_code_id" class="select-box">
                <option value="Any">Any</option>
                @foreach($distinctAreas as $area)
                    <option value="{{ $area->kodearea }}" {{ $areaCodeId == $area->kodearea ? 'selected' : '' }}>
                        {{ $area->kodearea }} - {{ $area->ketarea ?: 'Area ' . $area->kodearea }}
                    </option>
                @endforeach
            </select>
        </div>

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

        <button type="button" onclick="exportTableToCSV('favouriteAreaTable', 'favourite_area_report.csv')" class="btn-export">
            <i class="fa-solid fa-file-excel"></i> Export Data
        </button>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table" id="favouriteAreaTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Area Code</th>
                    <th>Area Name</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">Total Charge</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($grouped as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $item['area_code'] }}</strong></td>
                        <td>{{ $item['area_name'] }}</td>
                        <td class="text-right">{{ number_format($item['call']) }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $item['duration'] }}</td>
                        <td class="text-right">{{ number_format($item['total_charge'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    @if($isSearched ?? false)
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 1.5rem; color: #64748b;">
                                Tidak ada data untuk kriteria pencarian ini.
                            </td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">TOTAL</td>
                    <td class="text-right">{{ number_format($totalAll['call']) }}</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $totalAll['duration'] }}</td>
                    <td class="text-right">{{ number_format($totalAll['total_charge'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
