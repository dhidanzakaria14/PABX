<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- IDD Values in tbm_data_masuk ---\n";
$idds = DB::table('tbm_data_masuk')
    ->select('idd', DB::raw('count(*) as cnt'), DB::raw('sum(subtotal) as total'))
    ->groupBy('idd')
    ->get();
print_r($idds->toArray());

echo "\n--- CELL Values in tbm_data_masuk ---\n";
$cells = DB::table('tbm_data_masuk')
    ->select('cell', DB::raw('count(*) as cnt'))
    ->groupBy('cell')
    ->get();
print_r($cells->toArray());

echo "\n--- Kode Area / Top Areas in tbm_data_masuk ---\n";
$areas = DB::table('tbm_data_masuk')
    ->select('kodearea', 'ketarea', DB::raw('count(*) as cnt'), DB::raw('sum(durasi_detik) as dur'), DB::raw('sum(subtotal) as total'))
    ->groupBy('kodearea', 'ketarea')
    ->orderByDesc('cnt')
    ->limit(5)
    ->get();
print_r($areas->toArray());

echo "\n--- Top Destinations / Businesses in tbm_data_masuk ---\n";
$dests = DB::table('tbm_data_masuk')
    ->select('no_tujuan', 'ketarea', 'kodearea', DB::raw('count(*) as cnt'), DB::raw('sum(durasi_detik) as dur'), DB::raw('sum(subtotal) as total'))
    ->groupBy('no_tujuan', 'ketarea', 'kodearea')
    ->orderByDesc('cnt')
    ->limit(5)
    ->get();
print_r($dests->toArray());

echo "\n--- Hourly Data Sample (00 to 23) in tbm_data_masuk ---\n";
$hourly = DB::table('tbm_data_masuk')
    ->select(DB::raw('SUBSTRING(jammasuk, 1, 2) as hour'), DB::raw('count(*) as cnt'), DB::raw('sum(subtotal) as total'))
    ->groupBy('hour')
    ->orderBy('hour')
    ->get();
print_r($hourly->toArray());
