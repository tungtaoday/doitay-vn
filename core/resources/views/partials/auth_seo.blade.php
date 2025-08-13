{{-- SEO tối ưu cho các trang auth - chống Google index --}}

{{-- Favicon và icons --}}
<link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
<link rel="icon" type="image/png" sizes="32x32" href="{{ siteFavicon() }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ siteFavicon() }}">
<link rel="apple-touch-icon" href="{{ siteLogo() }}">

{{-- Meta tags chống index --}}
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
<meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
<meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">

{{-- Canonical URL - trỏ về homepage --}}
<link rel="canonical" href="{{ route('home') }}">

{{-- Meta tags cơ bản --}}
<meta name="description" content="Đăng nhập hoặc đăng ký tài khoản - {{ gs('site_name') }}">
<meta name="keywords" content="đăng nhập, đăng ký, tài khoản, {{ gs('site_name') }}">

{{-- Open Graph - chống share --}}
<meta property="og:type" content="website">
<meta property="og:title" content="{{ gs('site_name') }} - Đăng nhập/Đăng ký">
<meta property="og:description" content="Đăng nhập hoặc đăng ký tài khoản">
<meta property="og:url" content="{{ route('home') }}">
<meta property="og:image" content="{{ siteLogo() }}">
<meta property="og:site_name" content="{{ gs('site_name') }}">

{{-- Twitter Card - chống share --}}
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ gs('site_name') }} - Đăng nhập/Đăng ký">
<meta name="twitter:description" content="Đăng nhập hoặc đăng ký tài khoản">
<meta name="twitter:image" content="{{ siteLogo() }}">

{{-- Schema.org - chống rich snippets --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ gs('site_name') }} - Đăng nhập/Đăng ký",
    "description": "Đăng nhập hoặc đăng ký tài khoản",
    "url": "{{ route('home') }}",
    "mainEntity": {
        "@type": "Organization",
        "name": "{{ gs('site_name') }}",
        "url": "{{ route('home') }}"
    }
}
</script> 