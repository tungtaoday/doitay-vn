@php
    $breadcrumbContent = getContent('breadcrumb.content', true);
@endphp
<section class="inner-hero bg_img overlay--one"
    style="background-image: url('{{ frontendImage('breadcrumb', @$breadcrumbContent->data_values->image, '1920x840') }}');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title text-white">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
