<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu_masuk',
        'waktu_pulang',
        'latitude_masuk',
        'longitude_masuk',
        'accuracy_masuk',
        'jarak_masuk',
        'foto_masuk',
        'device_id_masuk',
        'device_name_masuk',
        'device_platform_masuk',
        'device_browser_masuk',
        'device_ip_masuk',
        'latitude_pulang',
        'longitude_pulang',
        'accuracy_pulang',
        'jarak_pulang',
        'foto_pulang',
        'device_id_pulang',
        'device_name_pulang',
        'device_platform_pulang',
        'device_browser_pulang',
        'device_ip_pulang',
        'waktu_absen',
        'latitude',
        'longitude',
        'jarak_meter',
        'foto',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_pulang' => 'datetime',
        'latitude_masuk' => 'decimal:8',
        'longitude_masuk' => 'decimal:8',
        'accuracy_masuk' => 'float',
        'jarak_masuk' => 'float',
        'latitude_pulang' => 'decimal:8',
        'longitude_pulang' => 'decimal:8',
        'accuracy_pulang' => 'float',
        'jarak_pulang' => 'float',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'jarak_meter' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
