@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element', false, null, true);
@endphp
<section class="pt-100 pb-100 overflow-hidden bg_img"
    style="background-image: url('{{ frontendImage('testimonial', @$testimonialContent->data_values->image, '1920x840') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-6">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <div class="section-subtitle border-left-right text--base">
                        {{ __(@$testimonialContent->data_values->subheading) }}
                    </div>
                    <h2 class="section-title text-white">{{ __(@$testimonialContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="testimonial-slider">
                    @foreach ($testimonialElements as $testimonialElement)
                        <div class="single-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-card__thumb">
                                    <img src="{{ frontendImage('testimonial', @$testimonialElement->data_values->image, '100x100') }}"
                                        alt="{{ __(@$testimonialElement->data_values->name) }}">
                                </div>
                                <h6 class="testimonial-card__name text-white">
                                    {{ __(@$testimonialElement->data_values->name) }}
                                </h6>
                                <span
                                    class="testimonial-card__location">{{ __(@$testimonialElement->data_values->address) }}</span>
                                <p class="testimonial-card__text">{{ __(@$testimonialElement->data_values->quote) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Đảm bảo chữ màu trắng cho section title trong testimonial */
section.testimonial-section .section-header .section-title.text-white,
section.pt-100.pb-100.overflow-hidden.bg_img .section-header .section-title.text-white {
    color: #ffffff !important;
    -webkit-text-fill-color: #ffffff !important;
}

/* Thay đổi màu cho section subtitle trong testimonial */
section.pt-100.pb-100.overflow-hidden.bg_img .section-subtitle.border-left-right.text--base {
    color: #0c96d1 !important;
    -webkit-text-fill-color: #0c96d1 !important;
}

/* Đảm bảo border cũng có màu xanh da trời */
section.pt-100.pb-100.overflow-hidden.bg_img .section-subtitle.border-left-right::before,
section.pt-100.pb-100.overflow-hidden.bg_img .section-subtitle.border-left-right::after {
    background-color: #0c96d1 !important;
}


</style>
