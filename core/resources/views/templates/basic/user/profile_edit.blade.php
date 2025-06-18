@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="profile-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <img src="{{ getUserAvatar($user) }}" alt="Profile">
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2>Cập nhật thông tin</h2>
                        <p>Chỉnh sửa và cập nhật thông tin tài khoản của bạn</p>
                        <div class="profile-status">
                            <span class="status-badge warning">
                                <i class="las la-edit"></i>
                                Chế độ chỉnh sửa
                            </span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('user.profile.view') }}" class="btn btn-outline-secondary">
                            <i class="las la-times me-2"></i>Hủy
                        </a>
                    </div>
                </div>

                <!-- Profile Form -->
                <div class="profile-form-container">
                    <form method="POST" action="{{ route('user.data.submit') }}" class="modern-form disableSubmission">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h4><i class="las la-user-edit me-2"></i>Thông tin cơ bản</h4>
                                <p>Cập nhật thông tin tài khoản và liên hệ</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-user"></i>
                                                @lang('Tên đăng nhập')
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" class="modern-input checkUser" required
                                                    name="username" value="{{ old('username', $user->username) }}"
                                                    placeholder="Nhập tên đăng nhập">
                                                <div class="input-focus"></div>
                                            </div>
                                            <small class="error-message usernameExist"></small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-phone"></i>
                                                @lang('Số điện thoại')
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="tel" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                                    class="modern-input checkUser" placeholder="Nhập số điện thoại"
                                                    pattern="[0-9]{10,11}">
                                                <div class="input-focus"></div>
                                            </div>
                                            <small class="error-message mobileExist"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h4><i class="las la-map-marker-alt me-2"></i>Thông tin địa chỉ</h4>
                                <p>Cập nhật địa chỉ để nhận dịch vụ tốt hơn</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-city"></i>
                                                @lang('Thành phố')
                                            </label>
                                            <div class="select-wrapper">
                                                <select id="city" class="modern-select" name="city" required>
                                                    <option value="">@lang('Chọn Thành phố')</option>
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="las la-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-building"></i>
                                                @lang('Quận/Huyện')
                                            </label>
                                            <div class="select-wrapper">
                                                <select id="district" class="modern-select" name="district" disabled required>
                                                    <option value="">@lang('Chọn Quận/Huyện')</option>
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="las la-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-home"></i>
                                                @lang('Phường/Xã')
                                            </label>
                                            <div class="select-wrapper">
                                                <select id="ward" class="modern-select" name="ward" disabled required>
                                                    <option value="">@lang('Chọn Phường/Xã')</option>
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="las la-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-map"></i>
                                                @lang('Địa chỉ chi tiết')
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" class="modern-input" name="address"
                                                    value="{{ old('address', $user->address) }}"
                                                    placeholder="Số nhà, tên đường...">
                                                <div class="input-focus"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Submit Section -->
                        <div class="form-actions">
                            <a href="{{ route('user.profile.view') }}" class="btn btn-secondary btn-lg me-3">
                                <i class="las la-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="las la-save me-2"></i>Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('style')
