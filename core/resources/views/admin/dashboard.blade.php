@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                link="{{route('admin.users.all')}}"
                icon="las la-users"
                title="Total Users"
                value="{{$widget['total_users']}}"
                bg="primary"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                link="{{route('admin.users.active')}}"
                icon="las la-user-check"
                title="Active Users"
                value="{{$widget['verified_users']}}"
                bg="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                link="{{route('admin.users.email.unverified')}}"
                icon="lar la-envelope"
                title="Email Unverified Users"
                value="{{$widget['email_unverified_users']}}"
                bg="danger"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                link="{{route('admin.users.mobile.unverified')}}"
                icon="las la-comment-slash"
                title="Mobile Unverified Users"
                value="{{$widget['mobile_unverified_users']}}"
                bg="warning"
            />
        </div>
    </div>

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                outline="true"
                link="{{route('admin.company.index')}}"
                icon="fas fa-bank"
                title="Total Companies"
                value="{{$widget['total_companies']}}"
                bg="primary"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                outline="true"
                link="{{route('admin.company.approved')}}"
                icon="fas fa-thumbs-up"
                title="Total Approved Companies"
                value="{{$widget['total_approved_companies']}}"
                bg="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                outline="true"
                link="{{route('admin.company.pending')}}"
                icon="fas fa-pause-circle"
                title="Total Pending Companies"
                value="{{$widget['total_pending_companies']}}"
                bg="warning"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="6"
                outline="true"
                link="{{route('admin.company.rejected')}}"
                icon="fas fa-ban"
                title="Total Rejected Companies"
                value="{{$widget['total_rejected_companies']}}"
                bg="danger"
            />
        </div>
    </div>
    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        piChart(
            document.getElementById('userBrowserChart'),
            @json(@$chart['user_browser_counter']->keys()),
            @json(@$chart['user_browser_counter']->flatten())
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(@$chart['user_os_counter']->keys()),
            @json(@$chart['user_os_counter']->flatten())
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(@$chart['user_country_counter']->keys()),
            @json(@$chart['user_country_counter']->flatten())
        );
    </script>
@endpush
