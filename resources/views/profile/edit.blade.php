@extends('layouts.app')

@section('title', 'EDIT ACCOUNT PROFILE - ANGKASA PURA')

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

    .btn-outline-back {
        text-decoration: none;
        padding: 7px 14px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .btn-outline-back:hover {
        background: #e2e8f0;
        color: #0f172a;
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

    /* Avatar Studio Section */
    .avatar-studio {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem 1rem;
    }

    .avatar-circle-wrapper {
        position: relative;
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
        cursor: pointer;
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

    .avatar-hover-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .avatar-circle-wrapper:hover .avatar-hover-overlay {
        opacity: 1;
    }

    .btn-pick-photo {
        margin-top: 1rem;
        width: 100%;
        padding: 8px 12px;
        background: #0288d1;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 0.775rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
    }

    .btn-pick-photo:hover {
        background: #0277bd;
    }

    .btn-remove-photo {
        margin-top: 6px;
        background: none;
        border: none;
        font-size: 0.75rem;
        color: #ef4444;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        transition: color 0.15s;
    }

    .btn-remove-photo:hover {
        color: #b91c1c;
        text-decoration: underline;
    }

    .avatar-guidelines {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 6px;
        line-height: 1.3;
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
        font-size: 0.7rem;
        font-weight: 700;
        background: #eff6ff;
        color: #1d4ed8;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #bfdbfe;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Form Fields Section */
    .form-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.85rem;
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

    .fields-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .field-label {
        font-size: 0.775rem;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .input-with-icon {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-lead {
        position: absolute;
        left: 12px;
        color: #94a3b8;
        font-size: 0.9rem;
        pointer-events: none;
        transition: color 0.15s ease;
    }

    .form-control-modern {
        width: 100%;
        height: 40px;
        padding: 8px 12px 8px 38px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.875rem;
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
        right: 10px;
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 0.95rem;
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
        font-size: 0.725rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 3px;
    }

    /* Form Footer Actions */
    .form-footer-actions {
        margin-top: 2rem;
        padding-top: 1.25rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.85rem;
    }

    .btn-action-cancel {
        text-decoration: none;
        padding: 8px 20px;
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

    .btn-action-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-action-save {
        padding: 8px 24px;
        background: linear-gradient(135deg, #0288d1 0%, #0277bd 100%);
        border: none;
        border-radius: 6px;
        font-size: 0.825rem;
        font-weight: 600;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(2, 136, 209, 0.35);
        transition: all 0.15s ease;
    }

    .btn-action-save:hover {
        background: linear-gradient(135deg, #0277bd 0%, #01579b 100%);
        box-shadow: 0 4px 10px rgba(2, 136, 209, 0.45);
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-card">
        <!-- Card Header -->
        <div class="profile-card-header">
            <div style="display: flex; align-items: center; gap: 12px;">
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
            <div style="margin: 1.25rem 2.25rem 0; padding: 0.85rem 1.25rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 6px; font-size: 0.825rem;">
                <div style="font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>Terdapat kesalahan saat validasi form:</span>
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; line-height: 1.5;">
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
                            <i class="fa-solid fa-camera" style="font-size: 1.25rem;"></i>
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
                        Format JPG atau PNG<br>Ukuran maksimal 2 MB
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
                                <i class="fa-solid fa-shield" style="font-size: 0.7rem;"></i> Role diatur melalui menu Pengaturan Pengguna
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Keamanan Akun -->
                    <div class="form-section-title">
                        <i class="fa-solid fa-key" style="color: #0288d1;"></i>
                        <span>Keamanan Akun (Ganti Password)</span>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1.15rem 1.25rem;">
                        <div class="field-group">
                            <label class="field-label" for="passwordInput">
                                <span>KATA SANDI BARU</span>
                            </label>
                            <div class="input-with-icon">
                                <input type="password" id="passwordInput" name="password" class="form-control-modern" placeholder="Ketik kata sandi baru untuk mengganti..." style="background: #ffffff; padding-right: 40px;">
                                <i class="fa-solid fa-lock input-icon-lead"></i>
                                <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" title="Lihat / Sembunyikan Password">
                                    <i class="fa-solid fa-eye" id="pwdToggleIcon"></i>
                                </button>
                            </div>
                            <div class="field-hint">
                                <i class="fa-solid fa-circle-info" style="color: #0288d1;"></i>
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