<style>
    .profile-section {
        background: linear-gradient(135deg, #102f4b 0%, #1a4568 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-header {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(16, 47, 75, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-avatar {
        flex-shrink: 0;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #48bbe2, #102f4b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.3);
    }

    .profile-info {
        flex: 1;
        min-width: 200px;
    }

    .profile-info h2 {
        color: #102f4b;
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .profile-info p {
        color: #6c757d;
        margin: 0;
        font-size: 16px;
    }

    .profile-actions .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .profile-actions .btn-outline-primary {
        border-color: #48bbe2;
        color: #48bbe2;
    }

    .profile-actions .btn-outline-primary:hover {
        background: #48bbe2;
        border-color: #48bbe2;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.3);
    }

    .profile-actions .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .profile-actions .btn-outline-secondary:hover {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    }

    .profile-form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(16, 47, 75, 0.1);
        overflow: hidden;
    }

    .form-section {
        padding: 40px;
        border-bottom: 1px solid #f1f3f7;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        margin-bottom: 30px;
    }

    .section-header h4 {
        color: #102f4b;
        font-size: 22px;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
    }

    .section-header h4 i {
        color: #48bbe2;
        margin-right: 8px;
    }

    .section-header p {
        color: #6c757d;
        margin: 0;
        font-size: 15px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .modern-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #102f4b;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .modern-label i {
        color: #48bbe2;
        margin-right: 8px;
        width: 18px;
    }

    .input-wrapper {
        position: relative;
    }

    .modern-input {
        width: 100%;
        padding: 16px 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 16px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        outline: none;
    }

    .modern-input:focus {
        border-color: #48bbe2;
        background: white;
        box-shadow: 0 0 0 4px rgba(72, 187, 226, 0.1);
    }

    .modern-input:focus + .input-focus {
        transform: scaleX(1);
    }

    .input-focus {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #48bbe2, #102f4b);
        transform: scaleX(0);
        transition: transform 0.3s ease;
        border-radius: 0 0 12px 12px;
    }

    .select-wrapper {
        position: relative;
    }

    .modern-select {
        width: 100%;
        padding: 16px 50px 16px 20px;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        font-size: 16px;
        background: #f8f9fa;
        transition: all 0.3s ease;
        outline: none;
        appearance: none;
        cursor: pointer;
    }

    .modern-select:focus {
        border-color: #48bbe2;
        background: white;
        box-shadow: 0 0 0 4px rgba(72, 187, 226, 0.1);
    }

    .modern-select:disabled {
        background: #f1f3f7;
        color: #6c757d;
        cursor: not-allowed;
    }

    .select-arrow {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        pointer-events: none;
        transition: color 0.3s ease;
    }

    .modern-select:focus + .select-arrow {
        color: #48bbe2;
    }

    .error-message {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
        display: block;
    }

    .expert-option {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 16px;
        padding: 24px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .expert-option:hover {
        border-color: #48bbe2;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.1);
    }

    .option-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .option-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #48bbe2, #102f4b);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }

    .option-text {
        flex: 1;
    }

    .option-text h5 {
        color: #102f4b;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 6px 0;
    }

    .option-text p {
        color: #6c757d;
        margin: 0;
        font-size: 14px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
        flex-shrink: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
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

    .slider:before {
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

    input:checked + .slider {
        background: linear-gradient(135deg, #48bbe2, #102f4b);
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #48bbe2;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .form-actions {
        padding: 40px;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .form-actions .btn {
        border-radius: 12px;
        padding: 16px 32px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        min-width: 160px;
    }

    .form-actions .btn-secondary {
        background: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    .form-actions .btn-secondary:hover {
        background: #5a6268;
        border-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(90, 98, 104, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #ffa500, #ff8c00);
        border: none;
        padding: 16px 40px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        color: white;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.3);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #ff8c00, #ff7700);
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(255, 165, 0, 0.4);
        color: white;
    }

    .form-actions .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
    }

    .form-actions .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .profile-header {
            text-align: center;
            flex-direction: column;
        }

        .profile-info {
            text-align: center;
        }

        .form-section {
            padding: 25px 20px;
        }

        .option-content {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .form-actions {
            padding: 30px 20px;
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }
    }

    /* Loading States */
    .modern-select:disabled {
        position: relative;
    }

    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #48bbe2;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced States */
    .expert-option.selected {
        border-color: #ffa500 !important;
        background: linear-gradient(135deg, #fff7e6 0%, #fff0d9 100%) !important;
        box-shadow: 0 8px 25px rgba(255, 165, 0, 0.2) !important;
    }

    .expert-option.selected .option-icon {
        background: linear-gradient(135deg, #ffa500, #ff8c00) !important;
        transform: scale(1.1);
    }

    .modern-input.is-invalid,
    .modern-select.is-invalid {
        border-color: #dc3545 !important;
        background: #fff5f5 !important;
        box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1) !important;
    }

    .input-wrapper.focused .input-focus {
        transform: scaleX(1);
    }

    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Smooth transitions for all interactive elements */
    .modern-input,
    .modern-select,
    .expert-option,
    .option-icon,
    .btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Success feedback */
    .success-feedback {
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        z-index: 1000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    }

    .success-feedback.show {
        transform: translateX(0);
    }

    /* Profile Status Badge */
    .profile-status {
        margin-top: 10px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-badge.success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-badge.warning {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-badge i {
        font-size: 14px;
    }
</style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.checkUser').on('focusout', function(e) {
                var value = $(this).val();
                var name = $(this).attr('name')
                checkUser(value, name);
            });

            function checkUser(value, name) {
                var url = '{{ route('user.checkUser') }}';
                var token = '{{ csrf_token() }}';

                if (name == 'mobile') {
                    var mobile = `${value}`;
                    var data = {
                        mobile: mobile,
                        mobile_code: $('.mobile-code').text().substr(1),
                        _token: token
                    }
                }
                if (name == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.field} already exist`);
                    } else {
                        $(`.${response.type}Exist`).text('');
                    }
                });
            }


        // Load cities với loading effect
        function loadCities() {
            $('#city').html('<option value="">Đang tải thành phố... <span class="loading-spinner"></span></option>');
            
            $.ajax({
                url: '/localtion/api/cities',
                type: 'GET',
                dataType: 'text',
                success: function(response) {
                    const cleanResponse = response.replace(/<!--|-->/g, '').trim();
                    
                    try {
                        const jsonResponse = JSON.parse(cleanResponse);
                        $('#city').empty().append('<option value="">Chọn Thành phố</option>');
                        jsonResponse.forEach(city => {
                            const isSelected = currentUserData.city_code === city.City_code ? 'selected' : '';
                            $('#city').append(`<option value="${city.City_code}" data-name="${city.City}" ${isSelected}>${city.City}</option>`);
                        });
                        
                        // Nếu có city được chọn, load districts
                        if ($('#city').val()) {
                            $('#city').trigger('change');
                        }
                        
                        // Hiệu ứng thành công
                        $('#city').parent().find('.select-arrow').html('<i class="las la-check" style="color: #28a745;"></i>');
                        setTimeout(() => {
                            $('#city').parent().find('.select-arrow').html('<i class="las la-chevron-down"></i>');
                        }, 2000);
                        
                    } catch (error) {
                        console.error("🚨 Lỗi phân tích JSON:", error);
                        $('#city').html('<option value="">Lỗi tải dữ liệu</option>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("🚨 Lỗi API cities:", textStatus, errorThrown);
                    $('#city').html('<option value="">Lỗi kết nối</option>');
                }
            });
        }
        
        // Dữ liệu hiện có của user
        const currentUserData = {
            city: '{{ $user->city ?? '' }}',
            district: '{{ $user->district ?? '' }}',
            ward: '{{ $user->ward ?? '' }}',
            city_code: '{{ $userLocationCodes['city_code'] ?? '' }}',
            district_code: '{{ $userLocationCodes['district_code'] ?? '' }}',
            ward_code: '{{ $userLocationCodes['ward_code'] ?? '' }}'
        };

        console.log('🔍 Current user data:', currentUserData);

        // Function to load cities
        function loadCities() {
            $.ajax({
                url: '/api/vietnam-locations/cities',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var citySelect = $('#city');
                    citySelect.empty().append('<option value="">Chọn Thành phố</option>');
                    
                    data.forEach(function(city) {
                        var cityCode = city.city_code || city.City_code;
                        var cityName = city.city || city.City;
                        var selected = currentUserData.city_code == cityCode ? 'selected' : '';
                        citySelect.append('<option value="' + cityCode + '" data-name="' + cityName + '" ' + selected + '>' + cityName + '</option>');
                    });
                    
                    // If city is pre-selected, load districts
                    if (currentUserData.city_code) {
                        loadDistricts(currentUserData.city_code);
                    }
                },
                error: function() {
                    console.error('Error loading cities');
                    $('#city').html('<option value="">Lỗi tải dữ liệu thành phố</option>');
                }
            });
        }
        
        // Function to load districts
        function loadDistricts(cityCode) {
            $.ajax({
                url: `/api/vietnam-locations/districts/${cityCode}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var districtSelect = $('#district');
                    districtSelect.empty().append('<option value="">Chọn Quận/Huyện</option>').prop('disabled', false);
                    
                    data.forEach(function(district) {
                        var districtCode = district.district_code || district.District_code;
                        var districtName = district.district || district.District;
                        var selected = currentUserData.district_code == districtCode ? 'selected' : '';
                        districtSelect.append('<option value="' + districtCode + '" data-name="' + districtName + '" ' + selected + '>' + districtName + '</option>');
                    });
                    
                    // If district is pre-selected, load wards  
                    if (currentUserData.district_code) {
                        loadWards(currentUserData.district_code);
                    }
                },
                error: function() {
                    console.error('Error loading districts');
                    $('#district').html('<option value="">Lỗi tải dữ liệu quận/huyện</option>');
                }
            });
        }
        
        // Function to load wards
        function loadWards(districtCode) {
            $.ajax({
                url: `/api/vietnam-locations/wards/${districtCode}`,
                type: 'GET', 
                dataType: 'json',
                success: function(data) {
                    var wardSelect = $('#ward');
                    wardSelect.empty().append('<option value="">Chọn Phường/Xã</option>').prop('disabled', false);
                    
                    data.forEach(function(ward) {
                        var wardCode = ward.ward_code || ward.Ward_code;
                        var wardName = ward.ward || ward.Ward;
                        var selected = currentUserData.ward_code == wardCode ? 'selected' : '';
                        wardSelect.append('<option value="' + wardCode + '" data-name="' + wardName + '" ' + selected + '>' + wardName + '</option>');
                    });
                },
                error: function() {
                    console.error('Error loading wards');
                    $('#ward').html('<option value="">Lỗi tải dữ liệu phường/xã</option>');
                }
            });
        }

        loadCities();

// Load districts when a city is selected
$('#city').change(function() {
    let cityCode = $(this).val();
    
    $('#district').empty().append('<option value="">Chọn Quận/Huyện</option>').prop('disabled', !cityCode);
    $('#ward').empty().append('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);

    if (cityCode) {
        $('#district').html('<option value="">Đang tải quận/huyện...</option>');
        loadDistricts(cityCode);
    }
});

// Load wards when a district is selected
$('#district').change(function() {
    let districtCode = $(this).val();
    $('#ward').empty().append('<option value="">Chọn Phường/Xã</option>').prop('disabled', !districtCode);

    if (districtCode) {
        $('#ward').html('<option value="">Đang tải phường/xã...</option>');
        loadWards(districtCode);
    }
});


// Form validation và submit
$('form').on('submit', function(e) {
    e.preventDefault();
    
    let isValid = true;
    let firstErrorField = null;
    
    // Validate required fields
    $('.modern-input[required], .modern-select[required]').each(function() {
        if (!$(this).val()) {
            isValid = false;
            if (!firstErrorField) firstErrorField = $(this);
            $(this).addClass('is-invalid');
            $(this).next('.error-message').remove();
            $(this).after('<small class="error-message">Vui lòng điền thông tin này</small>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.error-message').remove();
        }
    });
    
    if (!isValid) {
        firstErrorField.focus();
        return false;
    }
    
    // Show loading state
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.prop('disabled', true).html('<i class="las la-spinner la-spin me-2"></i>Đang xử lý...');
    
    let cityName = $('#city option:selected').data('name');
    let districtName = $('#district option:selected').data('name');
    let wardName = $('#ward option:selected').data('name');

    $('<input>').attr({
        type: 'hidden',
        name: 'City',
        value: cityName
    }).appendTo(this);

    $('<input>').attr({
        type: 'hidden',
        name: 'District',
        value: districtName
    }).appendTo(this);

    $('<input>').attr({
        type: 'hidden',
        name: 'Ward',
        value: wardName
    }).appendTo(this);

    // Reset form after 2 seconds (simulation)
    setTimeout(() => {
        this.submit();
    }, 1000);
});

// Input focus effects
$('.modern-input').on('focus', function() {
    $(this).parent().addClass('focused');
}).on('blur', function() {
    $(this).parent().removeClass('focused');
});

// Expert toggle animation
$('#registerAsExpert').change(function() {
    const expertOption = $('.expert-option');
    if ($(this).is(':checked')) {
        expertOption.addClass('selected');
    } else {
        expertOption.removeClass('selected');
    }
});

// Auto-save draft (optional enhancement)
let saveTimeout;
$('.modern-input, .modern-select').on('change', function() {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => {
        console.log('💾 Auto-saving draft...');
        // Implement auto-save logic here if needed
    }, 2000);
});

// Enhanced error styling
$('.checkUser').on('focusout', function() {
    setTimeout(() => {
        const errorMsg = $(this).siblings('.error-message').text();
        if (errorMsg && errorMsg.includes('exist')) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    }, 500);
});

        })(jQuery);
    </script>
@endpush

