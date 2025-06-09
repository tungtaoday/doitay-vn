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

        <!-- Enhanced Appointment Modal -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('appointments.create') }}" id="appointmentForm">
                        @csrf
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="appointmentModalLabel">
                                <i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn với') {{ $company->name }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <input type="hidden" name="company_id" value="{{ $company->id }}">
                            
                            <!-- Progress Steps -->
                            <div class="booking-steps mb-4">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="step active" data-step="1">
                                        <div class="step-number">1</div>
                                        <div class="step-title">Liên hệ</div>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-number">2</div>
                                        <div class="step-title">Thời gian</div>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-number">3</div>
                                        <div class="step-title">Chi tiết</div>
                                    </div>
                                </div>
                                <div class="progress" style="height: 3px;">
                                    <div class="progress-bar bg-primary" style="width: 33%"></div>
                                </div>
                            </div>

                            <!-- Step 1: Contact Info -->
                            <div class="booking-step-content" data-step="1">
                                @auth
                                    <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                    <div class="user-info-card mb-3 p-3 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-3">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                                <small class="text-muted">{{ auth()->user()->email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="recipient_name" value="{{ auth()->user()->name }}">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">@lang('Số điện thoại')</label>
                                        <input type="tel" id="phone" name="recipient_phone" class="form-control form-control-lg" 
                                               value="{{ auth()->user()->mobile }}" placeholder="0901234567" required>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="las la-info-circle me-2"></i>
                                        Chúng tôi sẽ tạo tài khoản cho bạn để theo dõi lịch hẹn
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">@lang('Họ và tên') <span class="text-danger">*</span></label>
                                            <input type="text" id="name" name="recipient_name" class="form-control form-control-lg" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="phone" class="form-label">@lang('Số điện thoại') <span class="text-danger">*</span></label>
                                            <input type="tel" id="phone" name="recipient_phone" class="form-control form-control-lg" 
                                                   placeholder="0901234567" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">@lang('Email') <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control form-control-lg"
                                               placeholder="email@example.com" required>
                                        <div id="email-error" class="text-danger mt-2" style="display: none;">
                                            @lang('Email đã tồn tại, vui lòng') <a href="{{ route('user.login') }}">@lang('đăng nhập')</a>.
                                        </div>
                                    </div>
                                @endauth
                            </div>

                            <!-- Step 2: Date & Time -->
                            <div class="booking-step-content d-none" data-step="2">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="appointmentDate" class="form-label">@lang('Chọn ngày') <span class="text-danger">*</span></label>
                                        <input type="date" id="appointmentDate" name="appointmentDate" class="form-control form-control-lg" 
                                               min="{{ date('Y-m-d') }}" required>
                                        <small class="text-muted">Từ hôm nay trở đi</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="appointmentTime" class="form-label">@lang('Chọn giờ') <span class="text-danger">*</span></label>
                                        <select id="appointmentTime" name="appointmentTime" class="form-select form-select-lg" required>
                                            <option value="">Chọn giờ hẹn</option>
                                            <option value="08:00">08:00 - Sáng sớm</option>
                                            <option value="09:00">09:00 - Giờ hành chính</option>
                                            <option value="10:00">10:00 - Giờ hành chính</option>
                                            <option value="11:00">11:00 - Trước giờ nghỉ trưa</option>
                                            <option value="13:00">13:00 - Sau giờ nghỉ trưa</option>
                                            <option value="14:00">14:00 - Giờ hành chính</option>
                                            <option value="15:00">15:00 - Giờ hành chính</option>
                                            <option value="16:00">16:00 - Chiều tối</option>
                                            <option value="17:00">17:00 - Sau giờ làm</option>
                                            <option value="18:00">18:00 - Tối</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Quick Time Slots -->
                                <div class="quick-time-slots mb-3">
                                    <label class="form-label">@lang('Hoặc chọn nhanh:')</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-outline-primary quick-time" data-time="09:00">9h Sáng</button>
                                        <button type="button" class="btn btn-outline-primary quick-time" data-time="14:00">2h Chiều</button>
                                        <button type="button" class="btn btn-outline-primary quick-time" data-time="16:00">4h Chiều</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: Additional Details -->
                            <div class="booking-step-content d-none" data-step="3">
                                <div class="mb-3">
                                    <label for="address" class="form-label">@lang('Địa chỉ thực hiện dịch vụ')</label>
                                    <textarea id="address" name="recipient_address" class="form-control" rows="2" 
                                              placeholder="Nhập địa chỉ chi tiết..."></textarea>
                                    <small class="text-muted">Để thợ có thể đến đúng địa điểm</small>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">@lang('Mô tả công việc')</label>
                                    <textarea id="notes" name="notes" class="form-control" rows="3" 
                                              placeholder="Mô tả chi tiết công việc cần làm..."></textarea>
                                    <small class="text-muted">Thông tin chi tiết giúp thợ chuẩn bị tốt hơn</small>
                                </div>
                                
                                <!-- Booking Summary -->
                                <div class="booking-summary p-3 bg-light rounded">
                                    <h6 class="mb-3">Tóm tắt lịch hẹn:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Thợ:</strong><br>
                                            <span>{{ $company->name }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Thời gian:</strong><br>
                                            <span id="summary-datetime">Chưa chọn</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" id="prevStep" style="display: none;">
                                <i class="las la-arrow-left me-1"></i> Quay lại
                            </button>
                            <div class="ms-auto">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-primary" id="nextStep">
                                    Tiếp theo <i class="las la-arrow-right ms-1"></i>
                                </button>
                                <button type="submit" class="btn btn-success" id="submitBooking" style="display: none;">
                                    <i class="las la-calendar-check me-1"></i> Đặt lịch hẹn
                                </button>
                            </div>
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
        flex-shrink: 0;
    }
    .single-company-info__list .text {
        color: #6c757d;
    }
    .verified-badge {
        background: #28a745;
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .company-action-bar {
        position: sticky;
        top: 0;
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 15px 0;
        z-index: 100;
        margin-bottom: 20px;
    }
    .fixed-appointment-btn {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 15px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
    }
    @media (min-width: 768px) {
        .fixed-appointment-btn {
            display: none !important;
        }
    }

    /* Enhanced Booking Modal Styles */
    .booking-steps {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 20px;
    }
    
    .step {
        text-align: center;
        position: relative;
        flex: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .step.active .step-number {
        background: #007bff;
        color: white;
    }
    
    .step.completed .step-number {
        background: #28a745;
        color: white;
    }
    
    .step-title {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .step.active .step-title {
        color: #007bff;
        font-weight: 600;
    }
    
    .user-info-card {
        border: 2px solid #007bff;
    }
    
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
    }
    
    .quick-time-slots .btn {
        border-radius: 25px;
    }
    
    .quick-time-slots .btn.active {
        background: #007bff;
        color: white;
        border-color: #007bff;
    }
    
    .booking-summary {
        border: 1px solid #dee2e6;
    }
    
    .form-control-lg, .form-select-lg {
        padding: 12px 16px;
        font-size: 16px;
    }
    
    /* Animation for step transitions */
    .booking-step-content {
        transition: all 0.3s ease;
    }
    
    .booking-step-content.fade-in {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            // Enhanced Booking Modal Script
            let currentStep = 1;
            const totalSteps = 3;
            
            // Initialize modal
            function initBookingModal() {
                updateStepDisplay();
                updateButtonsVisibility();
            }
            
            // Update step display
            function updateStepDisplay() {
                $('.step').removeClass('active completed');
                $('.booking-step-content').addClass('d-none');
                
                // Mark completed steps
                for(let i = 1; i < currentStep; i++) {
                    $(`.step[data-step="${i}"]`).addClass('completed');
                }
                
                // Mark current step as active
                $(`.step[data-step="${currentStep}"]`).addClass('active');
                $(`.booking-step-content[data-step="${currentStep}"]`).removeClass('d-none').addClass('fade-in');
                
                // Update progress bar
                const progress = (currentStep / totalSteps) * 100;
                $('.progress-bar').css('width', progress + '%');
            }
            
            // Update buttons visibility
            function updateButtonsVisibility() {
                if(currentStep === 1) {
                    $('#prevStep').hide();
                } else {
                    $('#prevStep').show();
                }
                
                if(currentStep === totalSteps) {
                    $('#nextStep').hide();
                    $('#submitBooking').show();
                } else {
                    $('#nextStep').show();
                    $('#submitBooking').hide();
                }
            }
            
            // Validate current step
            function validateCurrentStep() {
                let isValid = true;
                const currentStepContent = $(`.booking-step-content[data-step="${currentStep}"]`);
                
                currentStepContent.find('input[required], select[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                return isValid;
            }
            
            // Next step button
            $('#nextStep').on('click', function() {
                if (validateCurrentStep() && currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                    updateButtonsVisibility();
                    updateSummary();
                }
            });
            
            // Previous step button
            $('#prevStep').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                    updateButtonsVisibility();
                }
            });
            
            // Quick time slot buttons
            $('.quick-time').on('click', function() {
                const time = $(this).data('time');
                $('#appointmentTime').val(time);
                $('.quick-time').removeClass('active');
                $(this).addClass('active');
            });
            
            // Update summary
            function updateSummary() {
                const date = $('#appointmentDate').val();
                const time = $('#appointmentTime').val();
                
                if (date && time) {
                    const formattedDate = new Date(date).toLocaleDateString('vi-VN');
                    $('#summary-datetime').text(`${formattedDate} lúc ${time}`);
                } else {
                    $('#summary-datetime').text('Chưa chọn');
                }
            }
            
            // Date and time change events
            $('#appointmentDate, #appointmentTime').on('change', updateSummary);
            
            // Initialize modal when opened
            $('#appointmentModal').on('show.bs.modal', function() {
                currentStep = 1;
                initBookingModal();
            });
            
            // Remove validation classes on input
            $('#appointmentForm input, #appointmentForm select').on('input change', function() {
                $(this).removeClass('is-invalid');
            });
            
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

{{-- 
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
--}}

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