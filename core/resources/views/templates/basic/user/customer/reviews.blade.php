<!-- Reviews Overview -->
<div class="row mb-4">
    <div class="col-12">
        <div class="reviews-overview bg-gradient-info text-white rounded-4 p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2 text-white">
                        <i class="las la-star me-2"></i>
                        Đánh giá của tôi
                    </h3>
                    <p class="mb-0 opacity-90">
                        Chia sẻ trải nghiệm và giúp cộng đồng tìm được thợ uy tín
                    </p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="review-stats">
                        <h2 class="mb-1 text-white">{{ $reviews->count() }}</h2>
                        <p class="mb-0 text-white opacity-75">Đánh giá đã viết</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Reviews -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="las la-comments text-primary me-2"></i>
                        Đánh giá gần đây
                    </h5>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="las la-plus me-1"></i>
                        Viết đánh giá mới
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($reviews->count() > 0)
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            <div class="review-item border rounded p-4 mb-3">
                                <div class="review-header d-flex justify-content-between align-items-start mb-3">
                                    <div class="company-info">
                                        <h6 class="mb-1">
                                            <a href="{{ route('company.details', [$review->company->id, slug($review->company->name)]) }}" 
                                               class="text-decoration-none">
                                                {{ $review->company->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="las la-calendar me-1"></i>
                                            {{ $review->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <div class="review-rating">
                                        <div class="stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= $review->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ number_format($review->avg_rating, 1) }}/5</small>
                                    </div>
                                </div>
                                
                                <div class="review-content mb-3">
                                    <p class="mb-0">{{ $review->suggest }}</p>
                                </div>
                                
                                @if($review->details && $review->details->count() > 0)
                                    <div class="review-details mb-3">
                                        <h6 class="mb-2">Chi tiết đánh giá:</h6>
                                        <div class="row">
                                            @foreach($review->details as $detail)
                                                <div class="col-md-6 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">{{ $detail->feature->name ?? 'N/A' }}</span>
                                                        <div class="feature-rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="las la-star {{ $i <= $detail->rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                                            @endfor
                                                            <small class="text-muted ms-1">{{ $detail->rating }}/5</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="review-actions d-flex justify-content-between align-items-center">
                                    <div class="review-stats">
                                        @if($review->reactions && $review->reactions->count() > 0)
                                            <div class="reactions">
                                                @foreach($review->reactions->groupBy('reaction_type_id') as $typeId => $reactions)
                                                    <span class="reaction-item me-2">
                                                        <i class="las la-thumbs-up text-primary"></i>
                                                        <small class="text-muted">{{ $reactions->count() }}</small>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="action-buttons">
                                        <button class="btn btn-outline-primary btn-sm me-2" 
                                                onclick="editReview({{ $review->id }})">
                                            <i class="las la-edit me-1"></i>
                                            Sửa
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="deleteReview({{ $review->id }})">
                                            <i class="las la-trash me-1"></i>
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($reviews->count() >= 5)
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary">
                                <i class="las la-eye me-2"></i>
                                Xem tất cả đánh giá
                            </button>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state text-center py-5">
                        <div class="empty-icon mb-4">
                            <i class="las la-comment-slash text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-3">Chưa có đánh giá nào</h5>
                        <p class="text-muted mb-4">
                            Hãy hoàn thành lịch hẹn đầu tiên và chia sẻ trải nghiệm của bạn!
                        </p>
                        <a href="{{ route('company.all') }}" class="btn btn-primary">
                            <i class="las la-search me-2"></i>
                            Tìm thợ ngay
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Review Tips -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="las la-lightbulb text-warning me-2"></i>
                    Mẹo viết đánh giá hiệu quả
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="tip-item">
                            <i class="las la-check-circle text-success me-2"></i>
                            <small>Chia sẻ chi tiết về chất lượng dịch vụ</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="tip-item">
                            <i class="las la-check-circle text-success me-2"></i>
                            <small>Đánh giá khách quan và trung thực</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="tip-item">
                            <i class="las la-check-circle text-success me-2"></i>
                            <small>Nhận +50 điểm thưởng cho mỗi đánh giá</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-edit text-primary me-2"></i>
                    Chỉnh sửa đánh giá
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editReviewForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="company-info mb-4">
                        <h6 id="editCompanyName"></h6>
                    </div>
                    
                    <div class="rating-section mb-3">
                        <label class="form-label">Đánh giá tổng thể</label>
                        <div class="star-rating-edit">
                            <input type="radio" name="overall_rating" value="5" id="edit_star5">
                            <label for="edit_star5" class="star">★</label>
                            <input type="radio" name="overall_rating" value="4" id="edit_star4">
                            <label for="edit_star4" class="star">★</label>
                            <input type="radio" name="overall_rating" value="3" id="edit_star3">
                            <label for="edit_star3" class="star">★</label>
                            <input type="radio" name="overall_rating" value="2" id="edit_star2">
                            <label for="edit_star2" class="star">★</label>
                            <input type="radio" name="overall_rating" value="1" id="edit_star1">
                            <label for="edit_star1" class="star">★</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nhận xét của bạn</label>
                        <textarea name="review_text" id="editReviewText" class="form-control" rows="4" 
                                  placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-save me-1"></i>
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
}

.review-item {
    transition: all 0.3s ease;
}

.review-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.star-rating-edit {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 5px;
}

.star-rating-edit input {
    display: none;
}

.star-rating-edit label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.3s ease;
}

.star-rating-edit input:checked ~ label,
.star-rating-edit label:hover,
.star-rating-edit label:hover ~ label {
    color: #ffc107;
}

.tip-item {
    display: flex;
    align-items: center;
}

.reaction-item {
    display: inline-flex;
    align-items: center;
    gap: 2px;
}
</style>

<script>
function editReview(reviewId) {
    // Fetch review data and populate modal
    // This would typically make an AJAX call to get review details
    document.getElementById('editReviewForm').action = `/user/reviews/${reviewId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
    modal.show();
}

function deleteReview(reviewId) {
    if(confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
        // Submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/user/reviews/${reviewId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script> 