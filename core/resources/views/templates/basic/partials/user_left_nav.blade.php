<div class="profile-sidebar">
    <div class="profile-widget">
        <div class="thumb">
            <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile'),isAvatar:true) }}" />
        </div>
        <h4 class="profile-name text-center mt-4"> {{ __(@ucwords($user->fullname)) }} </h4>
        <p class="text-center"><i class="la la-map-marker-alt"></i>
            @if ($user->city)
                {{ __(@ucwords($user->city)) }},
            @endif
            {{ __(@ucwords($user->country_name)) }}
        </p>
        @if ($user->about)
            <hr>
            <p> {{ __($user->about) }} </p>
        @endif
    </div>

    <div class="profile-widget mt-5">
        <h5 class="profile-widget__title">@lang('Tóm tắt tài khoản')</h5>
        <ul class="profile-info-list">
            <li><i class="la la-user"></i> @lang('Là thành viên từ: '){{ showDateTime($user->created_at, 'Y') }} </li>
            <li><i class="la la-envelope"></i> {{ $user->email }}</li>
            <li><i class="la la-star"></i> @lang('Tổng số lượt đánh giá ')
                <span class="text--base"> &nbsp; {{ $totalReview ?? 0 }}</span>
            </li>
        </ul>
    </div>

    <div class="profile-widget mt-4">
        <h5 class="profile-widget__title">@lang('Menu nhanh')</h5>
        <div class="d-grid gap-2">
            <a href="{{ route('user.profile.setting') }}" class="btn btn-outline-primary btn-sm">
                <i class="las la-user-cog me-2"></i>@lang('Cập nhật hồ sơ')
            </a>
            <a href="{{ route('user.change.password') }}" class="btn btn-outline-secondary btn-sm">
                <i class="las la-key me-2"></i>@lang('Đổi mật khẩu')
            </a>
            <a href="{{ route('ticket.index') }}" class="btn btn-outline-info btn-sm">
                <i class="las la-life-ring me-2"></i>@lang('Hỗ trợ')
            </a>
            <a href="{{ route('user.logout') }}" class="btn btn-outline-danger btn-sm">
                <i class="las la-sign-out-alt me-2"></i>@lang('Đăng xuất')
            </a>
        </div>
    </div>

    {{-- Ad-div --}}
    <div class="has--link item--link mt-4">
        @php
            echo getAdvertisement('300x600');
        @endphp
    </div>
</div>
