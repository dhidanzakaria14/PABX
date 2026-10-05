<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmLevel extends Model
{
    protected $table = 'tbm_level';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(TblUser::class, 'idlevel', 'id');
    }

    public function akses()
    {
        return $this->hasMany(TbdAksesUser::class, 'idlevel', 'id');
    }
}
