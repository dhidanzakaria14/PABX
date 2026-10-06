@extends('layouts.app')

@section('title', 'EDIT ACCOUNT PROFILE - ANGKASA PURA')

@section('content')
<div class="report-card">
    <div class="report-header">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-pen-to-square" style="color: #0288d1;"></i>
            <span>EDIT ACCOUNT PROFILE</span>
        </div>
        <a href="{{ route('profile.show') }}" style="text-decoration: none; font-size: 0.8rem; color: #64748b; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Profil
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div style="margin: 1.25rem 1.75rem 0; padding: 0.75rem 1rem; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 4px; font-size: 0.825rem;">
            <strong><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan pada form:</strong>
            <ul style="margin: 4px 0 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="padding: 1.75rem 2rem;">
        @csrf
        <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">
        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/gif" style="display: none;">

        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 2.5rem; align-items: start;">
            <!-- Left Column: Photo Upload Section -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div style="width: 140px; height: 140px; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #f8fafc; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); position: relative;">
                    @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                        <img src="{{ asset('uploads/profile/' . $user->photo) }}" id="avatarPreview" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <i class="fa-solid fa-user" id="avatarPlaceholder" style="font-size: 3.5rem; color: #94a3b8; display: none;"></i>
                    @else
                        <img src="" id="avatarPreview" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <i class="fa-solid fa-user" id="avatarPlaceholder" style="font-size: 3.5rem; color: #94a3b8;"></i>
                    @endif
                </div>

                <div style="margin-top: 0.85rem; width: 100%;">
                    <label for="photoInput" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 6px 12px; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.775rem; font-weight: 600; color: #334155; cursor: pointer; transition: background 0.15s;">
                        <i class="fa-solid fa-camera"></i> Pilih Foto
                    </label>
                </div>

                <button type="button" onclick="removeCurrentPhoto()" style="margin-top: 6px; background: none; border: none; font-size: 0.75rem; color: #e11d48; cursor: pointer; text-decoration: underline;">
                    Hapus Foto
                </button>
                <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 4px;">Maksimal 2MB (JPG/PNG)</div>
            </div>

            <!-- Right Column: Editable Form Fields -->
            <div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem 2rem;">
                    <!-- Complete Name -->
                    <div>
                        <label style="display: block; font-size: 0.775rem; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 5px;">
                            Complete Name <span style="color: #e11d48;">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required style="width: 100%; height: 36px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; color: #0f172a; outline: none; background: #ffffff; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0288d1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <!-- Phone No -->
                    <div>
                        <label style="display: block; font-size: 0.775rem; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 5px;">
                            Phone No
                        </label>
                        <input type="text" name="telp" value="{{ old('telp', $user->telp) }}" style="width: 100%; height: 36px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; color: #0f172a; outline: none; background: #ffffff; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0288d1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <!-- Username -->
                    <div>
                        <label style="display: block; font-size: 0.775rem; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 5px;">
                            Username <span style="color: #e11d48;">*</span>
                        </label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required style="width: 100%; height: 36px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; color: #0f172a; outline: none; background: #ffffff; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0288d1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <!-- Password -->
                    <div>
                        <label style="display: block; font-size: 0.775rem; font-weight: 700; color: #334155; text-transform: uppercase; margin-bottom: 5px;">
                            Password
                        </label>
                        <div style="position: relative;">
                            <input type="password" id="passwordInput" name="password" placeholder="••••••••••••" style="width: 100%; height: 36px; padding: 6px 36px 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; color: #0f172a; outline: none; background: #ffffff; transition: border-color 0.15s;" onfocus="this.style.borderColor='#0288d1'" onblur="this.style.borderColor='#cbd5e1'">
                            <button type="button" onclick="togglePasswordVisibility()" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #64748b; cursor: pointer; padding: 4px;" title="Lihat Password">
                                <i class="fa-solid fa-eye" id="pwdToggleIcon"></i>
                            </button>
                        </div>
                        <div style="font-size: 0.725rem; color: #64748b; margin-top: 3px;">
                            Kosongkan jika tidak ingin mengubah password
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons: Batal & Update Data -->
                <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('profile.show') }}" class="btn-export" style="text-decoration: none; padding: 6px 18px; font-size: 0.825rem; background: #64748b; color: white; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <button type="submit" class="btn-search" style="padding: 6px 22px; font-size: 0.825rem; background: #0288d1; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-check"></i> Update Data
                    </button>
                </div>
            </div>
        </div>
    </form>
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
