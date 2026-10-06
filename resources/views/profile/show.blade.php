@extends('layouts.app')

@section('title', 'Detail Profil Akun - ANGKASA PURA PABX')

@section('styles')
<style>
    .profile-view-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .profile-view-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        letter-spacing: -0.02em;
    }

    .profile-view-subtitle {
        font-size: 0.825rem;
        color: #64748b;
        margin-top: 3px;
    }

    .btn-edit-top {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 6px;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(2, 136, 209, 0.25);
        transition: all 0.2s ease-in-out;
    }

    .btn-edit-top:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 4px 10px rgba(2, 136, 209, 0.35);
        color: #ffffff;
        transform: translateY(-1px);
    }

    /* Hero Showcase Card */
    .profile-hero-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .hero-banner {
        height: 120px;
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 50%, #e53935 100%);
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 2rem;
        color: white;
    }

    .hero-banner-title {
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 10px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .hero-banner i.watermark {
        font-size: 4.5rem;
        color: rgba(255, 255, 255, 0.12);
    }

    .hero-content {
        padding: 0 2rem 1.75rem;
        position: relative;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .hero-avatar-block {
        display: flex;
        align-items: flex-end;
        gap: 1.5rem;
        margin-top: -55px;
    }

    .hero-avatar-box {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
    }

    .hero-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-avatar-default {
        font-size: 3rem;
        color: #94a3b8;
    }

    .status-badge-dot {
        position: absolute;
        bottom: 6px;
        right: 6px;
        width: 14px;
        height: 14px;
        background: #22c55e;
        border: 2px solid #ffffff;
        border-radius: 50%;
        box-shadow: 0 0 4px #22c55e;
    }

    .hero-info-text {
        padding-bottom: 6px;
    }

    .hero-user-name {
        font-size: 1.4rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .hero-user-tag {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .badge-role-hero {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
        border-radius: 20px;
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .hero-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 6px;
    }

    /* Details Grid */
    .profile-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .profile-details-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .info-card-header {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .info-card-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .info-label i {
        width: 16px;
        color: #94a3b8;
    }

    .info-value {
        font-weight: 700;
        color: #0f172a;
        text-align: right;
    }

    /* Activity Logs Card */
    .activity-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    /* Bottom Action Bar */
    .profile-footer-bar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .btn-action-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-action-back:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .alert-banner-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="profile-view-header">
    <div>
        <div class="profile-view-title">
            <i class="fa-solid fa-address-card" style="color: #d32f2f;"></i>
            <span>Detail Profil Akun Pengguna</span>
        </div>
        <div class="profile-view-subtitle">
            Informasi akun administrator dan data identitas pengguna sistem PABX Billing PT Angkasa Pura Indonesia.
        </div>
    </div>
    
    <!-- Top Action Button -->
    <a href="{{ route('profile.edit') }}" class="btn-edit-top">
        <i class="fa-solid fa-user-pen"></i>
        <span>Edit Profil Akun</span>
    </a>
</div>

<!-- Success Alert -->
@if(session('success'))
    <div class="alert-banner-success">
        <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
        <div>
            <strong>Berhasil:</strong> {{ session('success') }}
        </div>
    </div>
@endif

<!-- Hero Card -->
<div class="profile-hero-card">
    <div class="hero-banner">
        <div class="hero-banner-title">
            <i class="fa-solid fa-tower-broadcast"></i>
            <span>PABX Billing System &bull; Angkasa Pura</span>
        </div>
        <i class="fa-solid fa-plane-departure watermark"></i>
    </div>

    <div class="hero-content">
        <div class="hero-avatar-block">
            <div class="hero-avatar-box">
                @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                    <img src="{{ asset('uploads/profile/' . $user->photo) }}" alt="Avatar">
                @else
                    <i class="fa-solid fa-user hero-avatar-default"></i>
                @endif
                <div class="status-badge-dot" title="Akun Aktif"></div>
            </div>

            <div class="hero-info-text">
                <div class="hero-user-name">{{ $user->nama ?: 'Administrator' }}</div>
                <div class="hero-user-tag">
                    <span>&#64;{{ $user->username }}</span>
                    <span>&bull;</span>
                    <span class="badge-role-hero">
                        <i class="fa-solid fa-shield-halved"></i>
                        {{ $user->leveluser ?: 'Super Administrator' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="hero-actions">
            <a href="{{ route('profile.edit') }}" class="btn-edit-top">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Profil</span>
            </a>
        </div>
    </div>
</div>

<!-- Details Grid -->
<div class="profile-details-grid">
    <!-- Card 1: Informasi Identitas & Kontak -->
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-title">
                <i class="fa-solid fa-id-badge" style="color: #0288d1;"></i>
                <span>1. Data Identitas & Kontak</span>
            </div>
            <span style="font-size: 0.725rem; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-weight: 700;">
                Terverifikasi
            </span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-user"></i> Nama Lengkap</span>
                <span class="info-value">{{ $user->nama ?: 'Administrator' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-at"></i> Username</span>
                <span class="info-value">{{ $user->username }}</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-phone"></i> Nomor Telepon / HP</span>
                <span class="info-value">{{ $user->telp ?: '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-building"></i> Cabang Bandara</span>
                <span class="info-value">Bandara Internasional Juanda & Soetta</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-fingerprint"></i> ID Akun Sistem</span>
                <span class="info-value" style="font-family: 'JetBrains Mono', monospace; color: #0288d1;">#USER-{{ str_pad($user->iduser, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    <!-- Card 2: Keamanan & Hak Akses -->
    <div class="info-card">
        <div class="info-card-header">
            <div class="info-card-title">
                <i class="fa-solid fa-lock" style="color: #2e7d32;"></i>
                <span>2. Keamanan & Akses Sistem</span>
            </div>
            <span style="font-size: 0.725rem; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 4px; font-weight: 700;">
                Aman
            </span>
        </div>
        <div class="info-card-body">
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-key"></i> Kata Sandi (Password)</span>
                <span class="info-value" style="font-family: 'JetBrains Mono', monospace; color: #64748b;">••••••••••••</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-shield"></i> Level Hak Akses</span>
                <span class="info-value" style="color: #1d4ed8;">{{ $user->leveluser ?: 'Super Administrator (Akses Penuh)' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-circle-check" style="color: #22c55e;"></i> Status Akun</span>
                <span class="info-value" style="color: #166534;">Aktif & Tidak Diblokir</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-layer-group"></i> Modul Sistem</span>
                <span class="info-value">Full Access (Report, Setting, Billing)</span>
            </div>
            <div class="info-row">
                <span class="info-label"><i class="fa-solid fa-shield-virus"></i> Enkripsi Password</span>
                <span class="info-value" style="font-size: 0.8rem; color: #475569;">Bcrypt Hash (12 Rounds)</span>
            </div>
        </div>
    </div>
</div>

<!-- Activity Logs Card -->
<div class="activity-card">
    <div class="info-card-header">
        <div class="info-card-title">
            <i class="fa-solid fa-clock-rotate-left" style="color: #f57c00;"></i>
            <span>3. Riwayat Aktivitas Login Terakhir</span>
        </div>
        <span style="font-size: 0.725rem; color: #64748b;">5 Sesi Terakhir</span>
    </div>
    <div class="table-responsive">
        <table class="ap-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Waktu & Tanggal Login</th>
                    <th>IP Address Client</th>
                    <th>Perangkat / Session</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($recentLogins as $log)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($log->tgl)->format('d M Y, H:i:s') }}</strong>
                        </td>
                        <td>
                            <span style="font-family: 'JetBrains Mono', monospace; color: #0288d1; background: #e0f2fe; padding: 2px 6px; border-radius: 3px;">
                                {{ $log->ip }}
                            </span>
                        </td>
                        <td>Web Browser Client (Local Network)</td>
                        <td>
                            <span style="font-size: 0.75rem; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-weight: 700;">
                                Sukses
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 1.5rem; color: #94a3b8;">
                            Belum ada catatan aktivitas login.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Bottom Action Bar -->
<div class="profile-footer-bar">
    <a href="{{ route('home') }}" class="btn-action-back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Dashboard</span>
    </a>

    <div style="display: flex; align-items: center; gap: 0.75rem;">
        <span style="font-size: 0.8rem; color: #64748b;">Ingin memperbarui nama, nomor telepon, username, atau password?</span>
        <a href="{{ route('profile.edit') }}" class="btn-edit-top" style="padding: 8px 24px;">
            <i class="fa-solid fa-user-pen"></i>
            <span>Edit Profil Akun</span>
        </a>
    </div>
</div>
@endsection
