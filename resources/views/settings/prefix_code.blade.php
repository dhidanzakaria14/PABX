@extends('layouts.app')

@section('title', 'PREFIX CODE - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-hashtag" style="color: #0284c7;"></i>
                PREFIX CODE
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
                    <button type="button" class="btn-dt" onclick="copyTableData('prefixTable')">Copy</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('prefixTable', 'prefix_code.csv')">CSV</button>
                    <button type="button" class="btn-dt" onclick="exportTableToCSV('prefixTable', 'prefix_code.xls')">Excel</button>
                    <button type="button" class="btn-dt" onclick="printTableData('prefixTable', 'PREFIX CODE')">PDF</button>
                    <button type="button" class="btn-dt" onclick="printTableData('prefixTable', 'PREFIX CODE')">Print</button>
                </div>
                <div class="dt-search">
                    <label>Search: <input type="text" id="dtSearchInput" class="dt-search-box" onkeyup="filterTable('prefixTable', this.value)"></label>
                </div>
            </div>

            <div class="table-responsive">
                <table class="setting-table" id="prefixTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('prefixTable', 0)" style="width: 70px;">No <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('prefixTable', 1)">Prefix Code <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('prefixTable', 2)">Distrik <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('prefixTable', 3)">Keterangan <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('prefixTable', 4)">Kelompok <span class="sort-icon">⇅</span></th>
                            <th onclick="sortTable('prefixTable', 5)">Zona <span class="sort-icon">⇅</span></th>
                            <th style="width: 110px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prefixes as $idx => $p)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0288d1;">{{ $p->kodeprefix }}</td>
                                <td>{{ $p->distrik ?: '-' }}</td>
                                <td>{{ $p->keterangan }}</td>
                                <td>
                                    <span style="font-weight: 600; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; {{ $p->kelompok === 'CELL' ? 'background: #e0f2fe; color: #0369a1;' : 'background: #f1f5f9; color: #475569;' }}">
                                        {{ $p->kelompok }}
                                    </span>
                                </td>
                                <td>{{ $p->zona ? $p->zona->namazone : '-' }}</td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-wrench" title="Edit Data"
                                            onclick="openEditPrefixModal({{ $p->idprefix }}, '{{ addslashes($p->kodeprefix) }}', '{{ addslashes($p->distrik) }}', '{{ addslashes($p->keterangan) }}', '{{ $p->kelompok }}', '{{ $p->idzone }}')">
                                            <i class="fa-solid fa-wrench"></i>
                                        </button>
                                        <button type="button" class="btn-action-trash" title="Hapus Data"
                                            onclick="confirmDelete('{{ route('settings.prefix.destroy', $p->idprefix) }}', 'Prefix {{ addslashes($p->kodeprefix) }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 2.5rem; color: #94a3b8;">
                                    <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; display: block; opacity: 0.5;"></i>
                                    Belum ada data Prefix Code terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="table-info" id="tableInfo">
                    Showing 1 to {{ $prefixes->count() }} of {{ $prefixes->count() }} entries
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
                <div>Tambah Prefix Code Baru</div>
            </div>
            <button type="button" onclick="closeModal('createModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form action="{{ route('settings.prefix.store') }}" method="POST">
            @csrf
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-hashtag"></i> Prefix Code <span class="required-star">*</span></label>
                        <input type="text" name="kodeprefix" class="form-control-modal font-mono" placeholder="Contoh: 08" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-map-location-dot"></i> Distrik</label>
                        <input type="text" name="distrik" class="form-control-modal font-mono" placeholder="Contoh: CELL / NDD / LDD">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-file-lines"></i> Keterangan <span class="required-star">*</span></label>
                    <input type="text" name="keterangan" class="form-control-modal" placeholder="Contoh: Selular Call" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-layer-group"></i> Kelompok <span class="required-star">*</span></label>
                        <select name="kelompok" class="form-control-modal" required>
                            <option value="PHONE">PHONE</option>
                            <option value="CELL">CELL</option>
                        </select>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-earth-asia"></i> Zona Tarif</label>
                        <select name="idzone" class="form-control-modal">
                            <option value="">-- Pilih Zona --</option>
                            @foreach($zonas as $z)
                                <option value="{{ $z->idzone }}">{{ $z->namazone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('createModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Simpan Prefix
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
                <div>Edit Data Prefix Code</div>
            </div>
            <button type="button" onclick="closeModal('editModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-hashtag"></i> Prefix Code <span class="required-star">*</span></label>
                        <input type="text" name="kodeprefix" id="editKodePrefix" class="form-control-modal font-mono" required>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-map-location-dot"></i> Distrik</label>
                        <input type="text" name="distrik" id="editDistrik" class="form-control-modal font-mono">
                    </div>
                </div>
                <div class="form-group-modal">
                    <label><i class="fa-solid fa-file-lines"></i> Keterangan <span class="required-star">*</span></label>
                    <input type="text" name="keterangan" id="editKeterangan" class="form-control-modal" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-layer-group"></i> Kelompok <span class="required-star">*</span></label>
                        <select name="kelompok" id="editKelompok" class="form-control-modal" required>
                            <option value="PHONE">PHONE</option>
                            <option value="CELL">CELL</option>
                        </select>
                    </div>
                    <div class="form-group-modal">
                        <label><i class="fa-solid fa-earth-asia"></i> Zona Tarif</label>
                        <select name="idzone" id="editIdZone" class="form-control-modal">
                            <option value="">-- Pilih Zona --</option>
                            @foreach($zonas as $z)
                                <option value="{{ $z->idzone }}">{{ $z->namazone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-card-footer">
                <button type="button" onclick="closeModal('editModal')" class="btn-modal-cancel">
                    <i class="fa-solid fa-xmark"></i> Batal
                </button>
                <button type="submit" class="btn-modal-save">
                    <i class="fa-solid fa-check"></i> Update Prefix
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
                <div>Import Data Prefix Code</div>
            </div>
            <button type="button" onclick="closeModal('importModal')" class="modal-close" aria-label="Close">&times;</button>
        </div>
        <div class="modal-card-body">
            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                Unggah file data prefix code (.csv atau .xlsx).
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

    function openEditPrefixModal(id, kodeprefix, distrik, keterangan, kelompok, idzone) {
        document.getElementById('editForm').action = "/settings/prefix/" + id;
        document.getElementById('editKodePrefix').value = kodeprefix;
        document.getElementById('editDistrik').value = distrik || "";
        document.getElementById('editKeterangan').value = keterangan;
        document.getElementById('editKelompok').value = kelompok || "PHONE";
        document.getElementById('editIdZone').value = idzone || "";
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
