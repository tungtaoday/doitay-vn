@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="text-white mb-2">
                                <i class="las la-store me-2 text--base"></i>
                                @lang('Thợ của tôi')
                            </h2>
                            <p class="text-white-50 mb-0">@lang('Quản lý và theo dõi thông tin thợ của bạn')</p>
                        </div>
                        <div>
                            <a href="{{ route('user.company.create') }}" class="btn btn--base btn-create">
                                <i class="las la-plus me-2"></i>
                                @lang('Tạo thợ mới')
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="filter-card">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <input type="text" id="searchInput" class="form-control search-input" placeholder="@lang('Tìm kiếm theo tên thợ...')" autocomplete="off">
                                    <i class="las la-search search-icon"></i>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="filter-buttons">
                                    <button class="filter-btn active" data-filter="all">
                                        <i class="las la-list"></i>
                                        @lang('Tất cả') 
                                        <span class="badge">{{ $companies->total() }}</span>
                                    </button>
                                    <button class="filter-btn" data-filter="approved">
                                        <i class="las la-check-circle"></i>
                                        @lang('Đã duyệt')
                                    </button>
                                    <button class="filter-btn" data-filter="pending">
                                        <i class="las la-clock"></i>
                                        @lang('Chờ duyệt')
                                    </button>
                                    <button class="filter-btn" data-filter="rejected">
                                        <i class="las la-times-circle"></i>
                                        @lang('Từ chối')
                                    </button>
                                </div>
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                            </div>

            <!-- Companies Grid -->
            <div class="row" id="companiesGrid">
                @forelse($companies as $company)
                    <div class="col-lg-6 col-xl-4 mb-4 company-card" data-status="{{ $company->status }}">
                        <div class="company-item">
                            <div class="company-header">
                                <div class="company-image">
                                    <img src="{{ getImage(getFilePath('company') . '/' . ($company->image ?? 'default.png'), getFileSize('company')) }}" alt="{{ $company->name }}">
                                    <div class="status-overlay">
                                                @php echo $company->statusBadge @endphp
                                    </div>
                                </div>
                                <div class="company-actions">
                                    @if ($company->status == 1)
                                        <button class="action-btn action-btn-primary infoScript" data-bs-toggle="modal" 
                                            title="@lang('Mã nhúng đánh giá')" data-bs-target="#scriptModal" 
                                            data-name="{{ $company->name }}" data-id="{{ $company->id }}"
                                            data-sitename="{{ gs('site_name') }}" 
                                            data-url="{{ route('company.rating', encrypt($company->id)) }}"
                                            data-redirectURL="{{ route('company.details', [$company->id, slug($company->name)]) }}">
                                            <i class="las la-code"></i>
                                        </button>
                                    @endif
                                    <a href="{{ route('user.company.edit', $company->id) }}" class="action-btn action-btn-warning" title="@lang('Chỉnh sửa')">
                                        <i class="las la-edit"></i>
                                    </a>
                                    @if ($company->admin_feedback)
                                        <button class="action-btn action-btn-info feedback" data-bs-toggle="modal"
                                            data-bs-target="#companyFeedBackModal" title="@lang('Phản hồi từ admin')"
                                                        data-feedback="{{ $company->admin_feedback }}">
                                            <i class="las la-comment-dots"></i>
                                                    </button>
                                                @endif
                                            </div>
                            </div>
                            
                            <div class="company-body">
                                <div class="company-title">
                                                @if ($company->status == 1)
                                        <a href="{{ route('company.details', [$company->id, $company->name]) }}" class="title-link">
                                            {{ $company->name }}
                                        </a>
                                    @else
                                        <span class="title-text">{{ $company->name }}</span>
                                    @endif
                                    <div class="category-badge">
                                        <i class="las la-tag"></i>
                                        {{ $company->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="company-meta">
                                    <div class="meta-item">
                                        <i class="las la-map-marker-alt"></i>
                                        <span>{{ Str::limit($company->address, 30) }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="las la-phone"></i>
                                        <span>{{ $company->phone ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                    <div class="meta-item">
                                        <i class="las la-calendar"></i>
                                        <span>{{ $company->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <div class="rating-section">
                                    <div class="rating-stars">
                                        @php echo avgRating($company->avg_rating); @endphp
                                    </div>
                                    <div class="rating-info">
                                        <span class="rating-score">{{ number_format($company->avg_rating, 1) }}</span>
                                        <span class="rating-count">({{ $company->reviews_count }} đánh giá)</span>
                                    </div>
                                </div>

                                @if($company->experience)
                                    <div class="experience-badge">
                                        <i class="las la-medal"></i>
                                        {{ $company->experience }} năm kinh nghiệm
                                    </div>
                                                @endif
                            </div>

                            <div class="company-footer">
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <span class="stat-value">0</span>
                                        <span class="stat-label">Dự án</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">{{ $company->reviews_count }}</span>
                                        <span class="stat-label">Đánh giá</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-value">0</span>
                                        <span class="stat-label">Lượt xem</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                                            </div>
                                @empty
                    <div class="col-12">
                                            <div class="empty-state">
                            <div class="empty-icon">
                                <i class="las la-store"></i>
                            </div>
                            <h3>@lang('Chưa có thợ nào')</h3>
                            <p>@lang('Bạn chưa tạo thông tin thợ nào. Hãy tạo thợ đầu tiên để bắt đầu nhận việc!')</p>
                            <a href="{{ route('user.company.create') }}" class="btn btn--base btn-lg">
                                <i class="las la-plus me-2"></i>
                                @lang('Tạo thợ đầu tiên')
                            </a>
                                            </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($companies->hasPages())
                <div class="pagination-wrapper">
                    {{ paginateLinks($companies) }}
                </div>
            @endif

            <!-- Advertisement -->
            <div class="advertisement-section">
                    @php echo getAdvertisement('728x90'); @endphp
            </div>
        </div>
    </section>

    <!-- Feedback Modal -->
    <div class="modal fade" id="companyFeedBackModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-comment-dots me-2"></i>
                        @lang('Phản hồi từ quản trị viên')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="feedback-content admin-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Modal -->
    <div class="modal fade" id="scriptModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-code me-2"></i>
                        @lang('Mã nhúng đánh giá')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">@lang('Sao chép mã bên dưới và dán vào website của bạn để hiển thị đánh giá:')</p>
                    <div class="code-container">
                    <div id="copyURL" class="companyScript"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base copyBoard" id="copyBoard">
                        <i class="las la-copy me-2"></i> 
                        @lang('Sao chép mã')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
:root {
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --dark-color: #1f2937;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
}

/* Header Styles */
.btn-create {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

/* Filter Card */
.filter-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Search Box */
.search-box {
    position: relative;
}

.search-input {
    padding: 12px 16px 12px 45px;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    background: white;
    font-size: 16px;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    font-size: 18px;
}

/* Filter Buttons */
.filter-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 2px solid var(--gray-200);
    background: white;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.filter-btn.active {
    border-color: var(--primary-color);
    background: var(--primary-color);
    color: white;
}

.filter-btn .badge {
    background: rgba(255, 255, 255, 0.2);
    color: inherit;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
}

/* Company Cards */
.company-item {
    background: white;
    border-radius: 16px;
        overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.company-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
}

/* Company Header */
.company-header {
    position: relative;
    height: 200px;
        overflow: hidden;
    }

.company-image {
    position: relative;
    width: 100%;
    height: 100%;
}

.company-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    transition: transform 0.3s ease;
}

.company-item:hover .company-image img {
    transform: scale(1.05);
}

.status-overlay {
    position: absolute;
    top: 12px;
    left: 12px;
}

.company-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    gap: 8px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.company-item:hover .company-actions {
    opacity: 1;
    transform: translateY(0);
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.action-btn-primary { background: var(--primary-color); }
.action-btn-warning { background: var(--warning-color); }
.action-btn-info { background: var(--info-color); }

.action-btn:hover {
    transform: scale(1.1);
}

/* Company Body */
.company-body {
    padding: 20px;
        flex: 1;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.company-title {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.title-link {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-color);
    text-decoration: none;
    line-height: 1.3;
    transition: color 0.3s ease;
}

.title-link:hover {
    color: var(--primary-color);
}

.title-text {
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-color);
    line-height: 1.3;
}

.category-badge {
    display: flex;
    align-items: center;
    gap: 4px;
    background: var(--gray-100);
    color: var(--gray-600);
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}

/* Company Meta */
.company-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    }

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-600);
    font-size: 14px;
}

.meta-item i {
    color: var(--gray-400);
    width: 16px;
}

/* Rating Section */
.rating-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px;
    background: var(--gray-100);
    border-radius: 8px;
}

.rating-info {
    display: flex;
    align-items: center;
    gap: 4px;
}

.rating-score {
    font-weight: 700;
    color: var(--warning-color);
}

.rating-count {
    color: var(--gray-500);
    font-size: 12px;
}

/* Experience Badge */
.experience-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, var(--warning-color), #fbbf24);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
        font-weight: 600;
    align-self: flex-start;
}

/* Company Footer */
.company-footer {
    padding: 16px 20px;
    background: var(--gray-100);
    border-top: 1px solid var(--gray-200);
    }

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.stat-item {
    text-align: center;
    }

.stat-value {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: var(--dark-color);
}

.stat-label {
    display: block;
    font-size: 12px;
    color: var(--gray-500);
    margin-top: 2px;
}

/* Empty State */
    .empty-state {
        text-align: center;
    padding: 60px 20px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 16px;
    backdrop-filter: blur(10px);
}

.empty-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.empty-icon i {
    font-size: 48px;
        color: white;
    }

.empty-state h3 {
    color: var(--dark-color);
    margin-bottom: 12px;
    }

.empty-state p {
    color: var(--gray-600);
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 40px;
}

/* Advertisement */
.advertisement-section {
    text-align: center;
    margin-top: 40px;
        }

/* Modal Improvements */
.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.modal-header {
    border-bottom: 1px solid var(--gray-200);
    padding: 20px 24px;
}

.modal-body {
    padding: 24px;
        }

.modal-footer {
    border-top: 1px solid var(--gray-200);
    padding: 16px 24px;
}

.feedback-content {
    background: var(--gray-100);
    border-radius: 8px;
    padding: 16px;
    font-style: italic;
    color: var(--gray-700);
}

.code-container {
    background: var(--gray-100);
    border-radius: 8px;
    padding: 16px;
}

.companyScript {
    background: #1f2937;
    color: #e5e7eb;
    padding: 16px;
            border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.4;
    word-break: break-all;
    white-space: pre-wrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-buttons {
        justify-content: flex-start;
        margin-top: 16px;
    }
    
    .filter-btn {
        font-size: 14px;
        padding: 6px 12px;
    }
    
    .company-actions {
        opacity: 1;
        transform: translateY(0);
    }
    
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        }
    
    .stat-value {
        font-size: 16px;
    }
}

/* Animation Classes */
.company-card {
    animation: fadeInUp 0.5s ease-out;
}

.company-card:nth-child(2) { animation-delay: 0.1s; }
.company-card:nth-child(3) { animation-delay: 0.2s; }
.company-card:nth-child(4) { animation-delay: 0.3s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Status Badges Custom Styles */
.badge {
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
    // Search functionality
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.company-card').each(function() {
            const companyName = $(this).find('.title-link, .title-text').text().toLowerCase();
            if (companyName.includes(searchTerm)) {
                $(this).show().addClass('animate__animated animate__fadeIn');
            } else {
                $(this).hide();
            }
        });
    });

    // Filter functionality
    $('.filter-btn').on('click', function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        $('.company-card').each(function() {
            if (filter === 'all') {
                $(this).show().addClass('animate__animated animate__fadeIn');
            } else {
                const status = $(this).data('status');
                let showCard = false;
                
                if (filter === 'approved' && status == 1) showCard = true;
                if (filter === 'pending' && status == 2) showCard = true;
                if (filter === 'rejected' && status == 0) showCard = true;
                
                if (showCard) {
                    $(this).show().addClass('animate__animated animate__fadeIn');
                } else {
                    $(this).hide();
                }
            }
        });
    });

    // Feedback modal
            $(".feedback").on('click', function() {
        $(".admin-feedback").text($(this).data('feedback'));
    });

    // Script modal
            $(".infoScript").on('click', function() {
                $(".companyScript").empty();

                let cid = $(this).data('id');
                let cName = $(this).data('name');
                let url = $(this).data('url');
                let siteUrl = $(this).data('redirecturl');
                let siteName = $(this).data('sitename');
                let halfStar = '{{ getImage('assets/images/half-star.svg') }}';
                let fullStar = '{{ getImage('assets/images/full-star.svg') }}';
                let blankStar = '{{ getImage('assets/images/blank-star.svg') }}';

        let scriptData = `<div title="${cName}" class="rating--here-${cid}" style="text-align: center; margin: 30px auto 30px;"></div><script>fetch("${url}").then((t=>t.json())).then((t=>{let a=t.rating?t.rating:0,s=0,e="",l=document.getElementsByClassName("rating--here-${cid}"),n=t=>e+='<img width="25px" style="margin: 5px auto 5px;" src="'+t+'"/>';for(;s<5;)n(a-s>=1?"${fullStar}":a-s>0?"${halfStar}":"${blankStar}"),s++;for(let a=0;a<l.length;a++)l[a].innerHTML="<div >"+e+"</div> <h6>"+t.rating+t.outOf+'</h6><a href="${siteUrl}" style="color: #d38a04;" target="_blank" class="text--base">Powered By : ${siteName} </a>'})).catch((function(t){console.warn("Something went wrong in script.",t)}));</script>`;

        $(".companyScript").text(scriptData);
            });

    // Copy functionality
            $('.copyBoard').on("click", function() {
        const textToCopy = document.getElementById("copyURL").textContent;
        navigator.clipboard.writeText(textToCopy).then(function() {
            notify('success', "@lang('Đã sao chép mã thành công')");
        }).catch(function() {
            // Fallback for older browsers
            const range = document.createRange();
                range.selectNode(document.getElementById("copyURL"));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand("copy");
                window.getSelection().removeAllRanges();
                notify('success', "@lang('Đã sao chép mã thành công')");
        });
    });

    // Smooth scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.company-card').forEach(card => {
        observer.observe(card);
            });
        });
    </script>
@endpush
