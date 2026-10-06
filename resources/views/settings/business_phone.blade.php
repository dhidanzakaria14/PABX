@extends('layouts.app')

@section('title', 'BUSINESS PHONE NO - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <!-- Header Card -->
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">BUSINESS PHONE NO</div>
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
                    <button type="button" class="btn-dt" onclick="copyTableData('bizTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('bizTable', 'business_phone.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('bizTable', 'business_phone.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('bizTable', 'BUSINESS PHONE NO')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('bizTable', 'BUSINESS PHONE NO')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('bizTable', this.value)"></label>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="setting-table" id="bizTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('bizTable', 0)" style="width: 80px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('bizTable', 1)">Company Name <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('bizTable', 2)">Phone No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('bizTable', 3)">Address <span class="sort-icon">⇅</span></th>
                            <th style="width: 120px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($businesses as $idx => $b)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-weight: 600;">{{ $b->nama }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace;">{{ $b->notelp }}</td>
                                <td>{{ $b->alamat ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditBizModal({{ $b->id }}, '{{ addslashes($b->nama) }}', '{{ $b->notelp }}', '{{ addslashes($b->alamat) }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.business-phone.destroy', $b->id) }}', '{{ $b->nama }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 2rem; color: #94a3b8;">
                                    Belum ada data Business Phone terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $businesses->count() }} of {{ $businesses->count() }} entries
                </div>
                <div class="table-pagination">
                    <button class="page-btn disabled">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn disabled">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: New Data -->
<div id="createModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div>Tambah Business Phone Baru</div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('settings.business-phone.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label>Company Name (Nama Perusahaan / Kontak) *</label>
                    <input type="text" name="nama" class="form-control-modal" placeholder="Contoh: Billing Engineer" required>
                </div>
                <div class="form-group-modal">
                    <label>Phone No (Nomor Telepon) *</label>
                    <input type="text" name="notelp" class="form-control-modal" placeholder="Contoh: 081298683751" required>
                </div>
                <div class="form-group-modal">
                    <label>Address (Alamat / Lokasi)</label>
                    <input type="text" name="alamat" class="form-control-modal" placeholder="Contoh: Jakarta">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn-modal-cancel">Batal</button>
                <button type="submit" class="btn-modal-save">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Data -->
<div id="editModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div>Edit Business Phone</div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label>Company Name (Nama Perusahaan / Kontak) *</label>
                    <input type="text" name="nama" id="editNama" class="form-control-modal" required>
                </div>
                <div class="form-group-modal">
                    <label>Phone No (Nomor Telepon) *</label>
                    <input type="text" name="notelp" id="editNotelp" class="form-control-modal" required>
                </div>
                <div class="form-group-modal">
                    <label>Address (Alamat / Lokasi)</label>
                    <input type="text" name="alamat" id="editAlamat" class="form-control-modal">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn-modal-cancel">Batal</button>
                <button type="submit" class="btn-modal-save">Update Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Import Data -->
<div id="importModal" class="modal-backdrop" style="display: none;">
    <div class="modal-card">
        <div class="modal-card-header">
            <div>Import Data Business Phone</div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1rem;">
                Unggah file data Business Phone (.csv atau .xlsx).
            </p>
            <div style="border: 2px dashed #cbd5e1; border-radius: 6px; padding: 2rem; text-align: center; background: #f8fafc;">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2.5rem; color: #0288d1; margin-bottom: 0.5rem;"></i>
                <div style="font-size: 0.85rem; font-weight: 600; color: #334155;">Pilih file atau drag & drop file ke sini</div>
                <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 4px;">Format didukung: CSV, XLS, XLSX</div>
                <input type="file" style="margin-top: 1rem; font-size: 0.8rem;">
            </div>
        </div>
        <div class="modal-card-footer">
            <button type="button" onclick="closeModal('importModal')" class="btn-modal-cancel">Tutup</button>
            <button type="button" onclick="alert('File berhasil dipilih untuk diproses.'); closeModal('importModal');" class="btn-modal-save">Upload & Import</button>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
    .setting-container { max-width: 1400px; margin: 0 auto; }
    .card-setting { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
    .card-setting-header { padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; gap: 0.75rem; }
    .card-setting-title { font-size: 1rem; font-weight: 800; color: #1e293b; letter-spacing: 0.03em; text-transform: uppercase; }
    .card-setting-actions { display: flex; gap: 8px; }
    .btn-import-blue { background: #0288d1; color: white; border: none; padding: 6px 14px; border-radius: 3px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-import-blue:hover { background: #0277bd; }
    .btn-new-green { background: #2e7d32; color: white; border: none; padding: 6px 14px; border-radius: 3px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-new-green:hover { background: #1b5e20; }
    .card-setting-body { padding: 1.25rem; }
    .table-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem; }
    .dt-buttons { display: flex; gap: 4px; }
    .btn-dt { background: #475569; color: white; border: none; padding: 5px 12px; border-radius: 3px; font-size: 0.775rem; font-weight: 600; cursor: pointer; }
    .btn-dt:hover { background: #334155; }
    .dt-search { font-size: 0.8rem; color: #475569; }
    .dt-search-box { border: 1px solid #cbd5e1; border-radius: 3px; padding: 4px 8px; font-size: 0.8rem; outline: none; margin-left: 6px; height: 28px; }
    .dt-search-box:focus { border-color: #0288d1; }
    .setting-table { width: 100%; border-collapse: collapse; font-size: 0.825rem; text-align: left; }
    .setting-table th { background: #ffffff; color: #475569; font-weight: 700; padding: 10px 12px; border-bottom: 2px solid #cbd5e1; border-top: 1px solid #e2e8f0; cursor: pointer; user-select: none; }
    .setting-table th .sort-icon { color: #94a3b8; font-size: 0.75rem; margin-left: 4px; }
    .setting-table td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; color: #334155; }
    .setting-table tbody tr:hover { background: #f8fafc; }
    .action-btn-group { display: inline-flex; align-items: center; gap: 12px; }
    .btn-action-wrench { background: none; border: none; color: #0288d1; font-size: 0.95rem; cursor: pointer; padding: 4px; transition: transform 0.15s; }
    .btn-action-wrench:hover { color: #01579b; transform: scale(1.15); }
    .btn-action-trash { background: none; border: none; color: #0288d1; font-size: 0.95rem; cursor: pointer; padding: 4px; transition: transform 0.15s; }
    .btn-action-trash:hover { color: #d32f2f; transform: scale(1.15); }
    .table-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 1rem; padding-top: 0.5rem; font-size: 0.8rem; color: #64748b; flex-wrap: wrap; gap: 0.75rem; }
    .table-pagination { display: flex; gap: 2px; }
    .page-btn { border: 1px solid #cbd5e1; background: white; padding: 4px 10px; font-size: 0.8rem; cursor: pointer; color: #334155; }
    .page-btn.active { background: #0288d1; color: white; border-color: #0288d1; font-weight: 700; }
    .page-btn.disabled { color: #94a3b8; cursor: not-allowed; }
    .modal-backdrop { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); z-index: 1200; display: flex; align-items: center; justify-content: center; padding: 1rem; }
    .modal-card { background: white; border-radius: 6px; width: 100%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; }
    .modal-card-header { padding: 1rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-weight: 700; font-size: 0.95rem; color: #1e293b; display: flex; justify-content: space-between; align-items: center; }
    .modal-close { background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer; }
    .modal-close:hover { color: #0f172a; }
    .modal-card-body { padding: 1.25rem; }
    .form-group-modal { margin-bottom: 1rem; }
    .form-group-modal label { display: block; font-size: 0.775rem; font-weight: 700; color: #475569; margin-bottom: 4px; }
    .form-control-modal { width: 100%; height: 36px; padding: 6px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.85rem; outline: none; }
    .form-control-modal:focus { border-color: #0288d1; box-shadow: 0 0 0 1px #0288d1; }
    .modal-card-footer { padding: 0.85rem 1.25rem; background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 8px; }
    .btn-modal-cancel { background: #e2e8f0; color: #475569; border: none; padding: 6px 14px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
    .btn-modal-save { background: #2e7d32; color: white; border: none; padding: 6px 14px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; }
</style>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function openEditBizModal(id, nama, notelp, alamat) {
        document.getElementById('editForm').action = "/settings/business-phone/" + id;
        document.getElementById('editNama').value = nama;
        document.getElementById('editNotelp').value = notelp;
        document.getElementById('editAlamat').value = alamat;
        openModal('editModal');
    }
    function confirmDelete(url, label) {
        if (confirm("Apakah Anda yakin ingin menghapus " + label + "?")) {
            const form = document.getElementById('deleteForm');
            form.action = url;
            form.submit();
        }
    }
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
            if (!isNaN(aNum) && !isNaN(bNum)) { return asc ? aNum - bNum : bNum - aNum; }
            return asc ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });
        table.setAttribute('data-sort-asc', asc ? 'true' : 'false');
        rows.forEach(r => tbody.appendChild(r));
    }
    function copyTableData(tableId) {
        const table = document.getElementById(tableId);
        let text = "";
        for (let row of table.rows) {
            let rowData = [];
            for (let i = 0; i < row.cells.length - 1; i++) { rowData.push(row.cells[i].innerText.trim()); }
            text += rowData.join("\t") + "\n";
        }
        navigator.clipboard.writeText(text).then(() => { alert("Data tabel berhasil disalin ke clipboard!"); });
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
        for (let row of table.rows) { row.deleteCell(row.cells.length - 1); }
        const win = window.open('', '', 'height=700,width=900');
        win.document.write('<html><head><title>' + title + '</title>');
        win.document.write('<style>table { width: 100%; border-collapse: collapse; font-family: sans-serif; font-size: 12px; } th, td { border: 1px solid #ccc; padding: 8px; text-align: left; } th { background: #f2f2f2; }</style>');
        win.document.write('</head><body><h2>' + title + '</h2>' + table.outerHTML + '</body></html>');
        win.document.close();
        win.print();
    }
</script>
@endsection
