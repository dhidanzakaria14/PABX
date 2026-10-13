@extends('layouts.app')

@section('title', 'BACKUP & MAINTENANCE - ANGKASA PURA PABX')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-box-archive" style="color: #16a34a;"></i>
                BACKUP & MAINTENANCE
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Pencadangan basis data, ekspor arsip panggilan CDR, optimasi tabel MySQL, dan pemeliharaan sistem
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('utility.server-health') }}" class="btn-search" style="background: #0288d1; text-decoration: none;">
                <i class="fa-solid fa-heart-pulse"></i> Server Diagnostics
            </a>
            <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none;">
                <i class="fa-solid fa-house"></i> Home Dashboard
            </a>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #16a34a; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Data CDR Terarsip</span>
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ number_format($totalCdr) }} Panggilan
                    </h3>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                        Periode: {{ $dateMin }} s/d {{ $dateMax }}
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #dcfce7; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #0288d1; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Basis Data Target</span>
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        MySQL: <code>{{ $dbName }}</code>
                    </h3>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                        {{ $totalDepts }} Ekstensi / Phone-ID aktif
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0288d1; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #7c3aed; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Arsip Backup Tersimpan</span>
                    <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ count($backupFiles) }} File Dump
                    </h3>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 4px;">
                        Direktori: <code>storage/app/backups</code>
                    </div>
                </div>
                <div style="width: 44px; height: 44px; border-radius: 10px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Row: Backup Generator & Maintenance Tools -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Card 1: Backup Database -->
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-cloud-arrow-down" style="color: #16a34a;"></i>
                    <span>Pencadangan Database (Dump SQL)</span>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                <p style="font-size: 0.825rem; color: #475569; line-height: 1.6; margin-bottom: 1.25rem;">
                    Buat snapshot cadangan lengkap seluruh skema dan data PABX termasuk konfigurasi tarif, departemen, ekstensi, dan seluruh data panggilan masuk (CDR).
                </p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <form action="{{ route('utility.backup.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="download" value="1">
                        <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #16a34a, #15803d); height: 38px; padding: 0 18px; font-weight: 700;">
                            <i class="fa-solid fa-download"></i> Unduh SQL Langsung
                        </button>
                    </form>

                    <form action="{{ route('utility.backup.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-search" style="background: #0288d1; height: 38px; padding: 0 16px;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan ke Server Storage
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Card 2: System Maintenance -->
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-wrench" style="color: #0288d1;"></i>
                    <span>Pemeliharaan & Optimasi Sistem</span>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                <p style="font-size: 0.825rem; color: #475569; line-height: 1.6; margin-bottom: 1.25rem;">
                    Jalankan pemeliharaan rutin untuk mengompresi indeks database MySQL, membersihkan memori cache framework, dan mempercepat respons aplikasi.
                </p>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <form action="{{ route('utility.backup.optimize') }}" method="POST" onsubmit="return confirm('Jalankan optimasi seluruh tabel database PABX sekarang?');">
                        @csrf
                        <button type="submit" class="btn-search" style="background: #f59e0b; height: 38px; padding: 0 16px; font-weight: 700;">
                            <i class="fa-solid fa-gauge-high"></i> Optimasi Tabel MySQL
                        </button>
                    </form>

                    <form action="{{ route('utility.backup.clear-cache') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-search" style="background: #64748b; height: 38px; padding: 0 16px;">
                            <i class="fa-solid fa-broom"></i> Bersihkan Cache Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup History Table -->
    <div class="report-card">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7;"></i>
                <span>Riwayat Berkas Cadangan Tersimpan di Server</span>
            </div>
            <span style="font-size: 0.75rem; color: #64748b;">
                Lokasi: <code>storage/app/backups/</code>
            </span>
        </div>

        <div class="table-responsive">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Nama Berkas</th>
                        <th>Waktu Pembuatan</th>
                        <th style="text-align: right;">Ukuran Berkas</th>
                        <th style="text-align: center;">Tipe</th>
                        <th style="text-align: center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backupFiles as $idx => $bk)
                        <tr>
                            <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                            <td>
                                <strong style="color: #0288d1; font-family: monospace;">{{ $bk['name'] }}</strong>
                            </td>
                            <td>{{ $bk['time'] }}</td>
                            <td style="text-align: right; font-weight: 700;">
                                {{ round($bk['size'] / (1024 * 1024), 2) }} MB
                            </td>
                            <td style="text-align: center;">
                                <span style="background: #e0f2fe; color: #0288d1; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    SQL Dump
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    Tersimpan
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2.5rem; color: #94a3b8;">
                                <i class="fa-solid fa-box-open" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                                Belum ada berkas backup yang disimpan di server. Silakan klik tombol <strong>"Simpan ke Server Storage"</strong> di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
