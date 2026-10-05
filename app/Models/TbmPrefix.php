<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmPrefix extends Model
{
    protected $table = 'tbm_prefix';
    protected $primaryKey = 'idprefix';
    public $timestamps = false;

    protected $guarded = [];

    public function zona()
    {
        return $this->belongsTo(TbmZona::class, 'idzone', 'idzone');
    }

    public function areas()
    {
        return $this->hasMany(TbmArea::class, 'idprefix', 'idprefix');
    }

    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'idprefix', 'idprefix');
    }
}
