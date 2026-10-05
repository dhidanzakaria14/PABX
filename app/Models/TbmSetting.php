<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmSetting extends Model
{
    protected $table = 'tbm_setting';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function tarifs()
    {
        return $this->hasMany(TbmTarif::class, 'idmesin', 'id');
    }

    public function areas()
    {
        return $this->hasMany(TbmArea::class, 'idmesin', 'id');
    }

    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'idmesin', 'id');
    }
}
