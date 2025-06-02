@php
    $ctaContent = getContent('cta.content', true);
    $ctaElements = getContent('cta.element', false, null, true);
@endphp
<section class="cta-section pt-100 pb-100 overflow-hidden">
    <div class="shape-one"></div>
    <div class="shape-two"></div>
    <div class="shape-three"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="section-header wow fadeInLeft" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <h2 class="section-title">{{ __(@$ctaContent->data_values->heading) }}</h2>
                    <p class="mt-3">
                        {{ __(@$ctaContent->data_values->subheading) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="row gy-5">
            @foreach ($ctaElements as $ctaElement)
                <div class="col-md-6 wow fadeInLeft" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <div class="cta-card">
                        <div class="cta-card__icon">
                            @php
                                echo @$ctaElement->data_values->icon;
                            @endphp
                        </div>
                        <div class="cta-card__content">
                            <h3 class="cta-card__title">{{ __(@$ctaElement->data_values->title) }}</h3>
                            <p class="fs--18px mt-3">{{ __(@$ctaElement->data_values->description) }}</p>
                            <a href="{{ @$ctaElement->data_values->url }}" class="btn btn--base mt-4">
                                {{ __(@$ctaElement->data_values->button_name) }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
/* Ghi đè gradient cho các shape trong cta section */
section.cta-section > .shape-one {
    background: linear-gradient(135deg, rgba(12, 150, 209, 0.4) 0%, rgba(19, 176, 228, 0.1) 100%) !important;
    opacity: 1 !important;
}

section.cta-section > .shape-two {
    background: linear-gradient(135deg, rgba(12, 150, 209, 0.3) 0%, rgba(19, 176, 228, 0.05) 100%) !important;
    opacity: 1 !important;
}

section.cta-section > .shape-three {
    background: linear-gradient(135deg, rgba(12, 150, 209, 0.2) 0%, rgba(19, 176, 228, 0.02) 100%) !important;
    opacity: 1 !important;
}

/* Thay đổi màu cho icon circle chỉ trong cta section */
section.cta-section .cta-card > .cta-card__icon {
    background: linear-gradient(135deg, #0c96d1 0%, #13b0e4 100%) !important;
    color: #ffffff !important;
}

/* Đảm bảo icon bên trong cta section có màu trắng */
section.cta-section .cta-card > .cta-card__icon i,
section.cta-section .cta-card > .cta-card__icon svg {
    color: #ffffff !important;
    fill: #ffffff !important;
}
</style>
