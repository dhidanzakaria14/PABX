@extends('layouts.app')

@section('title', 'RECALCULATING CALL - ANGKASA PURA')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-calculator" style="color: #0284c7;"></i>
                RECALCULATING CALLS
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Kalkulasi ulang tarif biaya CDR PABX berdasarkan tabel Master Rate & Special Rate terbaru
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('information.call-history') }}" class="btn-search" style="text-decoration: none; background: #475569;">
                <i class="fa-solid fa-clock-rotate-left"></i> Call History
            </a>
            <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none;">
                <i class="fa-solid fa-house"></i> Home Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: white; border-radius: 10px; border: 1px solid #e2e8f0; border-top: 3px solid #0288d1; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #e0f2fe; color: #0288d1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total CDR Dalam Filter</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #0f172a;">{{ number_format($stats['total']) }} Calls</div>
            </div>
        </div>

        <div style="background: white; border-radius: 10px; border: 1px solid #e2e8f0; border-top: 3px solid #16a34a; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="width: 48px; height: 48px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Akumulasi Biaya Subtotal</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #16a34a;">Rp {{ number_format($stats['total_cost'], 0, ',', '.') }}</div>
            </div>
        </div>

        <div style="background: white; border-radius: 10px; border: 1px solid #e2e8f0; border-top: 3px solid {{ $stats['uncounted'] > 0 ? '#ef4444' : '#64748b' }}; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="width: 48px; height: 48px; border-radius: 10px; background: {{ $stats['uncounted'] > 0 ? '#fee2e2' : '#f1f5f9' }}; color: {{ $stats['uncounted'] > 0 ? '#ef4444' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                <i class="fa-solid {{ $stats['uncounted'] > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
            </div>
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Belum Dihitung (Pending)</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: {{ $stats['uncounted'] > 0 ? '#ef4444' : '#0f172a' }};">{{ number_format($stats['uncounted']) }} Records</div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="report-card">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-sliders" style="color: #0284c7;"></i>
                <span>Filter & Eksekusi Hitung Ulang Tarif</span>
            </div>
            @if($stats['uncounted'] > 0)
                <span style="background: #fee2e2; color: #b91c1c; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                    Perhatian: {{ number_format($stats['uncounted']) }} panggilan belum dikalkulasi
                </span>
            @endif
        </div>

        <!-- Filter & Action Form -->
        <div style="padding: 1.5rem; background: #fafbfc; border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 1.5rem;">
                <!-- Filter Range Form -->
                <form action="{{ route('information.recalculating-call') }}" method="GET" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
                    <input type="hidden" name="searched" value="1">
                    <div class="filter-group">
                        <label class="filter-label">Dari Tanggal (YYYY-MM-DD)</label>
                        <input type="date" name="from_date" class="input-text" value="{{ $fromDate }}">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Sampai Tanggal (YYYY-MM-DD)</label>
                        <input type="date" name="to_date" class="input-text" value="{{ $toDate }}">
                    </div>
                    <button type="submit" class="btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i> Tampilkan Data
                    </button>
                    @if($isSearched)
                        <a href="{{ route('information.recalculating-call') }}" class="btn-search" style="background: #64748b; text-decoration: none;">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </form>

                <!-- Recalculate Trigger Form -->
                <form action="{{ route('billing.recalculate') }}" method="POST" onsubmit="return confirmRecalculate();" style="display: flex; align-items: center; gap: 10px;">
                    @csrf
                    <input type="hidden" name="from_date" value="{{ $fromDate }}">
                    <input type="hidden" name="to_date" value="{{ $toDate }}">
                    <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #0284c7, #0369a1); font-weight: 700; padding: 0 20px; height: 36px; box-shadow: 0 4px 10px rgba(2,132,199,0.3);">
                        <i class="fa-solid fa-play"></i> Jalankan Recalculating Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Table CDR Preview -->
        <div class="table-responsive">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ext</th>
                        <th>Department</th>
                        <th>No Tujuan</th>
                        <th>Durasi</th>
                        <th>Area / Zona</th>
                        <th>Tarif Rate</th>
                        <th style="text-align: right;">Biaya</th>
                        <th style="text-align: right;">Subtotal</th>
                        <th style="text-align: center;">Status Hitung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $index => $row)
                        <tr>
                            <td style="text-align: center;">{{ $records->firstItem() + $index }}</td>
                            <td><strong>{{ $row->tglmasuk }}</strong></td>
                            <td>{{ $row->jammasuk }}</td>
                            <td><span style="font-weight: 700; color: #0288d1;">{{ $row->ext_pemanggil }}</span></td>
                            <td>{{ $row->namadepartment ?: '-' }}</td>
                            <td><code>{{ $row->no_tujuan }}</code></td>
                            <td>{{ $row->durasi }}</td>
                            <td>{{ $row->ketarea ?: ($row->zona ?: '-') }}</td>
                            <td>{{ $row->namarate ?: '-' }}</td>
                            <td style="text-align: right;">Rp {{ number_format($row->biaya ?: 0, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 700; color: #16a34a;">Rp {{ number_format($row->subtotal ?: 0, 0, ',', '.') }}</td>
                            <td style="text-align: center;">
                                @if(($row->hitung ?? 0) >= 1)
                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-check"></i> Selesai
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #b91c1c; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-hourglass-half"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                                <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                                Silakan gunakan filter tanggal di atas untuk menampilkan data yang ingin dikalkulasi ulang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 0.8rem; color: #64748b;">
                    Menampilkan <strong>{{ $records->firstItem() }}</strong> - <strong>{{ $records->lastItem() }}</strong> dari <strong>{{ number_format($records->total()) }}</strong> entri
                </div>
                <div>
                    {{ $records->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function confirmRecalculate() {
    return confirm('Apakah Anda yakin ingin menjalankan Recalculating Call untuk rekaman ini?\nProses ini akan menghitung ulang seluruh biaya panggilan berdasarkan Master Tarif yang aktif.');
}
</script>
@endsection
