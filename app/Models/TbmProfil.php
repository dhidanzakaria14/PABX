<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmProfil extends Model
{
    protected $table = 'tbm_profil';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'idperusahaan', 'id');
    }
}
