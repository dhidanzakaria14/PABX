@extends('layouts.app')

@section('title', 'Cek Billing Telepon & Rekapitulasi PABX')

@section('styles')
<style>
    .grid-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        padding: 1.5rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .icon-indigo { background: rgba(99, 102, 241, 0.15); color: #818cf8; border: 1px solid rgba(99, 102, 241, 0.3); }
    .icon-emerald { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .icon-amber { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .icon-cyan { background: rgba(6, 182, 212, 0.15); color: #38bdf8; border: 1px solid rgba(6, 182, 212, 0.3); }

    .stat-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted);
        margin-bottom: 0.25rem;
        font-weight: 600;
    }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.02em;
    }

    .stat-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 0.25rem;
    }

    /* Layout grid */
    .grid-main {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 1024px) {
        .grid-main { grid-template-columns: 1fr; }
    }

    /* Filter Bar */
    .filter-card {
        margin-bottom: 1.5rem;
        padding: 1.25rem 1.5rem;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) auto;
        gap: 1rem;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .form-control, .form-select {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 0.6rem 0.85rem;
        color: white;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }

    /* Table */
    .table-container {
        overflow-x: auto;
    }

    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
        text-align: left;
    }

    .custom-table th {
        background: rgba(15, 23, 42, 0.8);
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.725rem;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border-glass);
    }

    .custom-table td {
        padding: 0.9rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
    }

    .custom-table tr:hover td {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-cell { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-local { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-sljj { background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); }
    .badge-idd { background: rgba(244, 63, 94, 0.15); color: #fb7185; border: 1px solid rgba(244, 63, 94, 0.3); }

    .cost-highlight {
        font-family: 'JetBrains Mono', monospace;
        font-weight: 700;
        color: #34d399;
    }

    .ext-pill {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background: rgba(99, 102, 241, 0.15);
        color: #c7d2fe;
        border-radius: 6px;
        font-weight: 700;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
    }

    /* Simulation Box */
    .sim-result {
        margin-top: 1rem;
        background: #0b0f19;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid rgba(99, 102, 241, 0.3);
        display: none;
    }

    .sim-row {
        display: flex;
        justify-content: space-between;
        padding: 0.4rem 0;
        font-size: 0.85rem;
        border-bottom: 1px dashed rgba(255, 255, 255, 0.08);
    }

    .sim-row:last-child {
        border-bottom: none;
        padding-top: 0.75rem;
        font-weight: 700;
        font-size: 1rem;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 200;
        align-items: center;
        justify-content: center;
    }

    .modal.active { display: flex; }

    .modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        width: 90%;
        max-width: 520px;
        padding: 1.75rem;
        box-shadow: var(--shadow-lg);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .close-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-size: 1.25rem;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
    <!-- Top Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.85rem; margin-bottom: 0.35rem;">
                <i class="fa-solid fa-calculator" style="color: #818cf8; margin-right: 0.5rem;"></i>
                Monitoring Billing Telepon PABX
            </h1>
            <p class="text-muted">
                {{ $profile ? $profile->namaperusahaan : 'PT Telekomunikasi Solusindo' }} — PABX CDR Logging & Real-time Billing Engine
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button onclick="openModal()" class="btn btn-emerald">
                <i class="fa-solid fa-plus"></i> Catat Panggilan Baru
            </button>
            <a href="{{ route('billing.schema') }}" class="btn btn-secondary">
                <i class="fa-solid fa-sitemap"></i> Cek Relasi Database
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid-stats">
        <div class="card stat-card">
            <div class="stat-icon icon-emerald">
                <i class="fa-solid fa-rupiah-sign"></i>
            </div>
            <div>
                <div class="stat-title">Total Tagihan Telepon</div>
                <div class="stat-value">Rp {{ number_format($totalCost, 0, ',', '.') }}</div>
                <div class="stat-sub">Termasuk PPN 11% & Tambahan</div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="stat-icon icon-indigo">
                <i class="fa-solid fa-phone-arrow-up-right"></i>
            </div>
            <div>
                <div class="stat-title">Total Panggilan (CDR)</div>
                <div class="stat-value">{{ number_format($totalCalls) }} Call</div>
                <div class="stat-sub">Semua trunk line aktif</div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="stat-icon icon-amber">
                <i class="fa-solid fa-stopwatch"></i>
            </div>
            <div>
                <div class="stat-title">Total Durasi Bicara</div>
                @php
                    $hrs = floor($totalDurationSec / 3600);
                    $mins = floor(($totalDurationSec % 3600) / 60);
                    $secs = $totalDurationSec % 60;
                @endphp
                <div class="stat-value">{{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}</div>
                <div class="stat-sub">Total {{ round($totalDurationSec / 60, 1) }} menit</div>
            </div>
        </div>

        <div class="card stat-card">
            <div class="stat-icon icon-cyan">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="stat-title">Ekstensi Terdaftar</div>
                <div class="stat-value">{{ $departments->count() }} Ext</div>
                <div class="stat-sub">Terhubung ke Group Dept</div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card filter-card">
        <form action="{{ route('billing.index') }}" method="GET" class="filter-form">
            <div class="form-group">
                <label class="form-label"><i class="fa-regular fa-calendar"></i> Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fa-regular fa-calendar"></i> Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-building"></i> Departemen / Ext</label>
                <select name="department_id" class="form-select">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            Ext {{ $dept->extcode }} - {{ $dept->extname }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-globe"></i> Zona Tujuan</label>
                <select name="zone_id" class="form-select">
                    <option value="">Semua Zona</option>
                    @foreach($zones as $z)
                        <option value="{{ $z->idzone }}" {{ request('zone_id') == $z->idzone ? 'selected' : '' }}>
                            {{ $z->namazone }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fa-solid fa-magnifying-glass"></i> Cari Nomor / CDR</label>
                <input type="text" name="search" class="form-control" placeholder="No Tujuan / Ext..." value="{{ request('search') }}">
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary" style="height: 40px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="{{ route('billing.index') }}" class="btn btn-secondary" style="height: 40px;" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Main Grid: Call Records & Simulator -->
    <div class="grid-main">
        <!-- Call Details Records Table -->
        <div class="card" style="padding: 0;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-glass); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-receipt" style="color: #38bdf8;"></i>
                    Daftar Log Panggilan & Tagihan Masuk (tbm_data_masuk)
                </h3>
                <span class="badge" style="background: rgba(255, 255, 255, 0.08); color: #cbd5e1;">
                    {{ $records->total() }} Data Ditemukan
                </span>
            </div>

            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>No CDR / Tgl</th>
                            <th>Ekstensi / Pemanggil</th>
                            <th>No Tujuan / Zona</th>
                            <th>Durasi</th>
                            <th>Tarif (Rate)</th>
                            <th>Subtotal Tagihan</th>
                            <th>Trunk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $rec)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: #818cf8; font-family: 'JetBrains Mono', monospace;">{{ $rec->nourut }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">
                                        {{ $rec->tglmasuk }} {{ $rec->jammasuk }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="ext-pill">{{ $rec->ext_pemanggil }}</span>
                                        <div>
                                            <div style="font-weight: 600;">{{ $rec->department ? $rec->department->extname : ($rec->namadepartment ?: 'Unknown') }}</div>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                {{ $rec->department && $rec->department->group ? $rec->department->group->namagroup : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 700; letter-spacing: 0.02em; font-family: 'JetBrains Mono', monospace;">
                                        {{ $rec->no_tujuan }}
                                    </div>
                                    <div style="margin-top: 3px;">
                                        @php
                                            $kel = $rec->zonaRel ? $rec->zonaRel->kelompok : 'LOCAL';
                                            $badgeClass = match($kel) {
                                                'CELL' => 'badge-cell',
                                                'IDD' => 'badge-idd',
                                                'NDD' => 'badge-sljj',
                                                default => 'badge-local'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $rec->zona ?: ($rec->zonaRel ? $rec->zonaRel->namazone : 'Lokal') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-family: 'JetBrains Mono', monospace; font-weight: 600;">{{ $rec->durasi }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $rec->pulsa }} Pulsa</div>
                                </td>
                                <td>
                                    <div style="font-size: 0.8rem; font-weight: 600;">{{ $rec->namarate ?: ($rec->tarifRel ? $rec->tarifRel->ratecode : '-') }}</div>
                                    <div style="font-size: 0.725rem; color: #94a3b8; font-family: 'JetBrains Mono', monospace;">{{ $rec->formularate }}</div>
                                </td>
                                <td>
                                    <div class="cost-highlight">Rp {{ number_format($rec->subtotal, 0, ',', '.') }}</div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted);">
                                        Pokok: {{ number_format($rec->biaya, 0, ',', '.') }} + PPN
                                    </div>
                                </td>
                                <td>
                                    <span style="font-size: 0.75rem; color: #94a3b8; font-family: 'JetBrains Mono', monospace;">
                                        {{ $rec->noline }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                                    <i class="fa-solid fa-phone-slash" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                    <div>Tidak ada data billing yang sesuai dengan filter pencarian.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div style="padding: 1rem 1.5rem; border-top: 1px solid var(--border-glass);">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

        <!-- Right Side: Simulator & Department Breakdown -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Live Billing Simulator Card -->
            <div class="card">
                <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-bolt" style="color: #fbbf24;"></i>
                    Simulasi Hitung Billing Telepon
                </h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.25rem;">
                    Uji coba kalkulasi tarif otomatis berdasarkan nomor tujuan, zona & durasi.
                </p>

                <div class="form-group" style="margin-bottom: 0.85rem;">
                    <label class="form-label">Pilih Ekstensi Pemanggil</label>
                    <select id="sim_ext" class="form-select">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">Ext {{ $dept->extcode }} - {{ $dept->extname }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0.85rem;">
                    <label class="form-label">Nomor Telepon Tujuan</label>
                    <input type="text" id="sim_dest" class="form-control" placeholder="Contoh: 08123456789 atau 0215290123" value="08129876543">
                </div>

                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label class="form-label">Durasi Bicara (Detik)</label>
                    <input type="number" id="sim_dur" class="form-control" value="180" min="1">
                    <span style="font-size: 0.725rem; color: var(--text-muted); margin-top: 3px;">
                        180 detik = 3 menit
                    </span>
                </div>

                <button type="button" onclick="runSimulation()" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-calculator"></i> Hitung Tagihan Sekarang
                </button>

                <!-- Result Box -->
                <div id="sim_result" class="sim-result">
                    <div style="font-weight: 700; color: #818cf8; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <i class="fa-solid fa-check-circle"></i> Hasil Perhitungan Billing:
                    </div>
                    <div class="sim-row">
                        <span class="text-muted">Zona / Prefix</span>
                        <span id="res_zone" style="font-weight: 600;">-</span>
                    </div>
                    <div class="sim-row">
                        <span class="text-muted">Skema Tarif</span>
                        <span id="res_tarif" style="font-weight: 600;">-</span>
                    </div>
                    <div class="sim-row">
                        <span class="text-muted">Formula Pulsa</span>
                        <span id="res_formula" style="font-family: 'JetBrains Mono', monospace;">-</span>
                    </div>
                    <div class="sim-row">
                        <span class="text-muted">Biaya Pokok</span>
                        <span id="res_biaya">-</span>
                    </div>
                    <div class="sim-row">
                        <span class="text-muted">PPN (11%)</span>
                        <span id="res_ppn">-</span>
                    </div>
                    <div class="sim-row" style="color: #34d399;">
                        <span>TOTAL TAGIHAN:</span>
                        <span id="res_total">-</span>
                    </div>
                </div>
            </div>

            <!-- Department Usage Summary Card -->
            <div class="card">
                <h3 style="font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-chart-pie" style="color: #10b981;"></i>
                    Pemakaian per Departemen
                </h3>
                <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                    @foreach($deptSummary->take(6) as $ds)
                        <div style="padding-bottom: 0.75rem; border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <div style="font-weight: 600; font-size: 0.875rem;">
                                    <span class="ext-pill" style="font-size: 0.725rem; margin-right: 4px;">{{ $ds['ext'] }}</span>
                                    {{ $ds['name'] }}
                                </div>
                                <div style="font-weight: 700; color: #34d399; font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;">
                                    Rp {{ number_format($ds['total_cost'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted);">
                                <span>{{ $ds['calls'] }} Panggilan</span>
                                <span>{{ round($ds['duration_sec'] / 60, 1) }} Menit</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Catat Panggilan Baru -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-size: 1.2rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-phone-plus" style="color: #10b981;"></i> Catat Panggilan Baru (CDR)
                </h3>
                <button type="button" class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form action="{{ route('billing.store') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Ekstensi Pemanggil</label>
                    <select name="ext_id" class="form-select" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">
                                Ext {{ $dept->extcode }} — {{ $dept->extname }} ({{ $dept->group ? $dept->group->namagroup : $dept->divisi }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Nomor Telepon Tujuan</label>
                    <input type="text" name="destination" class="form-control" placeholder="Contoh: 081288990011 atau 0215290123" required>
                    <span style="font-size: 0.725rem; color: var(--text-muted);">
                        Sistem otomatis menghubungkan ke tabel tbm_prefix, tbm_zona, dan tbm_tarif via Foreign Key.
                    </span>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Durasi Panggilan (Detik)</label>
                    <input type="number" name="duration_sec" class="form-control" value="120" min="1" required>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-emerald">
                        <i class="fa-solid fa-check"></i> Simpan & Hitung Billing
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function openModal() {
        document.getElementById('addModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('addModal').classList.remove('active');
    }

    function runSimulation() {
        const extId = document.getElementById('sim_ext').value;
        const destination = document.getElementById('sim_dest').value;
        const durationSec = document.getElementById('sim_dur').value;

        if (!destination || !durationSec) {
            alert('Silakan masukkan nomor tujuan dan durasi panggilan!');
            return;
        }

        fetch('{{ route('billing.simulate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ext_id: extId,
                destination: destination,
                duration_sec: durationSec
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('res_zone').textContent = `${data.zone} (${data.prefix} - ${data.prefix_ket})`;
                document.getElementById('res_tarif').textContent = data.tarif_name;
                document.getElementById('res_formula').textContent = data.formula;
                document.getElementById('res_biaya').textContent = 'Rp ' + Number(data.biaya).toLocaleString('id-ID');
                document.getElementById('res_ppn').textContent = 'Rp ' + Number(data.tambahan).toLocaleString('id-ID');
                document.getElementById('res_total').textContent = data.subtotal_formatted;

                document.getElementById('sim_result').style.display = 'block';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Gagal menghitung simulasi billing.');
        });
    }
</script>
@endsection
