@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)
        ->where('is_default', Status::NO)
        ->get();
@endphp

<!-- Modern Header -->
<header class="modern-header">
    <div class="header-container">
        <!-- Logo -->
        <a class="logo" href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}">
                </a>

        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <ul class="nav-menu">
                        <li class="{{ menuActive('home') }}">
                            <a href="{{ route('home') }}">@lang('Trang chủ')</a>
                        </li>
                        <li class="{{ menuActive('company.*') }}">
                            <a href="{{ route('company.all') }}">@lang('Thợ chuyên nghiệp')</a>
                        </li>
                        <li class="{{ menuActive('blog') }}">
                            <a href="{{ route('blog') }}">@lang('Blog')</a>
                        </li>
                        @auth
                            @if(auth()->user()->companies->count() > 0)
                                <li class="{{ menuActive('company.appointments.*') }}">
                                    <a href="{{ route('company.appointments.index') }}">@lang('Quản lý lịch hẹn')</a>
                                </li>
                            @else
                                <li class="{{ menuActive('appointments.*') }}">
                                    <a href="{{ route('appointments.index') }}">@lang('Lịch hẹn của tôi')</a>
                                </li>
                            @endif
                        @endauth
                        <!-- @foreach ($pages as $k => $data)
                            <li class="{{ menuActive('pages', null, $data->slug) }}">
                                <a href="{{ route('pages', $data->slug) }}">{{ __($data->name) }}</a>
                            </li>
                        @endforeach -->
                        @guest
                            <li class="{{ menuActive('contact') }}">
                                <a href="{{ route('contact') }}">@lang('Liên hệ')</a>
                            </li>
                        @endguest
                        <li class="become-contractor-nav">
                            <a href="{{ route('become.contractor') }}" class="btn-become-contractor">
                                <i class="las la-tools"></i>
                                <span>@lang('Trở thành thợ')</span>
                            </a>
                        </li>
                                </ul>
        </nav>

        <!-- Action Buttons -->
        <div class="header-actions">
                        @auth
                <!-- Notification Bell -->
                @include('user.partials.notification_bell')
            @endauth
            @guest
                <a href="{{ route('user.login.v2') }}" class="btn-login">
                    <i class="las la-sign-in-alt"></i>
                    <span>@lang('Đăng nhập')</span>
                                </a>
                <a href="{{ route('user.register.v2') }}" class="btn-register">
                    <i class="las la-user-plus"></i>
                    <span>@lang('Đăng ký')</span>
                                </a>
                            @endguest
                            @auth
                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="user-toggle">
                        <div class="user-avatar">
                            <i class="las la-user"></i>
                        </div>
                        <span class="user-name">{{ auth()->user()->username }}</span>
                        <i class="las la-chevron-down"></i>
                    </button>
                    <div class="user-menu">
                        @if(auth()->user()->companies->count() > 0)
                            {{-- Menu cho thợ --}}
                            <a href="{{ route('company.appointments.index') }}">
                                <i class="las la-calendar-check"></i>
                                @lang('Quản lý lịch hẹn')
                            </a>
                            <a href="{{ route('user.wallet.index') }}">
                                <i class="las la-wallet"></i>
                                @lang('Quản lý ví')
                            </a>
                            <a href="{{ route('user.company.index') }}">
                                <i class="las la-tools"></i>
                                @lang('Hồ sơ thợ của tôi')
                            </a>
                        @else
                            {{-- Menu cho khách hàng --}}
                            <a href="{{ route('appointments.index') }}">
                                <i class="las la-calendar-alt"></i>
                                @lang('Lịch hẹn của tôi')
                            </a>
                            <a href="{{ route('user.company.create') }}">
                                <i class="las la-plus-circle"></i>
                                @lang('Trở thành Người Thợ')
                            </a>
                        @endif
                                                        <a href="{{ route('user.profile.view') }}">
                            <i class="las la-user-cog"></i>
                            @lang('Thông tin cá nhân')
                        </a>
                        <a href="{{ route('ticket.index') }}">
                            <i class="las la-life-ring"></i>
                            @lang('Hỗ trợ')
                        </a>
                        <div class="menu-divider"></div>
                        <a href="{{ route('user.logout') }}" class="logout-btn">
                            <i class="las la-sign-out-alt"></i>
                            @lang('Đăng xuất')
                        </a>
                    </div>
                </div>
            @endauth
        </div>

        <!-- Mobile Menu Trigger -->
        <button class="mobile-menu-trigger" id="mobileMenuTrigger">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
</header>

