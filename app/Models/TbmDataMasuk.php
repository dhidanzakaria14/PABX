<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmDataMasuk extends Model
{
    protected $table = 'tbm_data_masuk';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    // Relasi ke Department / Extension
    public function department()
    {
        return $this->belongsTo(TbmDepartment::class, 'iddepartment', 'id');
    }

    // Relasi ke PBX Setting / Mesin
    public function setting()
    {
        return $this->belongsTo(TbmSetting::class, 'idmesin', 'id');
    }

    // Relasi ke Zona Tujuan
    public function zonaRel()
    {
        return $this->belongsTo(TbmZona::class, 'idzone', 'idzone');
    }

    // Relasi ke Prefix
    public function prefixRel()
    {
        return $this->belongsTo(TbmPrefix::class, 'idprefix', 'idprefix');
    }

    // Relasi ke Tarif
    public function tarifRel()
    {
        return $this->belongsTo(TbmTarif::class, 'idrate', 'idtarif');
    }

    // Relasi ke Profil Perusahaan
    public function profil()
    {
        return $this->belongsTo(TbmProfil::class, 'idperusahaan', 'id');
    }
}
