@extends('layouts.app')

@section('title', 'CALLS HISTORY - ANGKASA PURA')

@section('styles')
<style>
    /* Green Detail Button (Menyesuaikan dengan tombol hijau asli sistem PABX) */
    .btn-detail-green {
        height: 26px;
        padding: 0 10px;
        background: #2e7d32;
        color: white;
        border: none;
        border-radius: 3px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background 0.15s ease, transform 0.1s ease;
    }

    .btn-detail-green:hover {
        background: #1b5e20;
        transform: translateY(-1px);
    }

    /* Modal Backdrop for Detail Call Card */
    .detail-call-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2500;
        padding: 1.25rem;
        animation: fadeInOverlay 0.15s ease-out;
    }

    /* Detail Call Card Dialog */
    .detail-call-card {
        background: #ffffff;
        width: 100%;
        max-width: 680px;
        border-radius: 10px;
        box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: scaleUpCard 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Header Card (Angkasa Pura Red Tone) */
    .detail-card-header {
        background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
        padding: 1.15rem 1.5rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .detail-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .detail-header-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        color: #ffffff;
    }

    .detail-header-title {
        font-size: 1.05rem;
        font-weight: 800;
        letter-spacing: 0.02em;
        line-height: 1.2;
    }

    .detail-header-subtitle {
        font-size: 0.75rem;
        color: #fecdd3;
        margin-top: 2px;
    }

    .detail-close-x {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.5rem;
        cursor: pointer;
        line-height: 1;
        padding: 4px;
        transition: color 0.15s, transform 0.15s;
    }

    .detail-close-x:hover {
        color: #ffffff;
        transform: scale(1.1);
    }

    /* Body Card */
    .detail-card-body {
        padding: 1.5rem 1.75rem;
        max-height: 78vh;
        overflow-y: auto;
    }

    .detail-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 1.25rem;
    }

    .detail-cell {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.85rem 1rem;
        transition: border-color 0.15s;
    }

    .detail-cell:hover {
        border-color: #cbd5e1;
    }

    .detail-cell-label {
        font-size: 0.725rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-cell-label i {
        color: #d32f2f;
        font-size: 0.8rem;
    }

    .detail-cell-value {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-all;
    }

    /* Footer Card */
    .detail-card-footer {
        padding: 1rem 1.75rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-detail-back {
        padding: 8px 18px;
        background: #64748b;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-detail-back:hover {
        background: #475569;
    }

    @keyframes fadeInOverlay {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes scaleUpCard {
        from { opacity: 0; transform: scale(0.95) translateY(8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endsection

@section('content')
<!-- KPI Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #d32f2f;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Tagihan Telepon</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #d32f2f; margin-top: 4px;">
            Rp {{ number_format($totalCost, 0, ',', '.') }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Termasuk PPN 11%</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #0288d1;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Panggilan (CDR)</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #0288d1; margin-top: 4px;">
            {{ number_format($totalCalls) }} Calls
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Data Rekaman Masuk</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #2e7d32;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Durasi Bicara</div>
        @php
            $hrs = floor($totalDurationSec / 3600);
            $mins = floor(($totalDurationSec % 3600) / 60);
            $secs = $totalDurationSec % 60;
        @endphp
        <div style="font-size: 1.5rem; font-weight: 800; color: #2e7d32; margin-top: 4px;">
            {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">{{ round($totalDurationSec / 60, 1) }} Menit</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #f57c00;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Ekstensi / Phone-ID</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #f57c00; margin-top: 4px;">
            {{ $departments->count() }} Ext
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Terdaftar di PABX</div>
    </div>
</div>

<!-- Main Table Card: CALLS HISTORY -->
<div class="report-card">
    <div class="report-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-phone" style="color: #d32f2f;"></i>
            <span>CALLS HISTORY</span>
            @if(isset($dateMin) && isset($dateMax))
                <span style="font-size: 0.75rem; font-weight: normal; color: #64748b; margin-left: 8px;">
                    ({{ number_format($totalCalls) }} entri panggilan)
                </span>
            @endif
        </div>
        <button type="button" onclick="openSimModal()" class="btn-search" style="background: #2e7d32; font-size: 0.75rem; padding: 0 10px;">
            <i class="fa-solid fa-calculator"></i> Simulasi Billing
        </button>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('home') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        <div class="filter-group">
            <label class="filter-label">From Date</label>
            <input type="date" name="start_date" class="input-text" value="{{ request('start_date') }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">To Date</label>
            <input type="date" name="end_date" class="input-text" value="{{ request('end_date') }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">Phone ID / Extension</label>
            <select name="department_id" class="select-box">
                <option value="">All Extensions</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->extcode }} - {{ $dept->extname }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Destination Zone</label>
            <select name="zone_id" class="select-box">
                <option value="">All Zones</option>
                @foreach($zones as $z)
                    <option value="{{ $z->idzone }}" {{ request('zone_id') == $z->idzone ? 'selected' : '' }}>
                        {{ $z->namazone }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Search Dialed / CDR</label>
            <input type="text" name="search" class="input-text" placeholder="No Tujuan / Ext..." value="{{ request('search') }}">
        </div>

        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Search Data
        </button>

        <a href="{{ route('home') }}" class="btn-export" style="background: #64748b;" title="Reset Filter">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>

    <!-- Quick Presets -->
    <div style="padding: 0.5rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.775rem;">
        <span style="color: #64748b; font-weight: 600;">Filter Cepat:</span>
        <a href="{{ route('home') }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ !request()->has('start_date') && !request()->has('search') ? '#d32f2f; color: white;' : '#e2e8f0; color: #334155;' }}">Semua Data</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2026-08-01', 'end_date' => '2026-08-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2026-08-01' ? '#d32f2f; color: white;' : '#e2e8f0; color: #334155;' }}">Bulan Terakhir (Agu 2026)</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2026-01-01', 'end_date' => '2026-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2026-01-01' && request('end_date') == '2026-12-31' ? '#d32f2f; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2026</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2025-01-01', 'end_date' => '2025-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2025-01-01' ? '#d32f2f; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2025</a>
    </div>

    <!-- Table: Menyesuaikan Kolom Sistem Asli (No, Date, Time, No Trunk, Extentions, Duration, Destionation, Access, Pulsa, Total Bill, Detail) -->
    <div class="table-responsive">
        <table class="ap-table">
            <thead>
                <tr>
                    <th style="width: 45px;" class="text-center">No</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>No Trunk</th>
                    <th>Extentions</th>
                    <th class="text-right">Duration</th>
                    <th>Destionation</th>
                    <th>Access</th>
                    <th class="text-center">Pulsa</th>
                    <th class="text-right">Total Bill</th>
                    <th class="text-center" style="width: 85px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @php $no = $records->firstItem() ?: 1; @endphp
                @forelse($records as $rec)
                    @php
                        $callDate = $rec->tglmasuk ? \Carbon\Carbon::parse($rec->tglmasuk)->format('d/m/y') : '-';
                        $callTime = $rec->jammasuk ?: '-';
                        $trunk = $rec->noline ?: ($rec->no_trunk ?: '103');
                        $ext = $rec->ext_pemanggil ?: '-';
                        $userName = $rec->department ? $rec->department->extname : ($rec->namadepartment ?: 'ARO');
                        $dest = $rec->no_tujuan ?: '-';
                        $dur = $rec->durasi ?: '00:00:00';
                        $access = $rec->kodeakses ?: '-';
                        $pulsa = $rec->pulsa ?? 1;
                        $totalBill = number_format($rec->subtotal, 0, ',', '.');
                        $area = $rec->ketarea ?: ($rec->zona ?: 'Internal Call');
                        $zone = $rec->zona ?: 'X';
                        $rateName = $rec->namarate ?: 'X';
                        $rateTime = $rec->formularate ?: '0/3600';
                        $additional = $rec->tambahan ?? 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $callDate }}</td>
                        <td style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;">{{ $callTime }}</td>
                        <td>{{ $trunk }}</td>
                        <td><strong style="font-family: 'JetBrains Mono', monospace;">{{ $ext }}</strong></td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $dur }}</td>
                        <td><strong style="font-family: 'JetBrains Mono', monospace; color: #0f172a;">{{ $dest }}</strong></td>
                        <td>{{ $access }}</td>
                        <td class="text-center">{{ $pulsa }}</td>
                        <td class="text-right" style="font-weight: 700; color: {{ $rec->subtotal > 0 ? '#d32f2f' : '#334155' }};">
                            {{ $totalBill }}
                        </td>
                        <td class="text-center">
                            <!-- Tombol Hijau Detail Interaktif (Membuka Card Dialog) -->
                            <button type="button" 
                                class="btn-detail-green"
                                onclick="openDetailCallModal(this)"
                                data-calling-time="{{ $callDate }} {{ $callTime }}"
                                data-trunk="{{ $trunk }}"
                                data-ext="{{ $ext }}"
                                data-user-name="{{ $userName }}"
                                data-dest="{{ $dest }}"
                                data-duration="{{ $dur }}"
                                data-area="{{ $area }}"
                                data-zone="{{ $zone }}"
                                data-rate-name="{{ $rateName }}"
                                data-rate-time="{{ $rateTime }}"
                                data-additional="{{ $additional }}"
                                data-pulsa="{{ $pulsa }}"
                                data-total-charge="{{ $totalBill }}"
                                title="Klik untuk melihat Detail Call dalam bentuk Card"
                            >
                                <i class="fa-solid fa-file-lines" style="font-size: 0.7rem;"></i> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 2.5rem 1rem; color: #64748b;">
                            <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"><i class="fa-solid fa-inbox"></i></div>
                            <strong style="color: #334155; font-size: 0.95rem;">Tidak ada rekaman panggilan yang cocok dengan kriteria pencarian.</strong>
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; padding: 6px 14px; background: #d32f2f;">
                                    <i class="fa-solid fa-rotate-left"></i> Tampilkan Semua Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="font-weight: 700;">TOTAL</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace; font-weight: 700;">
                        {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
                    </td>
                    <td colspan="3"></td>
                    <td class="text-right" style="font-weight: 800; color: #d32f2f;">
                        Rp {{ number_format($totalCost, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($records->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #e2e8f0; background: #ffffff;">
            {{ $records->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

<!-- ========================================== -->
<!-- MODAL CARD: DETAIL CALL RECORD (POPOVER)    -->
<!-- ========================================== -->
<div id="detailCallModal" class="detail-call-backdrop" style="display: none;" onclick="handleBackdropClick(event)">
    <div class="detail-call-card">
        <!-- Header Card: Angkasa Pura Red -->
        <div class="detail-card-header">
            <div class="detail-header-left">
                <div class="detail-header-icon">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <div>
                    <div class="detail-header-title">DETAIL CALL</div>
                    <div class="detail-header-subtitle">Rincian Informasi Panggilan Telepon PABX</div>
                </div>
            </div>
            <button type="button" onclick="closeDetailCallModal()" class="detail-close-x" title="Tutup Card">&times;</button>
        </div>

        <!-- Body Card: Grid 2 Kolom Rapi -->
        <div class="detail-card-body">
            <div class="detail-info-grid">
                <!-- Calling Time -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Calling Time</span>
                    </div>
                    <div class="detail-cell-value" id="cardCallingTime">-</div>
                </div>

                <!-- No Trunk -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-network-wired"></i>
                        <span>No Trunk</span>
                    </div>
                    <div class="detail-cell-value" id="cardTrunk">-</div>
                </div>

                <!-- Extention -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-phone"></i>
                        <span>Extention</span>
                    </div>
                    <div class="detail-cell-value" id="cardExt" style="color: #d32f2f;">-</div>
                </div>

                <!-- User Name -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-user"></i>
                        <span>User Name</span>
                    </div>
                    <div class="detail-cell-value" id="cardUserName">-</div>
                </div>

                <!-- Destination No -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-phone-flip"></i>
                        <span>Destination No</span>
                    </div>
                    <div class="detail-cell-value" id="cardDest" style="color: #0288d1;">-</div>
                </div>

                <!-- Duration -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-clock"></i>
                        <span>Duration</span>
                    </div>
                    <div class="detail-cell-value" id="cardDuration" style="font-family: 'JetBrains Mono', monospace;">-</div>
                </div>

                <!-- Area -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Area</span>
                    </div>
                    <div class="detail-cell-value" id="cardArea">-</div>
                </div>

                <!-- Zone -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-map"></i>
                        <span>Zone</span>
                    </div>
                    <div class="detail-cell-value" id="cardZone">-</div>
                </div>

                <!-- Rate Name -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-tag"></i>
                        <span>Rate Name</span>
                    </div>
                    <div class="detail-cell-value" id="cardRateName">-</div>
                </div>

                <!-- Rate / Time -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Rate / Time</span>
                    </div>
                    <div class="detail-cell-value" id="cardRateTime">-</div>
                </div>

                <!-- Additional & Pulsa -->
                <div class="detail-cell">
                    <div class="detail-cell-label">
                        <i class="fa-solid fa-signal"></i>
                        <span>Additional / Pulsa</span>
                    </div>
                    <div class="detail-cell-value" id="cardAdditional">-</div>
                </div>

                <!-- Total Charge -->
                <div class="detail-cell" style="background: #f0fdf4; border-color: #bbf7d0;">
                    <div class="detail-cell-label" style="color: #166534;">
                        <i class="fa-solid fa-receipt" style="color: #166534;"></i>
                        <span>Total Charge</span>
                    </div>
                    <div class="detail-cell-value" id="cardTotalCharge" style="color: #166534; font-size: 1.15rem; font-weight: 800;">-</div>
                </div>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="detail-card-footer">
            <button type="button" onclick="closeDetailCallModal()" class="btn-detail-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat Panggilan
            </button>
        </div>
    </div>
</div>

<!-- Modal Simulasi Hitung Billing -->
<div id="simModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 4px; width: 90%; max-width: 500px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
            <h3 style="font-size: 1rem; color: #d32f2f; text-transform: uppercase;">Simulasi Kalkulator Billing PABX</h3>
            <button type="button" onclick="closeSimModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
        
        <div class="filter-group" style="margin-bottom: 0.85rem;">
            <label class="filter-label">Pilih Phone-ID / Ekstensi</label>
            <select id="sim_ext" class="select-box" style="width: 100%;">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">Ext {{ $dept->extcode }} - {{ $dept->extname }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group" style="margin-bottom: 0.85rem;">
            <label class="filter-label">Nomor Telepon Tujuan</label>
            <input type="text" id="sim_dest" class="input-text" style="width: 100%;" value="08129876543">
        </div>

        <div class="filter-group" style="margin-bottom: 1rem;">
            <label class="filter-label">Durasi Bicara (Detik)</label>
            <input type="number" id="sim_dur" class="input-text" style="width: 100%;" value="180">
        </div>

        <button type="button" onclick="runSim()" class="btn-search" style="width: 100%; justify-content: center; height: 36px; background: #d32f2f;">
            <i class="fa-solid fa-calculator"></i> Hitung Tagihan
        </button>

        <div id="sim_res" style="display: none; margin-top: 1rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.85rem; font-size: 0.85rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Zona / Operator:</span> <strong id="res_z">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Formula Rate:</span> <span id="res_f">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Biaya Pokok:</span> <span id="res_b">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 6px; font-weight: 700; color: #d32f2f;">
                <span>TOTAL CHARGE:</span> <span id="res_t">-</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Buka Modal Card Detail Call
    function openDetailCallModal(btn) {
        document.getElementById('cardCallingTime').textContent = btn.getAttribute('data-calling-time') || '-';
        document.getElementById('cardTrunk').textContent = btn.getAttribute('data-trunk') || '-';
        document.getElementById('cardExt').textContent = btn.getAttribute('data-ext') || '-';
        document.getElementById('cardUserName').textContent = btn.getAttribute('data-user-name') || '-';
        document.getElementById('cardDest').textContent = btn.getAttribute('data-dest') || '-';
        document.getElementById('cardDuration').textContent = btn.getAttribute('data-duration') || '-';
        document.getElementById('cardArea').textContent = btn.getAttribute('data-area') || '-';
        document.getElementById('cardZone').textContent = btn.getAttribute('data-zone') || '-';
        document.getElementById('cardRateName').textContent = btn.getAttribute('data-rate-name') || '-';
        document.getElementById('cardRateTime').textContent = btn.getAttribute('data-rate-time') || '-';
        
        const additional = btn.getAttribute('data-additional') || '0';
        const pulsa = btn.getAttribute('data-pulsa') || '1';
        document.getElementById('cardAdditional').textContent = `${additional} (Pulsa: ${pulsa})`;
        
        document.getElementById('cardTotalCharge').textContent = btn.getAttribute('data-total-charge') || 'Rp 0';

        document.getElementById('detailCallModal').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Kunci scroll halaman saat card muncul
    }

    // Tutup Modal Card Detail Call
    function closeDetailCallModal() {
        document.getElementById('detailCallModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik di luar card dialog
    function handleBackdropClick(event) {
        if (event.target.id === 'detailCallModal') {
            closeDetailCallModal();
        }
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailCallModal();
            closeSimModal();
        }
    });

    // Simulasi Billing
    function openSimModal() {
        document.getElementById('simModal').style.display = 'flex';
    }
    function closeSimModal() {
        document.getElementById('simModal').style.display = 'none';
    }
    function runSim() {
        const ext = document.getElementById('sim_ext').value;
        const dest = document.getElementById('sim_dest').value;
        const dur = document.getElementById('sim_dur').value;
        fetch('{{ route('billing.simulate') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ext_id: ext, destination: dest, duration_sec: dur })
        })
        .then(r => r.json())
        .then(d => {
            if(d.success) {
                document.getElementById('res_z').textContent = d.zone + ' (' + d.prefix_ket + ')';
                document.getElementById('res_f').textContent = d.formula;
                document.getElementById('res_b').textContent = 'Rp ' + Number(d.biaya).toLocaleString('id-ID');
                document.getElementById('res_t').textContent = d.subtotal_formatted;
                document.getElementById('sim_res').style.display = 'block';
            }
        });
    }
</script>
@endsection
