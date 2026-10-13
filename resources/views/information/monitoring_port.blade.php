@extends('layouts.app')

@section('title', 'LISTENING PORT - ANGKASA PURA PABX')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-network-wired" style="color: #0284c7;"></i>
                LISTENING PORT & SMDR BUFFER MONITOR
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Status soket TCP listener dan penangkap raw stream CDR langsung dari mesin PABX Bandara
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('information.call-history') }}" class="btn-search" style="text-decoration: none; background: #475569;">
                <i class="fa-solid fa-clock-rotate-left"></i> Call History
            </a>
            <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none;">
                <i class="fa-solid fa-house"></i> Home Dashboard
            </a>
        </div>
    </div>

    <!-- Status Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
        <!-- Port Status Card -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid {{ $portOpen ? '#16a34a' : '#0288d1' }}; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Status Listener Port</span>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        Port {{ $activeSetting->port ?? '5000' }}
                    </h3>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: {{ $portOpen ? '#dcfce7' : '#e0f2fe' }}; color: {{ $portOpen ? '#16a34a' : '#0288d1' }}; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-plug"></i>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $portOpen ? '#16a34a' : '#f59e0b' }};"></span>
                <span style="font-size: 0.825rem; font-weight: 600; color: {{ $portOpen ? '#16a34a' : '#d97706' }};">
                    {{ $portOpen ? 'PORT TERBUKA & LISTENING' : 'SIAP MENERIMA KONEKSI (STANDBY)' }}
                </span>
            </div>
        </div>

        <!-- PABX Hardware Settings Card -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #0284c7; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Tipe Mesin PABX</span>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ $activeSetting->mesin ?? 'PANASONIC KX-TDA/TDE' }}
                    </h3>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-server"></i>
                </div>
            </div>
            <div style="font-size: 0.8rem; color: #64748b;">
                Baudrate: <strong>{{ $activeSetting->baudrate ?? '9600' }}</strong> &bull; Parity: <strong>{{ $activeSetting->parity ?? 'None' }}</strong>
            </div>
        </div>

        <!-- IP Address Target -->
        <div style="background: white; border-radius: 12px; border: 1px solid #e2e8f0; border-top: 4px solid #8b5cf6; padding: 1.25rem; box-shadow: 0 2px 5px rgba(0,0,0,0.04);">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Host / IP Target</span>
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                        {{ $activeSetting->ip ?? '10.3.16.12' }}
                    </h3>
                </div>
                <div style="width: 42px; height: 42px; border-radius: 10px; background: #ede9fe; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid fa-ethernet"></i>
                </div>
            </div>
            <div style="font-size: 0.8rem; color: #64748b;">
                Mode Penangkapan: <strong>LAN Socket (TCP/IP SMDR)</strong>
            </div>
        </div>
    </div>

    <!-- Raw Data Buffer Stream -->
    <div class="report-card">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-terminal" style="color: #0284c7;"></i>
                <span>Raw SMDR Stream Buffer (15 Paket Terakhir)</span>
            </div>
            <button onclick="location.reload();" class="btn-search" style="height: 28px; padding: 0 12px; font-size: 0.75rem;">
                <i class="fa-solid fa-rotate-right"></i> Refresh Stream
            </button>
        </div>

        <div style="padding: 1.25rem; background: #0f172a; color: #38bdf8; font-family: 'JetBrains Mono', monospace; font-size: 0.825rem; line-height: 1.7; overflow-x: auto;">
            @forelse($recentRaw as $raw)
                <div style="padding: 4px 0; border-bottom: 1px solid rgba(255,255,255,0.07); display: flex; gap: 1rem;">
                    <span style="color: #64748b; width: 60px;">#{{ $raw->id }}</span>
                    <span style="color: #a78bfa;">[{{ $raw->tglmasuk }} {{ $raw->jammasuk }}]</span>
                    <span style="color: #34d399;">EXT: {{ $raw->ext_pemanggil }}</span>
                    <span style="color: #f1f5f9; font-weight: 600;">RAW &gt;&gt; {{ $raw->gabung ?: 'SMDR PACKET CAPTURED' }}</span>
                </div>
            @empty
                <div style="color: #94a3b8; text-align: center; padding: 2rem;">
                    Belum ada stream buffer yang tersimpan.
                </div>
            @endforelse
        </div>
        <div style="padding: 0.75rem 1.25rem; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 0.775rem; color: #64748b; display: flex; justify-content: space-between;">
            <span><i class="fa-solid fa-circle-info" style="color: #0288d1;"></i> Service Listener membaca data dari socket PABX dan menyimpannya ke tabel <code>tbm_data_masuk</code>.</span>
            <span>Auto parser: <strong>Active</strong></span>
        </div>
    </div>
</div>
@endsection
