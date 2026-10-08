@extends('layouts.app')

@section('title', 'MASTER USER - ANGKASA PURA')

@section('content')
<div class="setting-container">
    <div class="card-setting">
        <div class="card-setting-header">
            <div class="card-setting-title">
                <i class="fa-solid fa-users" style="color: #d32f2f;"></i>
                MASTER USER
            </div>
            <div class="card-setting-actions">
                <button type="button" class="btn-import-blue"><i class="fa-solid fa-file-import"></i> Import Data</button>
                <button type="button" class="btn-new-green"><i class="fa-solid fa-plus"></i> New Data</button>
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
                    <label>Search: <input type="text" class="dt-search-box" onkeyup="filterTable('userTable', this.value)"></label>
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
                            <th style="width: 100px;" class="text-center">Action <span class="sort-icon">⇅</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $idx => $u)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td style="font-family: 'JetBrains Mono', monospace; font-weight: 700; color: #0288d1;">{{ $u->username }}</td>
                                <td>{{ $u->nama }}</td>
                                <td><span style="background: #fee2e2; color: #b91c1c; padding: 2px 8px; border-radius: 4px; font-weight: 600; font-size: 0.75rem;">{{ $u->leveluser ?: ($u->level->level ?? 'User') }}</span></td>
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
                                        <button type="button" class="btn-action-wrench"><i class="fa-solid fa-wrench"></i></button>
                                        <button type="button" class="btn-action-trash"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center" style="padding: 2rem; color: #94a3b8;">Belum ada data User.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <div class="table-info" id="tableInfo">Showing 1 to {{ $users->count() }} of {{ $users->count() }} entries</div>
                <div class="table-pagination"><button class="page-btn active">1</button></div>
            </div>
        </div>
    </div>
</div>



<script>
    function filterTable(tableId, query) {
        const table = document.getElementById(tableId);
        const trs = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        const q = query.toLowerCase();
        let visibleCount = 0;
        for (let i = 0; i < trs.length; i++) {
            const rowText = trs[i].innerText.toLowerCase();
            if (rowText.includes(q)) { trs[i].style.display = ''; visibleCount++; }
            else { trs[i].style.display = 'none'; }
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
