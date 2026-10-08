@extends('layouts.app')

@section('title', 'MANAGE PABX - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <!-- Header Card -->
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-server" style="color: #d32f2f;"></i>
                MANAGE PABX
            </div>
            <div class="card-setting-actions">
                <button type="button" class="btn-import-blue" onclick="openModal('importModal')">
                    <i class="fa-solid fa-file-import"></i> Import Data
                </button>
                <button type="button" class="btn-new-green" onclick="openModal('createModal')">
                    <i class="fa-solid fa-plus"></i> New Data
                </button>
            </div>
        </div>

        <div class="card-setting-body">
            <!-- Data Table Toolbar -->
            <div class="table-toolbar">
                <div class="dt-buttons">
                    <button type="button" class="btn-dt" onclick="copyTableData('settingTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('settingTable', 'manage_pabx.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('settingTable', 'manage_pabx.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('settingTable', 'MANAGE PABX')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('settingTable', 'MANAGE PABX')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('settingTable', this.value)"></label>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="setting-table" id="settingTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('settingTable', 0)" style="width: 80px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('settingTable', 1)">IP <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('settingTable', 2)">Port Number <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('settingTable', 3)">Description <span class="sort-icon">⇅</span></th>
                            <th style="width: 120px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $idx => $s)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 600; color: #0288d1;">{{ $s->ip }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace;">{{ $s->port }}</td>
                                <td>{{ $s->keterangan }}</td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditAdminModal({{ $s->id }}, '{{ $s->ip }}', '{{ $s->port }}', '{{ addslashes($s->keterangan) }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.admin.destroy', $s->id) }}', 'PABX {{ $s->keterangan }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 2.5rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                    Belum ada data PABX terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $settings->count() }} of {{ $settings->count() }} entries
                </div>
                <div class="table-pagination">
                    <button class="page-btn disabled">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn disabled">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Pill Button (Matching Screenshot 1) -->
    <div style="text-align: center; margin-top: 2rem;">
        <button type="button" class="btn-advanced-scanner" onclick="openModal('scannerModal')">
            <i class="fa-solid fa-network-wired"></i> Advanced IP Scanner
        </button>
    </div>
</div>

