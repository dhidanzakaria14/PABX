@extends('layouts.app')

@section('title', 'ACCOUNT PROFILE - ANGKASA PURA')

@section('styles')
<style>
    /* Full expansive modern layout (Lebar & Gagah untuk monitor Widescreen) */
    .profile-container {
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }

    .profile-card-header {
        padding: 1.35rem 2.25rem;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0288d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .header-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 2px;
    }

    /* Single primary Edit Profile action button */
    .btn-edit-action {
        text-decoration: none;
        padding: 10px 24px;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 3px 10px rgba(2, 136, 209, 0.35);
        transition: all 0.2s ease;
    }

    .btn-edit-action:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 6px 16px rgba(2, 136, 209, 0.45);
        transform: translateY(-1px);
        color: #ffffff;
    }

    .profile-card-body {
        padding: 2.5rem 2.75rem;
    }

    .profile-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 3.25rem;
        align-items: start;
    }

    /* Avatar Side Box (Lebih Besar & Representatif) */
    .avatar-studio {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 2.25rem 1.5rem;
    }

    .avatar-circle-wrapper {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        border: 5px solid #ffffff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.14);
        overflow: hidden;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-circle-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-circle-wrapper .placeholder-icon {
        font-size: 4.5rem;
        color: #94a3b8;
    }

    .user-fullname {
        margin-top: 1.25rem;
        font-weight: 800;
        font-size: 1.25rem;
        color: #0f172a;
    }

    .user-tag {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 3px;
        font-family: monospace;
        font-weight: 600;
    }

    .role-badge-box {
        margin-top: 1.25rem;
        padding-top: 1rem;
        border-top: 1px dashed #cbd5e1;
        width: 100%;
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.8rem;
        font-weight: 800;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 6px 16px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    /* Details Section */
    .form-section-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem 1.75rem;
    }

    .detail-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.15rem;
        transition: all 0.2s ease;
    }

    .detail-item:hover {
        border-color: #cbd5e1;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        transform: translateY(-1px);
    }

    .detail-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #e0f2fe;
        color: #0288d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .detail-info {
        flex: 1;
        overflow: hidden;
    }

    .detail-label {
        font-size: 0.775rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 3px;
    }

    .detail-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-all;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #166534;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.25);
    }

    /* Footer Navigation */
    .profile-footer {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-dashboard {
        text-decoration: none;
        padding: 10px 22px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .btn-dashboard:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .profile-layout-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <!-- Card Header with Single Primary Edit Profile Button -->
        <div class="profile-card-header">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div class="header-icon-box">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <div class="header-title">Profil Akun Pengguna</div>
                    <div class="header-subtitle">Informasi identitas akun dan hak akses sistem PABX Angkasa Pura</div>
                </div>
            </div>
            <!-- Satu-satunya tombol Edit Profile -->
            <a href="{{ route('profile.edit') }}" class="btn-edit-action" title="Ubah informasi profil atau ganti password">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
        </div>

        @if(session('success'))
            <div style="margin: 1.5rem 2.75rem 0; padding: 1rem 1.5rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 8px; font-size: 0.875rem; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-circle-check" style="color: #22c55e; font-size: 1.15rem;"></i>
                <span style="font-weight: 600;">{{ session('success') }}</span>
            </div>
        @endif

        <div class="profile-card-body">
            <div class="profile-layout-grid">
                <!-- Left Column: Photo & Role Badge -->
                <div class="avatar-studio">
                    <div class="avatar-circle-wrapper">
                        @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                            <img src="{{ asset('uploads/profile/' . $user->photo) }}" alt="Avatar {{ $user->nama }}">
                        @else
                            <i class="fa-solid fa-user placeholder-icon"></i>
                        @endif
                    </div>
                    <div class="user-fullname">
                        {{ $user->nama ?: 'Administrator' }}
                    </div>
                    <div class="user-tag">
                        &#64;{{ $user->username }}
                    </div>
                    <div class="role-badge-box">
                        <span class="badge-role">
                            <i class="fa-solid fa-shield-halved"></i>
                            {{ $user->leveluser ?: 'Administrator' }}
                        </span>
                    </div>
                </div>

                <!-- Right Column: Details Grid -->
                <div>
                    <div class="form-section-title">
                        <i class="fa-solid fa-circle-info" style="color: #0288d1;"></i>
                        <span>Rincian Informasi Akun</span>
                    </div>

                    <div class="details-grid">
                        <!-- Complete Name -->
                        <div class="detail-item">
                            <div class="detail-icon-circle">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">Complete Name</div>
                                <div class="detail-value">{{ $user->nama ?: '-' }}</div>
                            </div>
                        </div>

                        <!-- Phone / Ext -->
                        <div class="detail-item">
                            <div class="detail-icon-circle">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">Phone No / Extension</div>
                                <div class="detail-value">{{ $user->telp ?: '-' }}</div>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="detail-item">
                            <div class="detail-icon-circle">
                                <i class="fa-solid fa-at"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">Username Akun</div>
                                <div class="detail-value">{{ $user->username }}</div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="detail-item">
                            <div class="detail-icon-circle">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">Kata Sandi</div>
                                <div class="detail-value" style="font-family: monospace; letter-spacing: 3px; color: #64748b; font-size: 1.25rem;">
                                    &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                                </div>
                            </div>
                        </div>

                        <!-- User Level / Role -->
                        <div class="detail-item">
                            <div class="detail-icon-circle">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">User Level / Role</div>
                                <div class="detail-value">{{ $user->leveluser ?: 'Administrator' }}</div>
                            </div>
                        </div>

                        <!-- Account Status -->
                        <div class="detail-item">
                            <div class="detail-icon-circle" style="background: #f0fdf4; color: #166534;">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="detail-info">
                                <div class="detail-label">Status Akun</div>
                                <div class="detail-value">
                                    <div class="status-badge">
                                        <span class="status-dot"></span>
                                        <span>Aktif & Terdaftar</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action: Back to Dashboard without duplicate edit button -->
                    <div class="profile-footer">
                        <a href="{{ route('home') }}" class="btn-dashboard">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 500;">
                            Terdaftar di Database PABX Billing System
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
