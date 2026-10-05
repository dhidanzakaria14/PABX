@extends('layouts.app')

@section('title', 'Diagram Relasi Antar Tabel Database PABX (25 Tabel)')

@section('styles')
<style>
    .schema-header {
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .schema-summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(99, 102, 241, 0.15);
        color: #818cf8;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.875rem;
        border: 1px solid rgba(99, 102, 241, 0.3);
    }

    .fk-table-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    .table-spec-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 12px;
        padding: 1.25rem;
        transition: transform 0.2s, border-color 0.2s;
    }

    .table-spec-card:hover {
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-2px);
    }

    .table-spec-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .table-spec-title {
        font-weight: 700;
        font-size: 1rem;
        color: #e2e8f0;
        font-family: 'JetBrains Mono', monospace;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .fk-item {
        background: #0b0f19;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-left: 3px solid #818cf8;
    }

    .fk-col {
        font-weight: 700;
        color: #38bdf8;
        font-family: 'JetBrains Mono', monospace;
    }

    .fk-arrow {
        color: var(--text-muted);
        font-size: 0.75rem;
    }

    .fk-target {
        font-weight: 600;
        color: #34d399;
        font-family: 'JetBrains Mono', monospace;
    }

    .mermaid-box {
        background: #090d16;
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 16px;
        padding: 2rem;
        overflow-x: auto;
        margin-bottom: 2.5rem;
        text-align: center;
    }

    .nav-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-glass);
        padding-bottom: 0.75rem;
    }

    .tab-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn.active {
        background: var(--primary-light);
        color: #818cf8;
        border: 1px solid rgba(129, 140, 248, 0.3);
    }
</style>
<!-- Mermaid JS for Interactive Visual ERD -->
<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    mermaid.initialize({
        startOnLoad: true,
        theme: 'dark',
        themeVariables: {
            darkMode: true,
            background: '#090d16',
            primaryColor: '#4f46e5',
            primaryTextColor: '#f3f4f6',
            primaryBorderColor: '#6366f1',
            lineColor: '#38bdf8',
            secondaryColor: '#10b981',
            tertiaryColor: '#1e293b'
        }
    });
</script>
@endsection

@section('content')
    <div class="schema-header">
        <div>
            <h1 style="font-size: 1.85rem; margin-bottom: 0.35rem;">
                <i class="fa-solid fa-diagram-project" style="color: #818cf8; margin-right: 0.5rem;"></i>
                Relasi Database PABX (25 Tabel InnoDB)
            </h1>
            <p class="text-muted">
                Semua tabel telah dikonfigurasi dengan Primary Key, Foreign Key, dan Index yang saling menyambung tanpa kesalahan.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <span class="schema-summary-badge">
                <i class="fa-solid fa-link"></i> {{ count($foreignKeys) }} Relasi Foreign Key Terhubung
            </span>
            <a href="http://localhost/phpmyadmin/index.php?db={{ $dbName }}" target="_blank" class="btn btn-primary">
                <i class="fa-solid fa-external-link"></i> Buka di phpMyAdmin Designer
            </a>
        </div>
    </div>

    <!-- Visual ERD Diagram Card -->
    <div class="card" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1.15rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-network-wired" style="color: #38bdf8;"></i>
                Diagram Interaktif Relasi Antar Tabel (Entity-Relationship Diagram)
            </h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">
                Engine: MySQL InnoDB • Auto Foreign Key Constraints
            </span>
        </div>

        <div class="mermaid-box">
            <pre class="mermaid">