<!-- Mobile Fullscreen Menu -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <div class="mobile-menu-content">
        <!-- Mobile Header -->
        <div class="mobile-header">
            <a class="mobile-logo" href="{{ route('home') }}">
                <img src="{{ siteLogo() }}" alt="{{ __(gs('site_name')) }}">
            </a>
            <button class="mobile-close" id="mobileMenuClose">
                <i class="las la-times"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <nav class="mobile-nav">
            <ul class="mobile-menu-list">
                <li class="{{ menuActive('home') }}">
                    <a href="{{ route('home') }}">
                        <i class="las la-home"></i>
                        @lang('Trang chủ')
                    </a>
                </li>
                <li class="{{ menuActive('company.*') }}">
                    <a href="{{ route('company.all') }}">
                        <i class="las la-tools"></i>
                        @lang('Thợ chuyên nghiệp')
                    </a>
                </li>
                <li class="{{ menuActive('blog') }}">
                    <a href="{{ route('blog') }}">
                        <i class="las la-blog"></i>
                        @lang('Blog')
                    </a>
                </li>
                @auth
                    @if(auth()->user()->companies->count() > 0)
                        <li class="{{ menuActive('company.appointments.*') }}">
                            <a href="{{ route('company.appointments.index') }}">
                                <i class="las la-calendar-check"></i>
                                @lang('Quản lý lịch hẹn')
                            </a>
                        </li>
                    @else
                        <li class="{{ menuActive('appointments.*') }}">
                            <a href="{{ route('appointments.index') }}">
                                <i class="las la-calendar-alt"></i>
                                @lang('Lịch hẹn của tôi')
                            </a>
                        </li>
                    @endif
                @endauth
                <!-- @foreach ($pages as $k => $data)
                    <li class="{{ menuActive('pages', null, $data->slug) }}">
                        <a href="{{ route('pages', $data->slug) }}">
                            <i class="las la-file-alt"></i>
                            {{ __($data->name) }}
                        </a>
                    </li>
                @endforeach -->
                @guest
                    <li class="{{ menuActive('contact') }}">
                        <a href="{{ route('contact') }}">
                            <i class="las la-envelope"></i>
                            @lang('Liên hệ')
                        </a>
                    </li>
                @endguest
                <li class="become-contractor-mobile">
                    <a href="{{ route('become.contractor') }}" class="mobile-become-contractor">
                        <i class="las la-tools"></i>
                        @lang('Trở thành thợ')
                    </a>
                </li>
            </ul>

            <!-- Mobile Auth Actions -->
            <div class="mobile-auth">
                @guest
                    <a href="{{ route('user.login.v2') }}" class="mobile-btn mobile-btn-primary">
                        <i class="las la-sign-in-alt"></i>
                        @lang('Đăng nhập')
                    </a>
                    <a href="{{ route('user.register.v2') }}" class="mobile-btn mobile-btn-secondary">
                        <i class="las la-user-plus"></i>
                        @lang('Đăng ký')
                    </a>
                @endguest
                @auth
                    <div class="mobile-user-info">
                        <div class="mobile-user-avatar">
                            <i class="las la-user"></i>
                        </div>
                        <div class="mobile-user-details">
                            <h4>{{ auth()->user()->username }}</h4>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                        <!-- Mobile Notification -->
                        <a href="{{ route('user.notifications.index') }}" class="mobile-notification-btn">
                            <i class="las la-bell"></i>
                            <span class="mobile-notification-badge" id="mobileNotificationBadge" style="display: none;">0</span>
                        </a>
                    </div>
                    <div class="mobile-user-actions">
                        @if(auth()->user()->companies->count() > 0)
                            {{-- Mobile menu cho thợ --}}
                            <a href="{{ route('company.appointments.index') }}" class="mobile-action-btn">
                                <i class="las la-calendar-check"></i>
                                @lang('Quản lý lịch hẹn')
                            </a>
                            <a href="{{ route('user.wallet.index') }}" class="mobile-action-btn">
                                <i class="las la-wallet"></i>
                                @lang('Quản lý ví')
                            </a>
                            <a href="{{ route('user.company.index') }}" class="mobile-action-btn">
                                <i class="las la-tools"></i>
                                @lang('Hồ sơ thợ của tôi')
                            </a>
                        @else
                            {{-- Mobile menu cho khách hàng --}}
                            <a href="{{ route('appointments.index') }}" class="mobile-action-btn">
                                <i class="las la-calendar-alt"></i>
                                @lang('Lịch hẹn của tôi')
                            </a>
                            <a href="{{ route('user.company.create') }}" class="mobile-action-btn">
                                <i class="las la-plus-circle"></i>
                                @lang('Trở thành Người Thợ')
                            </a>
                        @endif
                                                        <a href="{{ route('user.profile.view') }}" class="mobile-action-btn">
                            <i class="las la-user-cog"></i>
                            @lang('Thông tin cá nhân')
                        </a>
                        <a href="{{ route('ticket.index') }}" class="mobile-action-btn">
                            <i class="las la-life-ring"></i>
                            @lang('Hỗ trợ')
                        </a>
                        <a href="{{ route('user.logout') }}" class="mobile-action-btn logout">
                            <i class="las la-sign-out-alt"></i>
                            @lang('Đăng xuất')
                        </a>
                    </div>
                @endauth
            </div>
        </nav>
    </div>
