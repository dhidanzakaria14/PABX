@extends('layouts.app')

@section('title', 'ANGKASA PURA - PERSONAL FAVORITE DIALED NUMBER')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PERSONAL FAVORITE DIALED NUMBER</span>
    </div>

    <!-- Filter Bar matching Photo 3 -->
    <form action="{{ route('reports.personal-favorite-dialed') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        
        <div class="filter-group">
            <label class="filter-label">Phone ID</label>
            <select name="phone_id" class="select-box" style="min-width: 240px;">
                @foreach($departments as $dept)
                    <option value="{{ $dept->extcode }}" {{ $phoneId == $dept->extcode ? 'selected' : '' }}>
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

    <!-- Table matching Photo 3 -->
    <div class="table-responsive" style="padding: 1.25rem;">
        <table class="ap-table" style="border: 1px solid #e2e8f0; border-radius: 4px;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Dialed</th>
                    <th>Area Name</th>
                    <th>Dist</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">Cost</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $idx => $item)
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="font-family: 'JetBrains Mono', monospace;">{{ $item['dialed'] }}</td>
                        <td>{{ $item['area_name'] }}</td>
                        <td>{{ $item['dist'] }}</td>
                        <td class="text-right">{{ $item['call'] }}</td>
                        <td class="text-right">{{ $item['duration'] }}</td>
                        <td class="text-right">{{ number_format($item['cost'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 2rem; color: #64748b;">
                            Tidak ada data panggilan untuk Phone ID ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="font-weight: 700; color: #475569;">TOTAL</td>
                    <td class="text-right" style="font-weight: 700; color: #475569;">{{ $totalAll['call'] }}</td>
                    <td class="text-right" style="font-weight: 700; color: #475569;">{{ $totalAll['duration'] }}</td>
                    <td class="text-right" style="font-weight: 700; color: #475569;">{{ number_format($totalAll['cost'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
