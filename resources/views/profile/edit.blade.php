@extends('layouts.app')

@section('title', 'Account Profile - ANGKASA PURA PABX')

@section('styles')
<style>
    .profile-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        padding: 1.5rem 1.75rem;
        margin-bottom: 2rem;
    }

    .profile-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .profile-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        column-gap: 2.5rem;
        row-gap: 1.25rem;
    }

    @media (max-width: 768px) {
        .profile-form-grid {
            grid-template-columns: 1fr;
            column-gap: 0;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 0.85rem;
    }

    .form-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
    }

    .form-input {
        height: 34px;
        padding: 6px 10px;
        border: 1px solid #cbd5e1;
        border-radius: 3px;
        font-size: 0.85rem;
        color: #1e293b;
        background-color: #ffffff;
        transition: border-color 0.15s, box-shadow 0.15s;
        outline: none;
        width: 100%;
    }

    .form-input:focus {
        border-color: #0288d1;
        box-shadow: 0 0 0 1px #0288d1;
    }

    .form-input.is-invalid {
        border-color: #d32f2f;
    }

    .invalid-feedback {
        color: #d32f2f;
        font-size: 0.75rem;
        margin-top: 2px;
    }

    /* Photo Upload Box (matching screenshot) */
    .photo-preview-container {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 0.25rem;
    }

    .photo-preview-box {
        width: 140px;
        height: 100px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    .photo-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-preview-placeholder {
        color: #94a3b8;
        font-size: 2.2rem;
    }

    .file-input-wrapper {
        font-size: 0.8rem;
        color: #475569;
    }

    .btn-remove-photo {
        color: #0288d1;
        font-size: 0.775rem;
        text-decoration: underline;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        text-align: left;
        width: fit-content;
        transition: color 0.15s;
    }

    .btn-remove-photo:hover {
        color: #b71c1c;
    }

    /* Action Buttons (matching screenshot) */
    .btn-batal {
        background: #d32f2f;
        color: #ffffff;
        border: none;
        border-radius: 3px;
        padding: 6px 20px;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
        height: 32px;
        width: fit-content;
    }

    .btn-batal:hover {
        background: #b71c1c;
        color: #ffffff;
    }

    .btn-update {
        background: #0288d1;
        color: #ffffff;
        border: none;
        border-radius: 3px;
        padding: 6px 22px;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
        height: 32px;
        width: fit-content;
    }

    .btn-update:hover {
        background: #0277bd;
    }

    .button-group-left {
        margin-top: 1.5rem;
    }

    .button-group-right {
        margin-top: 1.5rem;
        display: flex;
        justify-content: flex-end;
    }

    .alert-danger-box {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #b91c1c;
        padding: 0.75rem 1rem;
        border-radius: 4px;
        margin-bottom: 1.25rem;
        font-size: 0.85rem;
    }
</style>
@endsection

@section('content')
<div class="profile-card">
    <div class="profile-card-title">ACCOUNT PROFILE</div>

    @if (isset($errors) && $errors->any())
        <div class="alert-danger-box">
            <strong style="display: block; margin-bottom: 4px;"><i class="fa-solid fa-triangle-exclamation"></i> Terdapat kesalahan pada form:</strong>
            <ul style="padding-left: 1.25rem; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">

        <div class="profile-form-grid">
            <!-- Left Column -->
            <div class="profile-col-left">
                <!-- Complete Name -->
                <div class="form-group">
                    <label class="form-label" for="nama">Complete Name</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        class="form-input @error('nama') is-invalid @enderror" 
                        value="{{ old('nama', $user->nama) }}" 
                        required
                    >
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input @error('username') is-invalid @enderror" 
                        value="{{ old('username', $user->username) }}" 
                        required
                    >
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Photo Upload Section -->
                <div class="form-group">
                    <div class="photo-preview-container">
                        <div class="photo-preview-box" id="previewContainer">
                            @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                                <img src="{{ asset('uploads/profile/' . $user->photo) }}" id="avatarPreview" alt="Profile Photo">
                            @else
                                <div id="avatarPlaceholder" class="photo-preview-placeholder">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                                <img src="" id="avatarPreview" alt="Profile Photo" style="display: none;">
                            @endif
                        </div>

                        <div class="file-input-wrapper">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/gif" style="font-size: 0.8rem;">
                        </div>

                        <button type="button" class="btn-remove-photo" id="removePhotoBtn" onclick="removeCurrentPhoto()">
                            Remove
                        </button>
                    </div>
                </div>

                <!-- Batal Button (Red) -->
                <div class="button-group-left">
                    <a href="{{ route('home') }}" class="btn-batal">Batal</a>
                </div>
            </div>

            <!-- Right Column -->
            <div class="profile-col-right">
                <!-- Phone No -->
                <div class="form-group">
                    <label class="form-label" for="telp">Phone No</label>
                    <input 
                        type="text" 
                        id="telp" 
                        name="telp" 
                        class="form-input @error('telp') is-invalid @enderror" 
                        value="{{ old('telp', $user->telp) }}"
                    >
                    @error('telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') is-invalid @enderror" 
                        placeholder="••••••••••••••••"
                        autocomplete="new-password"
                    >
                    <span style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">
                        Kosongkan jika tidak ingin mengubah password
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Update Data Button (Blue) -->
                <div class="button-group-right">
                    <button type="submit" class="btn-update">Update Data</button>
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
</script>
@endsection
