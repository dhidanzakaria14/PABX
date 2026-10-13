@extends('layouts.app')

@section('title', 'HOME - DASHBOARD ANALITIK PABX')

@section('styles')
<style>
    /* Executive Dashboard Modern Styles */
    .dash-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #334155 100%);
        border-radius: 16px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 1.75rem;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.25);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.25rem;
        position: relative;
        overflow: hidden;
    }

    .dash-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 380px;
        height: 380px;
        background: radial-gradient(circle, rgba(2, 132, 199, 0.25) 0%, transparent 70%);
        pointer-events: none;
    }

    .hero-title {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .hero-subtitle {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .hero-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
        z-index: 2;
    }

    .btn-hero {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-hero-primary {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: white;
        box-shadow: 0 4px 15px rgba(2, 132, 199, 0.35);
    }
    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.45);
    }

    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.12);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
    }
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
    }

    /* Modern KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }

    .kpi-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 1.35rem 1.5rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.1);
    }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .kpi-red::before { background: linear-gradient(90deg, #0284c7, #38bdf8); }
    .kpi-blue::before { background: linear-gradient(90deg, #0288d1, #38bdf8); }
    .kpi-green::before { background: linear-gradient(90deg, #16a34a, #4ade80); }
    .kpi-purple::before { background: linear-gradient(90deg, #7c3aed, #c084fc); }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.85rem;
    }

    .kpi-title {
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
    }

    .kpi-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .kpi-red .kpi-icon { background: #e0f2fe; color: #0284c7; }
    .kpi-blue .kpi-icon { background: #e0f2fe; color: #0288d1; }
    .kpi-green .kpi-icon { background: #dcfce7; color: #16a34a; }
    .kpi-purple .kpi-icon { background: #ede9fe; color: #7c3aed; }

    .kpi-value {
        font-size: 1.7rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .kpi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        color: #475569;
        width: fit-content;
    }

    /* Grid Layout for Charts */
    .analytics-grid-main {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
    }

    .analytics-grid-secondary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
    }

    @media (max-width: 1024px) {
        .analytics-grid-main,
        .analytics-grid-secondary {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px -2px rgba(15, 23, 42, 0.05);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.75rem;
    }

    .chart-title {
        font-size: 0.975rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-tag {
        font-size: 0.725rem;
        font-weight: 700;
        color: #0288d1;
        background: #e0f2fe;
        padding: 3px 8px;
        border-radius: 20px;
    }

    /* Top Extension Leaderboard */
    .leaderboard-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .leaderboard-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .leaderboard-item:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 800;
        color: white;
    }
    .rank-1 { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .rank-3 { background: linear-gradient(135deg, #d97706, #b45309); }
    .rank-other { background: #cbd5e1; color: #475569; }

    /* Green Detail Button */
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
        text-decoration: none;
    }

    .btn-detail-green:hover {
        background: #1b5e20;
        transform: translateY(-1px);
    }

    /* Modal Backdrop */
    .modal-backdrop-custom {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2500;
        padding: 1.25rem;
    }

    .modal-box-custom {
        background: #ffffff;
        width: 100%;
        max-width: 650px;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
        overflow: hidden;
        animation: modalScaleUp 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modalScaleUp {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
@endsection

@section('content')
<div style="max-width: 1500px; margin: 0 auto;">

    <!-- 1. Executive Dashboard Hero Banner -->
    <div class="dash-hero">
        <div>
            <div class="hero-title">
                <i class="fa-solid fa-chart-line" style="color: #ff5252;"></i>
                PABX TELECOMMUNICATION ANALYTICS
            </div>
            <div class="hero-subtitle">
                <span><i class="fa-solid fa-plane-departure" style="color: #38bdf8;"></i> Bandara Angkasa Pura</span>
                <span>&bull;</span>
                <span>Periode Data: <strong>{{ $dateMin ?? '2024-01-01' }}</strong> s/d <strong>{{ $dateMax ?? '2026-10-31' }}</strong></span>
                <span>&bull;</span>
                <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; padding: 2px 8px; border-radius: 20px; font-weight: 700;">
                    <i class="fa-solid fa-circle-check"></i> Server PABX Synced
                </span>
            </div>
        </div>
        <div class="hero-actions">
            <a href="{{ route('information.call-history') }}" class="btn-hero btn-hero-primary">
                <i class="fa-solid fa-table-list"></i> Buka Calls History (CDR)
            </a>
            <button type="button" class="btn-hero btn-hero-secondary" onclick="openSimulasiModal()">
                <i class="fa-solid fa-calculator"></i> Simulasi Billing
            </button>
            <a href="{{ route('information.recalculating-call') }}" class="btn-hero btn-hero-secondary">
                <i class="fa-solid fa-rotate"></i> Hitung Ulang
            </a>
        </div>
    </div>

    <!-- 2. KPI Cards Grid -->
    <div class="kpi-grid">
        <!-- Card 1: Total Cost -->
        <div class="kpi-card kpi-red">
            <div>
                <div class="kpi-header">
                    <span class="kpi-title">Total Tagihan Telepon</span>
                    <div class="kpi-icon"><i class="fa-solid fa-receipt"></i></div>
                </div>
                <div class="kpi-value">Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
            </div>
            <div class="kpi-badge">
                <i class="fa-solid fa-coins" style="color: #0284c7;"></i>
                Rata-rata Rp {{ number_format($avgCostPerCall) }} / call (Inc. PPN)
            </div>
        </div>

        <!-- Card 2: Total Calls -->
        <div class="kpi-card kpi-blue">
            <div>
                <div class="kpi-header">
                    <span class="kpi-title">Total Panggilan (CDR)</span>
                    <div class="kpi-icon"><i class="fa-solid fa-phone-volume"></i></div>
                </div>
                <div class="kpi-value">{{ number_format($totalCalls) }} <span style="font-size: 1rem; font-weight: 600; color: #64748b;">Calls</span></div>
            </div>
            <div class="kpi-badge">
                <i class="fa-solid fa-check-double" style="color: #0288d1;"></i>
                {{ number_format($calculatedCalls) }} terhitung otomatis (PABX)
            </div>
        </div>

        <!-- Card 3: Total Duration -->
        <div class="kpi-card kpi-green">
            <div>
                <div class="kpi-header">
                    <span class="kpi-title">Total Durasi Bicara</span>
                    <div class="kpi-icon"><i class="fa-solid fa-stopwatch"></i></div>
                </div>
                <div class="kpi-value">{{ $formattedTotalDuration }}</div>
            </div>
            <div class="kpi-badge">
                <i class="fa-solid fa-hourglass-end" style="color: #16a34a;"></i>
                Rata-rata {{ $formattedAvgDuration }} per sambungan
            </div>
        </div>

        <!-- Card 4: Total Extensions -->
        <div class="kpi-card kpi-purple">
            <div>
                <div class="kpi-header">
                    <span class="kpi-title">Ekstensi / Phone-ID</span>
                    <div class="kpi-icon"><i class="fa-solid fa-tty"></i></div>
                </div>
                <div class="kpi-value">{{ number_format($totalExtensions) }} <span style="font-size: 1rem; font-weight: 600; color: #64748b;">Ext</span></div>
            </div>
            <div class="kpi-badge">
                <i class="fa-solid fa-building" style="color: #7c3aed;"></i>
                Terdaftar di {{ count($departments) }} departemen bandara
            </div>
        </div>
    </div>

    <!-- 3. Primary Charts Row: Monthly Trend & Category Distribution -->
    <div class="analytics-grid-main">
        <!-- Monthly Trends Chart (Volume Panggilan & Biaya) -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fa-solid fa-chart-area" style="color: #0284c7;"></i>
                    Tren Panggilan & Akumulasi Tagihan (12 Bulan Terakhir)
                </div>
                <span class="chart-tag">Multi-Axis Volume & Biaya</span>
            </div>
            <div style="position: relative; height: 320px; width: 100%;">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Call Category Distribution Chart -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fa-solid fa-chart-pie" style="color: #0288d1;"></i>
                    Distribusi Kategori Panggilan
                </div>
                <span class="chart-tag">Porsi Panggilan</span>
            </div>
            <div style="position: relative; height: 230px; width: 100%; margin-bottom: 0.75rem;">
                <canvas id="categoryPieChart"></canvas>
            </div>
            <!-- Category Mini Stats -->
            <div style="display: flex; flex-direction: column; gap: 6px; font-size: 0.775rem;">
                @foreach($categoryQuery as $cat)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 4px 8px; background: #f8fafc; border-radius: 6px;">
                        <span style="font-weight: 600; color: #334155;">{{ $cat->cat_name }}</span>
                        <div style="display: flex; gap: 10px;">
                            <span style="color: #64748b;">{{ number_format($cat->calls) }} calls</span>
                            <span style="font-weight: 700; color: #16a34a;">Rp {{ number_format($cat->cost, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 4. Secondary Charts Row: Hourly Peak Traffic & Top 5 Extensions -->
    <div class="analytics-grid-secondary">
        <!-- 24-Hour Peak Time Traffic -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fa-solid fa-business-time" style="color: #f59e0b;"></i>
                    Distribusi Jam Sibuk Trafik PABX (24 Jam)
                </div>
                <span class="chart-tag" style="background: #fef3c7; color: #d97706;">Pola Jam Bandara</span>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="hourlyPeakChart"></canvas>
            </div>
        </div>

        <!-- Top 5 Active Extensions Leaderboard -->
        <div class="chart-card">
            <div class="chart-header">
                <div class="chart-title">
                    <i class="fa-solid fa-trophy" style="color: #eab308;"></i>
                    Top 5 Ekstensi Teraktif (Paling Banyak Panggilan)
                </div>
                <a href="{{ route('reports.phone-id-summary') }}" style="font-size: 0.75rem; font-weight: 700; color: #0288d1; text-decoration: none;">
                    Lihat Semua &rarr;
                </a>
            </div>
            <div class="leaderboard-list">
                @foreach($topExtensions as $idx => $ext)
                    @php
                        $rankClass = $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : 'rank-other'));
                        $durHours = floor($ext->total_duration / 3600);
                        $durMins = floor(($ext->total_duration % 3600) / 60);
                    @endphp
                    <div class="leaderboard-item">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="rank-badge {{ $rankClass }}">#{{ $idx + 1 }}</div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 1rem; font-weight: 800; color: #0f172a;">Ext {{ $ext->ext_pemanggil }}</span>
                                    <span style="font-size: 0.725rem; font-weight: 700; background: #e0f2fe; color: #0288d1; padding: 1px 6px; border-radius: 4px;">
                                        {{ $ext->namadepartment ?: 'UMUM' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;">
                                    Total Durasi: <strong>{{ sprintf('%02d:%02d', $durHours, $durMins) }}</strong> jam
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.05rem; font-weight: 800; color: #0288d1;">
                                {{ number_format($ext->total_calls) }} <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">calls</span>
                            </div>
                            <div style="font-size: 0.8rem; font-weight: 700; color: #16a34a; margin-top: 1px;">
                                Rp {{ number_format($ext->total_cost, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 5. Recent 10 Calls Feed (Real-Time CDR Monitor) -->
    <div class="report-card" style="margin-top: 0.5rem;">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i>
                <span>10 Panggilan Terkini (Live Feed CDR PABX)</span>
            </div>
            <a href="{{ route('information.call-history') }}" class="btn-search" style="height: 30px; padding: 0 14px; text-decoration: none; font-size: 0.775rem;">
                <i class="fa-solid fa-list-check"></i> Buka Semua Riwayat di Menu Information &rarr;
            </a>
        </div>

        <div class="table-responsive">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Ext Pemanggil</th>
                        <th>Departemen</th>
                        <th>Nomor Tujuan</th>
                        <th>Durasi</th>
                        <th>Zona / Area</th>
                        <th style="text-align: right;">Total Biaya</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCalls as $index => $row)
                        <tr>
                            <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                            <td><strong>{{ $row->tglmasuk }}</strong></td>
                            <td style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem; color: #475569;">{{ $row->jammasuk }}</td>
                            <td>
                                <span style="font-weight: 800; color: #0288d1; font-size: 0.875rem;">{{ $row->ext_pemanggil }}</span>
                            </td>
                            <td>{{ $row->namadepartment ?: '-' }}</td>
                            <td>
                                <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-weight: 600; color: #0f172a;">{{ $row->no_tujuan }}</code>
                            </td>
                            <td>{{ $row->durasi }}</td>
                            <td>
                                @php
                                    $areaBadge = '#64748b';
                                    if(str_contains(strtoupper($row->ketarea ?? ''), 'CELL')) $areaBadge = '#10b981';
                                    elseif(str_contains(strtoupper($row->ketarea ?? ''), 'LOCAL') || str_contains(strtoupper($row->ketarea ?? ''), 'DENPASAR')) $areaBadge = '#0288d1';
                                @endphp
                                <span style="font-weight: 600; color: {{ $areaBadge }};">
                                    {{ $row->ketarea ?: ($row->zona ?: '-') }}
                                </span>
                            </td>
                            <td style="text-align: right; font-weight: 800; color: {{ ($row->subtotal ?? 0) > 0 ? '#16a34a' : '#64748b' }};">
                                Rp {{ number_format($row->subtotal ?: 0, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn-detail-green" onclick="showCallDetail({{ json_encode($row) }})">
                                    <i class="fa-solid fa-circle-info"></i> Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                                Belum ada rekaman panggilan PABX.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding: 0.85rem 1.5rem; background: #fafbfc; border-top: 1px solid #e2e8f0; font-size: 0.775rem; color: #64748b; display: flex; justify-content: space-between; align-items: center;">
            <span><i class="fa-solid fa-circle-check" style="color: #16a34a;"></i> Menampilkan 10 panggilan terakhir yang direkam oleh buffer SMDR PABX.</span>
            <a href="{{ route('information.call-history') }}" style="color: #0288d1; font-weight: 700; text-decoration: none;">
                Lihat 51.008 rekaman lengkap di Information &gt; Call History &rarr;
            </a>
        </div>
    </div>
</div>

<!-- Modal Call Detail -->
<div id="callDetailModal" class="modal-backdrop-custom" style="display: none;">
    <div class="modal-box-custom">
        <div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 1.15rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #0284c7; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-phone" style="font-size: 0.85rem; color: white;"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; margin: 0;">RINCIAN ENTRI PANGGILAN CDR</h4>
                    <span id="dtRecordId" style="font-size: 0.75rem; color: #94a3b8;">ID: -</span>
                </div>
            </div>
            <button type="button" onclick="closeCallDetailModal()" style="background: transparent; border: none; color: #94a3b8; font-size: 1.25rem; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.725rem; color: #64748b; font-weight: 700;">WAKTU PANGGILAN</div>
                    <div id="dtDateTime" style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.725rem; color: #64748b; font-weight: 700;">DURASI BICARA</div>
                    <div id="dtDuration" style="font-size: 0.9rem; font-weight: 800; color: #0288d1;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.725rem; color: #64748b; font-weight: 700;">EKSTENSI PEMANGGIL</div>
                    <div id="dtExt" style="font-size: 0.9rem; font-weight: 800; color: #0f172a;">-</div>
                    <div id="dtDept" style="font-size: 0.75rem; color: #64748b;">-</div>
                </div>
                <div style="background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-size: 0.725rem; color: #64748b; font-weight: 700;">NOMOR TUJUAN</div>
                    <div id="dtDialed" style="font-size: 0.95rem; font-weight: 800; color: #0f172a; font-family: monospace;">-</div>
                </div>
            </div>

            <!-- Billing Summary Box -->
            <div style="background: #ecfdf5; border: 1.5px solid #a7f3d0; border-radius: 10px; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.75rem; font-weight: 700; color: #065f46;">TOTAL TAGIHAN BIAYA</div>
                    <div id="dtRateName" style="font-size: 0.75rem; color: #047857;">Tarif: -</div>
                </div>
                <div id="dtSubtotal" style="font-size: 1.45rem; font-weight: 900; color: #047857;">
                    Rp 0
                </div>
            </div>
        </div>
        <div style="padding: 1rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeCallDetailModal()" class="btn-search" style="background: #64748b;">
                Tutup Rincian
            </button>
        </div>
    </div>
</div>

<!-- Modal Simulasi Billing (Menggunakan Rumus PABX Asli) -->
<div id="simulasiModal" class="modal-backdrop-custom" style="display: none;">
    <div class="modal-box-custom" style="max-width: 600px;">
        <div style="background: linear-gradient(135deg, #15803d, #166534); color: white; padding: 1.15rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-calculator" style="font-size: 0.95rem;"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; margin: 0;">SIMULASI PERHITUNGAN BIAYA PABX</h4>
                    <span style="font-size: 0.725rem; opacity: 0.9;">Kalkulator Tarif Berdasarkan Prefix & Zona</span>
                </div>
            </div>
            <button type="button" onclick="closeSimulasiModal()" style="background: transparent; border: none; color: white; font-size: 1.25rem; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="simulasiForm" onsubmit="executeSimulation(event)" style="padding: 1.5rem;">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div class="filter-group">
                    <label class="filter-label">Nomor Tujuan / Dialed <span style="color: #ef4444;">*</span></label>
                    <input type="text" id="simDialed" name="dialed" class="input-text" placeholder="Contoh: 08113866479" required style="width: 100%;">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Durasi Bicara (Detik) <span style="color: #ef4444;">*</span></label>
                    <input type="number" id="simDuration" name="durasi_detik" class="input-text" placeholder="Contoh: 120" value="60" required style="width: 100%;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div class="filter-group">
                    <label class="filter-label">Ekstensi Pemanggil</label>
                    <input type="text" id="simExt" name="ext" class="input-text" placeholder="Contoh: 569" style="width: 100%;">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Waktu Panggilan</label>
                    <input type="time" id="simTime" name="jam" class="input-text" value="{{ date('H:i') }}" style="width: 100%;">
                </div>
            </div>
            <button type="submit" id="btnSimulateSubmit" class="btn-search" style="width: 100%; height: 38px; justify-content: center; background: #16a34a; font-size: 0.875rem;">
                <i class="fa-solid fa-play"></i> Hitung Biaya Sekarang
            </button>
        </form>

        <!-- Result Box -->
        <div id="simulasiResultBox" style="display: none; padding: 1.25rem; margin: 0 1.5rem 1.5rem; background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; font-weight: 700; color: #166534;">HASIL PERHITUNGAN BIAYA</span>
                <span id="simResultCost" style="font-size: 1.35rem; font-weight: 900; color: #15803d;">Rp 0</span>
            </div>
            <div style="font-size: 0.775rem; color: #374151; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                <div>Keterangan: <strong id="simResultArea">-</strong></div>
                <div>Zona: <strong id="simResultZone">-</strong></div>
                <div>Tarif Rate: <strong id="simResultRate">-</strong></div>
                <div>Formula: <code id="simResultFormula">-</code></div>
            </div>
        </div>
    </div>
</div>

<!-- Offline Chart.js Bundle -->
<script src="{{ asset('plugins/chartjs/Chart.bundle.min.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Monthly Trends Chart (Multi-Axis)
    var ctxMonthly = document.getElementById('monthlyTrendChart').getContext('2d');
    var monthlyChart = new Chart(ctxMonthly, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthLabels) !!},
            datasets: [
                {
                    label: 'Volume Panggilan (Calls)',
                    data: {!! json_encode($monthCalls) !!},
                    type: 'bar',
                    backgroundColor: 'rgba(2, 136, 209, 0.75)',
                    borderColor: '#0288d1',
                    borderWidth: 1,
                    yAxisID: 'yCalls',
                    order: 2
                },
                {
                    label: 'Biaya Tagihan (Ribu Rp)',
                    data: {!! json_encode($monthCostsInK) !!},
                    type: 'line',
                    borderColor: '#0284c7',
                    backgroundColor: 'rgba(2, 132, 199, 0.12)',
                    pointBackgroundColor: '#0284c7',
                    pointRadius: 4,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3,
                    yAxisID: 'yCost',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: { display: false }
                }],
                yAxes: [
                    {
                        id: 'yCalls',
                        type: 'linear',
                        position: 'left',
                        ticks: { beginAtZero: true },
                        scaleLabel: { display: true, labelString: 'Panggilan' }
                    },
                    {
                        id: 'yCost',
                        type: 'linear',
                        position: 'right',
                        ticks: { beginAtZero: true },
                        gridLines: { drawOnChartArea: false },
                        scaleLabel: { display: true, labelString: 'Ribu Rp (x1.000)' }
                    }
                ]
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                        if (tooltipItem.datasetIndex === 1) {
                            return datasetLabel + ': Rp ' + (tooltipItem.yLabel * 1000).toLocaleString('id-ID');
                        }
                        return datasetLabel + ': ' + tooltipItem.yLabel.toLocaleString('id-ID') + ' Calls';
                    }
                }
            }
        }
    });

    // 2. Category Pie / Doughnut Chart
    var ctxCategory = document.getElementById('categoryPieChart').getContext('2d');
    var categoryChart = new Chart(ctxCategory, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($catLabels) !!},
            datasets: [{
                data: {!! json_encode($catCalls) !!},
                backgroundColor: {!! json_encode($chartCatColors) !!},
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
                labels: { boxWidth: 12, fontSize: 11 }
            },
            cutoutPercentage: 65,
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var label = data.labels[tooltipItem.index] || '';
                        var val = data.datasets[0].data[tooltipItem.index];
                        return label + ': ' + val.toLocaleString('id-ID') + ' Calls';
                    }
                }
            }
        }
    });

    // 3. Hourly Peak Traffic Chart
    var ctxHourly = document.getElementById('hourlyPeakChart').getContext('2d');
    var hourlyChart = new Chart(ctxHourly, {
        type: 'bar',
        data: {
            labels: {!! json_encode($hourLabels) !!},
            datasets: [{
                label: 'Jumlah Panggilan',
                data: {!! json_encode($hourCalls) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.75)',
                borderColor: '#d97706',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    gridLines: { display: false },
                    ticks: { maxRotation: 45, minRotation: 45, fontSize: 10 }
                }],
                yAxes: [{
                    ticks: { beginAtZero: true },
                    scaleLabel: { display: true, labelString: 'Volume Panggilan' }
                }]
            }
        }
    });
});

