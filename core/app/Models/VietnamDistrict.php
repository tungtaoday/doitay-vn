<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VietnamDistrict extends Model
{
    use HasFactory;
    // Đặt tên bảng
    protected $table = 'vietnam_districts';

    // Đặt các cột có thể mass-assignable
    protected $fillable = [
        'city',
        'city_code',
        'district',
        'district_code',
        'ward',
        'ward_code',
        'level',
        'english_name',
    ];

    // Đặt các cột cần được kiểu hóa
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
}
