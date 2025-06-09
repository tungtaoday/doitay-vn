@extends('admin.layouts.master')
@section('content')
    <div class="login-main" style="background-image: url('{{ asset('assets/admin/images/login.jpg') }}')">
        <div class="container custom-container d-flex justify-content-center">
            <div class="login-area">
                <div class="text-center mb-3">
                    <h2 class="text-white mb-2">@lang('Verify Code')</h2>
                    <p class="text-white mb-2">@lang('Please check your email and enter the verification code you got in your email.')</p>
                </div>
                <form action="{{ route('admin.password.verify.code') }}" method="POST" class="login-form w-100">
                    @csrf

                    <div class="code-box-wrapper d-flex w-100">
                        <div class="form-group mb-3 flex-fill">
                            <span class="text-white">@lang('Verification Code')</span>
                            <div class="verification-code">
                                <input type="text" name="code" class="overflow-hidden" autocomplete="off">
                                <div class="boxes">
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                    <span>-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn cmn-btn w-100">@lang('Submit')</button>
                    <div class="d-flex flex-wrap justify-content-between mt-3">
                        <a href="{{ route('admin.password.reset') }}" class="forget-text">@lang('Try to send again')</a>
                        <a href="{{ route('admin.login') }}" class="text-white"><i class="las la-sign-in-alt"></i>@lang('Back to Login')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/verification_code.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            'use strict';
            
            // Add visual feedback for each digit
            $('[name=code]').on('input', function() {
                let input = $(this);
                let value = input.val();
                
                // Only allow numbers
                value = value.replace(/[^0-9]/g, '');

                $(this).val(function(i, val) {
                    if (val.length == 6) {
                        console.log('Code entered: ' + val);
                        $('form').find('button[type=submit]').html('<i class="las la-spinner fa-spin"></i>');
                        $('form').find('button[type=submit]').removeClass('disabled');
                        // Auto submit after slight delay
                        setTimeout(function() {
                        $('form')[0].submit();
                        }, 500);
                    } else {
                        $('form').find('button[type=submit]').addClass('disabled');
                        $('form').find('button[type=submit]').html('@lang("Submit")');
                    }
                    if (val.length > 6) {
                        return val.substring(0, val.length - 1);
                    }
                    return val;
                });

                // Update visual boxes
                $('.boxes span').each(function(index) {
                    if (index < value.length) {
                        $(this).html(value[index]);
                        $(this).addClass('filled');
                    } else {
                        $(this).html('-');
                        $(this).removeClass('filled');
                    }
                });
            });

            // Allow manual submit if needed
            $('form').on('submit', function(e) {
                let code = $('[name=code]').val();
                if (code.length < 6) {
                    e.preventDefault();
                    alert('Please enter complete 6-digit code');
                    return false;
                }
                console.log('Submitting code: ' + code);
            });

        })(jQuery)
    </script>
@endpush
@push('style')
    <style>
        .cmn-btn.disabled,
        .cmn-btn:disabled {
            color: #fff;
            background-color: #3d2bfb;
            border-color: #3d2bfb;
            opacity: 0.7;
        }
        
        .boxes span.filled {
            background-color: #28a745;
            color: white;
            border-radius: 4px;
            font-weight: bold;
        }
        
        .boxes span {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            margin: 0 5px;
            border: 2px solid #ddd;
            background-color: #f8f9fa;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
@endpush
