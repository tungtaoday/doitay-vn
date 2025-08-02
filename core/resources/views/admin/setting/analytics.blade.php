@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label><strong>📊 Google Analytics Configuration</strong></label>
                                    <div class="alert alert-info">
                                        <i class="las la-info-circle"></i>
                                        <strong>Hướng dẫn:</strong> Để lấy Google Analytics ID, truy cập 
                                        <a href="https://analytics.google.com" target="_blank">Google Analytics</a> → 
                                        Admin → Data Streams → Web Stream → Measurement ID
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label>Google Analytics ID</label>
                                    <input class="form-control" type="text" name="google_analytics_id" 
                                           value="{{ analytics('google_analytics_id') }}" 
                                           placeholder="G-XXXXXXXXXX">
                                    <small class="text-muted">Ví dụ: G-XXXXXXXXXX hoặc GA_XXXXXXXXX</small>
                                </div>
                            </div>
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label>Facebook Pixel ID</label>
                                    <input class="form-control" type="text" name="facebook_pixel_id" 
                                           value="{{ analytics('facebook_pixel_id') }}" 
                                           placeholder="123456789012345">
                                    <small class="text-muted">Tùy chọn: Facebook Pixel ID cho tracking</small>
                                </div>
                            </div>
                            
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label>Tracking Status</label>
                                    <div class="custom-switch">
                                        <input type="checkbox" id="analytics_enabled" name="analytics_enabled" 
                                               value="1" {{ analytics('analytics_enabled') ? 'checked' : '' }}>
                                        <label for="analytics_enabled">Enable Google Analytics Tracking</label>
                                    </div>
                                    <small class="text-muted">Bật/tắt tracking Google Analytics trên website</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="las la-chart-line"></i>
                                            Tracking Events Configuration
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>📅 Appointment Tracking</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_appointments" 
                                                           value="1" {{ analytics('track_appointments') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track appointment bookings</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_appointment_status" 
                                                           value="1" {{ analytics('track_appointment_status') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track appointment status changes</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h6>🏢 Company Tracking</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_company_views" 
                                                           value="1" {{ analytics('track_company_views') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track company profile views</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_company_contacts" 
                                                           value="1" {{ analytics('track_company_contacts') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track company contact actions</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h6>👤 User Tracking</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_user_registration" 
                                                           value="1" {{ analytics('track_user_registration') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track user registrations</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_user_login" 
                                                           value="1" {{ analytics('track_user_login') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track user logins</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <h6>🔍 Search & Engagement</h6>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_search" 
                                                           value="1" {{ analytics('track_search') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track search functionality</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="track_scroll_depth" 
                                                           value="1" {{ analytics('track_scroll_depth') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Track scroll depth</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="las la-cog"></i>
                                            Advanced Settings
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Enhanced Ecommerce</label>
                                                    <div class="custom-switch">
                                                        <input type="checkbox" id="enhanced_ecommerce" name="enhanced_ecommerce" 
                                                               value="1" {{ analytics('enhanced_ecommerce') ? 'checked' : '' }}>
                                                        <label for="enhanced_ecommerce">Enable Enhanced Ecommerce</label>
                                                    </div>
                                                    <small class="text-muted">Track appointments as transactions</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Custom Dimensions</label>
                                                    <div class="custom-switch">
                                                        <input type="checkbox" id="custom_dimensions" name="custom_dimensions" 
                                                               value="1" {{ analytics('custom_dimensions') ? 'checked' : '' }}>
                                                        <label for="custom_dimensions">Enable Custom Dimensions</label>
                                                    </div>
                                                    <small class="text-muted">Track user_type, page_category, action_type</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Debug Mode</label>
                                                    <div class="custom-switch">
                                                        <input type="checkbox" id="analytics_debug" name="analytics_debug" 
                                                               value="1" {{ analytics('analytics_debug') ? 'checked' : '' }}>
                                                        <label for="analytics_debug">Enable Debug Mode</label>
                                                    </div>
                                                    <small class="text-muted">Log tracking events to console (development only)</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>GDPR Compliance</label>
                                                    <div class="custom-switch">
                                                        <input type="checkbox" id="gdpr_compliance" name="gdpr_compliance" 
                                                               value="1" {{ analytics('gdpr_compliance') ? 'checked' : '' }}>
                                                        <label for="gdpr_compliance">Enable GDPR Compliance</label>
                                                    </div>
                                                    <small class="text-muted">Respect user privacy settings</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn--primary w-100 h-45">
                                <i class="las la-save"></i>
                                @lang('Save Settings')
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        
        <!-- Analytics Status Card -->
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="las la-chart-bar"></i>
                        Analytics Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="status-item">
                                <span class="status-label">Google Analytics ID:</span>
                                <span class="status-value {{ analytics('google_analytics_id') && analytics('google_analytics_id') != 'GA-XXXXXXXXX' ? 'text-success' : 'text-danger' }}">
                                    {{ analytics('google_analytics_id') ?: 'Not configured' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item">
                                <span class="status-label">Tracking Status:</span>
                                <span class="status-value {{ analytics('analytics_enabled') ? 'text-success' : 'text-warning' }}">
                                    {{ analytics('analytics_enabled') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item">
                                <span class="status-label">Enhanced Ecommerce:</span>
                                <span class="status-value {{ analytics('enhanced_ecommerce') ? 'text-success' : 'text-muted' }}">
                                    {{ analytics('enhanced_ecommerce') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="status-item">
                                <span class="status-label">Custom Dimensions:</span>
                                <span class="status-value {{ analytics('custom_dimensions') ? 'text-success' : 'text-muted' }}">
                                    {{ analytics('custom_dimensions') ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
.status-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.status-item:last-child {
    border-bottom: none;
}

.status-label {
    font-weight: 600;
    color: #333;
}

.status-value {
    font-weight: 500;
}

.custom-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.custom-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.custom-switch label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.custom-switch label:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

.custom-switch input:checked + label {
    background-color: #2196F3;
}

.custom-switch input:checked + label:before {
    transform: translateX(26px);
}
</style>
@endpush 
 