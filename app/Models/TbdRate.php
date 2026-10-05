<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbdRate extends Model
{
    protected $table = 'tbd_rate';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function tarif()
    {
        return $this->belongsTo(TbmTarif::class, 'idrate', 'idtarif');
    }
}
