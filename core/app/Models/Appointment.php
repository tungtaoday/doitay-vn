<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';

    protected $fillable = [
        'user_id',
        'company_id',
        'service_request_id',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'appointment_date',
        'appointment_time',
        'notes',
        'status',
    ];
    
    // Đảm bảo rằng kiểu dữ liệu phù hợp
    // protected $casts = [
    //     'appointment_date' => 'date',   // Chuyển đổi sang dạng DATE
    //     'appointment_time' => 'time',   // Chuyển đổi sang dạng TIME
    // ];
    
    // Định nghĩa quan hệ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function notifiable()
    {
        return $this->morphTo();
    }

}

