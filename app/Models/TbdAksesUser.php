<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbdAksesUser extends Model
{
    protected $table = 'tbd_akses_user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(TbmLevel::class, 'idlevel', 'id');
    }

    public function modul()
    {
        return $this->belongsTo(TbdModulUser::class, 'iddetailmodul', 'iddetail');
    }
}
