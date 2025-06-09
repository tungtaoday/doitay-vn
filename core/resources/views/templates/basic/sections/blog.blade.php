@php
    $blogContent = getContent('blog.content', true);
    $blogElements = getContent('blog.element', false, 3);
@endphp
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-6">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <div class="section-subtitle border-left-right text--base">
                        {{ __(@$blogContent->data_values->subheading) }}</div>
                    <h2 class="section-title">{{ __(@$blogContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($blogElements as $blogElement)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-post rounded-3">
                        <div class="blog-post__thumb rounded-2">
                            <a href="{{ route('blog.details', [$blogElement->slug, $blogElement->id]) }}" class="d-block w-100 h-100">
                                <img src="{{ frontendImage('blog', 'thumb_' . @$blogElement->data_values->image, '415x230') }}"
                                    alt="@lang(' Blog')" class="rounded-2">
                            </a>
                            <span class="blog-post__date">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ showDateTime($blogElement->created_at, 'Y-M-d') }}
                            </span>
                        </div>

                        <div class="blog-post__content">
                            <h5 class="blog-post__title">
                                <a href="{{ route('blog.details', [$blogElement->slug, $blogElement->id]) }}">
                                    {{ __(strLimit($blogElement->data_values->title, 80)) }}</a>
                            </h5>
                            <p class="mt-2">
                                @php echo __(strLimit(strip_tags($blogElement->data_values->description), 90));@endphp
                            </p>
                            <a href="{{ route('blog.details', [$blogElement->slug, $blogElement->id]) }}" class="blog-post__btn mt-3">
                                @lang('Read More') <i class="las la-long-arrow-alt-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
/* Thay đổi màu nền cho blog post date */
.blog-post__date {
    background-color: #1b723f !important;
    color: #ffffff !important;
}

/* Đảm bảo icon calendar cũng có màu trắng */
.blog-post__date i.far.fa-calendar-alt {
    color: #ffffff !important;
}

/* Thay đổi màu hover cho tiêu đề blog */
.blog-post__title a:hover {
    color: #0195d3 !important;
    -webkit-text-fill-color: #0195d3 !important;
    transition: all 0.3s ease !important;
}

/* Thay đổi màu cho nút Read More */
.blog-post__btn {
    color: #0195d3 !important;
    -webkit-text-fill-color: #0195d3 !important;
}

/* Đảm bảo icon mũi tên cũng có màu xanh */
.blog-post__btn i.las {
    color: #0195d3 !important;
    -webkit-text-fill-color: #0195d3 !important;
}
</style>
