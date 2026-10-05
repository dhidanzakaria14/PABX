<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TblUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_user';
    protected $primaryKey = 'iduser';
    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function level()
    {
        return $this->belongsTo(TbmLevel::class, 'idlevel', 'id');
    }

    public function logs()
    {
        return $this->hasMany(UserLog::class, 'iduser', 'iduser');
    }

    public function loginLogs()
    {
        return $this->hasMany(LogLogin::class, 'iduser', 'iduser');
    }

    public function manager()
    {
        return $this->belongsTo(TblUser::class, 'idmanajemen', 'iduser');
    }
}
