@extends($activeTemplate . 'layouts.frontend')
@php
    $contactContent = getContent('contact_us.content', true);
    $contactElements = getContent('contact_us.element', false, null, true);
    $iconElements = getContent('social_icon.element', false, null, true);
@endphp
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-wrapper d-flex flex-wrap">
                        <div class="contact-wrapper__left">
                            <form class="contact-form verify-gcaptcha disableSubmission" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Full Name')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-user"></i>
                                            <input type="text" name="name" value="{{ old('name', @$user->fullname) }}" class="form--control"
                                                placeholder="@lang('Full Name')" @if ($user && $user->profile_complete) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label>@lang('Email Address')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-envelope"></i>
                                            <input type="text" name="email" value="{{ old('email', @$user->email) }}" class="form--control"
                                                placeholder="@lang('Email address')" @if ($user) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Subject')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-notes-medical"></i>
                                            <input type="text" name="subject" value="{{ old('subject') }}" class="form--control"
                                                placeholder="@lang('Subject Line')" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>@lang('Message')</label>
                                        <div class="custom-icon-field">
                                            <i class="las la-comment-alt"></i>
                                            <textarea name="message" class="form--control" placeholder="@lang('Write message')" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    <x-captcha />
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn--base w-100">
                                            @lang('Submit')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="contact-wrapper__right">
                            <div class="contact-wrapper__shape-one"></div>
                            <div class="contact-wrapper__shape-two"></div>
                            <div class="top-part">
                                <h3 class="title text-white">{{ __(@$contactContent->data_values->title) }}</h3>
                                <ul class="contact-info-list mt-5">
                                    @foreach ($contactElements as $contactElement)
                                        <li>
                                            <div class="icon"> @php echo @$contactElement->data_values->icon @endphp</i></div>
                                            <div class="content">
                                                <p> {{ @$contactElement->data_values->content }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <ul class="social-links d-flex flex-wrap align-items-center">
                                @foreach ($iconElements as $iconElement)
                                    <li>
                                        <a href="{{ @$iconElement->data_values->url }}">
                                            @php echo @$iconElement->data_values->social_icon; @endphp
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
