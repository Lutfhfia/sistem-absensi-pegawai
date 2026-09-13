<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role',
        'location_id',
        'device_id',
        'device_name',
        'device_platform',
        'device_browser',
        'device_ip',
        'device_latitude',
        'device_longitude',
        'device_last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'device_last_login' => 'datetime',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function permits()
    {
        return $this->hasMany(Permit::class);
    }

    public function location()
    {
        return $this->belongsTo(AttendanceLocation::class);
    }
}
