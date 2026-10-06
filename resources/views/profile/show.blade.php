@extends('layouts.app')

@section('title', 'ACCOUNT PROFILE - ANGKASA PURA')

@section('styles')
<style>
    .profile-container {
        max-width: 920px;
        margin: 0 auto;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .profile-card-header {
        padding: 1.15rem 1.75rem;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #e0f2fe;
        color: #0288d1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .header-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.01em;
    }

    .header-subtitle {
        font-size: 0.775rem;
        color: #64748b;
        margin-top: 1px;
    }

    /* Single primary Edit Profile action button */
    .btn-edit-action {
        text-decoration: none;
        padding: 8px 18px;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        border: none;
        border-radius: 6px;
        font-size: 0.825rem;
        font-weight: 600;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(2, 136, 209, 0.35);
        transition: all 0.15s ease;
    }

    .btn-edit-action:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 4px 10px rgba(2, 136, 209, 0.45);
        transform: translateY(-1px);
        color: #ffffff;
    }

    .profile-card-body {
        padding: 2rem 2.25rem;
    }

    .profile-layout-grid {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 2.75rem;
        align-items: start;
    }

    /* Avatar Side Box */
    .avatar-studio {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.75rem 1rem;
    }

    .avatar-circle-wrapper {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
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
        font-size: 3.5rem;
        color: #94a3b8;
    }

    .user-fullname {
        margin-top: 1rem;
        font-weight: 700;
        font-size: 1rem;
        color: #0f172a;
    }

    .user-tag {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 2px;
        font-family: monospace;
    }

    .role-badge-box {
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px dashed #cbd5e1;
        width: 100%;
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.725rem;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Details Grid */
    .form-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
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
        border-radius: 6px;
        padding: 0.85rem 1rem;
        transition: border-color 0.15s ease;
    }

    .detail-item:hover {
        border-color: #cbd5e1;
    }

    .detail-label {
        font-size: 0.725rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        word-break: break-all;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #166534;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.2);
    }

    /* Footer Navigation */
    .profile-footer {
        margin-top: 2rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .btn-dashboard {
        text-decoration: none;
        padding: 8px 18px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.825rem;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .btn-dashboard:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <!-- Card Header with Single Primary Edit Profile Button -->
        <div class="profile-card-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="header-icon-box">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <div class="header-title">Profil Akun Pengguna</div>
                    <div class="header-subtitle">Informasi identitas akun dan status hak akses sistem PABX</div>
                </div>
            </div>
            <!-- Satu-satunya tombol Edit Profile -->
            <a href="{{ route('profile.edit') }}" class="btn-edit-action" title="Ubah informasi profil atau ganti password">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profile
            </a>
        </div>

        @if(session('success'))
            <div style="margin: 1.25rem 2.25rem 0; padding: 0.85rem 1.25rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 6px; font-size: 0.825rem; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-check" style="color: #22c55e;"></i>
                <span>{{ session('success') }}</span>
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
                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-user" style="color: #0288d1;"></i>
                                <span>Complete Name</span>
                            </div>
                            <div class="detail-value">
                                {{ $user->nama ?: '-' }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-phone" style="color: #0288d1;"></i>
                                <span>Phone No / Ext</span>
                            </div>
                            <div class="detail-value">
                                {{ $user->telp ?: '-' }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-at" style="color: #0288d1;"></i>
                                <span>Username</span>
                            </div>
                            <div class="detail-value">
                                {{ $user->username }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-lock" style="color: #0288d1;"></i>
                                <span>Password</span>
                            </div>
                            <div class="detail-value" style="font-family: monospace; letter-spacing: 2px; color: #64748b;">
                                &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-user-shield" style="color: #0288d1;"></i>
                                <span>User Level / Role</span>
                            </div>
                            <div class="detail-value">
                                {{ $user->leveluser ?: 'Administrator' }}
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <i class="fa-solid fa-circle-check" style="color: #0288d1;"></i>
                                <span>Account Status</span>
                            </div>
                            <div class="detail-value">
                                <div class="status-badge">
                                    <span class="status-dot"></span>
                                    <span>Aktif & Terdaftar</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action: Only 1 navigation link to Dashboard, NO duplicate edit button -->
                    <div class="profile-footer">
                        <a href="{{ route('home') }}" class="btn-dashboard">
                            <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <div style="font-size: 0.75rem; color: #94a3b8;">
                            Terdaftar di Database PABX Billing System
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
