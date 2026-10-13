@extends('layouts.app')

@section('title', 'ANGKASA PURA - PHONE-ID DETAIL REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PHONE-ID DETAIL REPORT</span>
    </div>

    <!-- Filter Bar matching Photo 5 -->
    <form action="{{ route('reports.phone-id-detail') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        
        <div class="filter-group">
            <label class="filter-label">Department</label>
            <select name="department" class="select-box" style="min-width: 200px;">
                <option value="Any" {{ $department == 'Any' ? 'selected' : '' }}>Any</option>
                @foreach($distinctDepts as $d)
                    <option value="{{ $d->namagroup }}" {{ $department == $d->namagroup ? 'selected' : '' }}>
                        {{ $d->namagroup }}
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

    <!-- Table matching Photo 5 -->
    <div class="table-responsive" style="padding: 1.25rem;">
        <table class="ap-table" style="border: 1px solid #e2e8f0; border-radius: 4px;">
            <thead>
                <tr>
                    <th style="width: 120px;">No</th>
                    <th>Ext. U</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Duration</th>
                    <th>Dialed</th>
                    <th>Area</th>
                    <th class="text-right">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hierarchy rows matching Photo 5 -->
                <tr style="background: #f8fafc; font-weight: 600;">
                    <td style="color: #64748b;">Division</td>
                    <td colspan="6" style="color: #1e293b;">{{ $divName }}</td>
                    <td></td>
                </tr>
                <tr style="background: #f8fafc; font-weight: 600;">
                    <td style="color: #64748b;">Department</td>
                    <td colspan="6" style="color: #1e293b;">{{ $deptName }}</td>
                    <td></td>
                </tr>
                <tr style="background: #f8fafc; font-weight: 600;">
                    <td style="color: #64748b;">Unit</td>
                    <td colspan="6" style="color: #1e293b;">{{ $unitName }}</td>
                    <td></td>
                </tr>

                <!-- Phone ID records -->
                @forelse($phoneList as $phone)
                    <tr>
                        <td style="color: #64748b; font-weight: 500;">Phone Id</td>
                        <td style="color: #1e293b; font-weight: 600;">{{ $phone['ext'] }} - {{ $phone['name'] }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="color: #1e293b; font-weight: 500;">
                            {{ number_format($phone['cost'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 2rem; color: #64748b;">
                            Tidak ada data Phone ID ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7" style="font-weight: 700; color: #475569;">Total</td>
                    <td class="text-right" style="font-weight: 700; color: #475569;">
                        {{ number_format($pageTotalCost, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        @if($departments->hasPages())
            <div style="margin-top: 1rem;">
                {{ $departments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