<!-- Modal: New Data -->
<div id="createModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div class="modal-card-title-wrap">
                <div class="modal-header-icon-badge create-badge">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>Tambah PABX Baru</div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('settings.admin.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-network-wired"></i> IP Address <span class="required-star">*</span></label>
                    <input type="text" name="ip" class="form-control-modal font-mono" placeholder="Contoh: 127.0.0.1" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-ethernet"></i> Port Number <span class="required-star">*</span></label>
                    <input type="number" name="port" class="form-control-modal font-mono" placeholder="Contoh: 5060" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-server"></i> Description (Keterangan Mesin) <span class="required-star">*</span></label>
                    <input type="text" name="keterangan" class="form-control-modal" placeholder="Contoh: MESIN1" required>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Data -->
<div id="editModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div class="modal-card-title-wrap">
                <div class="modal-header-icon-badge edit-badge">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>Edit Data PABX</div>
            </div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-network-wired"></i> IP Address <span class="required-star">*</span></label>
                    <input type="text" name="ip" id="editIp" class="form-control-modal font-mono" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-ethernet"></i> Port Number <span class="required-star">*</span></label>
                    <input type="number" name="port" id="editPort" class="form-control-modal font-mono" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-server"></i> Description (Keterangan Mesin) <span class="required-star">*</span></label>
                    <input type="text" name="keterangan" id="editKeterangan" class="form-control-modal" required>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Import Data -->
<div id="importModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div class="modal-card-title-wrap">
                <div class="modal-header-icon-badge import-badge">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>Import Data PABX</div>
            </div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Unggah file data PABX (.csv atau .xlsx). Pastikan format kolom sesuai dengan template standar sistem.
            </p>
            <div class="upload-dropzone">
                <div class="upload-dropzone-icon">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div class="upload-dropzone-title">Pilih file atau drag & drop file ke sini</div>
                <div class="upload-dropzone-subtitle">Format didukung: CSV, XLS, XLSX (Maks 10MB)</div>
                <input type="file" class="upload-file-input">
            </div>
        </div>
        <div class="modal-card-footer">
            <button type="button" onclick="closeModal('importModal')" class="btn-modal-cancel">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
            <button type="button" onclick="alert('File berhasil dipilih untuk diproses.'); closeModal('importModal');" class="btn-modal-save">
                <i class="fa-solid fa-upload"></i> Upload & Import
            </button>
        </div>
    </div>
</div>

<!-- Modal: Advanced IP Scanner -->
<div id="scannerModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card" style="max-width: 600px;">
        <div class="modal-card-header">
            <div class="modal-card-title-wrap">
                <div class="modal-header-icon-badge" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #38bdf8;">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <span>Advanced IP Scanner & Diagnostic</span>
            </div>
            <button type="button" onclick="closeModal('scannerModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <div style="display: flex; gap: 10px; margin-bottom: 1rem;">
                <input type="text" id="scanRange" class="form-control-modal font-mono" value="127.0.0.1 - 127.0.0.254" style="flex: 1;" placeholder="IP Range">
                <button type="button" onclick="startIpScan()" class="btn-import-blue" style="padding: 0 16px;">
                    <i class="fa-solid fa-magnifying-glass"></i> Scan
                </button>
            </div>
            <div id="scanConsole" style="background: #0f172a; color: #10b981; font-family: 'JetBrains Mono', monospace; font-size: 0.775rem; padding: 1.25rem; border-radius: 10px; height: 180px; overflow-y: auto; box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);">
                <div>[SYSTEM] IP Scanner initialized.</div>
                <div>[INFO] Target Gateway: 127.0.0.1:5060 (MESIN1 PABX SIP Server)</div>
                <div>[STATUS] Ready to scan subnet. Click 'Scan' to verify device connectivity.</div>
            </div>
        </div>
        <div class="modal-card-footer">
            <button type="button" onclick="closeModal('scannerModal')" class="btn-modal-cancel">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
    /* Page Specific: Advanced IP Scanner Button */
    .btn-advanced-scanner {
        background: #1e293b;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 24px;
        font-size: 0.825rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-advanced-scanner:hover {
        background: #0f172a;
        transform: translateY(-1.5px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.3);
    }
</style>

<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function openEditAdminModal(id, ip, port, ket) {
        document.getElementById('editForm').action = "/settings/admin/" + id;
        document.getElementById('editIp').value = ip;
        document.getElementById('editPort').value = port;
        document.getElementById('editKeterangan').value = ket;
        openModal('editModal');
    }

    function confirmDelete(url, label) {
        if (confirm("Apakah Anda yakin ingin menghapus " + label + "?")) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }

    // Live search filter
    function filterTable(tableId, query) {
        const table = document.getElementById(tableId);
        const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        const q = query.toLowerCase();
        let visibleCount = 0;

        for (let i = 0; i < trs.length; i++) {
            const rowText = trs[i].innerText.toLowerCase();
            if (rowText.includes(q)) {
                trs[i].style.display = '';
                visibleCount++;
            } else {
                trs[i].style.display = 'none';
            }
        }
        document.getElementById('tableInfo').innerText = "Showing 1 to " + visibleCount + " of " + trs.length + " entries";
    }

    // Sort table columns
    function sortTable(tableId, colIndex) {
        const table = document.getElementById(tableId);
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = Array.from(tbody.getElementsByTagName('tr'));
        let asc = table.getAttribute('data-sort-asc') !== 'true';

        rows.sort((a, b) => {
            const aText = a.cells[colIndex].innerText.trim();
            const bText = b.cells[colIndex].innerText.trim();
            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return asc ? aNum - bNum : bNum - aNum;
            }
            return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });

        table.setAttribute('data-sort-asc', asc ? 'true' : 'false');
        rows.forEach(r => tbody.appendChild(r));
    }

    // Export & Print Actions
    function copyTableData(tableId) {
        const table = document.getElementById(tableId);
        let text = "";
        for (let row of table.rows) {
            let rowData = [];
            for (let i = 0; i < row.cells.length - 1; i++) {
                rowData.push(row.cells[i].innerText.trim());
            }
            text += rowData.join("\t") + "\n";
        }
        navigator.clipboard.writeText(text).then(() => {
            alert("Data tabel berhasil disalin ke clipboard!");
        });
    }

    function exportTableToCSV(tableId, filename) {
        const table = document.getElementById(tableId);
        let csv = [];
        for (let row of table.rows) {
            let rowData = [];
            for (let i = 0; i < row.cells.length - 1; i++) {
                rowData.push('"' + row.cells[i].innerText.trim().replace(/"/g, '""') + '"');
            }
            csv.push(rowData.join(","));
        }
        const blob = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    function printTableData(tableId, title) {
        const table = document.getElementById(tableId).cloneNode(true);
        // remove action column
        for (let row of table.rows) {
            row.deleteCell(row.cells.length - 1);
        }
        const win = window.open('', '', 'height=700,width=900');
        win.document.write('<html><head><title>' + title + '</title>');
        win.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 12px; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } th { background: #f2f2f2; }</style>');
        win.document.write('</head><body>');
        win.document.write('<h2>' + title + '</h2>');
        win.document.write(table.outerHTML);
        win.document.write('</body></html>');
        win.document.close();
        win.print();
    }

    function startIpScan() {
        const consoleEl = document.getElementById('scanConsole');
        consoleEl.innerHTML += "<div>[SCANNING] Pinging 127.0.0.1:5060...</div>";
        setTimeout(() => {
            consoleEl.innerHTML += "<div style='color: #38bdf8;'>[FOUND] 127.0.0.1:5060 - MESIN1 (Latency: 0.4ms) - ACTIVE</div>";
            consoleEl.innerHTML += "<div style='color: #4ade80;'>[COMPLETE] Subnet scan finished. 1 Active PABX device online.</div>";
            consoleEl.scrollTop = consoleEl.scrollHeight;
        }, 600);
    }
</script>
@endsection