erDiagram
    tbm_profil ||--o{ tbm_data_masuk : "perusahaan (idperusahaan)"
    tbm_setting ||--o{ tbm_tarif : "mesin pbx (idmesin)"
    tbm_setting ||--o{ tbm_area : "mesin pbx (idmesin)"
    tbm_setting ||--o{ tbm_data_masuk : "mesin pbx (idmesin)"
    
    tbm_group_department ||--o{ tbm_department : "divisi (idgroup)"
    tbm_department ||--o{ tbm_data_masuk : "ekstensi pemanggil (iddepartment)"
    
    tbm_zona ||--o{ tbm_prefix : "kategori zona (idzone)"
    tbm_zona ||--o{ tbm_area : "zona wilayah (idzone)"
    tbm_zona ||--o{ tbm_data_masuk : "zona tujuan (idzone)"
    
    tbm_prefix ||--o{ tbm_area : "prefix dial (idprefix)"
    tbm_prefix ||--o{ tbm_data_masuk : "prefix tujuan (idprefix)"
    
    tbm_tarif ||--o{ tbd_rate : "rincian jam (idrate)"
    tbm_tarif ||--o{ tbm_tarif_khusus : "diskon promo (idtarif)"
    tbm_tarif ||--o{ tbm_area : "skema tarif (idrate)"
    tbm_tarif ||--o{ tbm_data_masuk : "tarif hitung (idrate)"
    
    tbm_tarif_khusus ||--o{ tbd_tarif_khusus : "jam promo (idtarifkhusus)"
    
    tbm_level ||--o{ tbl_user : "role user (idlevel)"
    tbm_level ||--o{ tbd_akses_user : "hak akses (idlevel)"
    tbd_modul_user ||--o{ tbd_akses_user : "modul menu (iddetailmodul)"
    
    tbl_user ||--o{ user_log : "audit log (iduser)"
    tbl_user ||--o{ log_login : "login history (iduser)"
    tbl_user ||--o{ tbl_user : "manajemen pimpinan (idmanajemen)"
            </pre>
        </div>
    </div>

    <!-- Foreign Key Specifications Grid -->
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <i class="fa-solid fa-list-check" style="color: #10b981;"></i>
        Daftar Detail Foreign Key Antar Tabel
    </h3>

    <div class="fk-table-grid">
        @php
            $groupedFk = collect($foreignKeys)->groupBy('TABLE_NAME');
        @endphp

        @foreach($groupedFk as $tableName => $fks)
            <div class="table-spec-card">
                <div class="table-spec-header">
                    <span class="table-spec-title">
                        <i class="fa-solid fa-table" style="color: #818cf8;"></i>
                        {{ $tableName }}
                    </span>
                    <span class="badge" style="background: rgba(99, 102, 241, 0.15); color: #a5b4fc;">
                        {{ count($fks) }} Foreign Key
                    </span>
                </div>

                <div style="margin-bottom: 0.5rem; font-size: 0.775rem; color: var(--text-muted);">
                    Kolom Foreign Key yang menghubungkan tabel:
                </div>

                @foreach($fks as $fk)
                    <div class="fk-item">
                        <span class="fk-col">{{ $fk->COLUMN_NAME }}</span>
                        <span class="fk-arrow"><i class="fa-solid fa-arrow-right-long"></i></span>
                        <span class="fk-target">{{ $fk->REFERENCED_TABLE_NAME }}.{{ $fk->REFERENCED_COLUMN_NAME }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- All 25 Tables Explorer -->
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        <i class="fa-solid fa-database" style="color: #f59e0b;"></i>
        Daftar 25 Tabel Lengkap di Database ({{ $dbName }})
    </h3>

    <div class="card" style="padding: 0;">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Tabel</th>
                        <th>Jumlah Kolom</th>
                        <th>Jumlah Data (Baris)</th>
                        <th>Peran & Hubungan dalam Sistem PABX</th>
                        <th>Status Relasi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($tableStats as $tblName => $stats)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <strong style="font-family: 'JetBrains Mono', monospace; color: #818cf8;">{{ $tblName }}</strong>
                            </td>
                            <td>{{ count($stats['columns']) }} Kolom</td>
                            <td>
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #34d399;">
                                    {{ $stats['count'] }} Baris
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: #cbd5e1;">
                                @if(str_contains($tblName, 'data_masuk'))
                                    Tabel inti pencatatan CDR (Call Detail Records) & kalkulasi tagihan telepon.
                                @elseif(str_contains($tblName, 'department'))
                                    Master ekstensi PABX dan pemetaan departemen/karyawan.
                                @elseif(str_contains($tblName, 'group_department'))
                                    Master grup divisi organisasi.
                                @elseif(str_contains($tblName, 'tarif') || str_contains($tblName, 'rate'))
                                    Master skema tarif dan breakdown biaya berdasarkan jam/hari.
                                @elseif(str_contains($tblName, 'zona') || str_contains($tblName, 'prefix') || str_contains($tblName, 'area'))
                                    Master penomoran telepon, kode area, dan pengelompokan zona (Lokal/Cell/SLJJ).
                                @elseif(str_contains($tblName, 'setting'))
                                    Konfigurasi koneksi IP & Port perangkat PABX (Panasonic, Asterisk, dll).
                                @elseif(str_contains($tblName, 'user') || str_contains($tblName, 'level') || str_contains($tblName, 'modul'))
                                    Manajemen pengguna sistem, hak akses modul, dan log audit.
                                @elseif(str_contains($tblName, 'scheduler') || str_contains($tblName, 'pjs'))
                                    Tabel penjadwalan proses kalkulasi billing otomatis di latar belakang.
                                @else
                                    Tabel master pendukung sistem PABX.
                                @endif
                            </td>
                            <td>
                                @if(isset($groupedFk[$tblName]) || in_array($tblName, collect($foreignKeys)->pluck('REFERENCED_TABLE_NAME')->toArray()))
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399;">
                                        <i class="fa-solid fa-check"></i> Terelasi
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(148, 163, 184, 0.15); color: #94a3b8;">
                                        Master Independen
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
