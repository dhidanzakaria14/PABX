<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmTarif extends Model
{
    protected $table = 'tbm_tarif';
    protected $primaryKey = 'idtarif';
    public $timestamps = false;

    protected $guarded = [];

    public function setting()
    {
        return $this->belongsTo(TbmSetting::class, 'idmesin', 'id');
    }

    public function rates()
    {
        return $this->hasMany(TbdRate::class, 'idrate', 'idtarif');
    }

    public function tarifKhusus()
    {
        return $this->hasMany(TbmTarifKhusus::class, 'idtarif', 'idtarif');
    }

    public function areas()
    {
        return $this->hasMany(TbmArea::class, 'idrate', 'idtarif');
    }

    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'idrate', 'idtarif');
    }
}
