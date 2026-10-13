@extends('layouts.app')

@section('title', 'ANGKASA PURA - PERSONAL FAVORITE AREA REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PERSONAL FAVORITE AREA REPORT</span>
    </div>

    <!-- Filter Form matching legacy layout -->
    <form action="{{ route('reports.personal-favorite-area') }}" method="GET" class="filter-section" style="flex-direction: column; align-items: stretch; gap: 0.85rem;">
        <input type="hidden" name="searched" value="1">

        <!-- Row 1: Phone ID -->
        <div style="display: flex; flex-wrap: wrap; gap: 1.25rem; align-items: flex-end;">
            <div class="filter-group">
                <label class="filter-label">Phone ID</label>
                <select name="phone_id" class="select-box" style="min-width: 200px;">
                    <option value="Any" {{ $phoneId == 'Any' ? 'selected' : '' }}>Any</option>
                    @foreach($allDepartments as $dept)
                        <option value="{{ $dept->extcode }}" {{ $phoneId == $dept->extcode ? 'selected' : '' }}>
                            {{ $dept->extcode }} - {{ $dept->extname }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Area Code, Area Name -->
        <div style="display: flex; flex-wrap: wrap; gap: 1.25rem; align-items: flex-end;">
            <div class="filter-group">
                <label class="filter-label">Area Code</label>
                <input type="text" name="area_code" class="input-text" value="{{ $areaCode }}" placeholder="">
            </div>

            <div class="filter-group">
                <label class="filter-label">Area Name</label>
                <input type="text" name="area_name" class="input-text" value="{{ $areaName }}" placeholder="">
            </div>
        </div>

        <!-- Row 3: From Date, To, Search Data, Export Data -->
        <div style="display: flex; flex-wrap: wrap; gap: 1.25rem; align-items: flex-end;">
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
        </div>
    </form>

    <!-- Grouped Tables by Phone ID -->
    <div style="padding: 1.25rem;">
        @forelse($reportGroups as $group)
            <div style="font-weight: 700; font-size: 0.95rem; margin-top: 1rem; margin-bottom: 0.6rem; color: #334155;">
                Phone ID : {{ $group['dept']->extcode }} - {{ $group['dept']->extname }}
            </div>

            <div class="table-responsive" style="margin-bottom: 2rem; border: 1px solid #e2e8f0; border-radius: 4px;">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Area</th>
                            <th>Distrik</th>
                            <th class="text-right">Call</th>
                            <th class="text-right">Duration</th>
                            <th class="text-right">Total Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $aNo = 1; @endphp
                        @foreach($group['areas'] as $item)
                            <tr>
                                <td>{{ $aNo++ }}</td>
                                <td>{{ $item->area }}</td>
                                <td>{{ $item->distrik }}</td>
                                <td class="text-right">{{ number_format($item->call_count) }}</td>
                                <td class="text-right">{{ sprintf('%d:%02d:%02d', floor($item->duration_sec / 3600), floor(($item->duration_sec % 3600) / 60), $item->duration_sec % 60) }}</td>
                                <td class="text-right">{{ number_format($item->total_cost, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="font-weight: 600; color: #64748b;">Total</td>
                            <td class="text-right" style="font-weight: 600; color: #64748b;">{{ $group['tot_calls'] }}</td>
                            <td class="text-right" style="font-weight: 600; color: #64748b;">{{ $group['tot_dur'] }}</td>
                            <td class="text-right" style="font-weight: 600; color: #64748b;">{{ number_format($group['tot_cost'], 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @empty
            <div style="text-align: center; padding: 2rem; color: #64748b;">
                Tidak ada data Phone ID ditemukan.
            </div>
        @endforelse

        @if($phoneGroups->hasPages())
            <div style="margin-top: 1rem;">
                {{ $phoneGroups->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
