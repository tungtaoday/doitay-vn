@extends($activeTemplate . 'layouts.master')
@php
    $loginContent = getContent('login.content', true);
@endphp
@section('content')
    <section class="account-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="account-wrapper">
                        <div class="row g-0">
                            <div class="col-lg-6">
                                <div class="account-left bg_img" style="background-image: url('{{ frontendImage('login', @$loginContent->data_values->image, '1920x1080') }}');">
                                    <div class="account-left-inner text-center">
                                        <h2 class="text-white mb-4">{{ __(@$loginContent->data_values->heading) }}</h2>
                                        <p class="text-white mb-4">{{ __(@$loginContent->data_values->greeting) }} {{ __(gs('site_name')) }}</p>
                                        <div class="mt-5">
                                            <a href="{{ route('user.register.v2') }}" class="btn btn--primary btn--sm">
                                                @lang("Nếu bạn chưa có tài khoản?") @lang('Tạo tài khoản')
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
                                                <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}" class="img-fluid">
                                            </a>
                                        </div>
                                        
                                        <div class="account-form-wrapper">
                                            @include($activeTemplate . 'partials.social_login')
                                            
                                            <form class="account-form verify-gcaptcha disableSubmission" method="POST" action="{{ route('user.login.v2.post') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="form-label text-dark">@lang('Username or Email')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg--primary text-white">
                                                            <i class="las la-user"></i>
                                                        </span>
                                                        <input type="text" name="username" class="form--control" placeholder="@lang('Nhập username hoặc email')" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label text-dark">@lang('Mật khẩu')</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg--primary text-white">
                                                            <i class="las la-lock"></i>
                                                        </span>
                                                        <input type="password" name="password" class="form--control" placeholder="@lang('Nhập mật khẩu')" required>
                                                        <button type="button" class="input-group-text border-0 bg--primary text-white toggle-password">
                                                            <i class="la la-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <x-captcha />
                                                
                                                <div class="form-group form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                    <label class="form-check-label text-dark" for="remember">
                                                        @lang('Nhớ thông tin đăng nhập')
                                                    </label>
                                                </div>

                                                <div class="form-group">
                                                    <button type="submit" id="recaptcha" class="btn btn--primary w-100">
                                                        <i class="las la-sign-in-alt me-2"></i> @lang('Đăng nhập')
                                                    </button>
                                                </div>

                                                <div class="form-group text-center">
                                                    <a class="text--primary" href="{{ route('user.password.request') }}">
                                                        @lang('Quên mật khẩu?')
                                                    </a>
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
@endsection

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
    .social-login {
        margin-bottom: 30px;
    }
    .social-login .btn {
        padding: 10px 20px;
        border-radius: 5px;
        margin: 0 5px;
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

@push('script')
    <script>
        "use strict";
        $(".toggle-password").on('click', function() {
            $(this).find('i').toggleClass("las la-eye-slash");
            var input = $(this).siblings('input');
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
@endpush
