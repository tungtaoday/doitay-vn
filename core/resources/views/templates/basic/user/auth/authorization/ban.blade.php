@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="maintenance-page flex-column justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="text-center">
                        <h4 class="text-center text-danger">@lang('YOU ARE BANNED')</h4>
                        <p class="fw-bold mb-1">@lang('Reason'):</p>
                        <p>{{ $user->ban_reason }}</p>
                        <div class="d-flex gap-2 justify-content-center mt-4">
                            <a href="{{route('home')}}" class="btn btn--base">@lang('Browse') {{ __(gs('site_name')) }}</a>
                            <a href="{{route('user.logout')}}" class="btn btn-outline--base">@lang('Logout')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('style')
    <style>
        header {
            display: none;
        }

        footer {
            display: none;
        }

        .breadcrumb,.inner-hero {
            display: none;
        }

        .maintenance-page {
            background-color: white;
            display: flex;
            align-items: center;
            height: 100vh;
            justify-content: center;
        }
    </style>
@endpush
