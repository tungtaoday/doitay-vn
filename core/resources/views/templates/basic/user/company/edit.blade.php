@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-50 pb-50 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center ">
                <div class="col-lg-10">
                    <form action="{{ route('user.company.update', $company->id) }}" method="post"
                        enctype="multipart/form-data" class="disableSubmission">
                        @csrf
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">@lang('Update Company Info')</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="required">@lang('Image') </label>
                                            <div class="profile-thumb justify-content-center">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url('{{ getImage(getFilePath('company') . '/' . $company->image, getFileSize('company')) }}');">
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type='file' class="profilePicUpload" name="image"
                                                            id="profilePicUpload1" accept=".png, .jpg, .jpeg" />
                                                        <label for="profilePicUpload1" class="btn btn--base mb-0"><i
                                                                class="las la-camera"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>@lang('Tên cá nhân hoặc Đơn vị kinh doanh') </label>
                                            <input type="text" name="name" class="form--control"
                                                value="{{ $company->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Dịch vụ cung cấp chính') </label>
                                            <select name="category"
                                                class="form-control form--control select2" required>
                                                <option value="" disabled>@lang('Select One')</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}" @selected($item->id == $company->category_id)>{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Website') </label>
                                        <input type="url" name="url" class="form--control"
                                            value="{{ $company->url }}">
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
                                                @if($company->tags)
                                                    @foreach (json_decode($company->tags) as $item)
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
                                                                            style="background-image: url('{{ isset($project['image']) ? asset('uploads/projects/' . $project['image']) : getImage('', '300x200') }}');">
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
                                    <button type="submit" class="btn btn--base w-100">
                                        <i class="la la-telegram-plane"></i>
                                        @lang('Cập nhật thông tin')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="has--link">
                    <div class="d-flex justify-content-center mt-5">
                        @php echo getAdvertisement('728x90'); @endphp
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
