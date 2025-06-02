@php
    $faqContent = getContent('faq.content', true);
    $faqElements = getContent('faq.element', false, null, true);
@endphp
<div class="section py-5"
    style="background-image: url({{ frontendImage('faq', @$faqContent->data_values->image, '1920x1080') }})">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="accordion custom--accordion" id="accordionExample">
                    @foreach ($faqElements as $faqElement)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ !$loop->first ? 'collapsed' : null }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq_{{ $loop->index }}"
                                    aria-expanded="{{ !$loop->first ? 'false' : 'true' }}">
                                    {{ __(@$faqElement->data_values->question) }}
                                </button>
                            </h2>
                            <div id="faq_{{ $loop->index }}"
                                class="accordion-collapse collapse {{ $loop->first ? 'show' : null }}"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body"> {{ __(@$faqElement->data_values->answer) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Thay đổi màu và style cho accordion */
.accordion .accordion-item {
    border: none !important;
    margin-bottom: 15px !important;
    border-radius: 10px !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05) !important;
}

/* Style cho header */
.accordion .accordion-header .accordion-button {
    color: #0197d0 !important;
    font-weight: 600 !important;
    padding: 20px 25px !important;
    border-radius: 10px !important;
    transition: all 0.3s ease !important;
}

/* Style khi active/hover */
.accordion .accordion-header .accordion-button:not(.collapsed),
.accordion .accordion-header .accordion-button:hover {
    color: #0197d0 !important;
    background: linear-gradient(135deg, rgba(1, 151, 208, 0.1) 0%, rgba(1, 151, 208, 0.05) 100%) !important;
    box-shadow: 0 3px 15px rgba(1, 151, 208, 0.1) !important;
    transform: translateY(-1px) !important;
}

/* Style cho icon mũi tên */
.accordion .accordion-header .accordion-button::after {
    color: #0197d0 !important;
    border-color: #0197d0 !important;
    transition: transform 0.3s ease !important;
}

/* Style cho phần nội dung */
.accordion .accordion-body {
    padding: 20px 25px !important;
    line-height: 1.6 !important;
    color: #555555 !important;
    background-color: rgba(1, 151, 208, 0.02) !important;
}

/* Animation khi mở/đóng */
.accordion .accordion-collapse {
    transition: all 0.3s ease-out !important;
}
</style>
