@php $addShowAfterColum = 3; @endphp

@forelse ($reviews as $k => $review)
    <div class="review-block">
        <p class="mb-2 mt-4">
            @lang('Đánh giá cho')
            <a href="{{ route('company.details', [$review->company_id, slug($review->company->name)]) }}"
                class="font-weight-bold text--base">
                {{ __($review->company->name) }}
            </a>
        </p>
        <div class="customer-review">
            <div class="customer-review__thumb">
                <img
                    src="{{ getImage(getFilePath('company') . '/' . $review->company->image, getFileSize('company')) }}" />
            </div>
            <div class="customer-review__content">
                <div class="customer-review__header">
                    <div class="left">
                        <h6>{{ auth()->user()->fullname }}</h6>
                        <span>
                            <i class="la la-map-marker-alt"></i>
                            {{ auth()->user()->country_name }}
                        </span>
                    </div>
                    <div class="right">
                        <div class="ratings d-flex align-items-center justify-content-end">
                            @php
                                echo rating($review->avg_rating);
                            @endphp
                        </div>
                    </div>
                </div>
                <div class="customer-review__body">
                    {{-- Temporarily comment out features until relationship is properly set up
                    @if(method_exists($review, 'features') && $review->features && count($review->features) > 0)
                        <div class="features-ratings">
                            @foreach($review->features as $feature)
                                <div class="feature-rating-item">
                                    <div class="feature-name">{{ $feature->name }}</div>
                                    <div class="feature-rating">
                                        @php
                                            $rating = $feature->pivot->rating;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="la la-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    --}}
                    <p class="mt-3">{{ __($review->suggest) }}</p>
                </div>
                <div class="customer-review__footer">
                    <div class="right">
                        <ul class="customer-review__action-list">
                            <li>
                                <button class="delete-review" type="button" data-bs-toggle="modal"
                                    data-bs-target="#reviewDeleteModal" data-id="{{ $review->id }}">
                                    <i class="la la-trash-alt"></i>@lang('Delete')
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- advertisement Block -->
    @if ($k + 1 == $addShowAfterColum)
        @php
            $addShowAfterColum += 3;
        @endphp
        <div class="my-3">
            @php
                echo getAdvertisement('728x90');
            @endphp
        </div>
    @endif

@empty
    <div class="bg-white p-5 rounded">
        <h5 class="text-center"> @lang('Bạn chưa thực hiện đánh giá nào')</h5>
    </div>
@endforelse

@if ($reviews->hasPages())
    {{ paginateLinks($reviews) }}
@endif

<!-- review delete modal -->
<div class="modal fade" id="reviewDeleteModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewDeleteModalLabel">@lang('Cảnh báo xác nhận')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>@lang('Bạn có chắc muốn xóa review này?')</p>
            </div>
            <div class="modal-footer">
                <form action="{{ route('user.review.delete') }}" method="POST" class="disableSubmission">
                    @csrf
                    <input type="hidden" name="id" value="" class="delete-id">
                    <button type="button" class="btn btn-sm btn--dark"
                        data-bs-dismiss="modal">@lang('Không')</button>
                    <button type="submit" class="btn btn-sm btn--base">@lang('Đồng ý')</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    .features-ratings {
        margin-bottom: 15px;
    }
    .feature-rating-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }
    .feature-name {
        font-weight: 500;
        color: #333;
    }
    .feature-rating {
        display: flex;
        gap: 5px;
    }
    .feature-rating i {
        font-size: 16px;
    }
    .text-warning {
        color: #ffc107;
    }
    .text-muted {
        color: #ddd;
    }
</style>
@endpush
