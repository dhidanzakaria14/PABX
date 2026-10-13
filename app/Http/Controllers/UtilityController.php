<?php

namespace App\Http\Controllers;

use App\Models\LogLogin;
use App\Models\TbmDataMasuk;
use App\Models\TbmDepartment;
use App\Models\TbmProfil;
use App\Models\TbmSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class UtilityController extends Controller
{
    // 1. Server Health & Diagnostics
    public function serverHealth()
    {
        $profile = TbmProfil::first();
        $setting = TbmSetting::first();

        // 1. Diagnostics Server System
        $phpVersion = phpversion();
        $laravelVersion = app()->version();
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'PHP CLI / Artisan';
        $osName = PHP_OS . ' (' . php_uname('m') . ')';

        // Disk space for current drive
        $drive = 'C:';
        $diskTotalBytes = @disk_total_space($drive) ?: (100 * 1024 * 1024 * 1024);
        $diskFreeBytes = @disk_free_space($drive) ?: (50 * 1024 * 1024 * 1024);
        $diskUsedBytes = $diskTotalBytes - $diskFreeBytes;
        $diskPercent = round(($diskUsedBytes / $diskTotalBytes) * 100, 1);

        // Memory Usage
        $memoryUsageBytes = memory_get_usage(true);
        $memoryPeakBytes = memory_get_peak_usage(true);
        $memoryLimit = ini_get('memory_limit');

        // Required PHP extensions
        $extensions = [
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'sockets' => extension_loaded('sockets'),
            'mbstring' => extension_loaded('mbstring'),
            'openssl' => extension_loaded('openssl'),
            'curl' => extension_loaded('curl'),
            'json' => extension_loaded('json'),
            'fileinfo' => extension_loaded('fileinfo'),
        ];

        // 2. Database Diagnostics
        $dbName = config('database.connections.mysql.database', 'pabx');
        $tables = [
            'tbm_data_masuk' => DB::table('tbm_data_masuk')->count(),
            'tbm_department' => DB::table('tbm_department')->count(),
            'tbm_area' => DB::table('tbm_area')->count(),
            'tbm_tarif' => DB::table('tbm_tarif')->count(),
            'tbm_zona' => DB::table('tbm_zona')->count(),
            'tbm_bisnis' => DB::table('tbm_bisnis')->count(),
            'tbd_modul_user' => DB::table('tbd_modul_user')->count(),
            'tbl_user' => DB::table('tbl_user')->count(),
        ];

        // MySQL database size
        $dbSizeResult = DB::select("
            SELECT SUM(data_length + index_length) as size_bytes 
            FROM information_schema.tables 
            WHERE table_schema = ?
        ", [$dbName]);
        $dbSizeBytes = $dbSizeResult[0]->size_bytes ?? 0;

        // 3. Socket PABX Status
        $pabxIp = $setting->ip ?? '10.3.16.12';
        $pabxPort = (int)($setting->port ?? 5000);
        $pabxConnected = false;
        $pabxLatency = null;

        $fpStart = microtime(true);
        $fp = @fsockopen($pabxIp, $pabxPort, $errno, $errstr, 0.4);
        if (is_resource($fp)) {
            $pabxConnected = true;
            $pabxLatency = round((microtime(true) - $fpStart) * 1000, 2);
            fclose($fp);
        }

        // 4. Recent Login Logs
        $recentLogins = DB::table('tbl_user')
            ->select('username', 'nama', 'leveluser', 'login', 'aktif', 'hit')
            ->orderByDesc('login')
            ->get();

        return view('utility.server_health', compact(
            'profile',
            'setting',
            'phpVersion',
            'laravelVersion',
            'serverSoftware',
            'osName',
            'diskTotalBytes',
            'diskFreeBytes',
            'diskUsedBytes',
            'diskPercent',
            'memoryUsageBytes',
            'memoryPeakBytes',
            'memoryLimit',
            'extensions',
            'dbName',
            'tables',
            'dbSizeBytes',
            'pabxIp',
            'pabxPort',
            'pabxConnected',
            'pabxLatency',
            'recentLogins'
        ));
    }

    // 2. Backup & Maintenance
    public function backupMaintenance()
    {
        $profile = TbmProfil::first();
        $dbName = config('database.connections.mysql.database', 'pabx');

        // Check existing backup files
        $backupDir = storage_app_path('backups');
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0777, true);
        }

        $backupFiles = [];
        foreach (glob($backupDir . '/*.sql') as $file) {
            $backupFiles[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'time' => date('Y-m-d H:i:s', filemtime($file)),
                'path' => $file,
            ];
        }
        usort($backupFiles, fn($a, $b) => strcmp($b['time'], $a['time']));

        // Database stats
        $totalCdr = DB::table('tbm_data_masuk')->count();
        $totalDepts = DB::table('tbm_department')->count();
        $dateMin = DB::table('tbm_data_masuk')->min('tglmasuk');
        $dateMax = DB::table('tbm_data_masuk')->max('tglmasuk');

        return view('utility.backup_maintenance', compact(
            'profile',
            'dbName',
            'backupFiles',
            'totalCdr',
            'totalDepts',
            'dateMin',
            'dateMax'
        ));
    }

    // Download / Generate Database Dump
    public function generateBackup(Request $request)
    {
        $dbName = config('database.connections.mysql.database', 'pabx');
        $filename = 'backup_pabx_' . date('Y_m_d_His') . '.sql';

        $tables = [
            'tbm_setting',
            'tbm_profil',
            'tbm_department',
            'tbm_group_department',
            'tbm_area',
            'tbm_prefix',
            'tbm_tarif',
            'tbm_tarif_khusus',
            'tbd_rate',
            'tbd_tarif_khusus',
            'tbm_zona',
            'tbm_bisnis',
            'tbm_hari',
            'tbl_user',
            'tbm_level',
            'tbd_modul_user',
            'tbd_akses_user',
            'tbm_data_masuk',
        ];

        $sqlDump = "-- ANGKASA PURA PABX BILLING SYSTEM SQL DUMP\n";
        $sqlDump .= "-- Database: {$dbName}\n";
        $sqlDump .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
            if (!empty($createTable)) {
                $sqlDump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $sqlDump .= $createTable[0]->{'Create Table'} . ";\n\n";

                // Insert data
                $rows = DB::table($table)->get();
                if ($rows->count() > 0) {
                    $sqlDump .= "INSERT INTO `{$table}` VALUES \n";
                    $rowStrings = [];
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array)$row as $val) {
                            if (is_null($val)) {
                                $values[] = "NULL";
                            } else {
                                $values[] = "'" . addslashes((string)$val) . "'";
                            }
                        }
                        $rowStrings[] = "(" . implode(", ", $values) . ")";
                    }
                    $sqlDump .= implode(",\n", $rowStrings) . ";\n\n";
                }
            }
        }

        $sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $backupDir = storage_app_path('backups');
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0777, true);
        }
        file_put_contents($backupDir . '/' . $filename, $sqlDump);

        if ($request->has('download')) {
            return response($sqlDump, 200, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]);
        }

        return redirect()->route('utility.backup-maintenance')->with('success', "Backup database berhasil dibuat: {$filename}");
    }

    // Optimize Database Tables
    public function optimizeDatabase()
    {
        $tables = [
            'tbm_data_masuk',
            'tbm_department',
            'tbm_area',
            'tbm_prefix',
            'tbm_tarif',
            'tbd_rate',
            'tbl_user',
        ];

        foreach ($tables as $table) {
            DB::statement("OPTIMIZE TABLE `{$table}`");
        }

        return redirect()->route('utility.backup-maintenance')->with('success', 'Optimasi tabel database PABX berhasil dijalankan!');
    }

    // Clear Application Cache
    public function clearCache()
    {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        return redirect()->route('utility.backup-maintenance')->with('success', 'Cache view dan konfigurasi sistem berhasil dibersihkan!');
    }

    // 3. Run Service (PABX Background Daemon Monitor)
    public function runService()
    {
        $setting = TbmSetting::first();
        $isListening = false;
        $port = (int)($setting->port ?? 5000);

        // Check if port is open locally
        $conn = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.4);
        if (is_resource($conn)) {
            $isListening = true;
            fclose($conn);
        }

        $recentEntries = DB::table('tbm_data_masuk')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('utility.run_service', compact('setting', 'isListening', 'port', 'recentEntries'));
    }

    // 4. Level Akses (Privileges Management)
    public function levelAkses(Request $request)
    {
        $levels = DB::table('tbm_level')->get();
        $selectedLevelId = $request->input('level_id', 1);

        $selectedLevel = DB::table('tbm_level')->where('id', $selectedLevelId)->first() ?: $levels->first();

        // All modules grouped by category
        $modules = DB::table('tbd_modul_user')
            ->orderBy('kategorimenu')
            ->orderBy('namadetail')
            ->get();

        // Current access privileges for this level
        $accessRights = DB::table('tbd_akses_user')
            ->where('idlevel', $selectedLevel ? $selectedLevel->id : 1)
            ->pluck('tampil', 'iddetailmodul')
            ->toArray();

        return view('utility.level_akses', compact('levels', 'selectedLevel', 'modules', 'accessRights'));
    }

    // Update Privilege Matrix
    public function updateLevelAkses(Request $request)
    {
        $levelId = $request->input('level_id');
        $moduleIds = $request->input('modules', []);

        // Delete existing access for this level
        DB::table('tbd_akses_user')->where('idlevel', $levelId)->delete();

        // Insert selected privileges
        foreach ($moduleIds as $modId) {
            DB::table('tbd_akses_user')->insert([
                'idlevel' => $levelId,
                'iddetailmodul' => $modId,
                'tampil' => 1,
                'ubah' => 1,
                'hapus' => 1,
                'tambah' => 1,
            ]);
        }

        return redirect()->route('utility.level-akses', ['level_id' => $levelId])
            ->with('success', 'Hak akses modul berhasil diperbarui!');
    }
}

if (!function_exists('storage_app_path')) {
    function storage_app_path($path = '') {
        return storage_path('app' . ($path ? DIRECTORY_SEPARATOR . $path : ''));
    }
}
