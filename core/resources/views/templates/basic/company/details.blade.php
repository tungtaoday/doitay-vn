@extends($activeTemplate . 'layouts.frontend')

@php
    $content = getContent('breadcrumb.content', true);
@endphp

@section('content')
    <section class="section--bg pb-100">
        <div class="company-details-bg bg_img d-lg-block d-none"
            style="background-image: url('{{ getImage('assets/images/frontend/breadcrumb/' . @$content->data_values->image, '1920x840') }}');">
        </div>
        <div class="company-details-header">
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-8 ps-xxl-5">
                        <div class="row gy-4">
                            <div class="col-md-8 text-md-start text-center">
                                <div class="company-profile">
                                    <h3 class="company-profile__name">{{ $company->name }}</h3>
                                    <span class="company-profile__address"><i class="las la-map-marker-alt"></i>{{ $company->address }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @php
                                    $facebookUrl = $company->facebook_url ?? $company->url ?? '#';
                                    $isValidFacebook = str_contains($facebookUrl, 'facebook.com') || $facebookUrl === '#';
                                @endphp
                                @if ($isValidFacebook && $facebookUrl !== '#')
                                    <div class="facebook-link text-center">
                                        <a href="{{ $facebookUrl }}" target="_blank" rel="nofollow" class="facebook-btn d-flex align-items-center justify-content-center">
                                            <i class="lab la-facebook-f me-2"></i>
                                            <span>@lang('Theo dõi trên Facebook')</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="facebook-link text-center">
                                        <span class="text-muted fs--14px">@lang('Chưa có liên kết Facebook')</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="company-sidebar">
                        <div class="row gy-5">
                            <div class="company-sidebar__widget col-lg-12 col-md-5">
                                <div class="company-overview">
                                    <div class="company-overview__thumb">
                                        <img src="{{ getImage(getFilePath('company') . '/' . $company->image) }}"
                                            alt="image">
                                    </div>
                                </div>
                            </div>
                            <div class="button-container text-center appointment-button">
                                <button type="button" class="btn btn-primary btn-lg px-5 py-3 appointment-btn" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                                    <i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn với chuyên gia')
                                </button>
                            </div>

                            <div class="company-sidebar__widget col-lg-12 col-md-7">
                                <div class="rating-area d-flex align-items-center justify-content-between mb-4">
                                    <div class="rating-number">
                                        <h3 class="mb-0">{{ showAmount(@$company->avg_rating) }}</h3>
                                        <span class="text-muted fs--14px">@lang('Dựa trên') {{ @$company->ratings->count() }} @lang('đánh giá')</span>
                                    </div>
                                    <div class="rating-stars">
                                        @php echo avgRating($company->avg_rating); @endphp
                                    </div>
                                </div>

                                @for ($i = 5; $i >= 1; $i--)
                                    @php
                                        $reviewCount = $company->ratings->filter(function ($rating) use ($i) {
                                            return round($rating->avg_rating) == $i;
                                        })->count();
                                        $totalReviews = $company->ratings->count();
                                        $percentage = $totalReviews > 0 ? ($reviewCount / $totalReviews) * 100 : 0;
                                    @endphp
                                    <div class="single-review d-flex align-items-center mb-2">
                                        <span class="me-2">{{ $i }} <i class="las la-star text--base"></i></span>
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg--base" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ms-2 text-muted fs--14px">{{ showAmount($percentage) }}%</span>
                                    </div>
                                @endfor
                            </div>

                            <div class="company-sidebar__widget col-lg-12">
                                <div class="single-company-info">
                                    <h5 class="single-company-info__title">@lang('Tags')</h5>
                                    <div class="mt-3">
                                        @php
                                            $tags = is_array($company->tags) ? $company->tags : (is_string($company->tags) ? explode(',', $company->tags) : []);
                                        @endphp
                                        @foreach ($tags as $tag)
                                            <span class="badge bg--base mb-2 me-2">{{ trim($tag) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="single-company-info">
                                    <h5 class="single-company-info__title">@lang('Thông tin liên hệ')</h5>
                                    <ul class="single-company-info__list">
                                        <li>
                                            <div class="icon"><i class="las la-link"></i></div>
                                            <div class="content">
                                                <a target="_blank" href="{{ @$company->url }}">{{ @$company->url }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon"><i class="las la-map-marker-alt"></i></div>
                                            <div class="content">
                                                <p>{{ __(@$company->address) }}</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="icon"><i class="las la-envelope"></i></div>
                                            <div class="content">
                                                <a href="mailto:{{ @$company->email }}">{{ @$company->email }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Advertisement -->
                            <div class="has--link mt-4">
                                @php
                                    echo getAdvertisement('300x250');
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 ps-xxl-5 mt-5">
                    <!-- Navigation Links -->
                    <ul class="nav section-nav py-3 mb-4" id="expertNav" style="background-color: #F0F0F0;">
                        <li class="nav-item">
                            <a class="nav-link active" href="#skills"><i class="las la-tools me-2"></i>@lang('Kỹ Năng & Chứng Chỉ')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#portfolio"><i class="las la-project-diagram me-2"></i>@lang('Dự Án Tiêu Biểu')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reviews"><i class="las la-star me-2"></i>@lang('Đánh Giá')</a>
                        </li>
                    </ul>

                    <!-- Sections -->
                    <div class="sections">
                        <!-- Skillset and Credential -->
                        <section id="skills" class="mb-5">
                            <h4 class="mb-4"><i class="las la-tools me-2"></i>@lang('Kỹ Năng & Chứng Chỉ')</h4>
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="single-company-info">
                                        <h5 class="single-company-info__title">@lang('Giới thiệu về') {{ __($company->name) }}</h5>
                                        <p class="mt-2">{{ __(@$company->description) }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="las la-tools me-2"></i>@lang('Kỹ Năng')</h6>
                                    <ul class="list-unstyled">
                                        @php
                                            $skills = is_array($company->tags) ? $company->tags : (is_string($company->tags) ? explode(',', $company->tags) : []);
                                        @endphp
                                        @forelse ($skills as $skill)
                                            <li><i class="las la-check-circle text-primary me-2"></i> {{ trim($skill) }}</li>
                                        @empty
                                            <li>@lang('Chưa có kỹ năng nào được liệt kê.')</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="las la-certificate me-2"></i>@lang('Chứng Chỉ & Kinh Nghiệm')</h6>
                                    {{-- <ul class="list-unstyled">
                                        @forelse ($credentials as $credential)
                                            <li><i class="las la-certificate text-primary me-2"></i> {{ $credential }}</li>
                                        @empty
                                            <li>@lang('Chưa có chứng chỉ nào được liệt kê.')</li>
                                        @endforelse
                                    </ul> --}}
                                </div>
                            </div>
                        </section>

                        <!-- Portfolio -->
                        <section id="portfolio" class="mb-5">
                            <h4 class="mb-4"><i class="las la-project-diagram me-2"></i>@lang('Dự Án Tiêu Biểu')</h4>
                            {{-- <div class="row">
                                @forelse ($portfolio as $item)
                                    <div class="col-md-4 mb-4">
                                        <div class="card">
                                            <img src="{{ asset('assets/images/portfolio/' . $item['image']) }}" class="card-img-top" alt="{{ $item['title'] }}" loading="lazy">
                                            <div class="card-body">
                                                <h6>{{ $item['title'] }}</h6>
                                                <p>{{ Str::limit($item['description'], 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <p>@lang('Chưa có dự án nào được liệt kê.')</p>
                                    </div>
                                @endforelse
                            </div> --}}
                        </section>

                        <!-- Reviews -->
                        <section id="reviews" class="mb-5">
                            <h4 class="mb-4"><i class="las la-star me-2"></i>@lang('Đánh Giá')</h4>
                            <div class="rating-area mb-4">
                                <div class="rating">{{ showAmount(@$company->avg_rating) }}</div>
                                <div class="ratings d-flex align-items-center">
                                    @php echo avgRating($company->avg_rating); @endphp
                                </div>
                                <span class="text-muted fs--14px">@lang('Dựa trên') {{ @$company->ratings->count() }} @lang('đánh giá')</span>
                            </div>
                            @auth
                                @if (!$myReview)
                                    <div class="give-rating-area mb-5">
                                        <form action="{{ route('company.user.review', $company->id) }}" method="POST" class="disableSubmission">
                                            @csrf
                                            <div class="give-rating-person d-flex align-items-center mb-3">
                                                <div class="thumb me-3">
                                                    <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image, isAvatar: true) }}" alt="User">
                                                </div>
                                                <div class="content">
                                                    <h6>{{ auth()->user()->fullname }}</h6>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <h5>@lang('Đánh giá các tính năng')</h5>
                                                @foreach($features as $feature)
                                                    <div class="feature-rating mb-3">
                                                        <label>{{ $feature->name }}</label>
                                                        <div class="give-rating">
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <span>
                                                                    <input
                                                                        id="feature{{ $feature->id }}-str{{ $i }}"
                                                                        name="rating[{{ $feature->id }}]"
                                                                        type="radio"
                                                                        data-feature-id="{{ $feature->id }}"
                                                                        value="{{ $i }}"
                                                                    >
                                                                    <label for="feature{{ $feature->id }}-str{{ $i }}">
                                                                        <i class="las la-star fa-sm"></i>
                                                                    </label>
                                                                </span>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <textarea name="review" class="form-control mt-3" placeholder="@lang('Viết đánh giá')" required>{{ old('review') }}</textarea>
                                            <div class="text-end mt-3">
                                                <button type="submit" class="btn btn-primary submitBtn" disabled>@lang('Gửi')</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <div class="give-rating-area mb-5 text-center">
                                    <p>@lang('Bạn cần') <a href="{{ route('user.login') }}" class="text--base">@lang('đăng nhập')</a> @lang('để gửi đánh giá.')</p>
                                </div>
                            @endauth
                            <div class="customer-review-wrapper">
                                @include($activeTemplate . 'partials.company_review')
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Review Modal -->
        <div class="modal fade" id="reviewUpdateModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewUpdateModalLabel">@lang('Cập nhật đánh giá')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('user.review.update') }}" method="POST" class="disableSubmission">
                            @csrf
                            <div class="row align-items-center mb-3">
                                <div class="col-lg-6">
                                    <div class="d-flex align-items-center">
                                        <div class="t-company-content">
                                            <h6 class="view-company"></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach($features as $feature)
                                <div class="mb-3">
                                    <label for="feature-rating-{{ $feature->id }}" class="form-label">{{ $feature->name }}</label>
                                    <div class="give-rating-update text--base" id="feature-rating-{{ $feature->id }}">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <span id="existed-rating-{{ $feature->id }}-{{ $i }}">
                                                <input id="star{{ $feature->id }}-{{ $i }}" name="rating[{{ $feature->id }}]" type="radio" value="{{ $i }}" data-feature-id="{{ $feature->id }}">
                                                <label for="star{{ $feature->id }}-{{ $i }}"><i class="las la-star fa-sm"></i></label>
                                            </span>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                            <input type="hidden" class="edit-id" value="" name="id">
                            <textarea name="review" class="form-control edit-review"></textarea>
                            <div class="text-end">
                                <button type="submit" class="btn btn--base">@lang('Cập nhật')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Delete Modal -->
        <div class="modal fade" id="reviewDeleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewDeleteModalLabel">@lang('Xác nhận')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Bạn có chắc chắn muốn xóa đánh giá này?')</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('user.review.delete') }}" method="POST" class="disableSubmission">
                            @csrf
                            <input type="hidden" name="id" value="" class="delete-id">
                            <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal">@lang('Không')</button>
                            <button type="submit" class="btn btn-sm btn--base">@lang('Có')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Modal -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('appointments.create') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="appointmentModalLabel"><i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="company_id" value="{{ $company->id }}">
                            @auth
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            @endauth
                            @guest
                                <div class="mb-3">
                                    <label for="email" class="form-label">@lang('Email')</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        class="form-control"
                                        placeholder="@lang('Nhập email của bạn')"
                                        required
                                        onchange="checkEmailExists(this.value)">
                                    <div id="email-error" class="text-danger mt-2" style="display: none;">
                                        @lang('Email đã tồn tại, vui lòng') <a href="{{ route('user.login') }}">@lang('đăng nhập')</a>.
                                    </div>
                                </div>
                            @endguest
                            <div class="mb-3">
                                <label for="name" class="form-label">@lang('Họ và tên')</label>
                                <input type="text" id="name" name="recipient_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">@lang('Số điện thoại')</label>
                                <input type="text" id="phone" name="recipient_phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">@lang('Địa chỉ')</label>
                                <textarea id="address" name="recipient_address" class="form-control" rows="1"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="appointmentDate" class="form-label">@lang('Ngày')</label>
                                <input type="date" id="appointmentDate" name="appointmentDate" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="appointmentTime" class="form-label">@lang('Giờ')</label>
                                <input type="time" id="appointmentTime" name="appointmentTime" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">@lang('Ghi chú')</label>
                                <textarea id="notes" name="notes" class="form-control" rows="1"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Đóng')</button>
                            <button type="submit" class="btn btn-primary">@lang('Đặt lịch')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>    
        <div class="fixed-appointment-btn d-none">
            <button type="button" class="btn btn-primary btn-lg w-100 py-3 appointment-btn" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                <i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn với chuyên gia')
            </button>
        </div>    
    </section>
    
@endsection

@push('style')
<style>
    .company-details-bg {
        height: 300px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    .company-details-header {
        background: rgba(0,0,0,0.7);
        padding: 30px 0;
        margin-top: -100px;
        position: relative;
        z-index: 1;
    }
    .company-profile__name {
        color: #fff;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
    }
    .company-profile__address {
        color: #fff;
        font-size: 16px;
    }
    .company-profile__address i {
        margin-right: 5px;
    }
    .company-sidebar {
        position: relative;
        z-index: 2;
    }
    .company-overview__thumb {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        position: relative;
        z-index: 3;
    }
    .company-overview__thumb img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }
    .facebook-link {
        position: relative;
        z-index: 3;
    }
    .facebook-btn {
        background: #1877f2;
        color: #fff;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .facebook-btn:hover {
        background: #166fe5;
        transform: translateY(-2px);
        color: #fff;
    }
    .rating-area {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .rating {
        font-size: 36px;
        font-weight: 600;
        color: var(--base);
    }
    .single-review {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .single-review .star {
        margin: 0;
        margin-right: 10px;
        color: var(--base);
    }
    .single-review .progress {
        flex: 1;
        height: 8px;
        margin: 0 10px;
        background: #e9ecef;
    }
    .single-review .progress-bar {
        background: var(--base);
    }
    .single-review .percentage {
        color: #6c757d;
        font-size: 14px;
    }
    .single-company-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .single-company-info__title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--base);
    }
    .single-company-info__list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .single-company-info__list li {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    .single-company-info__list li:last-child {
        margin-bottom: 0;
    }
    .single-company-info__list .icon {
        width: 30px;
        height: 30px;
        background: var(--base);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }
    .single-company-info__list .content {
        flex: 1;
    }
    .single-company-info__list .content a {
        color: var(--base);
        text-decoration: none;
    }
    .single-company-info__list .content a:hover {
        text-decoration: underline;
    }
    .section-nav {
        border-radius: 10px;
        overflow: hidden;
    }
    .section-nav .nav-link {
        color: #6c757d;
        font-weight: 500;
        padding: 15px 20px;
        transition: all 0.3s ease;
    }
    .section-nav .nav-link:hover,
    .section-nav .nav-link.active {
        color: var(--base);
        background: #f8f9fa;
    }
    .give-rating-area {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .give-rating-person {
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .give-rating-person .thumb {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    .give-rating-person .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .feature-rating {
        margin-bottom: 15px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .feature-rating label {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }
    .give-rating {
        display: flex;
        gap: 5px;
    }
    .give-rating input[type="radio"] {
        display: none;
    }
    .give-rating label {
        cursor: pointer;
        color: #ddd;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    .give-rating input[type="radio"]:checked ~ label,
    .give-rating label:hover,
    .give-rating label:hover ~ label {
        color: #ffc107;
    }
    .customer-review {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .customer-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .customer-review__thumb {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    .customer-review__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .customer-review__header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    .customer-review__content {
        flex: 1;
        margin-left: 15px;
    }
    .customer-review__name {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .customer-review__date {
        font-size: 12px;
        color: #6c757d;
    }
    .customer-review__body {
        margin-top: 15px;
    }
    .customer-review__features {
        margin-top: 10px;
    }
    .customer-review__feature {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    .customer-review__feature-name {
        width: 120px;
        font-size: 13px;
        color: #6c757d;
    }
    .customer-review__feature-rating {
        display: flex;
        gap: 2px;
    }
    .customer-review__feature-rating i {
        font-size: 12px;
        color: #ffc107;
    }
    .customer-review__text {
        margin-top: 10px;
        font-size: 14px;
        line-height: 1.6;
    }
    .customer-review__footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .customer-review__actions {
        display: flex;
        gap: 10px;
    }
    .customer-review__action {
        color: #6c757d;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .customer-review__action:hover {
        color: var(--base);
    }
    .reaction-icon {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .reaction-icon:hover {
        transform: scale(1.2);
    }
    .reaction-icon.active {
        color: var(--base);
    }
    .reaction-count {
        font-size: 12px;
        margin-left: 5px;
    }
    @media (max-width: 767px) {
        .customer-review {
            padding: 15px;
        }
        .customer-review__header {
            flex-direction: column;
            align-items: flex-start;
        }
        .customer-review__content {
            margin-left: 0;
            margin-top: 10px;
        }
        .customer-review__feature-name {
            width: 100px;
        }
    }
    .fixed-appointment-btn {
        position: fixed;
        right: 30px;
        bottom: 30px;
        z-index: 1000;
        width: auto;
        max-width: 300px;
        animation: slideIn 0.5s ease-out;
    }
    .fixed-appointment-btn .appointment-btn {
        border-radius: 50px;
        padding: 15px 30px;
        white-space: nowrap;
        background: #0056b3 !important;
    }
    @media (max-width: 991px) {
        .company-details-bg {
            height: 200px;
        }
        .company-details-header {
            margin-top: -50px;
        }
        .company-profile__name {
            font-size: 20px;
        }
        .company-profile__address {
            font-size: 14px;
        }
        .section-nav .nav-link {
            padding: 10px 15px;
            font-size: 14px;
        }
        .fixed-appointment-btn {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            max-width: 100%;
            padding: 10px 15px;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }
        .fixed-appointment-btn .appointment-btn {
            border-radius: 8px;
            width: 100%;
            background: #0056b3 !important;
        }
        .appointment-button {
            display: none;
        }
    }
    @keyframes slideIn {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .appointment-button.hide {
        display: none;
    }
    @media (max-width: 991px) {
        body {
            padding-bottom: 80px;
        }
    }
    /* Appointment Button Styles */
    .appointment-btn {
        background: #0056b3 !important;
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        z-index: 1;
        color: #fff !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .appointment-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #003d82;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: -1;
    }

    .appointment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        color: #fff !important;
    }

    .appointment-btn:hover::before {
        opacity: 1;
    }

    .appointment-btn i {
        font-size: 1.2em;
        vertical-align: middle;
        color: #fff !important;
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            // Smooth scroll for nav links
            $('.section-nav .nav-link').on('click', function(e) {
                e.preventDefault();
                const targetId = $(this).attr('href');
                const targetElement = $(targetId);
                const offset = 100;
                const targetPosition = targetElement.offset().top - offset;

                $('html, body').animate({
                    scrollTop: targetPosition
                }, 800);

                $('.section-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            });

            // Update active nav link on scroll
            $(window).on('scroll', function() {
                const scrollPosition = $(window).scrollTop() + 150;
                $('.sections section').each(function() {
                    const sectionTop = $(this).offset().top;
                    const sectionBottom = sectionTop + $(this).outerHeight();
                    const sectionId = $(this).attr('id');

                    if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                        $('.section-nav .nav-link').removeClass('active');
                        $('.section-nav .nav-link[href="#' + sectionId + '"]').addClass('active');
                    }
                });
            });

            // Review logic
            let result = $('.edit-review').data();
            $('.edit-id').val(result.id);
            $('#reviewUpdateModal').find('.edit-review').val(result.review);

            var existRating = result.rating;

            $('[name^=rating]').each(function() {
                var featureId = $(this).data('feature-id');
                var featureRating = result['feature_' + featureId];

                if (featureRating == 5) {
                    $('#existed-rating-' + featureId + '-5').addClass('checked');
                } else if (featureRating == 4) {
                    $('#existed-rating-' + featureId + '-4').addClass('checked');
                } else if (featureRating == 3) {
                    $('#existed-rating-' + featureId + '-3').addClass('checked');
                } else if (featureRating == 2) {
                    $('#existed-rating-' + featureId + '-2').addClass('checked');
                } else {
                    $('#existed-rating-' + featureId + '-1').addClass('checked');
                }
            });

            $('.delete-review').on('click', function() {
                $('.delete-id').val($(this).data('id'));
            });

            $(".give-rating input:radio").prop("checked", false);

            $(".give-rating input").on("click", function() {
                var featureId = $(this).data('feature-id');
                $(this).parent().siblings().removeClass("checked");
                $(this).parent().addClass("checked");

                if ($('[name^=rating]:checked').length === $('[name^=rating]').length) {
                    $('.submitBtn').removeAttr('disabled');
                }
            });

            $(".give-rating-update input:radio").prop("checked", false);

            $(".give-rating-update input").on("click", function() {
                var featureId = $(this).data('feature-id');
                $(this).parent().siblings().removeClass("checked");
                $(this).parent().addClass("checked");

                if ($('[name^=rating]:checked').length === $('[name^=rating]').length) {
                    $('.submitBtn').removeAttr('disabled');
                }
            });

            $('[name^=rating]').on('click', function() {
                const totalFeatures = new Set(
                    $('[name^=rating]').map(function() {
                        return $(this).data('feature-id');
                    }).get()
                ).size;

                const ratedFeatures = new Set(
                    $('[name^=rating]:checked').map(function() {
                        return $(this).data('feature-id');
                    }).get()
                ).size;

                if (ratedFeatures === totalFeatures) {
                    $('.submitBtn').removeAttr('disabled');
                } else {
                    $('.submitBtn').attr('disabled', 'disabled');
                }
            });

            // Show/hide fixed appointment button based on scroll
            $(window).on('scroll', function() {
                if ($(window).width() > 991) {
                    const scrollTop = $(window).scrollTop();
                    const windowHeight = $(window).height();
                    const documentHeight = $(document).height();
                    
                    if (scrollTop > 300 && scrollTop < documentHeight - windowHeight - 100) {
                        $('.fixed-appointment-btn').removeClass('d-none');
                        $('.appointment-button').addClass('hide');
                    } else {
                        $('.fixed-appointment-btn').addClass('d-none');
                        $('.appointment-button').removeClass('hide');
                    }
                }
            });

            // Show fixed button on mobile
            if ($(window).width() <= 991) {
                $('.fixed-appointment-btn').removeClass('d-none');
            }

            // Update on window resize
            $(window).on('resize', function() {
                if ($(window).width() <= 991) {
                    $('.fixed-appointment-btn').removeClass('d-none');
                } else {
                    $('.fixed-appointment-btn').addClass('d-none');
                    $('.appointment-button').removeClass('hide');
                }
            });
        });
    </script>
@endpush

@push('script')
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

            const reactionButtons = document.querySelectorAll('.reaction-icon');

            reactionButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const reactionContainer = this.parentElement;
                    const reviewId = reactionContainer.getAttribute('data-id');
                    const reactionTypeId = reactionContainer.getAttribute('data-val');
                    const isActive = reactionContainer.classList.contains('active');

                    if (isActive) {
                        handleReaction(reviewId, reactionTypeId, false, reactionContainer);
                    } else {
                        const siblingContainers = document.querySelectorAll(`[data-id="${reviewId}"]`);
                        siblingContainers.forEach(sibling => sibling.classList.remove('active'));
                        handleReaction(reviewId, reactionTypeId, true, reactionContainer);
                    }
                });
            });

            function handleReaction(reviewId, reactionTypeId, isAdd, container) {
                const url = isAdd
                    ? `/user/reaction/rating/${reviewId}/react/${reactionTypeId}`
                    : `/user/reaction/rating/${reviewId}/unreact/${reactionTypeId}`;

                axios.post(url)
                    .then(response => {
                        let rawData = response.data;
                        if (typeof rawData === 'string' && rawData.startsWith('<!--')) {
                            rawData = rawData.replace(/<!--|-->/g, '').trim();
                            rawData = JSON.parse(rawData);
                        }

                        if (rawData.success) {
                            if (isAdd) {
                                container.classList.add('active');
                                updateReactionCount(container, 1);
                            } else {
                                container.classList.remove('active');
                                updateReactionCount(container, -1);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error handling reaction:', error);
                    });
            }

            function updateReactionCount(container, delta) {
                const countSpan = container.querySelector('.reaction-count');
                if (countSpan) {
                    const currentCount = parseInt(countSpan.textContent) || 0;
                    const newCount = currentCount + delta;
                    countSpan.textContent = newCount >= 0 ? newCount : 0;
                }
            }
        });
    </script>
@endpush

@push('script')
    <script>
        "use strict";
        function checkEmailExists(email) {
            fetch(`user/check-email?email=${email}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById('email-error').style.display = 'block';
                    } else {
                        document.getElementById('email-error').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error checking email:', error));
        }
    </script>
@endpush

@push('meta')
    <meta name="description" content="Khám phá chuyên gia {{ $company->name }} tại {{ $company->address }}. Xem kỹ năng, dự án tiêu biểu và đánh giá.">
    <meta name="keywords" content="{{ $company->name }}, thuê chuyên gia, {{ $company->address }}, {{ is_array($company->tags) ? implode(', ', $company->tags) : $company->tags }}">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "{{ $company->name }}",
        "jobTitle": "Chuyên Gia",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $company->address }}"
        },
        "email": "{{ $company->email }}"
    }
    </script>
@endpush