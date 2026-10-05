<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogLogin extends Model
{
    protected $table = 'log_login';
    protected $primaryKey = 'idlog';
    public $timestamps = false;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(TblUser::class, 'iduser', 'iduser');
    }
}
