@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Review Details')</h5>
                            <a href="{{ route('user.company.statistics.detail', $company->id) }}" class="btn btn--base btn-sm">
                                <i class="las la-arrow-left"></i> @lang('Back to Statistics')
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="review-detail">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <h6>{{ $review->user->fullname }}</h6>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="review-date">{{ $review->created_at->format('d M, Y') }}</span>
                                </div>
                                <div class="review-content">
                                    <p>{{ $review->comment }}</p>
                                </div>
                                <div class="review-features">
                                    <form action="{{ route('user.review.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $review->id }}">
                                        
                                        @foreach($categories as $category)
                                            <div class="category-section mb-4">
                                                <h6 class="category-title">{{ $category->name }}</h6>
                                                <div class="row">
                                                    @foreach($category->features as $feature)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="feature-rating">
                                                                <label class="form-label">{{ $feature->name }}</label>
                                                                <div class="rating-input">
                                                                    @for($i = 1; $i <= 5; $i++)
                                                                        <input type="radio" name="features[{{ $feature->id }}]" 
                                                                            id="feature{{ $feature->id }}_{{ $i }}" 
                                                                            value="{{ $i }}"
                                                                            {{ isset($review->feature_ratings[$feature->id]) && $review->feature_ratings[$feature->id] == $i ? 'checked' : '' }}>
                                                                        <label for="feature{{ $feature->id }}_{{ $i }}">
                                                                            <i class="las la-star"></i>
                                                                        </label>
                                                                    @endfor
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="form-group mt-4">
                                            <label class="form-label">@lang('Overall Comment')</label>
                                            <textarea class="form-control" name="comment" rows="4">{{ $review->comment }}</textarea>
                                        </div>

                                        <div class="form-group mt-4">
                                            <button type="submit" class="btn btn--base">@lang('Update Review')</button>
                                            <button type="button" class="btn btn--danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                @lang('Delete Review')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">@lang('Delete Review')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.review.delete') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $review->id }}">
                        <p>@lang('Are you sure you want to delete this review?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn--danger">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .review-detail {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .reviewer-info h6 {
        margin-bottom: 5px;
    }
    .rating {
        color: #ffc107;
    }
    .review-date {
        color: #666;
        font-size: 14px;
    }
    .review-content {
        margin-bottom: 30px;
    }
    .review-features {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .category-section {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .category-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .feature-rating {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    .rating-input {
        display: flex;
        gap: 5px;
    }
    .rating-input input[type="radio"] {
        display: none;
    }
    .rating-input label {
        cursor: pointer;
        color: #ddd;
        transition: color 0.2s;
    }
    .rating-input input[type="radio"]:checked ~ label {
        color: #ffc107;
    }
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: #ffc107;
    }
</style>
@endpush 