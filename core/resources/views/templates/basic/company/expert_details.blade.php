@extends($activeTemplate . 'layouts.frontend')

@php
    $content = getContent('breadcrumb.content', true);
@endphp

@section('content')
    <section class="expert-details-section pb-100">
        <!-- Header with Background -->
        <div class="expert-details-bg bg_img d-lg-block d-none"
             style="background-image: url('{{ getImage('assets/images/frontend/breadcrumb/' . @$content->data_values->image, '1920x840') }}');">
        </div>
        <div class="expert-details-header py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="expert-profile d-flex align-items-center">
                            <div class="expert-thumb me-3">
                                <img src="{{ getImage(getFilePath('company') . '/' . $expert->image) }}" alt="{{ $expert->name }}" class="rounded-circle" style="width: 80px; height: 80px;">
                            </div>
                            <div class="expert-info">
                                <h3 class="expert-profile__name">{{ $expert->name }}</h3>
                                <span><i class="las la-map-marker-alt"></i> {{ $expert->address }}</span>
                                <div class="ratings mt-2">
                                    <span class="badge bg-success">{{ showAmount(@$expert->avg_rating) }} <i class="las la-star"></i></span>
                                    <span class="text-muted fs--14px">({{ @$expert->ratings->count() }} đánh giá)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end text-center mt-3 mt-lg-0">
                        <button class="btn btn-primary btn-lg px-5" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                            Liên Hệ Chuyên Gia
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="expert-sidebar">
                        <div class="row gy-4">
                            <!-- About Expert -->
                            <div class="expert-sidebar__widget col-lg-12">
                                <div class="single-expert-info">
                                    <h5 class="single-expert-info__title">Về {{ $expert->name }}</h5>
                                    <p class="mt-2">{{ __(@$expert->description) }}</p>
                                </div>
                            </div>
                            <!-- Skills -->
                            <div class="expert-sidebar__widget col-lg-12">
                                <div class="single-expert-info">
                                    <h5 class="single-expert-info__title">Kỹ Năng</h5>
                                    <div class="mt-3">
                                        @foreach (explode(',', $expert->tags) as $skill)
                                            <span class="badge bg-primary me-1 mb-1">{{ trim($skill) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- Contact Info -->
                            <div class="expert-sidebar__widget col-lg-12">
                                <div class="single-expert-info">
                                    <h5 class="single-expert-info__title">Liên Hệ</h5>
                                    <ul class="single-expert-info__list">
                                        <li>
                                            <div class="icon"><i class="las la-envelope"></i></div>
                                            <div class="content">
                                                <a href="mailto:{{ @$expert->email }}">{{ @$expert->email }}</a>
                                            </div>
                                        </li>
                                        @if ($expert->phone)
                                            <li>
                                                <div class="icon"><i class="las la-phone"></i></div>
                                                <div class="content">
                                                    <p>{{ __(@$expert->phone) }}</p>
                                                </div>
                                            </li>
                                        @endif
                                        <li>
                                            <div class="icon"><i class="las la-link"></i></div>
                                            <div class="content">
                                                <a href="{{ @$expert->url }}" target="_blank">{{ @$expert->url }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Availability -->
                            <div class="expert-sidebar__widget col-lg-12">
                                <div class="single-expert-info">
                                    <h5 class="single-expert-info__title">Lịch Trống</h5>
                                    <div class="calendar mt-3" id="expert-calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="col-lg-8 ps-xxl-5 mt-5">
                    <!-- Portfolio -->
                    <div class="portfolio-section mb-5">
                        <h5 class="mb-3">Dự Án Tiêu Biểu</h5>
                        <div class="row">
                            @foreach ($expert->portfolio ?? [] as $item)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <img src="{{ getImage(getFilePath('portfolio') . '/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" loading="lazy">
                                        <div class="card-body">
                                            <h6>{{ $item->title }}</h6>
                                            <p>{{ Str::limit($item->description, 100) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Reviews -->
                    <div class="expert-reviews mb-5">
                        <h5 class="mb-3">Đánh Giá Từ Khách Hàng</h5>
                        <div class="rating-area mb-4">
                            <div class="rating">{{ showAmount(@$expert->avg_rating) }}</div>
                            <div class="ratings d-flex align-items-center">
                                @php echo avgRating($expert->avg_rating); @endphp
                            </div>
                            <span class="text-muted fs--14px">Dựa trên {{ @$expert->ratings->count() }} đánh giá</span>
                        </div>
                        @for ($i = 5; $i >= 1; $i--)
                            @php
                                $reviewCount = $expert->ratings->filter(function ($rating) use ($i) {
                                    return round($rating->avg_rating) == $i;
                                })->count();
                                $totalReviews = $expert->ratings->count();
                                $percentage = $totalReviews > 0 ? ($reviewCount / $totalReviews) * 100 : 0;
                            @endphp
                            <div class="single-review">
                                <p class="star">{{ $i }} <i class="las la-star text--base"></i></p>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="percentage">{{ showAmount($percentage) }}%</span>
                            </div>
                        @endfor
                        <!-- Review Form -->
                        @auth
                            @if (!$myReview)
                                <div class="give-rating-area mt-4">
                                    <form action="{{ route('expert.user.review', $expert->id) }}" method="POST" class="disableSubmission">
                                        @csrf
                                        <div class="give-rating-person d-flex align-items-center mb-3">
                                            <div class="thumb me-3">
                                                <img src="{{ getUserAvatar(auth()->user()) }}" alt="User">
                                            </div>
                                            <div class="content">
                                                <h6>{{ auth()->user()->fullname }}</h6>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <h5>Đánh Giá Dịch Vụ</h5>
                                            @foreach ($features as $feature)
                                                <div class="feature-rating mb-3">
                                                    <label>{{ $feature->name }}</label>
                                                    <div class="give-rating">
                                                        @for ($i = 5; $i >= 1; $i--)
                                                            <span>
                                                                <input id="feature{{ $feature->id }}-str{{ $i }}" name="rating[{{ $feature->id }}]" type="radio" data-feature-id="{{ $feature->id }}" value="{{ $i }}">
                                                                <label for="feature{{ $feature->id }}-str{{ $i }}"><i class="las la-star fa-sm"></i></label>
                                                            </span>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <textarea name="review" class="form-control mt-3" placeholder="Viết đánh giá của bạn" required>{{ old('review') }}</textarea>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary submitBtn" disabled>Gửi Đánh Giá</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="give-rating-area mt-4 text-center">
                                <p>Bạn cần <a href="{{ route('user.login.v2') }}" class="text-primary">đăng nhập</a> để gửi đánh giá.</p>
                            </div>
                        @endauth
                        <!-- Review List -->
                        @include($activeTemplate . 'partials.expert_review')
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Modal -->
        <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('appointments.create') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="appointmentModalLabel">Liên Hệ {{ $expert->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="company_id" value="{{ $expert->id }}">
                            @auth
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            @else
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Nhập email của bạn" required onchange="checkEmailExists(this.value)">
                                    <div id="email-error" class="text-danger mt-2" style="display: none;">
                                        Email đã tồn tại, vui lòng <a href="{{ route('user.login.v2') }}">đăng nhập</a>.
                                    </div>
                                </div>
                            @endauth
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và Tên</label>
                                <input type="text" id="name" name="recipient_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số Điện Thoại</label>
                                <input type="text" id="phone" name="recipient_phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa Chỉ</label>
                                <textarea id="address" name="recipient_address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="appointmentDate" class="form-label">Ngày</label>
                                <input type="date" id="appointmentDate" name="appointmentDate" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="appointmentTime" class="form-label">Giờ</label>
                                <input type="time" id="appointmentTime" name="appointmentTime" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Ghi Chú</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Gửi Yêu Cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Review Modals -->
        <div class="modal fade" id="reviewUpdateModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewUpdateModalLabel">@lang('Update Review')</h5>
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
                            <textarea name="review" class="form--control edit-review"></textarea>
                            <div class="text-end">
                                <button type="submit" class="btn btn--base">@lang('Update')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="reviewDeleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewDeleteModalLabel">@lang('Confirmation Alert')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>@lang('Are you sure to delete this review?')</p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('user.review.delete') }}" method="POST" class="disableSubmission">
                            @csrf
                            <input type="hidden" name="id" value="" class="delete-id">
                            <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                            <button type="submit" class="btn btn-sm btn--base">@lang('Yes')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/fullcalendar.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/fullcalendar.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            // FullCalendar for Availability
            $('#expert-calendar').fullCalendar({
                events: '/experts/{{ $expert->slug }}/availability', // API endpoint
                defaultView: 'agendaWeek',
                minTime: '08:00:00',
                maxTime: '20:00:00',
                height: 'auto'
            });

            // Review Form Logic
            $(".give-rating input").on("click", function() {
                var featureId = $(this).data('feature-id');
                $(this).parent().siblings().removeClass("checked");
                $(this).parent().addClass("checked");
                if ($('[name^=rating]:checked').length === $('[name^=rating]').length) {
                    $('.submitBtn').removeAttr('disabled');
                }
            });

            // Email Check
            function checkEmailExists(email) {
                fetch(`/user/check-email?email=${email}`)
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
        });
    </script>
@endpush

@push('meta')
    <meta name="description" content="Thuê chuyên gia {{ $expert->name }} tại {{ $expert->address }}. Đặt lịch hẹn, xem đánh giá và dự án tiêu biểu.">
    <meta name="keywords" content="{{ $expert->name }}, thuê chuyên gia, {{ $expert->address }}, {{ implode(', ', explode(',', $expert->tags)) }}">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "{{ $expert->name }}",
        "jobTitle": "Chuyên Gia",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $expert->address }}"
        },
        "email": "{{ $expert->email }}"
    }
    </script>
@endpush