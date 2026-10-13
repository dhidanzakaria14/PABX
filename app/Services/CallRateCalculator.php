<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class CallRateCalculator
{
    /**
     * Hitung biaya panggilan berdasarkan rumus asli PABX Legacy (pabx_pt1)
     *
     * @param string $destination Nomor tujuan
     * @param int|string $duration Durasi dalam detik (int) atau format "HH:MM:SS" (string)
     * @param string|null $callDate Tanggal panggilan "YYYY-MM-DD" (default: hari ini)
     * @param string|null $callTime Jam panggilan "HH:MM:SS" (default: sekarang)
     * @param string|null $extCode Extension pemanggil
     * @return array
     */
    public static function calculate($destination, $duration, $callDate = null, $callTime = null, $extCode = null)
    {
        $callDate = $callDate ?: date('Y-m-d');
        $callTime = $callTime ?: date('H:i:s');
        $cleanDest = preg_replace('/[^0-9]/', '', (string)$destination);

        // 1. Konversi durasi ke detik
        if (is_numeric($duration)) {
            $detik = (int)$duration;
        } else {
            $parts = explode(':', (string)$duration);
            if (count($parts) === 3) {
                $detik = ((int)$parts[0] * 3600) + ((int)$parts[1] * 60) + (int)$parts[2];
            } elseif (count($parts) === 2) {
                $detik = ((int)$parts[0] * 60) + (int)$parts[1];
            } else {
                $detik = (int)$duration;
            }
        }

        $day = date('l', strtotime($callDate));

        // 2. Cari area berdasarkan prefix no_tujuan (Longest Prefix Match dari tbm_area)
        $tarifArea = DB::table('tbm_area')
            ->where(function($q) {
                $q->where('st', 0)->orWhereNull('st');
            })
            ->whereRaw("CONCAT(namaprefix, '', kodearea) = SUBSTR(?, 1, LENGTH(CONCAT(namaprefix, '', kodearea)))", [$cleanDest])
            ->orderByRaw("LENGTH(CONCAT(namaprefix, '', kodearea)) DESC")
            ->first();

        // Fallback jika tidak cocok dengan gabungan prefix + area
        if (!$tarifArea) {
            $tarifArea = DB::table('tbm_area')
                ->where(function($q) {
                    $q->where('st', 0)->orWhereNull('st');
                })
                ->whereRaw("? LIKE CONCAT(namaprefix, '%')", [$cleanDest])
                ->orderByRaw("LENGTH(namaprefix) DESC")
                ->first();
        }

        // 3. Cari perusahaan rekanan (tbm_bisnis)
        $bisnis = DB::table('tbm_bisnis')
            ->where('notelp', $cleanDest)
            ->where(function($q) {
                $q->where('st', 0)->orWhereNull('st');
            })
            ->first();

        // 4. Cari metadata prefix (tbm_prefix)
        $prefix = null;
        if ($tarifArea) {
            $prefix = DB::table('tbm_prefix')
                ->where('kodeprefix', $tarifArea->namaprefix)
                ->where(function($q) {
                    $q->where('st', 0)->orWhereNull('st');
                })
                ->first();

            if (!$prefix && !empty($tarifArea->idprefix)) {
                $prefix = DB::table('tbm_prefix')->where('idprefix', $tarifArea->idprefix)->first();
            }
        }

        // 5. Pencocokan Tarif 3-Tier (Khusus -> Hari Khusus / Special -> Normal)
        $koderate = $tarifArea ? $tarifArea->koderate : null;

        // Tier 1: Tarif Khusus (tbm_tarif_khusus & tbd_tarif_khusus)
        $tarifKhusus = null;
        if ($koderate) {
            $tarifKhusus = DB::table('tbm_tarif_khusus')
                ->join('tbd_tarif_khusus', 'tbm_tarif_khusus.idharga', '=', 'tbd_tarif_khusus.idtarifkhusus')
                ->where('tbm_tarif_khusus.idtarif', $koderate)
                ->where('tbm_tarif_khusus.tglselesai', '>=', $callDate)
                ->where('tbd_tarif_khusus.selesai', '>=', $callTime)
                ->where(function($q) {
                    $q->where('tbm_tarif_khusus.st', 0)->orWhereNull('tbm_tarif_khusus.st');
                })
                ->select(
                    'tbd_tarif_khusus.idtarifkhusus',
                    'tbd_tarif_khusus.mulai',
                    'tbm_tarif_khusus.tglmulai',
                    'tbm_tarif_khusus.mindurasi',
                    'tbd_tarif_khusus.tarif'
                )
                ->first();
        }

        // Tier 2: Special Rate (tbm_tarif & tbd_rate where FIND_IN_SET(day, harikhusus))
        $specialRate = null;
        if ($koderate) {
            $specialRate = DB::table('tbm_tarif')
                ->join('tbd_rate', 'tbm_tarif.idtarif', '=', 'tbd_rate.idrate')
                ->where('tbm_tarif.idtarif', $koderate)
                ->whereRaw("FIND_IN_SET(?, tbm_tarif.harikhusus)", [$day])
                ->where('tbd_rate.mulai', '<=', $callTime)
                ->where('tbd_rate.selesai', '>=', $callTime)
                ->where(function($q) {
                    $q->where('tbm_tarif.st', 0)->orWhereNull('tbm_tarif.st');
                })
                ->select(
                    'tbm_tarif.idtarif',
                    'tbm_tarif.mindurasi',
                    'tbd_rate.mulai',
                    'tbd_rate.tarifkhusus'
                )
                ->first();
        }

        // Tier 3: Normal Rate (tbm_tarif & tbd_rate)
        $normalRate = null;
        if ($koderate) {
            $normalRate = DB::table('tbm_tarif')
                ->join('tbd_rate', 'tbm_tarif.idtarif', '=', 'tbd_rate.idrate')
                ->where('tbm_tarif.idtarif', $koderate)
                ->where('tbd_rate.mulai', '<=', $callTime)
                ->where('tbd_rate.selesai', '>=', $callTime)
                ->where(function($q) {
                    $q->where('tbm_tarif.st', 0)->orWhereNull('tbm_tarif.st');
                })
                ->select(
                    'tbm_tarif.idtarif',
                    'tbm_tarif.mindurasi',
                    'tbd_rate.mulai',
                    'tbd_rate.tarifnormal'
                )
                ->first();

            // Fallback jika tidak tepat di rentang jam (ambil rate pertama untuk koderate tersebut)
            if (!$normalRate) {
                $normalRate = DB::table('tbm_tarif')
                    ->join('tbd_rate', 'tbm_tarif.idtarif', '=', 'tbd_rate.idrate')
                    ->where('tbm_tarif.idtarif', $koderate)
                    ->select(
                        'tbm_tarif.idtarif',
                        'tbm_tarif.mindurasi',
                        'tbd_rate.mulai',
                        'tbd_rate.tarifnormal'
                    )
                    ->first();
            }
        }

        // 6. Penerapan Rumus Perhitungan Biaya (Legacy Formula)
        $billing = 0;
        $formula = 'N/A';
        $tierType = 'none';
        $pulsa = 0;

        if ($tarifKhusus && $tarifKhusus->idtarifkhusus > 0 && $tarifKhusus->tglmulai >= $callDate && $tarifKhusus->mulai <= $callTime) {
            $tierType = 'tarif_khusus';
            $formula = $tarifKhusus->tarif;
            $minDur = (int)$tarifKhusus->mindurasi;
            $kurang = max(0, $detik - $minDur);
            $parts = explode('/', $tarifKhusus->tarif);
            $unitPrice = (float)($parts[0] ?? 0);
            $unitSec = max(1, (int)($parts[1] ?? 1));
            $akumulasiDetik = ceil($kurang / $unitSec);
            $billing = $akumulasiDetik * $unitPrice;
            $pulsa = $akumulasiDetik;
        } elseif ($specialRate && $specialRate->idtarif > 0 && $specialRate->mulai <= $callTime) {
            $tierType = 'special_rate';
            $formula = $specialRate->tarifkhusus;
            $minDur = (int)$specialRate->mindurasi;
            $kurang = max(0, $detik - $minDur);
            $parts = explode('/', $specialRate->tarifkhusus);
            $unitPrice = (float)($parts[0] ?? 0);
            $unitSec = max(1, (int)($parts[1] ?? 1));
            $akumulasiDetik = ceil($kurang / $unitSec);
            $billing = $akumulasiDetik * $unitPrice;
            $pulsa = $akumulasiDetik;
        } elseif ($normalRate && $normalRate->idtarif > 0) {
            $tierType = 'normal_rate';
            $formula = $normalRate->tarifnormal;
            $minDur = (int)$normalRate->mindurasi;
            $kurang = max(0, $detik - $minDur);
            $parts = explode('/', $normalRate->tarifnormal);
            $unitPrice = (float)($parts[0] ?? 0);
            $unitSec = max(1, (int)($parts[1] ?? 1));
            $akumulasiDetik = ceil($kurang / $unitSec);
            $billing = $akumulasiDetik * $unitPrice;
            $pulsa = $akumulasiDetik;
        }

        // 7. Cari data departemen pemanggil untuk biaya tambahan (surcharge)
        $department = null;
        $tambahan = 0;
        if ($extCode) {
            $department = DB::table('tbm_department')
                ->where('extcode', $extCode)
                ->where(function($q) {
                    $q->where('st', 0)->orWhereNull('st')->orWhere('st', 1);
                })
                ->first();

            if ($department) {
                $tambahan = (float)($department->additional ?? 0);
            }
        }

        // 8. Total Bill / Subtotal
        $totalBill = $billing > 0 ? ($billing + $tambahan) : $tambahan;

        // Tentukan IDD / Cell classification
        $idd = 'LOCAL';
        if ($prefix && !empty($prefix->distrik)) {
            $idd = $prefix->distrik;
        } elseif ($cleanDest == '1500800' || $cleanDest == '1500803') {
            $idd = 'L1';
        } elseif (str_starts_with($cleanDest, '08')) {
            $idd = 'CELL';
        } elseif (str_starts_with($cleanDest, '00') || str_starts_with($cleanDest, '01')) {
            $idd = 'IDD';
        } elseif (str_starts_with($cleanDest, '0')) {
            $idd = 'NDD';
        }

        $cell = 'PHONE';
        if ($prefix && !empty($prefix->kelompok)) {
            $cell = $prefix->kelompok;
        } elseif (str_starts_with($cleanDest, '08')) {
            $cell = 'CELL';
        }

        return [
            'success' => true,
            'duration_sec' => $detik,
            'call_date' => $callDate,
            'call_time' => $callTime,
            'day' => $day,
            'destination' => $cleanDest,
            // Area & Prefix Data
            'idarea' => $tarifArea ? $tarifArea->id : null,
            'namamesin' => $tarifArea ? $tarifArea->namamesin : 'MESIN 1',
            'kodearea' => $tarifArea ? $tarifArea->kodearea : null,
            'zona' => $tarifArea ? $tarifArea->zona : null,
            'ketarea' => $tarifArea ? $tarifArea->keterangan : 'Internal / Unknown',
            'idrate' => $koderate,
            'namarate' => $tarifArea ? $tarifArea->namarate : null,
            'prefix_code' => $tarifArea ? $tarifArea->namaprefix : null,
            // Prefix Classification
            'idd' => $idd,
            'iddname' => $prefix ? $prefix->keterangan : $idd,
            'cell' => $cell,
            // Business Partner
            'idperusahaan' => $bisnis ? $bisnis->id : null,
            'namaperusahaan' => $bisnis ? $bisnis->nama : null,
            // Department
            'department_id' => $department ? $department->id : null,
            'department_name' => $department ? $department->extname : null,
            // Pricing & Formula
            'tier' => $tierType,
            'formularate' => $formula,
            'pulsa' => $pulsa,
            'biaya' => $billing,
            'tambahan' => $tambahan,
            'subtotal' => $totalBill,
            'subtotal_formatted' => 'Rp ' . number_format($totalBill, 0, ',', '.'),
        ];
    }
}
