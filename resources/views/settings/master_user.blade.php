@extends('layouts.app')

@section('title', 'MASTER USER - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-users" style="color: #0284c7;"></i>
                MASTER USER
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
                    <button type="button" class="btn-dt" onclick="copyTableData('userTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('userTable', 'master_user.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('userTable', 'master_user.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('userTable', 'MASTER USER')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('userTable', 'MASTER USER')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('userTable', this.value)"></label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="setting-table" id="userTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('userTable', 0)" style="width: 70px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('userTable', 1)">Username <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('userTable', 2)">Nama Lengkap <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('userTable', 3)">Level User <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('userTable', 4)">Telepon <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('userTable', 5)">Status <span class="sort-icon">⇅</span></th>
                            <th style="width: 110px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $idx => $u)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0288d1;">{{ $u->username }}</td>
                                <td style="font-weight: 600;">{{ $u->nama }}</td>
                                <td>
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">
                                        {{ $u->leveluser ?: ($u->level->level ?? 'User') }}
                                    </span>
                                </td>
                                <td>{{ $u->telp ?: '-' }}</td>
                                <td>
                                    @if($u->blokir === 'N')
                                        <span style="color: #2e7d32; font-weight: 600;"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span style="color: #d32f2f; font-weight: 600;"><i class="fa-solid fa-circle-xmark"></i> Terblokir</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditUserModal({{ $u->iduser }}, '{{ addslashes($u->username) }}', '{{ addslashes($u->nama) }}', '{{ $u->idlevel }}', '{{ addslashes($u->telp) }}', '{{ $u->blokir }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.user.destroy', $u->iduser) }}', 'User {{ addslashes($u->username) }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 2.5rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                    Belum ada data User terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $users->count() }} of {{ $users->count() }} entries
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
                <div>Tambah Master User Baru</div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('settings.user.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-user"></i> Username <span class="required-star">*</span></label>
                        <input type="text" name="username" class="form-control-modal font-mono" placeholder="Contoh: user123" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-id-card"></i> Nama Lengkap <span class="required-star">*</span></label>
                        <input type="text" name="nama" class="form-control-modal" placeholder="Contoh: Budi Santoso" required>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-lock"></i> Password <span class="required-star">*</span></label>
                    <input type="password" name="password" class="form-control-modal" placeholder="Minimal 4 karakter" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-shield-halved"></i> Level User <span class="required-star">*</span></label>
                        <select name="idlevel" class="form-control-modal" required>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->level }}</option>
                            @endforeach
                            @if($levels->isEmpty())
                                <option value="1">Administrator</option>
                                <option value="2">User</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-toggle-on"></i> Status User <span class="required-star">*</span></label>
                        <select name="blokir" class="form-control-modal">
                            <option value="N">Aktif</option>
                            <option value="Y">Terblokir</option>
                        </select>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-phone"></i> Nomor Telepon</label>
                    <input type="text" name="telp" class="form-control-modal font-mono" placeholder="Contoh: 08112345678">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Simpan User
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
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>Edit Data Master User</div>
            </div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-user"></i> Username <span class="required-star">*</span></label>
                        <input type="text" name="username" id="editUsername" class="form-control-modal font-mono" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-id-card"></i> Nama Lengkap <span class="required-star">*</span></label>
                        <input type="text" name="nama" id="editNama" class="form-control-modal" required>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-lock"></i> Password <span style="font-size: 0.75rem; color: #64748b; font-weight: normal;">(Kosongkan bila tidak diubah)</span></label>
                    <input type="password" name="password" id="editPassword" class="form-control-modal" placeholder="Isi untuk mengganti password lama">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-shield-halved"></i> Level User <span class="required-star">*</span></label>
                        <select name="idlevel" id="editIdLevel" class="form-control-modal" required>
                            @foreach($levels as $lvl)
                                <option value="{{ $lvl->id }}">{{ $lvl->level }}</option>
                            @endforeach
                            @if($levels->isEmpty())
                                <option value="1">Administrator</option>
                                <option value="2">User</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-toggle-on"></i> Status User <span class="required-star">*</span></label>
                        <select name="blokir" id="editBlokir" class="form-control-modal">
                            <option value="N">Aktif</option>
                            <option value="Y">Terblokir</option>
                        </select>
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-phone"></i> Nomor Telepon</label>
                    <input type="text" name="telp" id="editTelp" class="form-control-modal font-mono">
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Update User
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
                <div>Import Data Master User</div>
            </div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Unggah file master user (.csv atau .xlsx).
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

    function openEditUserModal(id, username, nama, idlevel, telp, blokir) {
        document.getElementById('editForm').action = "/settings/user/" + id;
        document.getElementById('editUsername').value = username;
        document.getElementById('editNama').value = nama;
        document.getElementById('editPassword').value = "";
        document.getElementById('editIdLevel').value = idlevel || "1";
        document.getElementById('editTelp').value = telp;
        document.getElementById('editBlokir').value = blokir || "N";
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
