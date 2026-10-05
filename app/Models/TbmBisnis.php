<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbmBisnis extends Model
{
    protected $table = 'tbm_bisnis';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $guarded = [];
}
