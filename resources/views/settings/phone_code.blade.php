@extends('layouts.app')

@section('title', 'MASTER PHONE CODE / EXTENSION - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-address-book" style="color: #0284c7;"></i>
                MASTER PHONE CODE / EXTENSION
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
            <div class="table-toolbar">
                <div class="dt-buttons">
                    <button type="button" class="btn-dt" onclick="copyTableData('phoneCodeTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('phoneCodeTable', 'master_phone_code.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('phoneCodeTable', 'master_phone_code.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('phoneCodeTable', 'MASTER PHONE CODE')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('phoneCodeTable', 'MASTER PHONE CODE')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('phoneCodeTable', this.value)"></label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="setting-table" id="phoneCodeTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('phoneCodeTable', 0)" style="width: 70px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('phoneCodeTable', 1)">Ext Code <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('phoneCodeTable', 2)">Ext Name <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('phoneCodeTable', 3)">Division <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('phoneCodeTable', 4)">Department <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('phoneCodeTable', 5)">Position <span class="sort-icon">⇅</span></th>
                            <th style="width: 110px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $idx => $d)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0284c7;">{{ $d->extcode }}</td>
                                <td style="font-weight: 600;">{{ $d->extname }}</td>
                                <td>{{ $d->divisi ?: ($d->group->namadivisi ?? 'AP') }}</td>
                                <td>{{ $d->jabatan ?: ($d->group->namagroup ?? 'AP') }}</td>
                                <td>{{ $d->nik ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditPhoneModal({{ $d->id }}, '{{ $d->extcode }}', '{{ addslashes($d->extname) }}', '{{ $d->idgroup }}', '{{ addslashes($d->divisi) }}', '{{ addslashes($d->jabatan) }}', '{{ addslashes($d->nik) }}', '{{ addslashes($d->phonepassword) }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.phone-code.destroy', $d->id) }}', 'Ekstensi {{ $d->extcode }} ({{ addslashes($d->extname) }})')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 2.5rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                    Belum ada data Extension terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $departments->count() }} of {{ $departments->count() }} entries
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
                <div>Tambah Extension Baru</div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('settings.phone-code.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-phone"></i> Ext Code <span class="required-star">*</span></label>
                        <input type="number" name="extcode" class="form-control-modal font-mono" placeholder="Contoh: 100" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-key"></i> Phone Password</label>
                        <input type="text" name="phonepassword" class="form-control-modal font-mono" placeholder="Opsional">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-id-card"></i> Ext Name (Nama Ekstensi / PIC) <span class="required-star">*</span></label>
                    <input type="text" name="extname" class="form-control-modal" placeholder="Contoh: INFORMATION CENT" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-layer-group"></i> Department Group</label>
                    <select name="idgroup" id="createIdGroup" class="form-control-modal" onchange="autoFillGroup('createIdGroup', 'createDivisi', 'createJabatan')">
                        <option value="">-- Pilih Group Department --</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->idgroup }}" data-divisi="{{ $g->namadivisi }}" data-dept="{{ $g->namagroup }}">
                                {{ $g->groupcode }} - {{ $g->namagroup }} ({{ $g->namadivisi }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-sitemap"></i> Division</label>
                        <input type="text" name="divisi" id="createDivisi" class="form-control-modal" placeholder="Contoh: AP">
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-building-user"></i> Department</label>
                        <input type="text" name="jabatan" id="createJabatan" class="form-control-modal" placeholder="Contoh: AP">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-user-tag"></i> Position / NIK</label>
                    <input type="text" name="nik" class="form-control-modal" placeholder="Contoh: Supervisor / NIK">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Simpan Extension
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
                <div>Edit Data Extension</div>
            </div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-phone"></i> Ext Code <span class="required-star">*</span></label>
                        <input type="number" name="extcode" id="editExtCode" class="form-control-modal font-mono" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-key"></i> Phone Password</label>
                        <input type="text" name="phonepassword" id="editPhonePassword" class="form-control-modal font-mono">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-id-card"></i> Ext Name (Nama Ekstensi / PIC) <span class="required-star">*</span></label>
                    <input type="text" name="extname" id="editExtName" class="form-control-modal" required>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-layer-group"></i> Department Group</label>
                    <select name="idgroup" id="editIdGroup" class="form-control-modal" onchange="autoFillGroup('editIdGroup', 'editDivisi', 'editJabatan')">
                        <option value="">-- Pilih Group Department --</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->idgroup }}" data-divisi="{{ $g->namadivisi }}" data-dept="{{ $g->namagroup }}">
                                {{ $g->groupcode }} - {{ $g->namagroup }} ({{ $g->namadivisi }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-sitemap"></i> Division</label>
                        <input type="text" name="divisi" id="editDivisi" class="form-control-modal">
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-building-user"></i> Department</label>
                        <input type="text" name="jabatan" id="editJabatan" class="form-control-modal">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-user-tag"></i> Position / NIK</label>
                    <input type="text" name="nik" id="editNik" class="form-control-modal">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Update Extension
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
                <div>Import Data Extension</div>
            </div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Unggah file master phone code / extension (.csv atau .xlsx).
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

    function autoFillGroup(selectId, divisiId, jabatanId) {
        const select = document.getElementById(selectId);
        const selectedOption = select.options[select.selectedIndex];
        if (selectedOption && selectedOption.value) {
            const divisi = selectedOption.getAttribute('data-divisi');
            const dept = selectedOption.getAttribute('data-dept');
            if (divisi) document.getElementById(divisiId).value = divisi;
            if (dept) document.getElementById(jabatanId).value = dept;
        }
    }

    function openEditPhoneModal(id, extcode, extname, idgroup, divisi, jabatan, nik, phonepassword) {
        document.getElementById('editForm').action = "/settings/phone-code/" + id;
        document.getElementById('editExtCode').value = extcode;
        document.getElementById('editExtName').value = extname;
        document.getElementById('editIdGroup').value = idgroup || "";
        document.getElementById('editDivisi').value = divisi || "";
        document.getElementById('editJabatan').value = jabatan || "";
        document.getElementById('editNik').value = nik || "";
        document.getElementById('editPhonePassword').value = phonepassword || "";
        openModal('editModal');
    }

    function confirmDelete(url, label) {
        if (confirm("Apakah Anda yakin ingin menghapus data " + label + "?")) {
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
