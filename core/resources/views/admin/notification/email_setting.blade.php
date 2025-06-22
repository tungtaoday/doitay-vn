@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.notification.top_bar')
@endpush
<div class="row">
        <div class="col-md-12">
            <div class="card">
                <form method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Email Send Method')</label>
                            @php
                                $mailConfig = gs('mail_config');
                                $mailMethod = $mailConfig->name ?? 'php';
                            @endphp
                            <select name="email_method" class="select2 form-control" data-minimum-results-for-search="-1">
                                <option value="php" @if ($mailMethod == 'php') selected @endif>@lang('PHP Mail')</option>
                                <option value="smtp" @if ($mailMethod == 'smtp') selected @endif>@lang('SMTP')</option>
                                <option value="sendgrid" @if ($mailMethod == 'sendgrid') selected @endif>@lang('SendGrid API')</option>
                                <option value="mailjet" @if ($mailMethod == 'mailjet') selected @endif>@lang('Mailjet API')</option>
                            </select>
                        </div>
                        <div class="row mt-4 d-none configForm" id="smtp">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('SMTP Configuration')</h6>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Host') </label>
                                    <input type="text" class="form-control" placeholder="e.g. @lang('smtp.googlemail.com')" name="host" value="{{ $mailConfig->host ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Port') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Available port')" name="port" value="{{ $mailConfig->port ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Encryption')</label>
                                    <select class="form-control select2" data-minimum-results-for-search="-1" name="enc">
                                        <option value="ssl" @selected(($mailConfig->enc ?? '') == 'ssl')>@lang('SSL')</option>
                                        <option value="tls" @selected(($mailConfig->enc ?? '') == 'tls')>@lang('TLS')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Username') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Normally your email') address" name="username" value="{{ $mailConfig->username ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Password') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Normally your email password')" name="password" value="{{ $mailConfig->password ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="sendgrid">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('SendGrid API Configuration')</h6>
                            </div>
                            <div class="form-group col-md-12">
                                <label>@lang('App Key') </label>
                                <input type="text" class="form-control" placeholder="@lang('SendGrid App key')" name="appkey" value="{{ $mailConfig->appkey ?? '' }}">
                            </div>
                        </div>
                        <div class="row mt-4 d-none configForm" id="mailjet">
                            <div class="col-md-12">
                                <h6 class="mb-2">@lang('Mailjet API Configuration')</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Api Public Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Mailjet Api Public Key')" name="public_key" value="{{ $mailConfig->public_key ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Api Secret Key') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Mailjet Api Secret Key')" name="secret_key" value="{{ $mailConfig->secret_key ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>


    </div>


    {{-- TEST MAIL MODAL --}}
    <div id="testMailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Test Mail Setup')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.setting.notification.email.test') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Sent to') </label>
                                    <input type="text" name="email" class="form-control" placeholder="@lang('Email Address')">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('breadcrumb-plugins')
    <button type="button" data-bs-target="#testMailModal" data-bs-toggle="modal" class="btn btn-sm btn-outline--primary"><i class="las la-paper-plane"></i> @lang('Send Test Mail')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            var method = '{{ $mailMethod }}';
            emailMethod(method);
            $('select[name=email_method]').on('change', function() {
                var method = $(this).val();
                emailMethod(method);
            });

            function emailMethod(method) {
                $('.configForm').addClass('d-none');
                if (method != 'php') {
                    $(`#${method}`).removeClass('d-none');
                }
            }

        })(jQuery);
    </script>
@endpush
