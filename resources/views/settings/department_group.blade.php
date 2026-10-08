@extends('layouts.app')

@section('title', 'DIVISION - DEPARTMENT - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <!-- Header Card -->
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-sitemap" style="color: #d32f2f;"></i>
                DIVISION - DEPARTMENT
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
                    <button type="button" class="btn-dt" onclick="copyTableData('deptGroupTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('deptGroupTable', 'department_group.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('deptGroupTable', 'department_group.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('deptGroupTable', 'DIVISION - DEPARTMENT')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('deptGroupTable', 'DIVISION - DEPARTMENT')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('deptGroupTable', this.value)"></label>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="setting-table" id="deptGroupTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('deptGroupTable', 0)" style="width: 80px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('deptGroupTable', 1)">Group Code <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('deptGroupTable', 2)">Division Name <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('deptGroupTable', 3)">Department Group Name <span class="sort-icon">⇅</span></th>
                            <th style="width: 120px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $idx => $g)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-weight: 700; color: #0288d1; font-family: 'JetBrains Mono', monospace;">{{ $g->groupcode }}</td>
                                <td>{{ $g->namadivisi }}</td>
                                <td>{{ $g->namagroup }}</td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditGroupModal({{ $g->idgroup }}, '{{ addslashes($g->groupcode) }}', '{{ addslashes($g->namadivisi) }}', '{{ addslashes($g->namagroup) }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.department-group.destroy', $g->idgroup) }}', '{{ $g->groupcode }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding: 2.5rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                    Belum ada data Division - Department terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $groups->count() }} of {{ $groups->count() }} entries
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
            <div class="modal-card-title-wrap">
                <div class="modal-header-icon-badge create-badge">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div>Tambah Department Group Baru</div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('settings.department-group.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-barcode"></i> Group Code <span class="required-star">*</span></label>
                    <input type="text" name="groupcode" class="form-control-modal font-mono" placeholder="Contoh: SURABAYA" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-sitemap"></i> Division Name <span class="required-star">*</span></label>
                    <input type="text" name="namadivisi" class="form-control-modal" placeholder="Contoh: AP" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-users-gear"></i> Department Group Name <span class="required-star">*</span></label>
                    <input type="text" name="namagroup" class="form-control-modal" placeholder="Contoh: AP" required>
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
                <div>Edit Department Group</div>
            </div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-barcode"></i> Group Code <span class="required-star">*</span></label>
                    <input type="text" name="groupcode" id="editGroupCode" class="form-control-modal font-mono" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-sitemap"></i> Division Name <span class="required-star">*</span></label>
                    <input type="text" name="namadivisi" id="editNamaDivisi" class="form-control-modal" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-users-gear"></i> Department Group Name <span class="required-star">*</span></label>
                    <input type="text" name="namagroup" id="editNamaGroup" class="form-control-modal" required>
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
                <div>Import Data Department Group</div>
            </div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Unggah file data Department Group (.csv atau .xlsx).
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

<!-- Form Delete Hidden -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    function openEditGroupModal(id, code, divisi, group) {
        document.getElementById('editForm').action = "/settings/department-group/" + id;
        document.getElementById('editGroupCode').value = code;
        document.getElementById('editNamaDivisi').value = divisi;
        document.getElementById('editNamaGroup').value = group;
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
