@php
    $footerContent = getContent('footer.content', true);
    $iconElements = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element');
    $pages = App\Models\Page::where('tempname',$activeTemplate)->where('slug','!=','/')->where('is_default', 0)->get();
@endphp
<footer class="footer">
    <div class="shape-one"></div>
    <div class="shape-two"></div>
    <div class="footer__top">
        <div class="container">
            <div class="row gy-sm-4 gy-5 justify-content-between">
                <div class="col-lg-4 col-md-12 col-sm-6">
                    <div class="footer-widget">
                        <a class="site-logo site-title" href="{{ route('home') }}">
                            <img src="{{ getImage(getFilePath('logo_icon') . '/logo_white.png', '100X100') }}"
                                alt="{{ __(gs('site_name')) }}">
                        </a>
                        <p class="mt-lg-5">{{ __(strLimit(@$footerContent->data_values->description, 160)) }}</p>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Quick Menu')</h3>
                        <ul class="footer-menu">
                            @if (@$pages)
                                @foreach ($pages as $k => $data)
                                    <li>
                                        <a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                                    </li>
                                @endforeach
                            @endif
                            <li><a href="{{ route('contact') }}">@lang('Support')</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Important Link')</h3>
                        <ul class="footer-menu">
                            @foreach ($policyPages as $page)
                                <li>
                                    <a class="t-link t-link--danger text--white" href="{{ route('policy.pages', $page->slug) }}">
                                        {{ @$page->data_values->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="footer-widget">
                        <h3 class="footer-widget__title">@lang('Site Links')</h3>
                        <ul class="footer-menu">
                            <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                            <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                            <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom">
        <div class="container">
            <div class="row gy-2 align-items-center">
                <div class="col-md-6 text-md-start text-center">
                    <p class="mb-0 sm-text">
                        @include($activeTemplate . 'partials.copyright_text')
                    </p>
                </div>
                <div class="col-md-6">
                    <ul class="social-link d-flex flex-wrap align-items-center justify-content-md-end justify-content-center">
                        @foreach ($iconElements as $iconElement)
                            <li>
                                <a href="{{ @$iconElement->data_values->url }}" target="_blank">
                                    @php echo @$iconElement->data_values->social_icon; @endphp
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* Thay đổi màu background cho footer */
.footer {
    background-color: #06214e !important;
}

/* Đảm bảo các shape trong footer cũng có màu phù hợp */
.footer .shape-one {
    background: linear-gradient(135deg, rgba(6, 33, 78, 0.4) 0%, rgba(6, 33, 78, 0.1) 100%) !important;
}

.footer .shape-two {
    background: linear-gradient(135deg, rgba(6, 33, 78, 0.3) 0%, rgba(6, 33, 78, 0.05) 100%) !important;
}

/* Đảm bảo màu chữ vẫn dễ đọc trên nền xanh đậm */
.footer-widget__title,
.footer-menu li a,
.footer p,
.social-link li a {
    color: #ffffff !important;
}

/* Hiệu ứng hover cho menu items */
.footer-menu li a:hover {
    color: rgba(255, 255, 255, 0.8) !important;
}
</style>
