<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmArea extends Model
{
    protected $table = 'tbm_area';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function setting()
    {
        return $this->belongsTo(TbmSetting::class, 'idmesin', 'id');
    }

    public function prefix()
    {
        return $this->belongsTo(TbmPrefix::class, 'idprefix', 'idprefix');
    }

    public function zona()
    {
        return $this->belongsTo(TbmZona::class, 'idzone', 'idzone');
    }

    public function tarif()
    {
        return $this->belongsTo(TbmTarif::class, 'idrate', 'idtarif');
    }
}
