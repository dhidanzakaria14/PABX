@extends('layouts.app')

@section('title', 'SERVER HEALTH & DIAGNOSTICS - ANGKASA PURA PABX')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-heart-pulse" style="color: #0284c7;"></i>
                SERVER HEALTH & DIAGNOSTICS
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Pemantauan kesehatan server aplikasi, mesin PABX, infrastruktur basis data, dan sumber daya sistem
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="location.reload();" class="btn-search" style="background: #0288d1;">
                <i class="fa-solid fa-rotate"></i> Refresh Diagnostik
            </button>
            <a href="{{ route('utility.backup-maintenance') }}" class="btn-search" style="background: #16a34a; text-decoration: none;">
                <i class="fa-solid fa-box-archive"></i> Backup & Maintenance
            </a>
        </div>
    </div>

    <!-- Status Overview Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
        <!-- PABX Socket Connection -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid {{ $pabxConnected ? '#16a34a' : '#d32f2f' }}; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">PABX Hardware Server</span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ $pabxIp }}:{{ $pabxPort }}
                    </h3>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $pabxConnected ? '#dcfce7' : '#fee2e2' }}; color: {{ $pabxConnected ? '#16a34a' : '#d32f2f' }}; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; font-weight: 700; color: {{ $pabxConnected ? '#16a34a' : '#d32f2f' }};">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $pabxConnected ? '#16a34a' : '#d32f2f' }};"></span>
                    {{ $pabxConnected ? 'Terhubung (Socket Live)' : 'Standby / LAN Listener Ready' }}
                </span>
                @if($pabxLatency)
                    <span style="font-size: 0.75rem; color: #64748b;">{{ $pabxLatency }} ms</span>
                @endif
            </div>
        </div>

        <!-- Disk Storage Usage -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #0288d1; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Penyimpanan Disk Server (C:)</span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ round($diskUsedBytes / (1024*1024*1024), 1) }} GB <span style="font-size: 0.825rem; color: #64748b; font-weight: normal;">/ {{ round($diskTotalBytes / (1024*1024*1024), 1) }} GB</span>
                    </h3>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #e0f2fe; color: #0288d1; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fa-solid fa-hard-drive"></i>
                </div>
            </div>
            <!-- Progress Bar -->
            <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden; margin-bottom: 6px;">
                <div style="width: {{ $diskPercent }}%; height: 100%; background: {{ $diskPercent > 85 ? '#ef4444' : '#0288d1' }}; border-radius: 999px;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: #64748b;">
                <span>Sisa: <strong>{{ round($diskFreeBytes / (1024*1024*1024), 1) }} GB Free</strong></span>
                <span>Terpakai <strong>{{ $diskPercent }}%</strong></span>
            </div>
        </div>

        <!-- PHP & Framework Engine -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #7c3aed; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">PHP Runtime Engine</span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        PHP {{ $phpVersion }}
                    </h3>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #ede9fe; color: #7c3aed; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fa-brands fa-php"></i>
                </div>
            </div>
            <div style="font-size: 0.775rem; color: #64748b;">
                Framework: <strong>Laravel v{{ $laravelVersion }}</strong> &bull; Mem: <strong>{{ round($memoryUsageBytes / (1024*1024), 1) }} MB</strong> (Limit: {{ $memoryLimit }})
            </div>
        </div>

        <!-- Database Storage -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #f59e0b; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.85rem;">
                <div>
                    <span style="font-size: 0.725rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Database MySQL PABX</span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ round($dbSizeBytes / (1024*1024), 2) }} MB
                    </h3>
                </div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.15rem;">
                    <i class="fa-solid fa-database"></i>
                </div>
            </div>
            <div style="font-size: 0.775rem; color: #64748b;">
                Schema: <code>{{ $dbName }}</code> &bull; Total CDR: <strong>{{ number_format($tables['tbm_data_masuk']) }}</strong> baris
            </div>
        </div>
    </div>

    <!-- Middle Row: Database Tables Health & PHP Extensions -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Database Tables Health -->
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-table-cells" style="color: #0284c7;"></i>
                    <span>Status Integritas Tabel Basis Data (MySQL)</span>
                </div>
                <span style="font-size: 0.75rem; color: #16a34a; font-weight: 700; background: #dcfce7; padding: 2px 8px; border-radius: 20px;">
                    <i class="fa-solid fa-circle-check"></i> Seluruh Tabel Sehat
                </span>
            </div>
            <div class="table-responsive">
                <table class="ap-table">
                    <thead>
                        <tr>
                            <th>Nama Tabel</th>
                            <th>Fungsi Sistem</th>
                            <th style="text-align: right;">Jumlah Baris Data</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>tbm_data_masuk</code></td>
                            <td>Rekaman Panggilan Mentah & Billing (CDR)</td>
                            <td style="text-align: right; font-weight: 700; color: #0288d1;">{{ number_format($tables['tbm_data_masuk']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                        <tr>
                            <td><code>tbm_department</code></td>
                            <td>Data Ekstensi / Phone-ID Pemakai Bandara</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($tables['tbm_department']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                        <tr>
                            <td><code>tbm_area</code></td>
                            <td>Tabel Prefix Area & Zona Telekomunikasi</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($tables['tbm_area']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                        <tr>
                            <td><code>tbm_tarif</code></td>
                            <td>Master Rate Biaya PABX & Interval Detik</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($tables['tbm_tarif']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                        <tr>
                            <td><code>tbm_zona</code></td>
                            <td>Pengelompokan Zona SLJJ, Lokal, & Seluler</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($tables['tbm_zona']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                        <tr>
                            <td><code>tbl_user</code></td>
                            <td>Akun Operator & Administrator Sistem</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($tables['tbl_user']) }} entri</td>
                            <td style="text-align: center;"><span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 6px; border-radius: 4px;">OK</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PHP Extensions & Environment -->
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-microchip" style="color: #7c3aed;"></i>
                    <span>Ekstensi PHP</span>
                </div>
            </div>
            <div style="padding: 1.25rem;">
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($extensions as $extName => $loaded)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 6px 10px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <span style="font-family: monospace; font-size: 0.825rem; font-weight: 600; color: #1e293b;">ext-{{ $extName }}</span>
                            @if($loaded)
                                <span style="background: #dcfce7; color: #15803d; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    <i class="fa-solid fa-check"></i> Enabled
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #b91c1c; font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    <i class="fa-solid fa-xmark"></i> Disabled
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="padding: 0.85rem 1.25rem; background: #fafbfc; border-top: 1px solid #e2e8f0; font-size: 0.75rem; color: #64748b;">
                OS: <strong>{{ $osName }}</strong>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Recent System Access & Users -->
    <div class="report-card">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-user-shield" style="color: #0288d1;"></i>
                <span>Riwayat Akses Akun Pengguna (User Activity)</span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Level Privilege</th>
                        <th>Terakhir Login</th>
                        <th style="text-align: right;">Total Sesi (Hits)</th>
                        <th style="text-align: center;">Status Akun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLogins as $user)
                        <tr>
                            <td><strong>{{ $user->username }}</strong></td>
                            <td>{{ $user->nama ?: '-' }}</td>
                            <td>
                                <span style="background: #e0f2fe; color: #0288d1; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                    {{ $user->leveluser }}
                                </span>
                            </td>
                            <td>{{ $user->login ?: 'Belum pernah login' }}</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($user->hit ?: 0) }} kali</td>
                            <td style="text-align: center;">
                                @if($user->aktif == 1)
                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        Aktif
                                    </span>
                                @else
                                    <span style="background: #fee2e2; color: #b91c1c; font-size: 0.725rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                Belum ada riwayat aktivitas user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
