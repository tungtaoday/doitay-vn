<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    protected $fillable = [
        'act',
        'name', 
        'subject',
        'push_title',
        'email_body',
        'sms_body',
        'push_body',
        'shortcodes',
        'email_status',
        'sms_status',
        'push_status',
        'email_sent_from_name',
        'email_sent_from_address',
        'sms_sent_from'
    ];

    protected $casts = [
        'shortcodes' => 'object'
    ];

}
