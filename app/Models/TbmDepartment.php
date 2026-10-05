<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmDepartment extends Model
{
    protected $table = 'tbm_department';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];

    // Relasi ke Divisi / Group
    public function group()
    {
        return $this->belongsTo(TbmGroupDepartment::class, 'idgroup', 'idgroup');
    }

    // Relasi ke Rekaman Panggilan
    public function dataMasuk()
    {
        return $this->hasMany(TbmDataMasuk::class, 'iddepartment', 'id');
    }
}