</div>

<style>
/* ===== MODERN HEADER STYLES ===== */
.modern-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
}

/* Logo */
.logo img {
    height: 50px;
    width: auto;
    transition: all 0.3s ease;
}

.logo:hover img {
    transform: scale(1.05);
}

/* Desktop Navigation */
.desktop-nav {
    display: flex;
    align-items: center;
}

.nav-menu {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    gap: 8px;
}

.nav-menu li a {
    display: block;
    padding: 12px 20px;
    font-size: 15px;
    font-weight: 500;
    color: #374151;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.nav-menu li:hover a,
.nav-menu li.active a {
    color: #0b92d4;
    background: rgba(11, 146, 212, 0.1);
    transform: translateY(-2px);
}

/* Become Contractor Button Styles */
.become-contractor-nav {
    margin-left: 20px;
}

.btn-become-contractor {
    display: flex !important;
    align-items: center;
    gap: 8px;
    padding: 12px 24px !important;
    font-size: 14px;
    font-weight: 600 !important;
    color: white !important;
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    border: none;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-become-contractor::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-become-contractor:hover::before {
    left: 100%;
}

.btn-become-contractor:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #218838, #1fa085) !important;
    color: white !important;
}

.btn-become-contractor i {
    font-size: 16px;
}

/* Header Actions */
.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-login,
.btn-register {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-login {
    color: #0b92d4;
    background: transparent;
    border: 1px solid rgba(11, 146, 212, 0.3);
}

.btn-login:hover {
    background: rgba(11, 146, 212, 0.1);
    transform: translateY(-2px);
}

.btn-register {
    color: white;
    background: linear-gradient(135deg, #0b92d4, #0d84c1);
    border: none;
    box-shadow: 0 4px 15px rgba(11, 146, 212, 0.3);
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 146, 212, 0.4);
}

/* User Dropdown */
.user-dropdown {
    position: relative;
}

.user-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
    background: transparent;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-toggle:hover {
    background: rgba(0, 0, 0, 0.05);
}

.user-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #0b92d4, #0d84c1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.user-name {
    font-weight: 500;
    color: #374151;
}

.user-menu {
    position: absolute;
    top: 100%;
    right: 0;
    width: 240px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    padding: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 8px;
}

.user-dropdown:hover .user-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.user-menu a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #374151;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 14px;
}

.user-menu a:hover {
    background: rgba(11, 146, 212, 0.1);
    color: #0b92d4;
}

.user-menu a.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

.menu-divider {
    height: 1px;
    background: rgba(0, 0, 0, 0.1);
    margin: 8px 0;
}

/* Mobile Menu Trigger */
.mobile-menu-trigger {
    display: none;
    flex-direction: column;
    justify-content: space-around;
    width: 32px;
    height: 32px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 6px;
}

.hamburger-line {
    width: 100%;
    height: 2px;
    background: #374151;
    border-radius: 2px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform-origin: center;
}

.mobile-menu-trigger.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px);
}

.mobile-menu-trigger.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-menu-trigger.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px);
}

/* Mobile Menu Overlay */
.mobile-menu-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    z-index: 99999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

.mobile-menu-content {
    height: 100%;
    overflow-y: auto;
    padding: 24px;
}

.mobile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 48px;
}

.mobile-logo img {
    height: 40px;
}

.mobile-close {
    width: 48px;
    height: 48px;
    background: rgba(0, 0, 0, 0.05);
    border: none;
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #374151;
    transition: all 0.3s ease;
}

.mobile-close:hover {
    background: rgba(0, 0, 0, 0.1);
    transform: scale(1.05);
}

/* Mobile Navigation */
.mobile-menu-list {
    list-style: none;
    margin: 0;
    padding: 0;
    margin-bottom: 48px;
}

.mobile-menu-list li {
    margin-bottom: 8px;
}

