@extends('layouts.app')

@section('title', 'ANGKASA PURA - PERSONAL FAVORITE DIALED NUMBER')

@section('content')
<div class="report-card">
    <div class="report-header">
        <span>PERSONAL FAVORITE DIALED NUMBER</span>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('reports.personal-favorite-dialed') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        <div class="filter-group">
            <label class="filter-label">Phone ID</label>
            <select name="phone_id" class="select-box" style="min-width: 220px;">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $phoneId == $dept->id ? 'selected' : '' }}>
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

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table">
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
                @php $no = 1; @endphp
                @forelse($grouped as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong style="font-family: 'JetBrains Mono', monospace;">{{ $item['dialed'] }}</strong></td>
                        <td>{{ $item['area_name'] }}</td>
                        <td>{{ $item['dist'] }}</td>
                        <td class="text-right">{{ number_format($item['call']) }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $item['duration'] }}</td>
                        <td class="text-right" style="font-weight: 700; color: #2e7d32;">
                            {{ number_format($item['cost'], 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 2.5rem 1rem; color: #64748b;">
                            @if(!($isSearched ?? false))
                                <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"><i class="fa-solid fa-magnifying-glass"></i></div>
                                <strong style="color: #475569; font-size: 0.95rem;">Halaman Siap untuk Pencarian</strong>
                                <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 4px;">Silakan pilih Phone ID dan klik <strong>"Search Data"</strong> untuk menampilkan nomor telepon favorit.</div>
                            @else
                                <div style="font-size: 1.75rem; color: #e2e8f0; margin-bottom: 0.5rem;"><i class="fa-solid fa-inbox"></i></div>
                                Tidak ada data untuk ekstensi yang dipilih.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-right">{{ number_format($totalAll['call']) }}</td>
                    <td></td>
                    <td class="text-right" style="color: #2e7d32;">{{ number_format($totalAll['cost'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
