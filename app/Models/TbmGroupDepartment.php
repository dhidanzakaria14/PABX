<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmGroupDepartment extends Model
{
    protected $table = 'tbm_group_department';
    protected $primaryKey = 'idgroup';
    public $timestamps = false;

    protected $guarded = [];

    public function departments()
    {
        return $this->hasMany(TbmDepartment::class, 'idgroup', 'idgroup');
    }
}
