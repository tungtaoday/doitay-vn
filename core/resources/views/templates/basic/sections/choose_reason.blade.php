@php
    $chooseContent = getContent('choose_reason.content', true);
    $chooseElements = getContent('choose_reason.element', false, null, true);
@endphp
<section class="pt-100 pb-100 glass--overlay overflow-hidden">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-6">
                <div class="section-header text-center wow fadeInLeft" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <div class="section-subtitle border-left-right text--base">
                        {{ __(@$chooseContent->data_values->subheading) }}</div>
                    <h2 class="section-title">{{ __(@$chooseContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row g-5 justify-content-center">
            @foreach ($chooseElements as $chooseElement)
                <div class="col-lg-4 col-md-6 choose-item wow fadeInLeft" data-wow-duration="0.5" data-wow-delay="0.3s">
                    <div class="choose-card custom-choose-card">
                        <div class="cta-card__icon custom-choose-icon">
                            @php
                                echo @$chooseElement->data_values->icon;
                            @endphp
                        </div>
                        <div class="choose-card__content text-center">
                            <h3 class="choose-card__title text-primary-custom">{{ __(@$chooseElement->data_values->title) }}</h3>
                            <p class="choose-card__description mt-3 text-muted">
                                {{ __(@$chooseElement->data_values->description) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
/* Màu chủ đạo đồng bộ header */
.text-primary-custom {
    color: #08204e !important;
}

/* Card đẹp, bo góc, bóng đổ, hover nổi */
.custom-choose-card {
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(12,150,209,0.08);
    border: 1.5px solid #eaf6fb;
    background: #fff;
    transition: all 0.3s cubic-bezier(.4,2,.6,1);
    padding: 3.5rem 1.5rem 2rem 1.5rem;
    min-height: 340px;
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
}
.custom-choose-card:hover {
    box-shadow: 0 16px 40px 0 rgba(12,150,209,0.18);
    border-color: #0c96d1;
    background: linear-gradient(120deg, #eaf6fb 0%, #fafdff 100%);
    transform: translateY(-8px) scale(1.03);
}

/* Icon nổi bật, bo tròn, nền nhạt, icon xanh */
.custom-choose-icon {
    width: 70px;
    height: 70px;
    background: #eaf6fb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2.2rem;
    color: #0c96d1;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10);
    transition: box-shadow 0.3s, transform 0.3s, background 0.3s, color 0.3s;
}
.custom-choose-card:hover .custom-choose-icon {
    color: #fff;
    background: linear-gradient(135deg, #0c96d1 60%, #13b0e4 100%);
    box-shadow: 0 4px 16px rgba(12,150,209,0.18);
}

/* Tiêu đề và mô tả căn giữa */
.choose-card__title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #0c96d1;
    letter-spacing: 0.5px;
}
.choose-card__description {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .custom-choose-card { min-height: 280px; padding: 2rem 1rem; }
    .custom-choose-icon { width: 56px; height: 56px; font-size: 1.5rem; }
}

/* Ghi đè mọi background cam khi hover card */
div.choose-card,
div.custom-choose-card,
div.choose-card:hover,
div.custom-choose-card:hover,
div.choose-card:active,
div.custom-choose-card:active,
div.choose-card:focus,
div.custom-choose-card:focus,
div.choose-card:visited,
div.custom-choose-card:visited,
div.choose-card::before,
div.custom-choose-card::before,
div.choose-card::after,
div.custom-choose-card::after {
    background: #fff !important;
    background-color: #fff !important;
    background-image: none !important;
    box-shadow: none !important;
}

.custom-choose-icon i,
.custom-choose-icon svg {
    transition: transform 0.3s;
}

.custom-choose-card:hover .custom-choose-icon {
    color: #fff;
    background: linear-gradient(135deg, #0c96d1 60%, #13b0e4 100%);
    box-shadow: 0 4px 16px rgba(12,150,209,0.18);
}

/* Tắt toàn bộ hiệu ứng hover cho card và icon */
.custom-choose-card:hover,
.custom-choose-card:active,
.custom-choose-card:focus {
    /* Không đổi màu, không phóng to, không đổi bóng */
    box-shadow: 0 4px 24px rgba(12,150,209,0.08) !important;
    border-color: #eaf6fb !important;
    background: #fff !important;
    background-color: #fff !important;
    transform: none !important;
}

.custom-choose-card:hover .custom-choose-icon,
.custom-choose-card:active .custom-choose-icon,
.custom-choose-card:focus .custom-choose-icon {
    /* Không đổi màu, không phóng to, không đổi bóng */
    color: #0c96d1 !important;
    background: #eaf6fb !important;
    box-shadow: 0 2px 8px rgba(12,150,209,0.10) !important;
    transform: none !important;
}

.section-header {
    color: #08204e !important;
}
.section-header .section-title {
    color: #08204e !important;
}
.section-header .section-subtitle {
    color: #15733e !important;
}
</style>