.mobile-menu-list li a {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    font-size: 18px;
    font-weight: 500;
    color: #374151;
    text-decoration: none;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-menu-list li a:hover,
.mobile-menu-list li.active a {
    background: rgba(11, 146, 212, 0.1);
    color: #0b92d4;
    transform: translateX(8px);
}

.mobile-menu-list li a i {
    font-size: 24px;
    width: 32px;
    text-align: center;
}

/* Mobile Auth Actions */
.mobile-auth {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    padding-top: 32px;
}

.mobile-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 16px 24px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    border-radius: 16px;
    margin-bottom: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-btn-primary {
    color: white;
    background: linear-gradient(135deg, #0b92d4, #0d84c1);
    box-shadow: 0 8px 32px rgba(11, 146, 212, 0.3);
}

.mobile-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(11, 146, 212, 0.4);
}

.mobile-btn-secondary {
    color: #0b92d4;
    background: rgba(11, 146, 212, 0.1);
    border: 1px solid rgba(11, 146, 212, 0.3);
}

.mobile-btn-secondary:hover {
    background: rgba(11, 146, 212, 0.2);
    transform: translateY(-2px);
}

/* Mobile User Info */
.mobile-user-info {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    background: rgba(11, 146, 212, 0.05);
    border-radius: 16px;
    margin-bottom: 24px;
    position: relative;
}

.mobile-notification-btn {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px;
    color: #0b92d4;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.mobile-notification-btn:hover {
    background: #0b92d4;
    color: white;
    border-color: #0b92d4;
    transform: translateY(-50%) scale(1.1);
}

.mobile-notification-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #dc2626;
    color: white;
    border-radius: 12px;
    padding: 2px 6px;
    font-size: 10px;
    font-weight: 600;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.mobile-user-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #0b92d4, #0d84c1);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.mobile-user-details h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #374151;
}

.mobile-user-details p {
    margin: 4px 0 0 0;
    font-size: 14px;
    color: #6b7280;
}

.mobile-action-btn {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    font-size: 16px;
    color: #374151;
    text-decoration: none;
    background: rgba(0, 0, 0, 0.02);
    border-radius: 12px;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.mobile-action-btn:hover {
    background: rgba(11, 146, 212, 0.1);
    color: #0b92d4;
    transform: translateX(8px);
}

.mobile-action-btn.logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #dc2626;
}

/* Mobile Become Contractor Button */
.mobile-become-contractor {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    color: white !important;
    font-weight: 600 !important;
}

.mobile-become-contractor:hover {
    background: linear-gradient(135deg, #218838, #1fa085) !important;
    color: white !important;
    transform: translateX(8px) scale(1.02);
}

/* Responsive Behavior */
@media (max-width: 1024px) {
    .desktop-nav,
    .header-actions .btn-login,
    .header-actions .btn-register,
    .user-dropdown {
        display: none;
    }
    
    .mobile-menu-trigger {
        display: flex;
    }
    
    /* Force notification bell positioning on mobile - Absolute positioning */
    .header-container {
        position: relative !important;
        justify-content: space-between !important;
    }
    
    .notification-bell {
        position: absolute !important;
        right: 26px !important; /* Very close to mobile trigger button */
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 10 !important;
    }
    
    .header-actions {
        position: relative !important;
        display: flex !important;
        align-items: center !important;
    }
    
    .mobile-menu-trigger {
        position: absolute !important;
        right: 16px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 11 !important;
    }
    
    .logo {
        flex: 0 0 auto !important;
    }
}

@media (max-width: 768px) {
    .header-container {
        padding: 0 16px;
        height: 70px;
    }
    
    .logo img {
        height: 40px;
    }
    
    .mobile-menu-content {
        padding: 16px;
    }
    
    .mobile-menu-list li a {
        padding: 16px 20px;
        font-size: 16px;
    }
}

/* Body padding for fixed header */
body {
    padding-top: 80px;
}

@media (max-width: 768px) {
    body {
        padding-top: 70px;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuTrigger = document.getElementById('mobileMenuTrigger');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuClose = document.getElementById('mobileMenuClose');
    
    // Open mobile menu
    mobileMenuTrigger.addEventListener('click', function() {
        mobileMenuTrigger.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    // Close mobile menu
    function closeMobileMenu() {
        mobileMenuTrigger.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    
    // Close on overlay click
    mobileMenuOverlay.addEventListener('click', function(e) {
        if (e.target === mobileMenuOverlay) {
            closeMobileMenu();
        }
    });
    
    // Close on menu item click (mobile)
    const mobileMenuLinks = document.querySelectorAll('.mobile-menu-list a, .mobile-action-btn');
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', closeMobileMenu);
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('active')) {
            closeMobileMenu();
        }
    });
    
    // Header scroll effect
    let lastScrollTop = 0;
    const header = document.querySelector('.modern-header');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            header.style.transform = 'translateY(-100%)';
                } else {
            // Scrolling up
            header.style.transform = 'translateY(0)';
        }
        
        // Change background opacity on scroll
        if (scrollTop > 50) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.borderBottomColor = 'rgba(0, 0, 0, 0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.borderBottomColor = 'rgba(0, 0, 0, 0.05)';
        }
        
        lastScrollTop = scrollTop;
    });
});
</script> 
