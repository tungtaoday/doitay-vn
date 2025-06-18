@php
    $addShowAfterColum = 3;
@endphp

@if ($myReview && (request()->page == 1 || request()->page == null))
    <div class="customer-review mb-3">
        <div class="customer-review__thumb">
                            <img src="{{ getUserAvatar($myReview->user) }}" alt="image" />
        </div>
        <div class="customer-review__content">
            <div class="customer-review__header">
                <div class="left">
                    <h6>{{ __(@$myReview->user->username) }}</h6>
                    <span>
                        <i class="la la-map-marker-alt"></i>
                        {{ __(@$myReview->user->country_name) }}
                    </span>
                </div>
                <div class="right">
                    <div class="ratings d-flex align-items-center justify-content-end">
                        @php
                            // Trung bình rating từ controller
                            $averageRating = $averageRating ?? 0;  // Nếu không có rating thì mặc định 0
                        @endphp
                        
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)                                
                                <i class="la la-star fa-sm text-warning"></i> <!-- Sao vàng -->
                            @else
                                <i class="la la-star fa-sm text-muted"></i> <!-- Sao xám -->
                            @endif
                        @endfor
                    </div>
                </div>
            </div>

            <div class="customer-review__body">
                <ul class="feature-ratings">
                    @foreach ($myReview->ratingDetails as $ratingDetail)
                        <li class="feature-rating-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $ratingDetail->feature->name }}</strong>
                                <span class="text-muted">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $ratingDetail->rating)
                                            <i class="la la-star fa-sm text-warning"></i> <!-- Sao vàng -->
                                        @else
                                            <i class="la la-star fa-sm text-muted"></i> <!-- Sao xám -->
                                        @endif
                                    @endfor
                                </span>
                            </div>
                        </li>
                    @endforeach
                    <p>{{ __(@$myReview->suggest) }}</p>                    
                </ul>                
                    <!-- Chèn đoạn hiển thị reactions -->                    
                </div>
            </div>
            
            @if (auth()->id() == $myReview->user_id)
                <div class="customer-review__footer">
                    <div class="left">
                        <ul class="customer-review__action-list">
                            <li>
                                <button class="edit-review" type="button" data-bs-toggle="modal"
                                    data-bs-target="#reviewUpdateModal" data-id="{{ $myReview->id }}"
                                    data-review="{{ $myReview->review }}" data-rating="{{ $myReview->rating }}">
                                    <i class="la la-edit text--base"></i>
                                    @lang('Edit Review')
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="right">
                        <ul class="customer-review__action-list">
                            <li>
                                <button class="delete-review" type="button" data-bs-toggle="modal"
                                    data-bs-target="#reviewDeleteModal" data-id="{{ $myReview->id }}">
                                    <i class="la la-trash-alt text--danger"></i>@lang('Delete')
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif

@foreach ($reviews as $review)
    <div class="customer-review mb-3">
        <div class="customer-review__thumb">
                                    <img src="{{ getUserAvatar($review->user) }}" alt="image" />
        </div>
        <div class="customer-review__content">
            <div class="customer-review__header">
                <div class="left">
                    <h6>{{ __(@$review->user->username) }}</h6>
                    <span>
                        <i class="la la-map-marker-alt"></i>
                        {{ __(@$review->user->country_name) }}
                    </span>
                </div>
                <div class="right">
                    <div class="ratings d-flex align-items-center justify-content-end">
                        @php
                            // Trung bình rating từ controller
                            $avgRating = $review->avg_rating ?? 0; // Nếu không có avgRating thì mặc định 0
                        @endphp
                        
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $avgRating)                                
                                <i class="la la-star fa-sm text-warning"></i> <!-- Sao vàng -->
                            @else
                                <i class="la la-star fa-sm text-muted"></i> <!-- Sao xám -->
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
            <div class="customer-review__body">
                <ul class="feature-ratings">
                    @if(isset($review->ratingDetails) && count($review->ratingDetails) > 0)
                        @foreach ($review->ratingDetails as $ratingDetail)
                            <li class="feature-rating-item mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>{{ $ratingDetail->feature->name }}</strong>
                                    <span class="text-muted">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $ratingDetail->rating)
                                                <i class="la la-star fa-sm text-warning"></i> <!-- Sao vàng -->
                                            @else
                                                <i class="la la-star fa-sm text-muted"></i> <!-- Sao xám -->
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    @endif
                    <p>{{ __(@$review->suggest) }}</p>
                </ul>
            </div>
        </div>
        @if (auth()->id() == $review->user_id)
            <div class="review-actions mt-3 text-end">
                <button class="btn btn-sm btn-outline-primary edit-review" type="button" 
                    data-bs-toggle="modal" data-bs-target="#reviewUpdateModal" 
                    data-id="{{ $review->id }}" data-review="{{ $review->review }}" 
                    data-rating="{{ $review->rating }}">
                    <i class="la la-edit"></i> @lang('Edit')
                </button>
                <button class="btn btn-sm btn-outline-danger delete-review" type="button" 
                    data-bs-toggle="modal" data-bs-target="#reviewDeleteModal" 
                    data-id="{{ $review->id }}">
                    <i class="la la-trash-alt"></i> @lang('Delete')
                </button>
            </div>
        @endif
    </div>
@endforeach

@if ($reviews->hasPages())
    <div class="mt-4">
        {{ paginateLinks($reviews) }}
    </div>
@endif

@if (empty($myReview) && $reviews->isEmpty())
    <div class="review-block">
        <div class="customer-review d-flex justify-content-center">
            <h5>@lang('No review yet!')</h5>
        </div>
    </div>
@endif
