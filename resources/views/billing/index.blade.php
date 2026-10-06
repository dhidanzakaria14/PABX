@extends('layouts.app')

@section('title', 'ANGKASA PURA - PABX Billing & Call Logs')

@section('content')
<!-- KPI Summary Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #d32f2f;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Tagihan Telepon</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #d32f2f; margin-top: 4px;">
            Rp {{ number_format($totalCost, 0, ',', '.') }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Termasuk PPN 11%</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #0288d1;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Panggilan (CDR)</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #0288d1; margin-top: 4px;">
            {{ number_format($totalCalls) }} Calls
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Data Rekaman Masuk</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #2e7d32;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Durasi Bicara</div>
        @php
            $hrs = floor($totalDurationSec / 3600);
            $mins = floor(($totalDurationSec % 3600) / 60);
            $secs = $totalDurationSec % 60;
        @endphp
        <div style="font-size: 1.5rem; font-weight: 800; color: #2e7d32; margin-top: 4px;">
            {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">{{ round($totalDurationSec / 60, 1) }} Menit</div>
    </div>

    <div style="background: white; border: 1px solid #cbd5e1; border-radius: 4px; padding: 1.15rem; border-left: 4px solid #f57c00;">
        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Ekstensi / Phone-ID</div>
        <div style="font-size: 1.5rem; font-weight: 800; color: #f57c00; margin-top: 4px;">
            {{ $departments->count() }} Ext
        </div>
        <div style="font-size: 0.725rem; color: #94a3b8; margin-top: 2px;">Terdaftar di PABX</div>
    </div>
</div>

<!-- Main Table Card -->
<div class="report-card">
    <div class="report-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <span>INCOMING CALL LOGS & BILLING CDR (tbm_data_masuk)</span>
            @if(isset($dateMin) && isset($dateMax))
                <div style="font-size: 0.725rem; font-weight: normal; color: #64748b; margin-top: 2px;">
                    <i class="fa-solid fa-database text-success"></i> Data di Database: <strong>{{ number_format($totalCalls) }}</strong> rekaman ({{ \Carbon\Carbon::parse($dateMin)->format('d/m/Y') }} &ndash; {{ \Carbon\Carbon::parse($dateMax)->format('d/m/Y') }})
                </div>
            @endif
        </div>
        <button type="button" onclick="openSimModal()" class="btn-search" style="background: #2e7d32; font-size: 0.75rem; padding: 0 10px;">
            <i class="fa-solid fa-calculator"></i> Simulasi Billing
        </button>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('home') }}" method="GET" class="filter-section">
        <input type="hidden" name="searched" value="1">
        <div class="filter-group">
            <label class="filter-label">From Date</label>
            <input type="date" name="start_date" class="input-text" value="{{ request('start_date') }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">To Date</label>
            <input type="date" name="end_date" class="input-text" value="{{ request('end_date') }}">
        </div>

        <div class="filter-group">
            <label class="filter-label">Phone ID / Extension</label>
            <select name="department_id" class="select-box">
                <option value="">All Extensions</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->extcode }} - {{ $dept->extname }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Destination Zone</label>
            <select name="zone_id" class="select-box">
                <option value="">All Zones</option>
                @foreach($zones as $z)
                    <option value="{{ $z->idzone }}" {{ request('zone_id') == $z->idzone ? 'selected' : '' }}>
                        {{ $z->namazone }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Search Dialed / CDR</label>
            <input type="text" name="search" class="input-text" placeholder="No Tujuan / Ext..." value="{{ request('search') }}">
        </div>

        <button type="submit" class="btn-search">
            <i class="fa-solid fa-magnifying-glass"></i> Search Data
        </button>

        <a href="{{ route('home') }}" class="btn-export" style="background: #64748b;" title="Reset Filter">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>

    <!-- Quick Presets -->
    <div style="padding: 0.5rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; font-size: 0.775rem;">
        <span style="color: #64748b; font-weight: 600;">Filter Cepat:</span>
        <a href="{{ route('home') }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ !request()->has('start_date') && !request()->has('search') ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Semua Data</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2026-08-01', 'end_date' => '2026-08-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2026-08-01' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Bulan Terakhir (Agu 2026)</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2026-01-01', 'end_date' => '2026-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2026-01-01' && request('end_date') == '2026-12-31' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2026</a>
        <a href="{{ route('home', ['searched' => 1, 'start_date' => '2025-01-01', 'end_date' => '2025-12-31']) }}" style="text-decoration: none; padding: 2px 8px; border-radius: 3px; background: {{ request('start_date') == '2025-01-01' ? '#0288d1; color: white;' : '#e2e8f0; color: #334155;' }}">Tahun 2025</a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="ap-table">
            <thead>
                <tr>
                    <th style="width: 45px;">No</th>
                    <th>CDR No</th>
                    <th>Phone ID (Ext)</th>
                    <th>User / Department</th>
                    <th>Dialed (Destination)</th>
                    <th>Area Name / Zone</th>
                    <th>Date & Time</th>
                    <th class="text-right">Duration</th>
                    <th class="text-right">Tarif Rate</th>
                    <th class="text-right">Cost (Rp)</th>
                    <th>Trunk</th>
                </tr>
            </thead>
            <tbody>
                @php $no = $records->firstItem() ?: 1; @endphp
                @forelse($records as $rec)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong style="color: #0288d1; font-family: 'JetBrains Mono', monospace;">{{ $rec->nourut ?: '-' }}</strong></td>
                        <td><span style="font-weight: 700; font-family: 'JetBrains Mono', monospace;">{{ $rec->ext_pemanggil }}</span></td>
                        <td>{{ $rec->department ? $rec->department->extname : ($rec->namadepartment ?: '-') }}</td>
                        <td><strong style="font-family: 'JetBrains Mono', monospace;">{{ $rec->no_tujuan }}</strong></td>
                        <td>
                            <div>{{ $rec->ketarea ?: ($rec->zona ?: 'Lokal') }}</div>
                            <span style="font-size: 0.7rem; color: #64748b;">{{ $rec->zona }}</span>
                        </td>
                        <td>{{ $rec->tglmasuk }} {{ $rec->jammasuk }}</td>
                        <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">{{ $rec->durasi }}</td>
                        <td class="text-right" style="font-size: 0.775rem;">{{ $rec->formularate ?: '-' }}</td>
                        <td class="text-right" style="font-weight: 700; color: #2e7d32;">
                            {{ number_format($rec->subtotal, 0, ',', '.') }}
                        </td>
                        <td>{{ $rec->noline }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center" style="padding: 2.5rem 1rem; color: #64748b;">
                            <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.5rem;"><i class="fa-solid fa-inbox"></i></div>
                            <strong style="color: #334155; font-size: 0.95rem;">Tidak ada rekaman panggilan yang cocok dengan kriteria pencarian.</strong>
                            @if(request()->filled('start_date') && isset($dateMax) && request('start_date') > $dateMax)
                                <div style="font-size: 0.825rem; color: #e11d48; margin-top: 6px;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Tanggal pencarian Anda ({{ request('start_date') }}) melebihi tanggal data terakhir di database ({{ \Carbon\Carbon::parse($dateMax)->format('d-m-Y') }}).
                                </div>
                            @endif
                            <div style="margin-top: 1rem;">
                                <a href="{{ route('home') }}" class="btn-search" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px; font-size: 0.8rem; padding: 6px 14px; background: #0288d1;">
                                    <i class="fa-solid fa-rotate-left"></i> Tampilkan Semua Data
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">TOTAL</td>
                    <td class="text-right" style="font-family: 'JetBrains Mono', monospace;">
                        {{ sprintf('%02d:%02d:%02d', $hrs, $mins, $secs) }}
                    </td>
                    <td></td>
                    <td class="text-right" style="color: #2e7d32;">
                        Rp {{ number_format($totalCost, 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($records->hasPages())
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid #e2e8f0; background: #ffffff;">
            {{ $records->onEachSide(1)->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>

<!-- Modal Simulasi Hitung Billing -->
<div id="simModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 4px; width: 90%; max-width: 500px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">
            <h3 style="font-size: 1rem; color: #d32f2f; text-transform: uppercase;">Simulasi Kalkulator Billing PABX</h3>
            <button type="button" onclick="closeSimModal()" style="background: none; border: none; font-size: 1.25rem; cursor: pointer;">&times;</button>
        </div>
        
        <div class="filter-group" style="margin-bottom: 0.85rem;">
            <label class="filter-label">Pilih Phone-ID / Ekstensi</label>
            <select id="sim_ext" class="select-box" style="width: 100%;">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">Ext {{ $dept->extcode }} - {{ $dept->extname }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group" style="margin-bottom: 0.85rem;">
            <label class="filter-label">Nomor Telepon Tujuan</label>
            <input type="text" id="sim_dest" class="input-text" style="width: 100%;" value="08129876543">
        </div>

        <div class="filter-group" style="margin-bottom: 1rem;">
            <label class="filter-label">Durasi Bicara (Detik)</label>
            <input type="number" id="sim_dur" class="input-text" style="width: 100%;" value="180">
        </div>

        <button type="button" onclick="runSim()" class="btn-search" style="width: 100%; justify-content: center; height: 36px;">
            <i class="fa-solid fa-calculator"></i> Hitung Tagihan
        </button>

        <div id="sim_res" style="display: none; margin-top: 1rem; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.85rem; font-size: 0.85rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Zona / Operator:</span> <strong id="res_z">-</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Formula Rate:</span> <span id="res_f">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                <span>Biaya Pokok:</span> <span id="res_b">-</span>
            </div>
            <div style="display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; padding-top: 6px; font-weight: 700; color: #2e7d32;">
                <span>TOTAL CHARGE:</span> <span id="res_t">-</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openSimModal() {
        document.getElementById('simModal').style.display = 'flex';
    }
    function closeSimModal() {
        document.getElementById('simModal').style.display = 'none';
    }
    function runSim() {
        const ext = document.getElementById('sim_ext').value;
        const dest = document.getElementById('sim_dest').value;
        const dur = document.getElementById('sim_dur').value;
        fetch('{{ route('billing.simulate') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ext_id: ext, destination: dest, duration_sec: dur })
        })
        .then(r => r.json())
        .then(d => {
            if(d.success) {
                document.getElementById('res_z').textContent = d.zone + ' (' + d.prefix_ket + ')';
                document.getElementById('res_f').textContent = d.formula;
                document.getElementById('res_b').textContent = 'Rp ' + Number(d.biaya).toLocaleString('id-ID');
                document.getElementById('res_t').textContent = d.subtotal_formatted;
                document.getElementById('sim_res').style.display = 'block';
            }
        });
    }
</script>
@endsection
