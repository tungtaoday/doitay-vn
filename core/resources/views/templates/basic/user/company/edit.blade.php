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
                            <img src="{{ getCompanyAvatar($company) }}" alt="{{ $company->name }}">
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2>Chỉnh sửa thông tin thợ</h2>
                        <p>Cập nhật thông tin và dịch vụ của {{ $company->name }}</p>
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

                <!-- Company Form -->
                <div class="profile-form-container">
                    <form action="{{ route('user.company.update', $company->id) }}" method="post"
                        enctype="multipart/form-data" class="modern-form disableSubmission">
                        @csrf
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h4><i class="las la-store me-2"></i>Thông tin cơ bản</h4>
                                <p>Cập nhật thông tin cơ bản của thợ/đơn vị kinh doanh</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-image"></i>
                                                @lang('Hình ảnh đại diện')
                                            </label>
                                            <div class="image-upload-wrapper">
                                                <div class="image-preview" id="imagePreview">
                                                    <img src="{{ getCompanyAvatar($company) }}" alt="Company Image">
                                                </div>
                                                <div class="upload-overlay">
                                                    <input type="file" class="image-input" name="image" id="companyImage" accept=".png, .jpg, .jpeg">
                                                    <label for="companyImage" class="upload-btn">
                                                        <i class="las la-camera"></i>
                                                        <span>Thay đổi ảnh</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-user"></i>
                                                @lang('Tên cá nhân hoặc Đơn vị kinh doanh')
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="text" name="name" class="modern-input"
                                                    value="{{ $company->name }}" required placeholder="Nhập tên thợ/đơn vị">
                                                <div class="input-focus"></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-tools"></i>
                                                @lang('Dịch vụ cung cấp chính')
                                            </label>
                                            <div class="select-wrapper">
                                                <select name="category" class="modern-select" required>
                                                    <option value="" disabled>@lang('Chọn dịch vụ')</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}" @selected($item->id == $company->category_id)>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="select-arrow">
                                                    <i class="las la-chevron-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h4><i class="las la-phone me-2"></i>Thông tin liên hệ</h4>
                                <p>Cập nhật thông tin liên hệ và địa chỉ</p>
                            </div>
                            
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="modern-label">
                                                <i class="las la-globe"></i>
                                                @lang('Website')
                                            </label>
                                            <div class="input-wrapper">
                                                <input type="url" name="url" class="modern-input"
                                                    value="{{ $company->url }}" placeholder="https://example.com">
                                                <div class="input-focus"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Email công việc') </label>
                                        <input type="email" name="email" class="form--control"
                                            value="{{ $company->email }}" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Địa chỉ') </label>
                                        <input type="text" name="address" class="form--control"
                                            value="{{ $company->address }}" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Thành phố/Tỉnh') </label>
                                        <select name="city" class="form--control" id="city" required>
                                            <option value="">@lang('Chọn Tỉnh/Thành phố')</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->city }}" {{ $company->city == $city->city ? 'selected' : '' }}>{{ $city->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Quận/Huyện') </label>
                                        <select name="district" class="form--control" id="district" required>
                                            <option value="">@lang('Chọn Quận/Huyện')</option>
                                            @if(isset($districts))
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->district }}" {{ $company->district == $district->district ? 'selected' : '' }}>{{ $district->district }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Phường/Xã') </label>
                                        <select name="ward" class="form--control" id="ward" required>
                                            <option value="">@lang('Chọn Phường/Xã')</option>
                                            @if(isset($wards))
                                                @foreach($wards as $ward)
                                                    <option value="{{ $ward->ward }}" {{ $company->ward == $ward->ward ? 'selected' : '' }}>{{ $ward->ward }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <div class="position-relative">
                                            <label>@lang('Tags') </label>
                                            <select name="tags[]" class="form--control select2 select2-auto-tokenize"
                                                multiple="multiple" required>
                                                @if($company->tags && is_array($company->tags))
                                                    @foreach ($company->tags as $item)
                                                        <option value="{{ $item }}" selected>{{ $item }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <small class="tag-text">@lang('Ngăn cách nhiều từ khóa bằng dấu ') <code>@lang('(,)')</code>
                                                @lang('hoặc nhấn') <code>@lang('Enter')</code>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Mô tả') </label>
                                        <textarea name="description" class="form--control" required>{{ $company->description }}</textarea>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Chứng chỉ & Kinh nghiệm') </label>
                                        <div class="certificate-container">
                                            @if(!empty($certificates))
                                                @foreach($certificates as $i => $certificate)
                                                    <div class="certificate-entry mb-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="text" name="certificates[{{ $i }}][name]" class="form--control"
                                                                    placeholder="@lang('Tên chứng chỉ')" value="{{ $certificate['name'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="number" name="certificates[{{ $i }}][year]" class="form--control"
                                                                    placeholder="@lang('Năm cấp')" min="1900" max="{{ date('Y') }}"
                                                                    value="{{ $certificate['year'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn--danger btn-sm remove-certificate"><i class="la la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="certificate-entry mb-3">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" name="certificates[0][name]" class="form--control"
                                                                placeholder="@lang('Tên chứng chỉ')">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" name="certificates[0][year]" class="form--control"
                                                                placeholder="@lang('Năm cấp')" min="1900" max="{{ date('Y') }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn--danger btn-sm remove-certificate"><i class="la la-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn--base btn-sm mt-2 add-certificate"><i class="la la-plus"></i> @lang('Thêm chứng chỉ')</button>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Dự án tiêu biểu') </label>
                                        <div class="project-container">
                                            @if(!empty($projects))
                                                @foreach($projects as $i => $project)
                                                    <div class="project-entry mb-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="text" name="projects[{{ $i }}][title]" class="form--control"
                                                                    placeholder="@lang('Tiêu đề dự án')" value="{{ $project['title'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="profile-thumb">
                                                                    <div class="avatar-preview">
                                                                        <div class="projectPicPreview"
                                                                            style="background-image: url('{{ isset($project['image']) ? asset('assets/images/portfolio/' . $project['image']) : getImage('', '300x200') }}');">
                                                                        </div>
                                                                        <div class="avatar-edit">
                                                                            <input type='file' class="projectPicUpload" name="projects[{{ $i }}][image]"
                                                                                id="projectPicUpload{{ $i }}" accept=".png, .jpg, .jpeg" />
                                                                            <label for="projectPicUpload{{ $i }}" class="btn btn--base btn-sm mb-0"><i class="la la-camera"></i></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 mt-2">
                                                                <textarea name="projects[{{ $i }}][description]" class="form--control"
                                                                    placeholder="@lang('Mô tả dự án')" rows="4">{{ $project['description'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="col-md-12 text-end">
                                                                <button type="button" class="btn btn--danger btn-sm remove-project"><i class="la la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="project-entry mb-4">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="text" name="projects[0][title]" class="form--control"
                                                                placeholder="@lang('Tiêu đề dự án')">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="profile-thumb">
                                                                <div class="avatar-preview">
                                                                    <div class="projectPicPreview"
                                                                        style="background-image: url('{{ getImage('', '300x200') }}');">
                                                                    </div>
                                                                    <div class="avatar-edit">
                                                                        <input type='file' class="projectPicUpload" name="projects[0][image]"
                                                                            id="projectPicUpload0" accept=".png, .jpg, .jpeg" />
                                                                        <label for="projectPicUpload0" class="btn btn--base btn-sm mb-0"><i class="la la-camera"></i></label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 mt-2">
                                                            <textarea name="projects[0][description]" class="form--control"
                                                                placeholder="@lang('Mô tả dự án')" rows="4"></textarea>
                                                        </div>
                                                        <div class="col-md-12 text-end">
                                                            <button type="button" class="btn btn--danger btn-sm remove-project"><i class="la la-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn--base btn-sm mt-2 add-project"><i class="la la-plus"></i> @lang('Thêm dự án')</button>
                                    </div>
                        <!-- Submit Section -->
                        <div class="form-actions">
                            <a href="{{ route('user.profile.view') }}" class="btn btn-secondary btn-lg">
                                <i class="las la-times me-2"></i>Hủy bỏ
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="las la-save me-2"></i>Cập nhật thông tin
                            </button>
                        </div>
                                </div>
                            </div>
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

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
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
            overflow: hidden;
        }

        .avatar-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
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
            margin: 0 0 15px 0;
            font-size: 16px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .status-badge.warning {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .profile-actions .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
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

        .modern-input:focus + .input-focus {
            transform: scaleX(1);
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

        .select-arrow {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        /* Image Upload */
        .image-upload-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            padding: 20px 10px 10px;
        }

        .image-input {
            display: none;
        }

        .upload-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
        }

        /* Form Actions */
        .form-actions {
            padding: 30px 40px;
            background: #f8f9fa;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #218838, #1abc9c);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-lg {
            padding: 14px 28px;
            font-size: 16px;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Legacy form elements */
        .form--control {
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 0px !important;
        }
        .certificate-entry, .project-entry {
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }
        .certificate-entry .row, .project-entry .row {
            align-items: center;
        }
        .projectPicPreview {
            width: 100%;
            height: 100px;
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .avatar-edit .btn-sm {
            padding: 5px 10px;
        }
        .remove-certificate, .remove-project {
            display: none;
        }
        .certificate-entry:not(:first-child) .remove-certificate,
        .project-entry:not(:first-child) .remove-project {
            display: inline-block;
        }
        .add-certificate, .add-project {
            background-color: #007bff;
            color: #fff;
        }
        .add-certificate:hover, .add-project:hover {
            background-color: #0056b3;
        }
        
        /* Image Upload Styles */
        .image-upload-wrapper {
            position: relative;
            display: inline-block;
            width: 150px;
            height: 150px;
        }
        
        .image-preview {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            position: relative;
        }
        
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        .upload-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            padding: 8px;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .image-upload-wrapper:hover .upload-overlay {
            opacity: 1;
        }
        
        .upload-btn {
            color: white;
            font-size: 12px;
            cursor: pointer;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .image-input {
            display: none;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.select2').select2();

            $(".select2-auto-tokenize").select2({
                tags: true,
                tokenSeparators: [',']
            });

            function companyProfilePhoto(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".profilePicUpload").on('change', function() {
                companyProfilePhoto(this);
            });

            // Handle project image upload
            function projectProfilePhoto(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.profile-thumb').find('.projectPicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).on('change', '.projectPicUpload', function() {
                projectProfilePhoto(this);
            });

            // Handle company image upload
            $('#companyImage').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Add/Remove Certificate functionality
            var certificateCount = {{ count($certificates ?? []) > 0 ? count($certificates) : 1 }};
            
            $('.add-certificate').on('click', function() {
                var newCertificate = `
                    <div class="certificate-entry mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="certificates[${certificateCount}][name]" class="form--control"
                                    placeholder="@lang('Tên chứng chỉ')">
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="certificates[${certificateCount}][year]" class="form--control"
                                    placeholder="@lang('Năm cấp')" min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn--danger btn-sm remove-certificate"><i class="la la-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                $('.certificate-container').append(newCertificate);
                certificateCount++;
            });

            $(document).on('click', '.remove-certificate', function() {
                $(this).closest('.certificate-entry').remove();
            });

            // Add/Remove Project functionality
            var projectCount = {{ count($projects ?? []) > 0 ? count($projects) : 1 }};
            
            $('.add-project').on('click', function() {
                var newProject = `
                    <div class="project-entry mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="projects[${projectCount}][title]" class="form--control"
                                    placeholder="@lang('Tiêu đề dự án')">
                            </div>
                            <div class="col-md-6">
                                <div class="profile-thumb">
                                    <div class="avatar-preview">
                                        <div class="projectPicPreview"
                                            style="background-image: url('{{ getImage('', '300x200') }}');">
                                        </div>
                                        <div class="avatar-edit">
                                            <input type='file' class="projectPicUpload" name="projects[${projectCount}][image]"
                                                id="projectPicUpload${projectCount}" accept=".png, .jpg, .jpeg" />
                                            <label for="projectPicUpload${projectCount}" class="btn btn--base btn-sm mb-0"><i class="la la-camera"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <textarea name="projects[${projectCount}][description]" class="form--control"
                                    placeholder="@lang('Mô tả dự án')" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn--danger btn-sm remove-project"><i class="la la-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                $('.project-container').append(newProject);
                projectCount++;
            });

            $(document).on('click', '.remove-project', function() {
                $(this).closest('.project-entry').remove();
            });

            // Debug form submission
            $('form.modern-form').on('submit', function(e) {
                console.log('🚀 Form submitting...');
                console.log('Form data:', new FormData(this));
                console.log('Form action:', $(this).attr('action'));
                console.log('Form method:', $(this).attr('method'));
                
                // Check if all required fields are filled
                let missingFields = [];
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        missingFields.push($(this).attr('name') || $(this).attr('id'));
                    }
                });
                
                if (missingFields.length > 0) {
                    console.log('❌ Missing required fields:', missingFields);
                } else {
                    console.log('✅ All required fields filled');
                }
            });
        })(jQuery);
    </script>
@endpush

@php
    $certificates = old('certificates');
    if (!$certificates && isset($company->certificates)) {
        $certificates = is_array($company->certificates)
            ? $company->certificates
            : (is_string($company->certificates) ? json_decode($company->certificates, true) : []);
    }

    $projects = old('projects');
    if (!$projects && isset($company->projects)) {
        $projects = is_array($company->projects)
            ? $company->projects
            : (is_string($company->projects) ? json_decode($company->projects, true) : []);
    }
@endphp
