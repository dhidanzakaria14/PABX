<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmZona extends Model
{
    protected $table = 'tbm_zona';
    protected $primaryKey = 'idzone';
    public $timestamps = false;

    protected $guarded = [];

    public function prefixes()
    {
        return $this->hasMany(TbmPrefix::class, 'idzone', 'idzone');
    }

    public function areas()
    {
        return $this->hasMany(TbmArea::class, 'idzone', 'idzone');
    }

    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'idzone', 'idzone');
    }
}
