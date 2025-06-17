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
        'city', 'City',
        'city_code', 'City_code',
        'district', 'District',
        'district_code', 'District_code',
        'ward', 'Ward',
        'ward_code', 'Ward_code',
        'level',
        'english_name',
    ];
    
    // Accessor methods for backward compatibility
    public function getCityAttribute($value)
    {
        return $value ?: $this->attributes['City'] ?? null;
    }
    
    public function getCityCodeAttribute($value)
    {
        return $value ?: $this->attributes['City_code'] ?? null;
    }
    
    public function getDistrictAttribute($value)
    {
        return $value ?: $this->attributes['District'] ?? null;
    }
    
    public function getDistrictCodeAttribute($value)
    {
        return $value ?: $this->attributes['District_code'] ?? null;
    }
    
    public function getWardAttribute($value)
    {
        return $value ?: $this->attributes['Ward'] ?? null;
    }
    
    public function getWardCodeAttribute($value)
    {
        return $value ?: $this->attributes['Ward_code'] ?? null;
    }

    // Đặt các cột cần được kiểu hóa
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
}
