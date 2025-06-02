@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.data.submit') }}" class="disableSubmission">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Username')</label>
                                            <input type="text" class="form-control form--control checkUser" required
                                                name="username" value="{{ old('username') }}">
                                            <small class="text--danger usernameExist"></small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Mobile')</label>
                                            <div class="input-group ">                                                
                                                <input type="number" name="mobile" value="{{ old('mobile') }}"
                                                    class="form-control form--control checkUser" >
                                            </div>
                                            <small class="text--danger mobileExist"></small>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Thành phố')</label>
                                        <select id="city" class="form-control form--control" name="city">
                                            <option value="" >@lang('Chọn Thành phố')</option>
                                            <!-- Thành phố sẽ được tải động -->
                                        </select>
                                        
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Quận/Huyện')</label>
                                        <select id="district" class="form-control form--control" name="district" disabled>
                                            <option value="">@lang('Chọn Quận/Huyện')</option>
                                            <!-- Quận/Huyện sẽ được tải động -->
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Phường/Xã')</label>
                                        <select id="ward" class="form-control form--control" name="ward" disabled>
                                            <option value="">@lang('Chọn Phường/Xã')</option>
                                            <!-- Phường/Xã sẽ được tải động -->
                                        </select>
                                    </div>                                    

                                    <div class="form-group col-sm-6">
                                        <label class="form-label">@lang('Địa chỉ')</label>
                                        <input type="text" class="form-control form--control" name="address"
                                            value="{{ old('address') }}">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">
                                        <input type="checkbox" id="registerAsExpert" name="register_as_expert" value="1">
                                        @lang('Bạn có muốn đăng ký là chuyên gia không?')
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn--base w-100">
                                        @lang('Xác nhận')
                                    </button>
                                </div>
                            </form>
                        </div>
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


        // Load cities
$.ajax({
    url: '/localtion/api/cities',
    type: 'GET',
    dataType: 'text', // Lấy dữ liệu dưới dạng text
    success: function(response) {
        // Loại bỏ comment HTML
        const cleanResponse = response.replace(/<!--|-->/g, '').trim();

        try {
            const jsonResponse = JSON.parse(cleanResponse); // Chuyển thành JSON
            $('#city').empty().append('<option value="">Chọn Thành phố</option>');
            jsonResponse.forEach(city => {
                $('#city').append(`<option value="${city.City_code}" data-name="${city.City}">${city.City}</option>`);
            });
        } catch (error) {
            console.error("Lỗi phân tích JSON:", error);
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.error("Lỗi API:", textStatus, errorThrown);
        console.error("Phản hồi từ server:", jqXHR.responseText);
    }
});

// Load districts when a city is selected
$('#city').change(function() {
    let cityCode = $(this).val();
    $('#district').empty().append('<option value="">Chọn Quận/Huyện</option>').prop('disabled', !cityCode);
    $('#ward').empty().append('<option value="">Chọn Phường/Xã</option>').prop('disabled', true);

    if (cityCode) {
        $.ajax({
            url: `/localtion/api/districts/${cityCode}`,
            type: 'GET',
            dataType: 'text', // Lấy dữ liệu dưới dạng text
            success: function(response) {
                const cleanResponse = response.replace(/<!--|-->/g, '').trim();

                try {
                    const jsonResponse = JSON.parse(cleanResponse); // Chuyển thành JSON
                    jsonResponse.forEach(function(district) {
                        $('#district').append(
                            `<option value="${district.District_code}" data-name="${district.District}">${district.District}</option>`
                        );
                    });
                    } catch (error) {
                    console.error("Lỗi phân tích JSON (districts):", error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Lỗi API (districts):", textStatus, errorThrown);
                console.error("Phản hồi từ server:", jqXHR.responseText);
            }
        });
    }
});

// Load wards when a district is selected
$('#district').change(function() {
    let districtCode = $(this).val();
    $('#ward').empty().append('<option value="">Chọn Phường/Xã</option>').prop('disabled', !districtCode);

    if (districtCode) {
        $.ajax({
            url: `/localtion/api/wards/${districtCode}`,
            type: 'GET',
            dataType: 'text', // Lấy dữ liệu dưới dạng text
            success: function(response) {
                const cleanResponse = response.replace(/<!--|-->/g, '').trim();

                try {
                    const jsonResponse = JSON.parse(cleanResponse); // Chuyển thành JSON
                    jsonResponse.forEach(function(ward) {
                        $('#ward').append(
                            `<option value="${ward.Ward_code}" data-name="${ward.Ward}">${ward.Ward}</option>`
                        );
                    });
                } catch (error) {
                    console.error("Lỗi phân tích JSON (wards):", error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Lỗi API (wards):", textStatus, errorThrown);
                console.error("Phản hồi từ server:", jqXHR.responseText);
            }
        });
    }
});


$('form').on('submit', function() {
    let registerAsExpert = $('#registerAsExpert').is(':checked');
    


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

    this.submit();
});

        })(jQuery);
    </script>
@endpush

