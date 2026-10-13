@extends('layouts.app')

@section('title', 'LEVEL AKSES (HAK AKSES PENGGUNA) - ANGKASA PURA PABX')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-user-lock" style="color: #7c3aed;"></i>
                LEVEL AKSES & MATRIKS HAK AKSES PENGGUNA
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Konfigurasi izin akses per level pengguna (Privilege Matrix) untuk seluruh modul sistem PABX
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('settings.user') }}" class="btn-search" style="background: #0288d1; text-decoration: none;">
                <i class="fa-solid fa-users"></i> Master User
            </a>
            <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none;">
                <i class="fa-solid fa-house"></i> Home Dashboard
            </a>
        </div>
    </div>

    <!-- Level Selection Tabs / Selector -->
    <div class="report-card" style="margin-bottom: 1.5rem;">
        <div style="padding: 1.25rem; background: #fafbfc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 0.825rem; font-weight: 700; color: #334155;">Pilih Role / Level Pengguna:</span>
                <div style="display: flex; gap: 8px;">
                    @foreach($levels as $lvl)
                        <a href="{{ route('utility.level-akses', ['level_id' => $lvl->id]) }}" 
                           class="btn-search" 
                           style="height: 32px; text-decoration: none; background: {{ ($selectedLevel && $selectedLevel->id == $lvl->id) ? '#7c3aed' : '#ffffff' }}; color: {{ ($selectedLevel && $selectedLevel->id == $lvl->id) ? '#ffffff' : '#334155' }}; border: 1px solid {{ ($selectedLevel && $selectedLevel->id == $lvl->id) ? '#7c3aed' : '#cbd5e1' }}; font-weight: 700;">
                            <i class="fa-solid fa-shield-halved"></i> {{ $lvl->level }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div style="font-size: 0.775rem; color: #64748b;">
                Level Aktif: <strong style="color: #7c3aed;">{{ $selectedLevel ? $selectedLevel->level : 'Administrator' }}</strong>
            </div>
        </div>

        <!-- Privilege Matrix Form -->
        <form action="{{ route('utility.level-akses.update') }}" method="POST">
            @csrf
            <input type="hidden" name="level_id" value="{{ $selectedLevel ? $selectedLevel->id : 1 }}">

            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-table-list" style="color: #7c3aed;"></i>
                    <span>Daftar Modul & Izin Akses ({{ $selectedLevel ? $selectedLevel->level : '' }})</span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="toggleAllCheckboxes(true)" class="btn-search" style="background: #0288d1; height: 28px; padding: 0 10px; font-size: 0.75rem;">
                        Pilih Semua
                    </button>
                    <button type="button" onclick="toggleAllCheckboxes(false)" class="btn-search" style="background: #64748b; height: 28px; padding: 0 10px; font-size: 0.75rem;">
                        Hapus Semua
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="width: 140px;">Kelompok Menu</th>
                            <th>Nama Modul</th>
                            <th>URL Route / Link</th>
                            <th style="width: 130px; text-align: center;">Hak Akses Tampil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($modules as $idx => $mod)
                            @php
                                $isChecked = isset($accessRights[$mod->iddetail]) || ($selectedLevel && $selectedLevel->id == 1);
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                                <td>
                                    <span style="background: #f1f5f9; color: #334155; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        {{ $mod->kategorimenu ?: 'Umum' }}
                                    </span>
                                </td>
                                <td><strong>{{ $mod->namadetail }}</strong></td>
                                <td><code style="font-size: 0.775rem;">{{ $mod->link }}</code></td>
                                <td style="text-align: center;">
                                    <label style="cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                                        <input type="checkbox" name="modules[]" value="{{ $mod->iddetail }}" class="mod-checkbox" {{ $isChecked ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #7c3aed; cursor: pointer;">
                                        <span style="font-size: 0.775rem; font-weight: 600; color: {{ $isChecked ? '#15803d' : '#64748b' }};">Aktif</span>
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-search" style="background: linear-gradient(135deg, #7c3aed, #6d28d9); height: 38px; padding: 0 22px; font-weight: 700; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Hak Akses
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAllCheckboxes(checked) {
    document.querySelectorAll('.mod-checkbox').forEach(function(cb) {
        cb.checked = checked;
    });
}
</script>
@endsection
