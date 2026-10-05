<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbdTarifKhusus extends Model
{
    protected $table = 'tbd_tarif_khusus';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function tarifKhusus()
    {
        return $this->belongsTo(TbmTarifKhusus::class, 'idtarifkhusus', 'idharga');
    }
}
