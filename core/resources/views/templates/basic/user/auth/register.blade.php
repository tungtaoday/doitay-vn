@extends($activeTemplate . 'layouts.master')
@php
    $regContent = getContent('register.content', true);
@endphp
@section('content')
    @if (gs('registration'))
        <section class="account-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="account-wrapper">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="account-left bg_img" style="background-image: url('{{ frontendImage('register', @$regContent->data_values->image, '1920x1280') }}');">
                                        <div class="account-left-inner text-center">
                                            <h2 class="text-white mb-4">{{ __(@$regContent->data_values->heading) }}</h2>
                                            <p class="text-white mb-4">@lang('Welcome to ') {{ __(gs('site_name')) }}</p>
                                            <div class="mt-5">
                                                <a href="{{ route('user.login.v2') }}" class="btn btn--primary btn--sm">
                                                    @lang('Bạn đã có tài khoản?') @lang('Đăng nhập ngay')
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="account-right">
                                        <div class="account-right-inner">
                                            <div class="text-center mb-4">
                                                <a class="account-logo" href="{{ route('home') }}">
                                                    <img src="{{ getImage(getFilePath('logo_icon') . '/logo.png') }}" alt="{{ __(gs('site_name')) }}" class="img-fluid">
                                                </a>
                                            </div>
                                            
                                            @if(isset($referrer))
                                                <div class="alert alert-success mb-3">
                                                    <i class="las la-gift me-2"></i>
                                                    <strong>Chúc mừng!</strong> Bạn được giới thiệu bởi <strong>{{ $referrer->fullname }}</strong>. 
                                                    Bạn sẽ nhận được ưu đãi đặc biệt khi đăng ký!
                                                </div>
                                            @endif

                                            <div class="account-form-wrapper">
                                                @include($activeTemplate . 'partials.social_login')
                                                
                                                <form action="{{ route('user.register.v2.post') }}" method="POST" class="account-form verify-gcaptcha disableSubmission">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label text-dark">@lang('Họ và đệm')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg--primary text-white">
                                                                        <i class="las la-user"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label text-dark">@lang('Tên')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg--primary text-white">
                                                                        <i class="las la-user"></i>
                                                                    </span>
                                                                    <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="form-label text-dark">@lang('E-Mail')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg--primary text-white">
                                                                        <i class="las la-envelope"></i>
                                                                    </span>
                                                                    <input type="email" class="form-control form--control checkUser" name="email" value="{{ old('email') }}" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label text-dark">@lang('Mật khẩu')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg--primary text-white">
                                                                        <i class="las la-lock"></i>
                                                                    </span>
                                                                    <input type="password" class="form-control form--control @if (gs('secure_password')) secure-password @endif" name="password" required>
                                                                    <button type="button" class="input-group-text border-0 bg--primary text-white toggle-password">
                                                                        <i class="la la-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="form-label text-dark">@lang('Xác nhận mật khẩu')</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg--primary text-white">
                                                                        <i class="las la-lock"></i>
                                                                    </span>
                                                                    <input type="password" class="form-control form--control" name="password_confirmation" required>
                                                                    <button type="button" class="input-group-text border-0 bg--primary text-white toggle-password">
                                                                        <i class="la la-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <x-captcha />
                                                    </div>

                                                    @if (gs('agree'))
                                                        @php
                                                            $policyPages = getContent('policy_pages.element', false, orderById: true);
                                                        @endphp
                                                        <div class="form-group form-check">
                                                            <input type="checkbox" class="form-check-input" id="agree" @checked(old('agree')) name="agree" required>
                                                            <label class="form-check-label text-dark" for="agree">
                                                                @lang('I agree with')
                                                                @foreach ($policyPages as $policy)
                                                                    <a href="{{ route('policy.pages', $policy->slug) }}" class="text--primary">
                                                                        {{ __($policy->data_values->title) }}
                                                                    </a>
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </label>
                                                        </div>
                                                    @endif

                                                    <div class="form-group">
                                                        <button type="submit" id="recaptcha" class="btn btn--primary w-100">
                                                            <i class="las la-user-plus me-2"></i> @lang('Register')
                                                        </button>
                                                    </div>

                                                    <div class="form-group text-center">
                                                        <p class="mb-0">
                                                            @lang('Already have an account?')
                                                            <a href="{{ route('user.login.v2') }}" class="text--primary">@lang('Login')</a>
                                                        </p>
                                                        <div class="mt-3">
                                                            <a href="{{ route('home') }}" class="btn btn--primary">
                                                                <i class="las la-home me-2"></i> @lang('Quay lại trang chủ')
                                                            </a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif

    <div class="modal fade" id="existModalCenter">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h6 class="text-center">@lang('You already have an account please Login ')</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                                                            <a href="{{ route('user.login.v2') }}" class="btn btn--base btn-sm">@lang('Login')</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@if (gs('secure_password'))
    @push('script-lib')
        <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';

                var data = {
                    email: value,
                    _token: token
                }

                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $('#existModalCenter').modal('show');
                    }
                });
            });

            $(".toggle-password").on('click', function() {
                $(this).find('i').toggleClass("las la-eye-slash");
                var input = $(this).siblings('input');
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        :root {
            --primary-color: #2196F3;
            --primary-hover: #1976D2;
        }
        
        .account-wrapper {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .account-left {
            height: 100%;
            min-height: 500px;
            position: relative;
            padding: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .account-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .account-left-inner {
            position: relative;
            z-index: 1;
        }
        .account-right {
            padding: 40px;
        }
        .account-form-wrapper {
            max-width: 400px;
            margin: 0 auto;
        }
        .form--control,
        .form-control {
            height: 45px;
            border: 1px solid #333 !important;
            border-radius: 5px;
            padding: 10px 15px;
            color: #333 !important;
            background-color: #fff !important;
        }
        .form--control:focus,
        .form-control:focus {
            border-color: var(--primary-color) !important;
            box-shadow: none;
            color: #333 !important;
        }
        .input-group-text {
            border: 1px solid #e5e5e5;
        }
        .btn--primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            height: 45px;
            border-radius: 5px;
            font-weight: 500;
        }
        .btn--primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }
        .bg--primary {
            background: var(--primary-color) !important;
        }
        .text--primary {
            color: var(--primary-color) !important;
        }
        .form-label {
            color: #333;
            font-weight: 500;
        }

        /* Captcha styles */
        .captcha-input {
            height: 45px;
            border: 1px solid #333 !important;
            border-radius: 5px;
            padding: 10px 15px;
            color: #333 !important;
            width: 100%;
            margin-top: 10px;
            background-color: #fff !important;
        }
        .captcha-input:focus {
            border-color: var(--primary-color) !important;
            outline: none;
            box-shadow: none;
            color: #333 !important;
        }
        .captcha-image {
            border: 1px solid #333;
            border-radius: 5px;
            padding: 5px;
            background: #fff;
        }
        .captcha-wrapper {
            margin-bottom: 20px;
        }
        .captcha-wrapper label {
            color: #333;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }

        /* Override any existing styles */
        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="number"] {
            color: #333 !important;
            border: 1px solid #333 !important;
        }
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            color: #333 !important;
            border-color: var(--primary-color) !important;
        }

        .form-label.required {
            color: #333 !important;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }
    </style>
@endpush
