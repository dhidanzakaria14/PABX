@extends('layouts.app')

@section('title', 'ACCOUNT PROFILE - ANGKASA PURA')

@section('content')
<div class="report-card">
    <div class="report-header">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-id-card" style="color: #0288d1;"></i>
            <span>ACCOUNT PROFILE</span>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn-search" style="text-decoration: none; padding: 6px 16px; font-size: 0.8rem; background: #0288d1; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-pen-to-square"></i> Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div style="margin: 1.25rem 1.75rem 0; padding: 0.75rem 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; border-radius: 4px; font-size: 0.825rem; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div style="padding: 1.75rem 2rem;">
        <div style="display: grid; grid-template-columns: 180px 1fr; gap: 2.5rem; align-items: start;">
            <!-- Left Column: Photo & Role Badge -->
            <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
                <div style="width: 140px; height: 140px; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; background: #f8fafc; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    @if($user->photo && file_exists(public_path('uploads/profile/' . $user->photo)))
                        <img src="{{ asset('uploads/profile/' . $user->photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <i class="fa-solid fa-user" style="font-size: 3.5rem; color: #94a3b8;"></i>
                    @endif
                </div>
                <div style="margin-top: 0.85rem; font-weight: 700; font-size: 0.95rem; color: #1e293b;">
                    {{ $user->nama ?: 'Administrator' }}
                </div>
                <div style="font-size: 0.775rem; color: #64748b; margin-top: 2px;">
                    &#64;{{ $user->username }}
                </div>
                <div style="margin-top: 0.5rem;">
                    <span style="font-size: 0.7rem; font-weight: 700; background: #eff6ff; color: #1d4ed8; padding: 3px 10px; border-radius: 12px; border: 1px solid #bfdbfe; text-transform: uppercase;">
                        {{ $user->leveluser ?: 'Administrator' }}
                    </span>
                </div>
            </div>

            <!-- Right Column: Clean Details Grid -->
            <div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem 2rem;">
                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Complete Name</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                            {{ $user->nama ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Phone No</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                            {{ $user->telp ?: '-' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Username</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                            {{ $user->username }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Password</div>
                        <div style="font-size: 0.95rem; color: #64748b; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9; font-family: monospace;">
                            &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">User Level / Role</div>
                        <div style="font-size: 0.95rem; font-weight: 600; color: #0f172a; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                            {{ $user->leveluser ?: 'Administrator' }}
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Account Status</div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #166534; margin-top: 4px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e;"></span>
                            Aktif & Terdaftar
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div style="margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem;">
                    <a href="{{ route('home') }}" class="btn-export" style="text-decoration: none; padding: 6px 16px; font-size: 0.8rem; background: #64748b; color: white; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                    </a>
                    <a href="{{ route('profile.edit') }}" class="btn-search" style="text-decoration: none; padding: 6px 20px; font-size: 0.8rem; background: #0288d1; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
