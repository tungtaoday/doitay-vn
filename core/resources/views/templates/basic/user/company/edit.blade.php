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
                <div class="profile-form-container edit-form-buttons">
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
                                    
                                    <!-- Experience Field -->
                                    <div class="col-lg-6 form-group">
                                        <label class="modern-label">
                                            <i class="las la-clock"></i>
                                            @lang('Kinh nghiệm làm việc')
                                        </label>
                                        <div class="select-wrapper">
                                            <select name="experience" class="modern-select" required>
                                                <option value="">@lang('Chọn kinh nghiệm')</option>
                                                <option value="0" @selected($company->experience == 0)>0-1 năm</option>
                                                <option value="2" @selected($company->experience == 2)>2-3 năm</option>
                                                <option value="5" @selected($company->experience == 5)>5-7 năm</option>
                                                <option value="8" @selected($company->experience == 8)>8-10 năm</option>
                                                <option value="10" @selected($company->experience == 10)>Trên 10 năm</option>
                                            </select>
                                            <div class="select-arrow">
                                                <i class="las la-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tags Field -->
                                    <div class="col-lg-6 form-group">
                                        <label class="modern-label">
                                            <i class="las la-tags"></i>
                                            @lang('Từ khóa dịch vụ')
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="text" name="tags_input" class="modern-input"
                                                value="{{ is_array($company->tags) ? implode(', ', $company->tags) : ($company->tags ?? '') }}"
                                                placeholder="@lang('VD: sửa chữa, thi công, bảo trì')">
                                            <div class="input-focus"></div>
                                        </div>
                                        <small class="form-text text-muted">@lang('Phân cách bằng dấu phẩy')</small>
                                    </div>
                                    
                                    <!-- Services Section -->
                                    <div class="col-lg-12 form-group">
                                        <label class="modern-label">
                                            <i class="las la-concierge-bell"></i>
                                            @lang('Dịch vụ cung cấp')
                                        </label>
                                        <div class="services-container">
                                            @if(!empty($company->services))
                                                @foreach($company->services as $i => $service)
                                                    <div class="service-entry mb-3">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="text" name="services[{{ $i }}][name]" class="form--control"
                                                                    placeholder="@lang('Tên dịch vụ')" value="{{ $service['name'] ?? '' }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" name="services[{{ $i }}][price]" class="form--control"
                                                                    placeholder="@lang('Giá dịch vụ')" value="{{ $service['price'] ?? '' }}">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <textarea name="services[{{ $i }}][description]" class="form--control"
                                                                    placeholder="@lang('Mô tả dịch vụ')" rows="2">{{ $service['description'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn--danger btn-sm remove-service"><i class="la la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="service-entry mb-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <input type="text" name="services[0][name]" class="form--control"
                                                                placeholder="@lang('Tên dịch vụ')" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="services[0][price]" class="form--control"
                                                                placeholder="@lang('Giá dịch vụ')">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <textarea name="services[0][description]" class="form--control"
                                                                placeholder="@lang('Mô tả dịch vụ')" rows="2"></textarea>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn--danger btn-sm remove-service"><i class="la la-trash"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn--base btn-sm mt-2 add-service" style="display: inline-block !important; background-color: #28a745 !important; color: #fff !important; border: 1px solid #28a745 !important; padding: 8px 16px !important; border-radius: 4px !important; cursor: pointer !important;"><i class="la la-plus"></i> @lang('Thêm dịch vụ')</button>
                                    </div>
                                    
                                    <!-- Business Hours Section -->
                                    <div class="col-lg-12 form-group">
                                        <label class="modern-label">
                                            <i class="las la-clock"></i>
                                            @lang('Giờ làm việc')
                                        </label>
                                        <div class="business-hours-container">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('Ngày thường')</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input type="time" name="business_hours[weekdays][start]" class="form--control"
                                                                value="{{ $company->business_hours['weekdays']['start'] ?? '08:00' }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="time" name="business_hours[weekdays][end]" class="form--control"
                                                                value="{{ $company->business_hours['weekdays']['end'] ?? '18:00' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('Thứ 7')</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input type="time" name="business_hours[saturday][start]" class="form--control"
                                                                value="{{ $company->business_hours['saturday']['start'] ?? '08:00' }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="time" name="business_hours[saturday][end]" class="form--control"
                                                                value="{{ $company->business_hours['saturday']['end'] ?? '16:00' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('Chủ nhật')</label>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="business_hours[sunday][status]" class="form--control">
                                                                <option value="open" @selected(($company->business_hours['sunday']['status'] ?? 'closed') == 'open')>@lang('Mở cửa')</option>
                                                                <option value="closed" @selected(($company->business_hours['sunday']['status'] ?? 'closed') == 'closed')>@lang('Đóng cửa')</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="time" name="business_hours[sunday][start]" class="form--control"
                                                                value="{{ $company->business_hours['sunday']['start'] ?? '09:00' }}"
                                                                @if(($company->business_hours['sunday']['status'] ?? 'closed') == 'closed') disabled @endif>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="time" name="business_hours[sunday][end]" class="form--control"
                                                                value="{{ $company->business_hours['sunday']['end'] ?? '15:00' }}"
                                                                @if(($company->business_hours['sunday']['status'] ?? 'closed') == 'closed') disabled @endif>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">@lang('24/7')</label>
                                                    <div class="form-check">
                                                        <input type="checkbox" name="business_hours[24_7]" class="form-check-input" value="1"
                                                            @checked(($company->business_hours['24_7'] ?? false) == true)>
                                                        <label class="form-check-label">@lang('Hoạt động 24/7')</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
        .certificate-entry, .project-entry, .service-entry {
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }
        
        .service-entry .row {
            align-items: center;
        }
        
        /* Ensure buttons are visible and clickable */
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        
        .btn--danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }
        
        .btn--danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        
        .business-hours-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        
        .business-hours-container .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .remove-service {
            display: none;
        }
        
        .service-entry:not(:first-child) .remove-service {
            display: inline-block;
        }
        
        .add-service {
            background-color: #007bff !important;
            color: #fff !important;
            border: 1px solid #007bff !important;
            padding: 8px 16px !important;
            border-radius: 4px !important;
            font-size: 14px !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-block !important;
            text-decoration: none !important;
            margin-top: 8px !important;
            margin-bottom: 8px !important;
        }
        
        .add-service:hover {
            background-color: #0056b3 !important;
            border-color: #0056b3;
            transform: translateY(-1px);
        }
        
        .btn--base {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }
        
        .btn--base:hover {
            background-color: #0056b3;
            border-color: #0056b3;
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
                        $('#imagePreview img').attr('src', e.target.result);
                    }
                    
                    reader.readAsDataURL(input.files[0]);
                }
            }
            
            // Services Management
            let serviceIndex = $('.service-entry').length;
            
            console.log('Service index initialized:', serviceIndex);
            console.log('Add service button found:', $('.add-service').length);
            
            $('.add-service').on('click', function() {
                console.log('Add service button clicked!');
                console.log('Current service index:', serviceIndex);
                serviceIndex++;
                const newService = `
                    <div class="service-entry mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="services[${serviceIndex}][name]" class="form--control"
                                    placeholder="@lang('Tên dịch vụ')" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="services[${serviceIndex}][price]" class="form--control"
                                    placeholder="@lang('Giá dịch vụ')">
                            </div>
                            <div class="col-md-3">
                                <textarea name="services[${serviceIndex}][description]" class="form--control"
                                    placeholder="@lang('Mô tả dịch vụ')" rows="2"></textarea>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn--danger btn-sm remove-service"><i class="la la-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                $('.services-container').append(newService);
            });
            
            $(document).on('click', '.remove-service', function() {
                $(this).closest('.service-entry').remove();
            });
            
            // Business Hours Management
            $('select[name="business_hours[sunday][status]"]').on('change', function() {
                const isOpen = $(this).val() === 'open';
                $('input[name="business_hours[sunday][start]"]').prop('disabled', !isOpen);
                $('input[name="business_hours[sunday][end]"]').prop('disabled', !isOpen);
            });
            
            // 24/7 Toggle
            $('input[name="business_hours[24_7]"]').on('change', function() {
                const is247 = $(this).is(':checked');
                if (is247) {
                    $('input[name="business_hours[weekdays][start]"], input[name="business_hours[weekdays][end]"]').prop('disabled', true);
                    $('input[name="business_hours[saturday][start]"], input[name="business_hours[saturday][end]"]').prop('disabled', true);
                    $('select[name="business_hours[sunday][status]"]').prop('disabled', true);
                    $('input[name="business_hours[sunday][start]"], input[name="business_hours[sunday][end]"]').prop('disabled', true);
                } else {
                    $('input[name="business_hours[weekdays][start]"], input[name="business_hours[weekdays][end]"]').prop('disabled', false);
                    $('input[name="business_hours[saturday][start]"], input[name="business_hours[saturday][end]"]').prop('disabled', false);
                    $('select[name="business_hours[sunday][status]"]').prop('disabled', false);
                    // Re-check Sunday status
                    const sundayStatus = $('select[name="business_hours[sunday][status]"]').val();
                    if (sundayStatus === 'open') {
                        $('input[name="business_hours[sunday][start]"], input[name="business_hours[sunday][end]"]').prop('disabled', false);
                    }
                }
            });
            
            // Initialize 24/7 state
            if ($('input[name="business_hours[24_7]"]').is(':checked')) {
                $('input[name="business_hours[weekdays][start]"], input[name="business_hours[weekdays][end]"]').prop('disabled', true);
                $('input[name="business_hours[saturday][start]"], input[name="business_hours[saturday][end]"]').prop('disabled', true);
                $('select[name="business_hours[sunday][status]"]').prop('disabled', true);
                $('input[name="business_hours[sunday][start]"], input[name="business_hours[sunday][end]"]').prop('disabled', true);
            }
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
