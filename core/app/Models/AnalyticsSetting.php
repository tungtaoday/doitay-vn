<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalyticsSetting extends Model
{
    protected $fillable = [
        'google_analytics_id',
        'facebook_pixel_id',
        'analytics_enabled',
        'track_appointments',
        'track_appointment_status',
        'track_company_views',
        'track_company_contacts',
        'track_user_registration',
        'track_user_login',
        'track_search',
        'track_scroll_depth',
        'enhanced_ecommerce',
        'custom_dimensions',
        'analytics_debug',
        'gdpr_compliance'
    ];

    protected $casts = [
        'analytics_enabled' => 'boolean',
        'track_appointments' => 'boolean',
        'track_appointment_status' => 'boolean',
        'track_company_views' => 'boolean',
        'track_company_contacts' => 'boolean',
        'track_user_registration' => 'boolean',
        'track_user_login' => 'boolean',
        'track_search' => 'boolean',
        'track_scroll_depth' => 'boolean',
        'enhanced_ecommerce' => 'boolean',
        'custom_dimensions' => 'boolean',
        'analytics_debug' => 'boolean',
        'gdpr_compliance' => 'boolean'
    ];

    /**
     * Get the first analytics settings record
     */
    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    /**
     * Check if analytics is enabled
     */
    public function isEnabled()
    {
        return $this->analytics_enabled && !empty($this->google_analytics_id);
    }

    /**
     * Check if specific tracking is enabled
     */
    public function isTrackingEnabled($type)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $trackingMap = [
            'appointments' => 'track_appointments',
            'appointment_status' => 'track_appointment_status',
            'company_views' => 'track_company_views',
            'company_contacts' => 'track_company_contacts',
            'user_registration' => 'track_user_registration',
            'user_login' => 'track_user_login',
            'search' => 'track_search',
            'scroll_depth' => 'track_scroll_depth'
        ];

        return isset($trackingMap[$type]) ? $this->{$trackingMap[$type]} : false;
    }

    /**
     * Get Google Analytics ID
     */
    public function getGoogleAnalyticsId()
    {
        return $this->google_analytics_id;
    }

    /**
     * Get Facebook Pixel ID
     */
    public function getFacebookPixelId()
    {
        return $this->facebook_pixel_id;
    }

    /**
     * Check if enhanced ecommerce is enabled
     */
    public function isEnhancedEcommerceEnabled()
    {
        return $this->enhanced_ecommerce;
    }

    /**
     * Check if custom dimensions are enabled
     */
    public function isCustomDimensionsEnabled()
    {
        return $this->custom_dimensions;
    }

    /**
     * Check if debug mode is enabled
     */
    public function isDebugEnabled()
    {
        return $this->analytics_debug;
    }

    /**
     * Check if GDPR compliance is enabled
     */
    public function isGdprCompliant()
    {
        return $this->gdpr_compliance;
    }
} 
 