@extends('layouts.app')

@section('title', 'Profil Akun Pengguna - ANGKASA PURA PABX')

@section('styles')
<style>
    .profile-page-header {
        margin-bottom: 1.5rem;
    }

    .profile-page-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        letter-spacing: -0.02em;
    }

    .profile-page-subtitle {
        font-size: 0.825rem;
        color: #64748b;
        margin-top: 4px;
    }

    .profile-container-grid {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    @media (max-width: 992px) {
        .profile-container-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Left Card: User Profile Showcase */
    .profile-showcase-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .showcase-banner {
        height: 100px;
        background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 50%, #e53935 100%);
        position: relative;
        display: flex;
        justify-content: flex-end;
        padding: 0.85rem 1rem;
    }

    .showcase-banner i {
        font-size: 3rem;
        color: rgba(255, 255, 255, 0.15);
    }

    .showcase-body {
        padding: 0 1.5rem 1.5rem;
        position: relative;
        text-align: center;
    }

    .avatar-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: -50px auto 1rem;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #ffffff;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-default-icon {
        font-size: 2.8rem;
        color: #94a3b8;
    }

    .avatar-btn-overlay {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 30px;
        height: 30px;
        background: #0288d1;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: transform 0.15s, background 0.15s;
    }

    .avatar-btn-overlay:hover {
        background: #0277bd;
        transform: scale(1.08);
    }

    .user-fullname {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .user-tagline {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #dbeafe;
        border-radius: 20px;
        font-size: 0.725rem;
        font-weight: 700;
        margin-top: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .photo-actions-bar {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin: 1.15rem 0 0.5rem;
    }

    .btn-choose-photo {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .btn-choose-photo:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .btn-remove-photo-styled {
        background: #fff1f2;
        border: 1px solid #fecdd3;
        color: #e11d48;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 0.775rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }

    .btn-remove-photo-styled:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    .photo-guideline {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 4px;
    }

    .profile-meta-list {
        margin-top: 1.25rem;
        border-top: 1px solid #f1f5f9;
        padding-top: 1rem;
        text-align: left;
    }

    .meta-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        font-size: 0.8rem;
        border-bottom: 1px dashed #f1f5f9;
    }

    .meta-item:last-child {
        border-bottom: none;
    }

    .meta-label {
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-value {
        font-weight: 600;
        color: #1e293b;
    }

    /* Right Card: Modern Form */
    .profile-form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .form-card-header {
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-body {
        padding: 1.75rem;
    }

    .form-section-title {
        font-size: 0.825rem;
        font-weight: 700;
        color: #0288d1;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-row-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 640px) {
        .form-row-grid {
            grid-template-columns: 1fr;
        }
    }

    .input-field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .input-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-prefix {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .modern-input {
        width: 100%;
        height: 42px;
        padding: 8px 12px 8px 38px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.875rem;
        color: #0f172a;
        transition: all 0.2s ease-in-out;
        outline: none;
    }

    .modern-input:focus {
        border-color: #0288d1;
        box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
    }

    .modern-input.is-invalid {
        border-color: #e11d48;
        background-color: #fff1f2;
    }

    .input-addon-btn {
        position: absolute;
        right: 10px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 6px;
        font-size: 0.9rem;
        transition: color 0.15s;
    }

    .input-addon-btn:hover {
        color: #0f172a;
    }

    .field-hint {
        font-size: 0.725rem;
        color: #64748b;
        margin-top: 2px;
    }

    .field-error-msg {
        font-size: 0.75rem;
        color: #e11d48;
        font-weight: 500;
        margin-top: 2px;
    }

    /* Form Footer Actions */
    .form-card-footer {
        padding: 1.25rem 1.75rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-action-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 18px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-action-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .btn-action-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 24px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #ffffff;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        border: none;
        box-shadow: 0 2px 4px rgba(2, 136, 209, 0.25);
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-action-save:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 4px 8px rgba(2, 136, 209, 0.35);
        transform: translateY(-1px);
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

    .alert-banner-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="profile-page-header">
    <div class="profile-page-title">
        <i class="fa-solid fa-id-card-clip" style="color: #d32f2f;"></i>
        <span>Pengaturan Akun & Profil Pengguna</span>
    </div>
    <div class="profile-page-subtitle">
        Kelola kredensial login, informasi kontak person, dan foto profil akun administrator PABX Angkasa Pura.
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert-banner-success">
        <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
        <div>
            <strong>Berhasil Diperbarui:</strong> {{ session('success') }}
        </div>
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert-banner-error">
        <div style="font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan pada pengisian form:
        </div>
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Main Two-Column Layout -->
<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">
    <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/gif" style="display: none;">

    <div class="profile-container-grid">
        <!-- Left Column: User Showcase Card -->
        <div class="profile-showcase-card">
            <div class="showcase-banner">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="showcase-body">
                <div class="avatar-wrapper">
                    <div class="avatar-circle">
                        @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                            <img src="{{ asset('uploads/profile/' . $user->photo) }}" id="avatarPreview" alt="Profile Photo">
                            <div id="avatarPlaceholder" class="avatar-default-icon" style="display: none;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @else
                            <div id="avatarPlaceholder" class="avatar-default-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <img src="" id="avatarPreview" alt="Profile Photo" style="display: none;">
                        @endif
                    </div>
                    <label for="photoInput" class="avatar-btn-overlay" title="Pilih Foto Baru">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>

                <div class="user-fullname">{{ $user->nama ?: 'Administrator' }}</div>
                <div class="user-tagline">&#64;{{ $user->username }}</div>
                
                <div>
                    <span class="badge-role">
                        <i class="fa-solid fa-shield-halved"></i>
                        {{ $user->leveluser ?: 'Super Administrator' }}
                    </span>
                </div>

                <!-- Photo Action Buttons -->
                <div class="photo-actions-bar">
                    <label for="photoInput" class="btn-choose-photo">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Ganti Foto
                    </label>
                    <button type="button" class="btn-remove-photo-styled" onclick="removeCurrentPhoto()" title="Hapus foto profil">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
                <div class="photo-guideline">Format JPG/PNG, ukuran maks 2 MB</div>

                <!-- Account Metadata Details -->
                <div class="profile-meta-list">
                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-fingerprint"></i> ID Pengguna</span>
                        <span class="meta-value">#{{ str_pad($user->iduser, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-building"></i> Cabang PABX</span>
                        <span class="meta-value">Juanda & Soetta</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-circle-dot" style="color: #22c55e;"></i> Status Akun</span>
                        <span class="meta-value" style="color: #166534; font-weight: 700;">Aktif</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label"><i class="fa-solid fa-clock-rotate-left"></i> Login Terakhir</span>
                        <span class="meta-value">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Account Profile Form Card -->
        <div class="profile-form-card">
            <div class="form-card-header">
                <div class="form-card-title">
                    <i class="fa-solid fa-pen-to-square" style="color: #0288d1;"></i>
                    <span>Formulir Pembaharuan Akun</span>
                </div>
                <span style="font-size: 0.75rem; background: #e0f2fe; color: #0369a1; padding: 3px 8px; border-radius: 4px; font-weight: 600;">
                    Autentikasi Aman
                </span>
            </div>

            <div class="form-card-body">
                <!-- Section 1: Informasi Identitas & Kontak -->
                <div class="form-section-title">
                    <i class="fa-solid fa-user-check"></i>
                    <span>1. Informasi Identitas & Kontak</span>
                </div>

                <div class="form-row-grid">
                    <div class="input-field-group">
                        <label class="input-label" for="nama">
                            <span>Nama Lengkap (Complete Name)</span>
                            <span style="color: #e11d48;">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon-prefix"></i>
                            <input 
                                type="text" 
                                id="nama" 
                                name="nama" 
                                class="modern-input @error('nama') is-invalid @enderror" 
                                value="{{ old('nama', $user->nama) }}" 
                                placeholder="Masukkan nama lengkap Anda..."
                                required
                            >
                        </div>
                        @error('nama')
                            <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-field-group">
                        <label class="input-label" for="telp">
                            <span>Nomor Telepon / Kontak</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-phone input-icon-prefix"></i>
                            <input 
                                type="text" 
                                id="telp" 
                                name="telp" 
                                class="modern-input @error('telp') is-invalid @enderror" 
                                value="{{ old('telp', $user->telp) }}"
                                placeholder="Contoh: 0811200..."
                            >
                        </div>
                        @error('telp')
                            <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Section 2: Kredensial & Keamanan Login -->
                <div class="form-section-title" style="margin-top: 1.75rem;">
                    <i class="fa-solid fa-lock"></i>
                    <span>2. Kredensial Login & Keamanan Sandi</span>
                </div>

                <div class="form-row-grid">
                    <div class="input-field-group">
                        <label class="input-label" for="username">
                            <span>Username Login</span>
                            <span style="color: #e11d48;">*</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-at input-icon-prefix"></i>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="modern-input @error('username') is-invalid @enderror" 
                                value="{{ old('username', $user->username) }}" 
                                placeholder="Username untuk masuk sistem..."
                                required
                            >
                        </div>
                        @error('username')
                            <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-field-group">
                        <label class="input-label" for="password">
                            <span>Kata Sandi Baru (Password)</span>
                        </label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-key input-icon-prefix"></i>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="modern-input @error('password') is-invalid @enderror" 
                                placeholder="Ketik kata sandi baru jika ingin diubah..."
                                autocomplete="new-password"
                            >
                            <button type="button" class="input-addon-btn" id="togglePasswordBtn" title="Lihat/Sembunyikan Sandi">
                                <i class="fa-solid fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <div class="field-hint">
                            <i class="fa-solid fa-circle-info"></i> Kosongkan kolom ini jika tidak ingin mengubah password saat ini.
                        </div>
                        @error('password')
                            <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Security Tip Box -->
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 0.85rem 1rem; display: flex; align-items: flex-start; gap: 10px; margin-top: 0.5rem;">
                    <i class="fa-solid fa-shield-cat" style="color: #0288d1; font-size: 1.15rem; margin-top: 2px;"></i>
                    <div style="font-size: 0.775rem; color: #64748b; line-height: 1.4;">
                        <strong style="color: #334155;">Catatan Keamanan Akun:</strong> Perubahan profil dan kredensial login akan langsung diterapkan saat sesi login berikutnya. Pastikan username dan nomor telepon selalu valid.
                    </div>
                </div>
            </div>

            <!-- Card Action Footer -->
            <div class="form-card-footer">
                <a href="{{ route('home') }}" class="btn-action-cancel">
                    <i class="fa-solid fa-arrow-left"></i> Batal / Kembali
                </a>
                <button type="submit" class="btn-action-save">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Profil
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    const photoInput = document.getElementById('photoInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarPlaceholder = document.getElementById('avatarPlaceholder');
    const removePhotoInput = document.getElementById('removePhotoInput');

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Size validation max 2MB
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto melebihi batas 2MB. Silakan pilih foto yang lebih kecil.');
                    photoInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    avatarPreview.src = evt.target.result;
                    avatarPreview.style.display = 'block';
                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'none';
                    }
                    removePhotoInput.value = '0';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function removeCurrentPhoto() {
        if (photoInput) {
            photoInput.value = '';
        }
        avatarPreview.src = '';
        avatarPreview.style.display = 'none';
        if (avatarPlaceholder) {
            avatarPlaceholder.style.display = 'flex';
        }
        removePhotoInput.value = '1';
    }

    // Toggle Password Visibility
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (toggleBtn && passwordInput && toggleIcon) {
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    }
</script>
@endsection
