@extends('layouts.app')

@section('title', 'ANGKASA PURA - PERSONAL SUMMARY REPORT')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PERSONAL SUMMARY REPORT</span>
    </div>

    <!-- Filter Bar matching Photo 4 -->
    <form action="{{ route('reports.personal-summary') }}" method="GET" class="filter-section">
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

    <!-- Table matching Photo 4 -->
    <div class="table-responsive" style="padding: 1.25rem;">
        <table class="ap-table" style="border: 1px solid #e2e8f0; border-radius: 4px;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Ext</th>
                    <th>Nama Pemakai</th>
                    <th>Departemen</th>
                    <th class="text-right">Call</th>
                    <th class="text-right">Durasi</th>
                    <th class="text-right">IDD Cost</th>
                    <th class="text-right">NDD Cost</th>
                    <th class="text-right">CELL Cost</th>
                    <th class="text-right">LDD Cost</th>
                    <th class="text-right">Total Cost</th>
                    <th>Last Call</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $r)
                    <tr>
                        <td>{{ $r['no'] }}</td>
                        <td>{{ $r['ext'] }}</td>
                        <td>{{ $r['nama'] }}</td>
                        <td>{{ $r['dept'] }}</td>
                        <td class="text-right">{{ $r['call'] }}</td>
                        <td class="text-right">{{ $r['dur'] }}</td>
                        <td class="text-right">{{ number_format($r['idd'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r['ndd'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r['cell'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r['ldd'], 0, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($r['cost'], 0, ',', '.') }}</td>
                        <td style="font-size: 0.775rem; color: #475569;">{{ $r['last_call'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center" style="padding: 2rem; color: #64748b;">
                            Tidak ada data ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($departments->hasPages())
            <div style="margin-top: 1rem;">
                {{ $departments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
