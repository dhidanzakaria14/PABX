@extends('layouts.app')

@section('title', 'EDIT ACCOUNT PROFILE - ANGKASA PURA')

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

    .btn-outline-back {
        text-decoration: none;
        padding: 9px 18px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .btn-outline-back:hover {
        background: #e2e8f0;
        color: #0f172a;
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

    /* Avatar Studio Section */
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
        position: relative;
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
        cursor: pointer;
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

    .avatar-hover-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.65);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 0.825rem;
        font-weight: 700;
    }

    .avatar-circle-wrapper:hover .avatar-hover-overlay {
        opacity: 1;
    }

    .btn-pick-photo {
        margin-top: 1.25rem;
        width: 100%;
        height: 40px;
        background: #0288d1;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: background 0.15s ease;
        box-shadow: 0 2px 6px rgba(2, 136, 209, 0.25);
    }

    .btn-pick-photo:hover {
        background: #0277bd;
    }

    .btn-remove-photo {
        margin-top: 8px;
        background: none;
        border: none;
        font-size: 0.8rem;
        color: #ef4444;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px;
        transition: color 0.15s;
    }

    .btn-remove-photo:hover {
        color: #b91c1c;
        text-decoration: underline;
    }

    .avatar-guidelines {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 8px;
        line-height: 1.4;
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

    /* Form Fields Section */
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

    .fields-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem 1.75rem;
        margin-bottom: 2rem;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-lead {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        transition: color 0.15s ease;
    }

    .form-control-modern {
        width: 100%;
        height: 46px;
        padding: 8px 14px 8px 44px;
        background: #f8fafc;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.925rem;
        font-weight: 500;
        color: #0f172a;
        outline: none;
        transition: all 0.15s ease;
    }

    .form-control-modern:focus {
        background: #ffffff;
        border-color: #0288d1;
        box-shadow: 0 0 0 3px rgba(2, 136, 209, 0.15);
    }

    .form-control-modern:focus + .input-icon-lead,
    .input-with-icon:focus-within .input-icon-lead {
        color: #0288d1;
    }

    .form-control-modern:disabled,
    .form-control-modern[readonly] {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }

    .btn-toggle-eye {
        position: absolute;
        right: 12px;
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.05rem;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }

    .btn-toggle-eye:hover {
        color: #0288d1;
    }

    .field-hint {
        font-size: 0.775rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
    }

    /* Form Footer Actions */
    .form-footer-actions {
        margin-top: 2.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-action-cancel {
        text-decoration: none;
        padding: 10px 24px;
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

    .btn-action-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-action-save {
        padding: 10px 30px;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        border: none;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(2, 136, 209, 0.35);
        transition: all 0.15s ease;
    }

    .btn-action-save:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 6px 16px rgba(2, 136, 209, 0.45);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 900px) {
        .profile-layout-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .fields-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <!-- Card Header -->
        <div class="profile-card-header">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div class="header-icon-box">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <div class="header-title">Edit Profil Pengguna</div>
                    <div class="header-subtitle">Perbarui identitas profil, nomor kontak, dan keamanan akun Anda</div>
                </div>
            </div>
            <a href="{{ route('profile.show') }}" class="btn-outline-back" title="Kembali ke halaman rincian profil">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Profil
            </a>
        </div>

        @if (isset($errors) && $errors->any())
            <div style="margin: 1.5rem 2.75rem 0; padding: 1rem 1.5rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 8px; font-size: 0.85rem;">
                <div style="font-weight: 800; display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 1.1rem;"></i>
                    <span>Terdapat kesalahan saat validasi formulir:</span>
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; line-height: 1.6;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-card-body">
            @csrf
            <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">
            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/gif" style="display: none;">

            <div class="profile-layout-grid">
                <!-- Left Column: Photo & Role Badge -->
                <div class="avatar-studio">
                    <div class="avatar-circle-wrapper" id="avatarTrigger" onclick="document.getElementById('photoInput').click()" title="Klik untuk mengunggah foto profil baru">
                        @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                            <img src="{{ asset('uploads/profile/' . $user->photo) }}" id="avatarPreview" alt="Avatar">
                            <i class="fa-solid fa-user placeholder-icon" id="avatarPlaceholder" style="display: none;"></i>
                        @else
                            <img src="" id="avatarPreview" alt="Avatar" style="display: none;">
                            <i class="fa-solid fa-user placeholder-icon" id="avatarPlaceholder"></i>
                        @endif
                        <div class="avatar-hover-overlay">
                            <i class="fa-solid fa-camera" style="font-size: 1.5rem;"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>

                    <button type="button" class="btn-pick-photo" onclick="document.getElementById('photoInput').click()">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i> Pilih Foto Baru
                    </button>

                    <button type="button" id="btnRemovePhoto" class="btn-remove-photo" onclick="removeCurrentPhoto()">
                        <i class="fa-solid fa-trash-can"></i> Hapus Foto
                    </button>

                    <div class="avatar-guidelines">
                        Format JPG atau PNG<br>Ukuran berkas maksimal 2 MB
                    </div>

                    <div class="role-badge-box">
                        <span class="badge-role">
                            <i class="fa-solid fa-shield-halved"></i>
                            {{ $user->leveluser ?: 'Administrator' }}
                        </span>
                    </div>
                </div>

                <!-- Right Column: Form Fields -->
                <div>
                    <!-- Section 1: Informasi Pengguna -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-id-card-clip" style="color: #0288d1;"></i>
                        <span>Informasi Identitas & Kontak</span>
                    </div>

                    <div class="fields-grid-2">
                        <!-- Complete Name -->
                        <div class="field-group">
                            <label class="field-label" for="inputNama">
                                <span>NAMA LENGKAP</span>
                                <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="text" id="inputNama" name="nama" value="{{ old('nama', $user->nama) }}" class="form-control-modern" placeholder="Masukkan nama lengkap" required>
                                <i class="fa-solid fa-user input-icon-lead"></i>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="field-group">
                            <label class="field-label" for="inputTelp">
                                <span>NOMOR TELEPON / EXT</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="text" id="inputTelp" name="telp" value="{{ old('telp', $user->telp) }}" class="form-control-modern" placeholder="Contoh: 0811200 / Ext 101">
                                <i class="fa-solid fa-phone input-icon-lead"></i>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="field-group">
                            <label class="field-label" for="inputUsername">
                                <span>USERNAME LOGIN</span>
                                <span style="color: #ef4444;">*</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="text" id="inputUsername" name="username" value="{{ old('username', $user->username) }}" class="form-control-modern" placeholder="Username untuk masuk sistem" required>
                                <i class="fa-solid fa-at input-icon-lead"></i>
                            </div>
                        </div>

                        <!-- User Level / Role (Read-only for security) -->
                        <div class="field-group">
                            <label class="field-label">
                                <span>HAK AKSES / ROLE</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="text" value="{{ $user->leveluser ?: 'Administrator' }}" class="form-control-modern" readonly disabled>
                                <i class="fa-solid fa-lock input-icon-lead"></i>
                            </div>
                            <div class="field-hint">
                                <i class="fa-solid fa-shield" style="font-size: 0.75rem;"></i> Role akun diatur melalui Master Pengguna
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Keamanan Akun -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-key" style="color: #0288d1;"></i>
                        <span>Keamanan Akun (Ganti Password)</span>
                    </div>

                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 1.35rem 1.5rem;">
                        <div class="field-group">
                            <label class="field-label" for="passwordInput">
                                <span>KATA SANDI BARU</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="password" id="passwordInput" name="password" class="form-control-modern" placeholder="Ketik kata sandi baru untuk mengganti..." style="background: #ffffff; padding-right: 44px;">
                                <i class="fa-solid fa-lock input-icon-lead"></i>
                                <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" title="Lihat / Sembunyikan Password">
                                    <i class="fa-solid fa-eye" id="pwdToggleIcon"></i>
                                </button>
                            </div>
                            <div class="field-hint" style="margin-top: 6px;">
                                <i class="fa-solid fa-circle-info" style="color: #0288d1; font-size: 0.85rem;"></i>
                                <span>Kosongkan bidang ini jika Anda <strong>tidak ingin</strong> mengubah kata sandi saat ini.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="form-footer-actions">
                        <a href="{{ route('profile.show') }}" class="btn-action-cancel">
                            <i class="fa-solid fa-xmark"></i> Batal
                        </a>
                        <button type="submit" class="btn-action-save">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
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
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto melebihi batas 2MB. Silakan pilih foto dengan ukuran lebih kecil.');
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
            avatarPlaceholder.style.display = 'block';
        }
        removePhotoInput.value = '1';
    }

    function togglePasswordVisibility() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('pwdToggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
