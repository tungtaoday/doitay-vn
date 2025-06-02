@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <section class="pt-50 pb-50 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <form class="create-company-form disableSubmission" action="{{ route('user.company.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">@lang('Cung cấp thông tin của bạn')</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <div class="form-group">
                                            <label>@lang('Image') </label>
                                            <div class="profile-thumb justify-content-center">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview"
                                                        style="background-image: url('{{ getImage('', getFileSize('company')) }}');">
                                                    </div>
                                                    <div class="avatar-edit">
                                                        <input type='file' class="profilePicUpload" name="image" id="profilePicUpload1"
                                                            accept=".png, .jpg, .jpeg" />
                                                        <label for="profilePicUpload1" class="btn btn--base mb-0"><i class="la la-camera"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>@lang('Tên cá nhân hoặc Đơn vị kinh doanh') </label>
                                            <input type="text" name="name" class="form--control" value="{{ old('name') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Dịch vụ cung cấp chính') </label>
                                            <select name="category" class="form-control form--control select2" required>
                                                <option value="" disabled selected>@lang('Select One')</option>
                                                @foreach ($categories as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Website') </label>
                                        <input type="url" name="url" class="form--control" value="{{ old('url') }}">
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Email công việc') </label>
                                        <input type="email" name="email" class="form--control" value="{{ old('email') }}" required>
                                    </div>
                                    
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Địa chỉ') </label>
                                        <input type="text" name="address" class="form--control" value="{{ old('address') }}" required>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Thành phố/Tỉnh') </label>
                                        <select name="city_code" class="form--control" required>
                                            <option value="">@lang('Chọn Tỉnh/Thành phố')</option>
                                            @if(isset($cities) && count($cities) > 0)
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->city_code }}" {{ old('city_code') == $city->city_code ? 'selected' : '' }}>
                                                        {{ $city->city }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @if(empty($cities))
                                            <small class="text-danger">Không có dữ liệu thành phố</small>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Quận/Huyện') </label>
                                        <select name="district_code" class="form--control" required>
                                            <option value="">@lang('Chọn Quận/Huyện')</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Phường/Xã') </label>
                                        <select name="ward_code" class="form--control" required>
                                            <option value="">@lang('Chọn Phường/Xã')</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <div class="position-relative">
                                            <label>@lang('Tags') </label>
                                            <select name="tags[]" class="form--control select2 select2-auto-tokenize" multiple="multiple" required>
                                            </select>
                                            <small class="tag-text">@lang('Ngăn cách nhiều từ khóa bằng dấu ') <code>@lang('(,)')</code>
                                                @lang('hoặc nhấn') <code>@lang('Enter')</code>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Mô tả') </label>
                                        <textarea name="description" class="form--control" required>{{ old('description') }}</textarea>
                                    </div>

                                    <!-- Certificates Section -->
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Chứng chỉ & Kinh nghiệm') </label>
                                        <div class="certificate-container">
                                            <div class="certificate-entry mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" name="certificates[0][name]" class="form--control"
                                                            placeholder="@lang('Tên chứng chỉ')" value="{{ old('certificates.0.name') }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="certificates[0][year]" class="form--control"
                                                            placeholder="@lang('Năm cấp')" min="1900" max="{{ date('Y') }}"
                                                            value="{{ old('certificates.0.year') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn--danger btn-sm remove-certificate"><i class="la la-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn--base btn-sm mt-2 add-certificate"><i class="la la-plus"></i> @lang('Thêm chứng chỉ')</button>
                                    </div>

                                    <!-- Featured Projects Section -->
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Dự án tiêu biểu') </label>
                                        <div class="project-container">
                                            <div class="project-entry mb-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="text" name="projects[0][title]" class="form--control"
                                                            placeholder="@lang('Tiêu đề dự án')" value="{{ old('projects.0.title') }}">
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
                                                            placeholder="@lang('Mô tả dự án')" rows="4">{{ old('projects.0.description') }}</textarea>
                                                    </div>
                                                    <div class="col-md-12 text-end">
                                                        <button type="button" class="btn btn--danger btn-sm remove-project"><i class="la la-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn--base btn-sm mt-2 add-project"><i class="la la-plus"></i> @lang('Thêm dự án')</button>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn--base w-100"><i class="fa fa-paper-plane"></i> @lang('Cập nhật thông tin')</button>
                            </div>
                        </div>
                    </form>
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

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
    <style>
        .certificate-entry, .project-entry {
            border: 1px solid #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
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
            display: none; /* Hidden for the first entry */
        }
        .certificate-entry:not(:first-child) .remove-certificate,
        .project-entry:not(:first-child) .remove-project {
            display: inline-block; /* Show for additional entries */
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

@push('script-lib')
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            // Initialize Select2
            $('.select2').select2();
            $(".select2-auto-tokenize").select2({
                tags: true,
                tokenSeparators: [',']
            });

            // Load cities
            $.ajax({
                url: '/localtion/api/cities',
                type: 'GET',
                dataType: 'text',
                success: function(response) {
                    const cleanResponse = response.replace(/<!--|-->/g, '').trim();
                    try {
                        const jsonResponse = JSON.parse(cleanResponse);
                        $('select[name=city_code]').empty().append('<option value="">@lang("Chọn Tỉnh/Thành phố")</option>');
                        jsonResponse.forEach(city => {
                            $('select[name=city_code]').append(
                                `<option value="${city.City_code}" data-name="${city.City}">${city.City}</option>`
                            );
                        });
                    } catch (error) {
                        console.error("Lỗi phân tích JSON:", error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Lỗi API:", textStatus, errorThrown);
                }
            });

            // Load districts when city changes
            $('select[name=city_code]').on('change', function() {
                let cityCode = $(this).val();
                $('select[name=district_code]').empty().append('<option value="">@lang("Chọn Quận/Huyện")</option>');
                $('select[name=ward_code]').empty().append('<option value="">@lang("Chọn Phường/Xã")</option>');

                if (cityCode) {
                    $.ajax({
                        url: `/localtion/api/districts/${cityCode}`,
                        type: 'GET',
                        dataType: 'text',
                        success: function(response) {
                            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
                            try {
                                const jsonResponse = JSON.parse(cleanResponse);
                                jsonResponse.forEach(function(district) {
                                    $('select[name=district_code]').append(
                                        `<option value="${district.District_code}" data-name="${district.District}">${district.District}</option>`
                                    );
                                });
                            } catch (error) {
                                console.error("Lỗi phân tích JSON (districts):", error);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Lỗi API (districts):", textStatus, errorThrown);
                        }
                    });
                }
            });

            // Load wards when district changes
            $('select[name=district_code]').on('change', function() {
                let districtCode = $(this).val();
                $('select[name=ward_code]').empty().append('<option value="">@lang("Chọn Phường/Xã")</option>');

                if (districtCode) {
                    $.ajax({
                        url: `/localtion/api/wards/${districtCode}`,
                        type: 'GET',
                        dataType: 'text',
                        success: function(response) {
                            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
                            try {
                                const jsonResponse = JSON.parse(cleanResponse);
                                jsonResponse.forEach(function(ward) {
                                    $('select[name=ward_code]').append(
                                        `<option value="${ward.Ward_code}" data-name="${ward.Ward}">${ward.Ward}</option>`
                                    );
                                });
                            } catch (error) {
                                console.error("Lỗi phân tích JSON (wards):", error);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Lỗi API (wards):", textStatus, errorThrown);
                        }
                    });
                }
            });

            // Thêm hidden fields khi submit form
            $('form').on('submit', function() {
                let cityName = $('select[name=city_code] option:selected').text();
                let districtName = $('select[name=district_code] option:selected').text();
                let wardName = $('select[name=ward_code] option:selected').text();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'city',
                    value: cityName
                }).appendTo(this);

                $('<input>').attr({
                    type: 'hidden',
                    name: 'district',
                    value: districtName
                }).appendTo(this);

                $('<input>').attr({
                    type: 'hidden',
                    name: 'ward',
                    value: wardName
                }).appendTo(this);
            });

            // Company Profile Photo Preview
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

            // Project Photo Preview
            function projectPhotoPreview(input) {
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
                projectPhotoPreview(this);
            });

            // Add Certificate Entry
            let certificateIndex = 1;
            $('.add-certificate').on('click', function() {
                const template = `
                    <div class="certificate-entry mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="certificates[${certificateIndex}][name]" class="form--control"
                                    placeholder="@lang('Tên chứng chỉ')">
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="certificates[${certificateIndex}][year]" class="form--control"
                                    placeholder="@lang('Năm cấp')" min="1900" max="${new Date().getFullYear()}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn--danger btn-sm remove-certificate"><i class="la la-trash"></i></button>
                            </div>
                        </div>
                    </div>`;
                $('.certificate-container').append(template);
                certificateIndex++;
            });

            // Remove Certificate Entry
            $(document).on('click', '.remove-certificate', function() {
                $(this).closest('.certificate-entry').remove();
            });

            // Add Project Entry
            let projectIndex = 1;
            $('.add-project').on('click', function() {
                const template = `
                    <div class="project-entry mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="projects[${projectIndex}][title]" class="form--control"
                                    placeholder="@lang('Tiêu đề dự án')">
                            </div>
                            <div class="col-md-6">
                                <div class="profile-thumb">
                                    <div class="avatar-preview">
                                        <div class="projectPicPreview"
                                            style="background-image: url('{{ getImage('', '300x200') }}');">
                                        </div>
                                        <div class="avatar-edit">
                                            <input type='file' class="projectPicUpload" name="projects[${projectIndex}][image]"
                                                id="projectPicUpload${projectIndex}" accept=".png, .jpg, .jpeg" />
                                            <label for="projectPicUpload${projectIndex}" class="btn btn--base btn-sm mb-0"><i class="la la-camera"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <textarea name="projects[${projectIndex}][description]" class="form--control"
                                    placeholder="@lang('Mô tả dự án')" rows="4"></textarea>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn--danger btn-sm remove-project"><i class="la la-trash"></i></button>
                            </div>
                        </div>
                    </div>`;
                $('.project-container').append(template);
                projectIndex++;
            });

            // Remove Project Entry
            $(document).on('click', '.remove-project', function() {
                $(this).closest('.project-entry').remove();
            });
        })(jQuery);
    </script>
@endpush

{{-- @push('script')
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
@endpush --}}
