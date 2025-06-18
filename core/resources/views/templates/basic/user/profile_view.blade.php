@extends($activeTemplate . 'layouts.frontend')
@section('content')
<section class="profile-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-circle">
                            <img src="{{ getUserAvatar($user) }}" alt="Profile">
                        </div>
                    </div>
                    <div class="profile-info">
                        <h2>{{ $user->fullname ?? 'Chưa cập nhật' }}</h2>
                        <p>{{ $user->email }}</p>
                        <div class="profile-status">
                            <span class="status-badge success">
                                <i class="las la-check-circle"></i>
                                Hồ sơ đã hoàn thiện
                            </span>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('user.home') }}" class="btn btn-outline-primary">
                            <i class="las la-arrow-left me-2"></i>Quay lại
                        </a>
                        <a href="{{ route('user.profile.edit') }}" class="btn btn-primary">
                            <i class="las la-edit me-2"></i>Chỉnh sửa
                        </a>
                    </div>
                </div>

                <!-- Profile Information Display -->
                <div class="profile-display-container">
                    
                    <!-- Basic Information Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="las la-user-edit me-2"></i>Thông tin cơ bản</h4>
                        </div>
                        
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-user"></i>
                                            Tên đăng nhập
                                        </div>
                                        <div class="info-value">{{ $user->username }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-envelope"></i>
                                            Email
                                        </div>
                                        <div class="info-value">{{ $user->email }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-phone"></i>
                                            Số điện thoại
                                        </div>
                                        <div class="info-value">{{ $user->mobile ?: 'Chưa cập nhật' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-calendar"></i>
                                            Thành viên từ
                                        </div>
                                        <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="las la-map-marker-alt me-2"></i>Thông tin địa chỉ</h4>
                        </div>
                        
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-city"></i>
                                            Thành phố
                                        </div>
                                        <div class="info-value">{{ $user->city ?: 'Chưa cập nhật' }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-building"></i>
                                            Quận/Huyện
                                        </div>
                                        <div class="info-value">{{ $user->district ?: 'Chưa cập nhật' }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-home"></i>
                                            Phường/Xã
                                        </div>
                                        <div class="info-value">{{ $user->ward ?: 'Chưa cập nhật' }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-map"></i>
                                            Địa chỉ chi tiết
                                        </div>
                                        <div class="info-value">{{ $user->address ?: 'Chưa cập nhật' }}</div>
                                    </div>
                                </div>

                                @if($user->city && $user->district && $user->ward && $user->address)
                                <div class="col-md-12">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-location-arrow"></i>
                                            Địa chỉ đầy đủ
                                        </div>
                                        <div class="info-value address-full">
                                            {{ $user->address }}, {{ $user->ward }}, {{ $user->district }}, {{ $user->city }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Account Status Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="las la-shield-alt me-2"></i>Trạng thái tài khoản</h4>
                        </div>
                        
                        <div class="section-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-user-check"></i>
                                            Trạng thái tài khoản
                                        </div>
                                        <div class="info-value">
                                            <span class="badge badge-{{ $user->status == 1 ? 'success' : 'warning' }}">
                                                {{ $user->status == 1 ? 'Đã kích hoạt' : 'Chưa kích hoạt' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-envelope-open-text"></i>
                                            Email
                                        </div>
                                        <div class="info-value">
                                            <span class="badge badge-{{ $user->ev == 1 ? 'success' : 'warning' }}">
                                                {{ $user->ev == 1 ? 'Đã xác thực' : 'Chưa xác thực' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-sms"></i>
                                            Số điện thoại
                                        </div>
                                        <div class="info-value">
                                            <span class="badge badge-{{ $user->sv == 1 ? 'success' : 'warning' }}">
                                                {{ $user->sv == 1 ? 'Đã xác thực' : 'Chưa xác thực' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="las la-user-cog"></i>
                                            Hoàn thiện hồ sơ
                                        </div>
                                        <div class="info-value">
                                            <span class="badge badge-{{ $user->profile_complete == 1 ? 'success' : 'warning' }}">
                                                {{ $user->profile_complete == 1 ? 'Đã hoàn thiện' : 'Chưa hoàn thiện' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($user->companies->count() > 0)
                    <!-- Business Information Section -->
                    <div class="info-section">
                        <div class="section-header">
                            <h4><i class="las la-briefcase me-2"></i>Thông tin doanh nghiệp</h4>
                        </div>
                        
                        <div class="section-content">
                            <div class="companies-list">
                                @foreach($user->companies as $company)
                                <div class="company-item">
                                    <div class="company-info">
                                        <div class="company-name">
                                            <i class="las la-building"></i>
                                            {{ $company->name }}
                                        </div>
                                        <div class="company-details">
                                            <span class="badge badge-{{ $company->status == 1 ? 'success' : 'warning' }}">
                                                {{ $company->status == 1 ? 'Đã duyệt' : 'Chờ duyệt' }}
                                            </span>
                                            <span class="company-date">
                                                <i class="las la-calendar"></i>
                                                Tạo {{ $company->created_at->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="company-actions">
                                        <a href="{{ route('company.details', [$company->id, slug($company->name)]) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="las la-eye"></i>
                                            Xem chi tiết
                                        </a>
                                        <a href="{{ route('user.company.edit', $company->id) }}" class="btn btn-sm btn-primary">
                                            <i class="las la-edit"></i>
                                            Chỉnh sửa
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    .profile-section {
        background: linear-gradient(135deg, #102f4b 0%, #1a4568 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .profile-header {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(16, 47, 75, 0.1);
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-avatar {
        flex-shrink: 0;
    }

    .avatar-circle {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #48bbe2, #102f4b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 40px;
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.3);
        overflow: hidden;
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .profile-info {
        flex: 1;
        min-width: 200px;
    }

    .profile-info h2 {
        color: #102f4b;
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .profile-info > p {
        color: #6c757d;
        margin: 0 0 15px 0;
        font-size: 18px;
    }

    .profile-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .profile-actions .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    /* Fix btn-outline-primary globally on this page */
    .btn-outline-primary {
        border-color: #48bbe2 !important;
        color: #48bbe2 !important;
        background: transparent !important;
    }

    .btn-outline-primary:hover {
        background: #48bbe2 !important;
        border-color: #48bbe2 !important;
        color: white !important;
    }

    .profile-actions .btn-outline-primary {
        border-color: #48bbe2 !important;
        color: #48bbe2 !important;
        background: transparent !important;
    }

    .profile-actions .btn-outline-primary:hover {
        background: #48bbe2 !important;
        border-color: #48bbe2 !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.3);
    }

    .profile-actions .btn-primary {
        background: linear-gradient(135deg, #48bbe2, #102f4b);
        border: none;
    }

    .profile-actions .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 187, 226, 0.4);
    }

    .profile-display-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(16, 47, 75, 0.1);
        overflow: hidden;
    }

    .info-section {
        padding: 40px;
        border-bottom: 1px solid #f1f3f7;
    }

    .info-section:last-child {
        border-bottom: none;
    }

    .section-header {
        margin-bottom: 30px;
    }

    .section-header h4 {
        color: #102f4b;
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .section-header h4 i {
        color: #48bbe2;
        margin-right: 8px;
    }

    .info-item {
        margin-bottom: 25px;
    }

    .info-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #102f4b;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .info-label i {
        color: #48bbe2;
        margin-right: 8px;
        width: 18px;
    }

    .info-value {
        font-size: 16px;
        color: #495057;
        padding: 12px 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #48bbe2;
    }

    .info-value.address-full {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        font-weight: 500;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .badge-warning {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .status-badge.success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-badge i {
        font-size: 16px;
    }

    .companies-list {
        space-y: 15px;
    }

    .company-item {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .company-item:hover {
        border-color: #48bbe2;
        box-shadow: 0 4px 15px rgba(72, 187, 226, 0.1);
    }

    .company-info {
        flex: 1;
    }

    .company-name {
        font-size: 18px !important;
        font-weight: 600 !important;
        color: #102f4b !important;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }

    .company-name i {
        color: #48bbe2;
        margin-right: 8px;
    }

    .company-details {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .company-date {
        color: #6c757d;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .company-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .company-actions .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        flex: 1;
        min-width: 120px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .profile-header {
            text-align: center;
            flex-direction: column;
        }

        .profile-actions {
            justify-content: center;
        }

        .company-item {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .company-details {
            justify-content: center;
        }
    }
</style>
@endpush 