// Modal Functions
function showCallDetail(record) {
    document.getElementById('dtRecordId').textContent = 'ID: #' + (record.id || '-');
    document.getElementById('dtDateTime').textContent = (record.tglmasuk || '-') + ' ' + (record.jammasuk || '');
    document.getElementById('dtDuration').textContent = (record.durasi || '-') + ' (' + (record.durasi_detik || 0) + ' detik)';
    document.getElementById('dtExt').textContent = record.ext_pemanggil || '-';
    document.getElementById('dtDept').textContent = record.namadepartment || 'Dept Tidak Terdaftar';
    document.getElementById('dtDialed').textContent = record.no_tujuan || '-';
    document.getElementById('dtRateName').textContent = 'Tarif: ' + (record.namarate || 'Standar') + ' (' + (record.ketarea || record.zona || '-') + ')';
    
    var subtotal = parseInt(record.subtotal || 0);
    document.getElementById('dtSubtotal').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

    document.getElementById('callDetailModal').style.display = 'flex';
}

function closeCallDetailModal() {
    document.getElementById('callDetailModal').style.display = 'none';
}

function openSimulasiModal() {
    document.getElementById('simulasiModal').style.display = 'flex';
}

function closeSimulasiModal() {
    document.getElementById('simulasiModal').style.display = 'none';
    document.getElementById('simulasiResultBox').style.display = 'none';
}

