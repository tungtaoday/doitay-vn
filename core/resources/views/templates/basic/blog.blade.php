@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- Debug Info -->
    <!-- @if(config('app.debug'))
        <div class="container mt-3">
            <div class="alert alert-info">
                <strong>Debug Info:</strong><br>
                Blogs Count: {{ $blogs->count() }}<br>
                Latest Count: {{ $latest->count() }}<br>
                Active Template: {{ activeTemplate() }}<br>
                Sections: {{ $sections ? 'Found' : 'Not Found' }}
            </div>
        </div>
    @endif -->

    <section class="pt-100 pb-100 contact-section overflow-hidden">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @if($blogs->count() > 0)
                    @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-post rounded-3">
                            <div class="blog-post__thumb rounded-2">
                                <a href="{{ route('blog.details', [$blog->slug, $blog->id]) }}" class="d-block w-100 h-100">
                                    @if(@$blog->data_values->image)
                                        <img src="{{ frontendImage('blog', 'thumb_' . @$blog->data_values->image, '415x230') }}"
                                            alt="@lang('Blog')" class="rounded-2">
                                    @else
                                        <div class="no-image-placeholder" style="width: 100%; height: 100%; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                            <i class="las la-image" style="font-size: 2rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                </a>
                                <span class="blog-post__date"><i class="far fa-calendar-alt me-1"></i>
                                    {{ showDateTime($blog->created_at, 'd-M-Y') }}</span>
                            </div>
                            <div class="blog-post__content">
                                <h5 class="blog-post__title">
                                    <a href="{{ route('blog.details', [$blog->slug, $blog->id]) }}">
                                        {{ __(strLimit(@$blog->data_values->title ?? 'No Title', 80)) }}</a>
                                </h5>
                                <p class="mt-2">
                                    @php
                                        echo __(strLimit(strip_tags(@$blog->data_values->description ?? 'No description'), 130));
                                    @endphp
                                </p>
                                <a href="{{ route('blog.details', [$blog->slug, $blog->id]) }}" class="blog-post__btn mt-3">
                                    @lang('Read More')
                                    <i class="las la-long-arrow-alt-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if ($blogs->hasPages())
                        <div class="mt-5">
                            {{ paginateLinks($blogs) }}
                        </div>
                    @endif
                @else
                    <div class="col-12">
                        <div class="text-center">
                            <div class="empty-state">
                                <i class="las la-newspaper" style="font-size: 4rem; color: #ccc;"></i>
                                <h3>Chưa có bài viết blog nào</h3>
                                <p>Hãy tạo bài viết blog đầu tiên trong admin panel</p>
                                <div class="mt-3">
                                    <a href="{{ route('admin.frontend.elements') }}" class="btn btn-primary">
                                        <i class="las la-plus"></i> Tạo bài viết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <div class="pt-100">
        @if ($sections->secs != null)
            @foreach (json_decode($sections->secs) as $sec)
                @include($activeTemplate . 'sections.' . $sec)
            @endforeach
        @endif
    </div>
@endsection

<style>
/* Đổi toàn bộ màu cam (base) sang xanh dương */
:root {
    --r: 12;
    --g: 150;
    --b: 209;
}

/* Card blog đẹp hơn */
.blog-post {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px 0 rgba(12,150,209,0.10);
    transition: box-shadow 0.3s, transform 0.3s;
    overflow: hidden;
    border: 1px solid #e3f2fd;
    margin-bottom: 32px;
    min-height: 420px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.blog-post:hover {
    box-shadow: 0 16px 48px 0 rgba(12,150,209,0.18);
    transform: translateY(-6px) scale(1.02);
}

/* Ảnh thumbnail */
.blog-post__thumb {
    border-radius: 12px 12px 0 0;
    overflow: hidden;
    position: relative;
    height: 230px;
    background: #f7fbfd;
}
.blog-post__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}
.blog-post:hover .blog-post__thumb img {
    transform: scale(1.05);
}

/* Ngày tháng */
.blog-post__date {
    position: absolute;
    top: 16px;
    left: 16px;
    background: #0c96d1;
    color: #fff;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.95rem;
    font-weight: 500;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10);
    z-index: 2;
}

/* Tiêu đề */
.blog-post__title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0c96d1;
    margin-bottom: 8px;
    min-height: 48px;
    line-height: 1.3;
}
.blog-post__title a {
    color: #0c96d1;
    text-decoration: none;
    transition: color 0.2s;
}
.blog-post__title a:hover {
    color: #0880b3;
    text-decoration: underline;
}

/* Nội dung mô tả */
.blog-post__content p {
    color: #505050;
    font-size: 1rem;
    margin-bottom: 0;
    min-height: 60px;
}

/* Nút Read More */
.blog-post__btn {
    display: inline-flex;
    align-items: center;
    background: #0c96d1;
    color: #fff !important;
    border-radius: 8px;
    padding: 8px 22px;
    font-weight: 600;
    font-size: 1rem;
    margin-top: 18px;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10);
    transition: background 0.2s, color 0.2s;
    text-decoration: none;
}
.blog-post__btn i {
    margin-left: 8px;
    font-size: 1.2em;
}
.blog-post__btn:hover {
    background: #0880b3;
    color: #fff !important;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 991px) {
    .blog-post {
        min-height: 380px;
    }
    .blog-post__thumb {
        height: 180px;
    }
}
@media (max-width: 575px) {
    .blog-post {
        min-height: 320px;
        padding: 10px;
    }
    .blog-post__thumb {
        height: 140px;
    }
    .blog-post__title {
        font-size: 1.1rem;
        min-height: 36px;
    }
}

/* Tiêu đề blog */
.blog-post__title,
.blog-post__title a {
    color: #0c96d1 !important;
}

/* Nút Read More */
.blog-post__btn {
    background: #0c96d1 !important;
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
}
.blog-post__btn:hover {
    background: #0880b3 !important;
    color: #fff !important;
}

/* Ngày tháng */
.blog-post__date {
    background: #0c96d1 !important;
    color: #fff !important;
}

/* Đổi màu link tiêu đề khi hover */
.blog-post__title a:hover {
    color: #0880b3 !important;
    text-decoration: underline !important;
}

/* Đảm bảo nút Read More luôn xanh dương, kể cả khi có mt-3 */
a.blog-post__btn,
a.blog-post__btn.mt-3 {
    background: #0c96d1 !important;
    color: #fff !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 1rem !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10) !important;
}
a.blog-post__btn:hover,
a.blog-post__btn.mt-3:hover {
    background: #0880b3 !important;
    color: #fff !important;
}

/* Đảm bảo ngày tháng luôn xanh dương */
span.blog-post__date {
    background: #0c96d1 !important;
    color: #fff !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10) !important;
}
</style>
