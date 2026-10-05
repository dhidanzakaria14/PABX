<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbdModulUser extends Model
{
    protected $table = 'tbd_modul_user';
    protected $primaryKey = 'iddetail';
    public $timestamps = false;

    protected $guarded = [];

    public function akses()
    {
        return $this->hasMany(TbdAksesUser::class, 'iddetailmodul', 'iddetail');
    }
}
