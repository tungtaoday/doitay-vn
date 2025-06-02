<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anotification extends Model
{
    use HasFactory;
    protected $table = 'a_notifications';

    protected $fillable = [
        'user_id', 'company_id', 'appointment_id', 'title', 'message', 
        'type_id', 'is_read', 'notifiable_id', 'notifiable_type', 
        'created_at', 'updated_at'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }
    public function notification()
    {
        return $this->belongsTo(\Illuminate\Notifications\DatabaseNotification::class, 'notification_id');
    }
    // Định nghĩa quan hệ
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

}
