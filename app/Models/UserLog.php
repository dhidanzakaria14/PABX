<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'user_log';
    protected $primaryKey = 'idlog';
    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(TblUser::class, 'iduser', 'iduser');
    }
}
