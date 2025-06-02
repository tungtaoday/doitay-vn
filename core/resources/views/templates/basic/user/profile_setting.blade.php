<!-- meta tags and other links -->
@extends($activeTemplate . 'layouts.auth')
@section('content')
    <form class="edit-profile-form disableSubmission" method="post" enctype="multipart/form-data">
        @csrf
        <div class="custom--card">
            <div class="card-header bg--dark">
                <h5 class="text-white">@lang('Hồ sơ cá nhân')</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <div class="profile-thumb-wrapper">
                            <label>@lang('Ảnh đại diện')</label>
                            <div class="profile-thumb justify-content-center">
                                <div class="avatar-preview">
                                    <div class="profilePicPreview"
                                        style="background-image: url('{{ getImage(getFilePath('userProfile') . '/' . $user->image,isAvatar:true )}}');">
                                    </div>
                                    <div class="avatar-edit">
                                        <input type='file' class="profilePicUpload" name="image" id="profilePicUpload1"
                                            accept=".png, .jpg, .jpeg" />
                                        <label for="profilePicUpload1" class="btn btn--base mb-0"><i
                                                class="la la-camera"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang('Họ và tên đệm')</label>
                            <div class="custom-icon-field">
                                <i class="la la-user"></i>
                                <input type="text" name="firstname" class="form--control"
                                    value="{{$user->firstname }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tên')</label>
                            <div class="custom-icon-field">
                                <i class="la la-user"></i>
                                <input type="text" name="lastname" class="form--control"
                                    value="{{$user->lastname }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label>@lang('Email')</label>
                        <div class="custom-icon-field">
                            <i class="la la-envelope"></i>
                            <input type="email" class="form--control"
                                value="{{$user->email }}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-6 form-group">
                        <label>@lang('Điện thoại')</label>
                        <div class="custom-icon-field">
                            <i class="la la-phone"></i>
                            <input type="tel" class="form--control"
                                value="{{ $user->mobile }}" readonly>
                        </div>
                    </div>

                    <div class="col-lg-12 form-group">
                        <label>@lang('Địa chỉ')</label>
                        <div class="custom-icon-field">
                            <i class="la la-map-marker-alt"></i>
                            <input type="text" name="address" class="form--control" value="{{$user->address }}">
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>@lang('Quốc tịch')</label>
                        <div class="custom-icon-field">
                            <i class="la la-globe"></i>
                            <input type="text" class="form--control"
                                value="{{$user->country_name }}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>@lang('Thành phố')</label>
                        <div class="custom-icon-field">
                            <i class="la la-map-pin"></i>
                            <input type="text" name="city" class="form--control" value="{{$user->city }}">
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label>@lang('Quận/Huyện')</label>
                        <div class="custom-icon-field">
                            <i class="la la-map-signs"></i>
                            <input type="text" name="district" class="form--control" value="{{$user->district }}">
                        </div>
                    </div>                    
                    <div class="col-lg-6 form-group">
                        <label>@lang('Phường/Xã')</label>
                        <div class="custom-icon-field">
                            <i class="la la-location-arrow"></i>
                            <input type="text" name="ward" class="form--control" value="{{$user->ward }}">
                        </div>
                    </div>

                    <div class="col-lg-12 form-group">
                        <label>@lang('Giới thiệu bản thân')</label>
                        <div class="custom-icon-field">
                            <i class="la la-address-card"></i>
                            <textarea name="about" class="form--control" placeholder="@lang('Viết vài dòng về bạn đi....')">{{$user->about }}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn--base w-100"> @lang('Cập nhật')</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@push('script')
    <script>
        function proPicURL(input) {
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
            proPicURL(this);
        });

        $(".remove-image").on('click', function() {
            $(".profilePicPreview").css('background-image', 'none');
            $(".profilePicPreview").removeClass('has-image');
        })
    </script>
@endpush
