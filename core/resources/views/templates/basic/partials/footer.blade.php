@php
    $footerContent = getContent('footer.content', true);
    $iconElements = getContent('social_icon.element', false, null, true);
    $policyPages = getContent('policy_pages.element');
    $pages = App\Models\Page::where('tempname',$activeTemplate)->where('slug','!=','/')->where('is_default', 0)->get();
@endphp

<footer class="footer-modern">
    <!-- Footer Top Section -->
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget company-info">
                        <a class="footer-logo" href="{{ route('home') }}">
                            <img src="{{ getImage(getFilePath('logo_icon') . '/logo_white.png', '300X300') }}"
                                alt="{{ __(gs('site_name')) }}">
                        </a>
                        <p class="company-description">
                            {{ __(strLimit(@$footerContent->data_values->description, 120)) }}
                        </p>
                        
                        <!-- Company Stats -->
                        <div class="company-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ App\Models\Company::approved()->count() }}+</span>
                                <span class="stat-label">Thợ chuyên nghiệp</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ App\Models\Rating::count() }}+</span>
                                <span class="stat-label">Dự án hoàn thành</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ App\Models\Category::where('status', 1)->count() }}+</span>
                                <span class="stat-label">Ngành nghề</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">
                            <i class="las la-link"></i>
                            @lang('Liên Kết')
                        </h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}">@lang('Trang Chủ')</a></li>
                            <li><a href="{{ route('company.all') }}">@lang('Tìm Thợ')</a></li>
                            <li><a href="{{ route('become.contractor') }}">@lang('Trở Thành Thợ')</a></li>
                            <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                            <li><a href="{{ route('contact') }}">@lang('Liên Hệ')</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">
                            <i class="las la-tools"></i>
                            @lang('Dịch Vụ')
                        </h4>
                        <ul class="footer-links">
                            @foreach(App\Models\Category::where('status', 1)->take(5)->get() as $category)
                            <li>
                                <a href="{{ route('companies.category', $category->id) }}">{{ $category->name }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Contact & Newsletter -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">
                            <i class="las la-envelope"></i>
                            @lang('Kết Nối Với Chúng Tôi')
                        </h4>
                        
                        <!-- Contact Info -->
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="las la-map-marker-alt"></i>
                                <span>{{ gs('address') ?? 'Hà Nội, Việt Nam' }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="las la-phone"></i>
                                <span>{{ gs('phone') ?? '+84 901 234 567' }}</span>
                            </div>
                            <div class="contact-item">
                                <i class="las la-envelope"></i>
                                <span>{{ gs('email_from') ?? 'support@doitay.vn' }}</span>
                            </div>
                        </div>

                        <!-- Newsletter Signup -->
                        <div class="newsletter-signup">
                            <p class="newsletter-text">Đăng ký nhận tin tức mới nhất</p>
                            <form class="newsletter-form" action="#" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Email của bạn..." required>
                                    <button type="submit" class="btn btn-newsletter">
                                        <i class="las la-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row gy-3 align-items-center">
                <div class="col-md-6">
                    <div class="copyright">
                        <p class="mb-0">
                            © {{ date('Y') }} {{ __(gs('site_name')) }}. 
                            <span class="highlight">Tất cả quyền được bảo lưu.</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="footer-bottom-right">
                        <!-- Social Links -->
                        <div class="social-links">
                            @foreach ($iconElements as $iconElement)
                            <a href="{{ @$iconElement->data_values->url }}" target="_blank" class="social-link">
                                @php echo @$iconElement->data_values->social_icon; @endphp
                            </a>
                            @endforeach
                        </div>
                        
                        <!-- Policy Links -->
                        <div class="policy-links">
                            @foreach ($policyPages->take(2) as $page)
                            <a href="{{ route('policy.pages', $page->slug) }}">
                                {{ @$page->data_values->title }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
/* ============================================
   MODERN FOOTER PROFESSIONAL STYLING
   Colors: #102f4b (Primary) & #48bbe2 (Accent)
============================================ */

.footer-modern {
    background: linear-gradient(135deg, #102f4b 0%, #0a1f35 100%);
    color: #ffffff;
    position: relative;
    overflow: hidden;
}

.footer-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="%2348bbe2" opacity="0.03"/></svg>') repeat;
    pointer-events: none;
}

.footer-top {
    padding: 4rem 0 2rem;
    position: relative;
    z-index: 2;
    border-bottom: 1px solid rgba(72, 187, 226, 0.1);
}

.footer-bottom {
    padding: 1.5rem 0;
    background: rgba(0, 0, 0, 0.2);
    position: relative;
    z-index: 2;
}

/* Company Info Section */
.company-info .footer-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    margin-bottom: 1.5rem;
}

.company-info .footer-logo img {
    width: 300px;
    height: 300px;
    object-fit: contain;
}

.company-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    letter-spacing: -0.02em;
}

.company-description {
    font-size: 1rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
}

/* Company Stats */
.company-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(72, 187, 226, 0.1);
    border-radius: 12px;
    border: 1px solid rgba(72, 187, 226, 0.2);
    transition: all 0.3s ease;
}