// Simulasi Billing Ajax Execution
function executeSimulation(e) {
    e.preventDefault();
    var btn = document.getElementById('btnSimulateSubmit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghitung...';

    var dialed = document.getElementById('simDialed').value;
    var duration = document.getElementById('simDuration').value;
    var ext = document.getElementById('simExt').value;
    var time = document.getElementById('simTime').value;

    fetch("{{ route('billing.simulate') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            dialed: dialed,
            durasi_detik: duration,
            ext: ext,
            jam: time
        })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-play"></i> Hitung Biaya Sekarang';

        if (data.status === 'success') {
            document.getElementById('simResultCost').textContent = 'Rp ' + parseInt(data.subtotal || 0).toLocaleString('id-ID');
            document.getElementById('simResultArea').textContent = data.area || '-';
            document.getElementById('simResultZone').textContent = data.zona || '-';
            document.getElementById('simResultRate').textContent = data.rate_name || '-';
            document.getElementById('simResultFormula').textContent = data.formula || '-';
            document.getElementById('simulasiResultBox').style.display = 'block';
        } else {
            alert('Gagal menghitung: ' + (data.message || 'Terjadi kesalahan sistem'));
        }
    })
    .catch(function(err) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-play"></i> Hitung Biaya Sekarang';
        alert('Terjadi kesalahan jaringan/server');
    });
}
</script>
@endsection
