<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmTarifKhusus extends Model
{
    protected $table = 'tbm_tarif_khusus';
    protected $primaryKey = 'idharga';
    public $timestamps = false;

    protected $guarded = [];

    public function tarif()
    {
        return $this->belongsTo(TbmTarif::class, 'idtarif', 'idtarif');
    }

    public function details()
    {
        return $this->hasMany(TbdTarifKhusus::class, 'idtarifkhusus', 'idharga');
    }
}