.stat-item:hover {
    background: rgba(72, 187, 226, 0.15);
    transform: translateY(-2px);
}

.stat-number {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: #48bbe2;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.3;
}

/* Footer Titles */
.footer-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
}

.footer-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 30px;
    height: 2px;
    background: #48bbe2;
    border-radius: 1px;
}

.footer-title i {
    color: #48bbe2;
    font-size: 1.2rem;
}

/* Footer Links */
.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
    padding-left: 1rem;
}

.footer-links a::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 4px;
    background: #48bbe2;
    border-radius: 50%;
    opacity: 0;
    transition: all 0.3s ease;
}

.footer-links a:hover {
    color: #48bbe2;
    padding-left: 1.5rem;
}

.footer-links a:hover::before {
    opacity: 1;
}

/* Contact Info */
.contact-info {
    margin-bottom: 2rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

.contact-item i {
    color: #48bbe2;
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

/* Newsletter */
.newsletter-signup {
    margin-top: 2rem;
}

.newsletter-text {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 1rem;
}

.newsletter-form .input-group {
    display: flex;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.newsletter-form .form-control {
    border: none;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    background: rgba(255, 255, 255, 0.95);
    color: #102f4b;
    flex: 1;
}

.newsletter-form .form-control::placeholder {
    color: rgba(16, 47, 75, 0.6);
}

.newsletter-form .form-control:focus {
    outline: none;
    box-shadow: none;
    background: #ffffff;
}

.btn-newsletter {
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
    border: none;
    padding: 0.75rem 1.25rem;
    color: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-newsletter:hover {
    background: linear-gradient(135deg, #3aa8d1, #2a95c0);
    transform: translateX(-2px);
}

.btn-newsletter i {
    font-size: 1rem;
}

/* Footer Bottom */
.copyright p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    line-height: 1.4;
}

.copyright .highlight {
    color: #48bbe2;
    font-weight: 500;
}

.footer-bottom-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1rem;
}

/* Social Links */
.social-links {
    display: flex;
    gap: 0.75rem;
}

.social-link {
    width: 40px;
    height: 40px;
    background: rgba(72, 187, 226, 0.1);
    border: 1px solid rgba(72, 187, 226, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #48bbe2;
    text-decoration: none;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: #48bbe2;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(72, 187, 226, 0.3);
}

/* Policy Links */
.policy-links {
    display: flex;
    gap: 1.5rem;
}

.policy-links a {
    color: rgba(255, 255, 255, 0.6);
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.3s ease;
}

.policy-links a:hover {
    color: #48bbe2;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .footer-top {
        padding: 3rem 0 1.5rem;
    }
    
    .company-stats {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .stat-item {
        flex: 1;
        min-width: calc(33.333% - 0.5rem);
        padding: 0.75rem 0.5rem;
    }
    
    .stat-number {
        font-size: 1.1rem;
    }
    
    .stat-label {
        font-size: 0.75rem;
    }
    
    .footer-bottom-right {
        align-items: flex-start;
    }
    
    .social-links {
        justify-content: flex-start;
    }
    
    .policy-links {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    /* Newsletter mobile optimization */
    .newsletter-form .form-control {
        padding: 1rem;
        font-size: 1rem;
        min-height: 50px;
    }
    
    .btn-newsletter {
        padding: 1rem 1.5rem;
        min-height: 50px;
    }
}

@media (max-width: 576px) {
    .footer-top {
        padding: 2.5rem 0 1rem;
    }
    
    .company-info .footer-logo {
        justify-content: center;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .company-info .footer-logo img {
        width: 200px;
        height: 200px;
    }
    
    .company-description {
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    /* Stats single row on mobile */
    .company-stats {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .stat-item {
        flex: 1;
        padding: 0.75rem 0.25rem;
        min-width: auto;
    }
    
    .stat-number {
        font-size: 1rem;
        margin-bottom: 0.15rem;
    }
    
    .stat-label {
        font-size: 0.65rem;
        line-height: 1.2;
    }
    
    /* Newsletter mobile improvements */
    .newsletter-signup {
        margin-top: 1.5rem;
    }
    
    .newsletter-text {
        text-align: center;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    .newsletter-form .input-group {
        flex-direction: column;
        gap: 0;
    }
    
    .newsletter-form .form-control {
        border-radius: 8px 8px 0 0;
        padding: 1.25rem 1rem;
        font-size: 1rem;
        min-height: 55px;
        border-bottom: 1px solid rgba(16, 47, 75, 0.1);
    }
    
    .btn-newsletter {
        border-radius: 0 0 8px 8px;
        padding: 1.25rem;
        min-height: 55px;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .btn-newsletter i {
        font-size: 1.1rem;
    }
}
</style>
