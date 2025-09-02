# 📱 HERO SECTION MOBILE OPTIMIZATION

## 📋 Tổng quan
Bộ CSS này tối ưu hóa hero section để hiển thị background image đầy đủ width trên mobile devices.

## 🎯 Vấn đề được giải quyết
- **Background image không hiển thị đầy đủ width** trên mobile
- **Responsive design** cho các kích thước màn hình khác nhau
- **Tối ưu hóa performance** cho mobile devices

## 📁 Các file CSS

### 1. `hero-mobile.css` (Chính)
- CSS cơ bản cho hero section
- Responsive design cho mobile
- Tối ưu hóa layout và typography

### 2. `hero-additional.css` (Bổ sung)
- CSS bổ sung cho mobile optimization
- Đảm bảo background image hiển thị đầy đủ width
- Tương thích với các thiết bị khác nhau

## 🔧 Cách hoạt động

### Background Image Optimization
```css
@media (max-width: 767px) {
    .hero-section {
        width: 100vw !important;
        max-width: 100vw !important;
        left: 50% !important;
        right: 50% !important;
        margin-left: -50vw !important;
        margin-right: -50vw !important;
        position: relative !important;
    }
}
```

### JavaScript Enhancement
- Tự động chuyển đổi giữa desktop và mobile image
- Đảm bảo full width trên mobile
- Xử lý orientation change

## 📱 Responsive Breakpoints

### Mobile (≤767px)
- Full viewport width
- Optimized typography
- Centered content
- Full background image coverage

### Tablet (768px - 991px)
- Balanced layout
- Medium typography
- Responsive buttons

### Desktop (≥992px)
- Standard layout
- Large typography
- Side-by-side content

## 🚀 Cách sử dụng

### 1. Include CSS files
```html
<link rel="stylesheet" href="{{ asset('assets/templates/basic/css/hero-mobile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/templates/basic/css/hero-additional.css') }}">
```

### 2. HTML Structure
```html
<section class="hero-section bg_img" 
         data-desktop-image="desktop.jpg"
         data-mobile-image="mobile.jpg">
    <div class="hero-overlay"></div>
    <div class="container">
        <!-- Content here -->
    </div>
</section>
```

### 3. JavaScript (Tự động)
- Không cần thêm JavaScript
- Tự động xử lý responsive
- Tự động chuyển đổi image

## 🎨 Customization

### Thay đổi colors
```css
.hero-overlay {
    background: rgba(0, 0, 0, 0.4); /* Overlay color */
}

.gradient-text {
    background: linear-gradient(45deg, #ff6b6b, #4ecdc4); /* Gradient colors */
}
```

### Thay đổi typography
```css
.hero-title {
    font-size: 2.5rem; /* Title size */
    font-weight: 700;  /* Title weight */
}

.hero-description {
    font-size: 1.1rem; /* Description size */
}
```

### Thay đổi spacing
```css
.hero-section {
    min-height: 100vh; /* Section height */
}

.hero-content {
    padding: 2rem 0; /* Content padding */
}
```

## 🔍 Testing

### Mobile Testing
1. **Chrome DevTools**: Toggle device toolbar
2. **Responsive Design Mode**: Test các breakpoints
3. **Device Simulation**: Test trên các thiết bị thật

### Breakpoint Testing
- **Mobile**: ≤767px
- **Tablet**: 768px - 991px  
- **Desktop**: ≥992px

### Orientation Testing
- **Portrait**: Test chiều dọc
- **Landscape**: Test chiều ngang

## 🐛 Troubleshooting

### Background image không hiển thị
1. Kiểm tra đường dẫn image
2. Kiểm tra CSS đã được include
3. Kiểm tra JavaScript console

### Layout bị vỡ trên mobile
1. Kiểm tra CSS specificity
2. Kiểm tra conflicting styles
3. Kiểm tra media queries

### Performance issues
1. Tối ưu hóa image size
2. Sử dụng WebP format
3. Lazy loading cho images

## 📈 Performance Tips

### Image Optimization
- **Desktop**: 1920x840px (JPEG/WebP)
- **Mobile**: 600x800px (JPEG/WebP)
- **Compression**: 80-90% quality
- **Format**: WebP với JPEG fallback

### CSS Optimization
- Minify CSS files
- Combine multiple CSS files
- Use critical CSS inline

### JavaScript Optimization
- Defer non-critical JavaScript
- Use event delegation
- Minimize DOM queries

## 🔄 Updates & Maintenance

### Version History
- **v1.0**: Initial mobile optimization
- **v1.1**: Added responsive breakpoints
- **v1.2**: Enhanced JavaScript functionality

### Future Improvements
- CSS Grid support
- Advanced animations
- Accessibility enhancements
- Performance optimizations

## 📞 Support

Nếu gặp vấn đề:
1. Kiểm tra browser compatibility
2. Kiểm tra CSS conflicts
3. Kiểm tra JavaScript errors
4. Liên hệ developer

---
**⚠️ Lưu ý**: Luôn test trên nhiều thiết bị và browser khác nhau!
