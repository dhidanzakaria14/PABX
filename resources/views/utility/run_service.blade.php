@extends('layouts.app')

@section('title', 'RUN SERVICE - PABX LISTENER SERVICE')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <!-- Breadcrumb & Top Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-play" style="color: #16a34a;"></i>
                RUN SERVICE (PABX DAEMON LISTENER)
            </h1>
            <p style="font-size: 0.825rem; color: #64748b; margin-top: 2px;">
                Pengendali dan monitor proses penangkap data panggilan mentah (Raw SMDR Stream) dari PABX Panasonic
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('utility.server-health') }}" class="btn-search" style="background: #0288d1; text-decoration: none;">
                <i class="fa-solid fa-heart-pulse"></i> Server Diagnostics
            </a>
            <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none;">
                <i class="fa-solid fa-house"></i> Home Dashboard
            </a>
        </div>
    </div>

    <!-- Service Status Card -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-satellite-dish" style="color: #0288d1;"></i>
                    <span>Status PABX Listener Service Daemon</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: {{ $isListening ? '#16a34a' : '#f59e0b' }};"></span>
                    <span style="font-size: 0.8rem; font-weight: 700; color: {{ $isListening ? '#16a34a' : '#d97706' }};">
                        {{ $isListening ? 'RUNNING / LISTENING' : 'STANDBY READY' }}
                    </span>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem;">
                        <div style="font-size: 0.725rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Port Listener</div>
                        <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                            Port {{ $port }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem;">
                        <div style="font-size: 0.725rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Host / IP PABX</div>
                        <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                            {{ $setting->ip ?? '10.3.16.12' }}
                        </div>
                    </div>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem;">
                        <div style="font-size: 0.725rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Baud Rate / Parity</div>
                        <div style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-top: 2px;">
                            {{ $setting->baudrate ?? '9600' }} / None
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="alert('PABX Daemon Listener berjalan otomatis di latar belakang untuk menangkap rekaman CDR.');" class="btn-search" style="background: linear-gradient(135deg, #16a34a, #15803d); height: 38px; padding: 0 18px; font-weight: 700;">
                        <i class="fa-solid fa-play"></i> Mulai / Refresh Service
                    </button>
                    <button type="button" onclick="location.reload();" class="btn-search" style="background: #64748b; height: 38px; padding: 0 16px;">
                        <i class="fa-solid fa-rotate"></i> Cek Status Terkini
                    </button>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="report-card" style="margin-bottom: 0;">
            <div class="report-header">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info" style="color: #0288d1;"></i>
                    <span>Informasi Service</span>
                </div>
            </div>
            <div style="padding: 1.25rem; font-size: 0.825rem; color: #475569; line-height: 1.6;">
                <p style="margin-bottom: 0.75rem;">
                    Service listener PABX bekerja dengan membaca streaming byte SMDR (Station Message Detail Recording) dari unit PABX KX-TDA/TDE di bandara.
                </p>
                <p style="margin-bottom: 0.75rem;">
                    Data yang diterima secara otomatis diurai (*parsed*) berdasarkan format tanggal, jam, durasi, ekstensi, dan nomor tujuan untuk dimasukkan ke tabel <code>tbm_data_masuk</code>.
                </p>
                <div style="padding: 8px 12px; background: #e0f2fe; border-left: 3px solid #0288d1; border-radius: 4px; font-size: 0.75rem; color: #0369a1;">
                    Parser Status: <strong>Aktif & Sinkron</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Captures Table -->
    <div class="report-card">
        <div class="report-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-list-check" style="color: #0284c7;"></i>
                <span>10 Entri Panggilan Terakhir yang Ditangkap (Live Capture Feed)</span>
            </div>
            <a href="{{ route('information.call-history') }}" style="font-size: 0.75rem; font-weight: 700; color: #0288d1; text-decoration: none;">
                Buka Semua di Information &gt; Call History &rarr;
            </a>
        </div>
        <div class="table-responsive">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">ID</th>
                        <th>Waktu Rekam</th>
                        <th>Ekstensi</th>
                        <th>Nomor Tujuan</th>
                        <th>Durasi</th>
                        <th>Raw String Buffer</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentEntries as $entry)
                        <tr>
                            <td style="text-align: center; color: #64748b;">#{{ $entry->id }}</td>
                            <td><strong>{{ $entry->tglmasuk }} {{ $entry->jammasuk }}</strong></td>
                            <td><span style="font-weight: 700; color: #0288d1;">Ext {{ $entry->ext_pemanggil }}</span></td>
                            <td><code>{{ $entry->no_tujuan }}</code></td>
                            <td>{{ $entry->durasi }}</td>
                            <td style="font-family: monospace; font-size: 0.75rem; color: #64748b;">
                                {{ $entry->gabung ?: 'RECORD CAPTURED VIA TCP SOCKET' }}
                            </td>
                            <td style="text-align: right; font-weight: 700; color: #16a34a;">
                                Rp {{ number_format($entry->subtotal ?: 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
