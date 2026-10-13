<?php

namespace App\Console\Commands;

use App\Services\CallRateCalculator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateCalls extends Command
{
    protected $signature = 'pabx:recalculate {--from= : Tanggal mulai (YYYY-MM-DD)} {--to= : Tanggal akhir (YYYY-MM-DD)} {--limit=5000 : Batas jumlah record} {--uncounted : Hanya yang belum dihitung}';
    protected $description = 'Hitung ulang biaya panggilan di tbm_data_masuk menggunakan rumus PABX asli';

    public function handle()
    {
        $fromDate = $this->option('from');
        $toDate = $this->option('to');
        $limit = (int)$this->option('limit');
        $onlyUncounted = $this->option('uncounted');

        $query = DB::table('tbm_data_masuk');
        if ($onlyUncounted) {
            $query->where(function($q) {
                $q->where('hitung', '<', 1)->orWhereNull('hitung');
            });
        }
        if ($fromDate) {
            $query->where('tglmasuk', '>=', $fromDate);
        }
        if ($toDate) {
            $query->where('tglmasuk', '<=', $toDate);
        }

        $totalRecords = (clone $query)->count();
        $this->info("Menemukan {$totalRecords} rekaman panggilan untuk diproses (Limit: {$limit})...");

        $records = $query->select('id', 'ext_pemanggil', 'no_tujuan', 'durasi', 'durasi_detik', 'tglmasuk', 'jammasuk')
            ->limit($limit)
            ->get();

        $bar = $this->output->createProgressBar($records->count());
        $bar->start();

        $updatedCount = 0;
        $totalCost = 0;

        foreach ($records as $r) {
            $durSec = $r->durasi_detik;
            if (!$durSec && $r->durasi) {
                $parts = explode(':', $r->durasi);
                if (count($parts) === 3) {
                    $durSec = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
                }
            }

            $calc = CallRateCalculator::calculate(
                $r->no_tujuan,
                $durSec ?: 0,
                $r->tglmasuk,
                $r->jammasuk,
                $r->ext_pemanggil
            );

            DB::table('tbm_data_masuk')->where('id', $r->id)->update([
                'iddepartment' => $calc['department_id'],
                'namadepartment' => $calc['department_name'],
                'namamesin' => $calc['namamesin'],
                'kodearea' => $calc['kodearea'],
                'zona' => $calc['zona'],
                'ketarea' => $calc['ketarea'],
                'idrate' => $calc['idrate'],
                'namarate' => $calc['namarate'],
                'biaya' => $calc['biaya'],
                'tambahan' => $calc['tambahan'],
                'subtotal' => $calc['subtotal'],
                'formularate' => $calc['formularate'],
                'hitung' => 1,
                'idd' => $calc['idd'],
                'iddname' => $calc['iddname'],
                'cell' => $calc['cell'],
                'idperusahaan' => $calc['idperusahaan'],
                'namaperusahaan' => $calc['namaperusahaan'],
                'durasi_detik' => $calc['duration_sec'],
            ]);

            $updatedCount++;
            $totalCost += $calc['subtotal'];
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai! {$updatedCount} panggilan telah dihitung ulang.");
        $this->info("Total Tagihan: Rp " . number_format($totalCost, 0, ',', '.'));

        return Command::SUCCESS;
    }
}
