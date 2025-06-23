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
        'sms_sent_from',
        'flow_type',
        'flow_description',
        'priority',
        'is_scheduled',
        'scheduled_at',
        'recipient_criteria',
        'sent_count',
        'last_sent_at'
    ];

    protected $casts = [
        'shortcodes' => 'object',
        'recipient_criteria' => 'array',
        'is_scheduled' => 'boolean',
        'scheduled_at' => 'datetime',
        'last_sent_at' => 'datetime'
    ];

    // Scopes for different flows
    public function scopeAutoFlow($query)
    {
        return $query->where('flow_type', 'auto');
    }

    public function scopeMarketingFlow($query)
    {
        return $query->where('flow_type', 'marketing');
    }

    public function scopeSystemFlow($query)
    {
        return $query->where('flow_type', 'system');
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    public function scopeReadyToSend($query)
    {
        return $query->where('is_scheduled', true)
                    ->where('scheduled_at', '<=', now());
    }
}
