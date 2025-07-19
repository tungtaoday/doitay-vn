# 🎯 Google Analytics Admin Panel Setup Guide

## 📊 Tổng quan

Đã tích hợp Google Analytics tracking toàn diện vào admin panel với các tính năng:

### ✅ **Đã hoàn thành:**

1. **📁 Files đã tạo:**
   - `core/resources/views/admin/setting/analytics.blade.php` - Trang admin settings
   - `core/app/Http/Controllers/Admin/GeneralSettingController.php` - Controller methods
   - `core/routes/admin.php` - Routes cho analytics
   - `core/resources/views/admin/setting/settings.json` - Menu configuration
   - `core/resources/views/partials/google-analytics-tracking.blade.php` - Tracking script
   - `core/resources/views/templates/basic/partials/appointment-tracking.blade.php` - Appointment tracking
   - `core/resources/views/templates/basic/partials/company-lead-tracking.blade.php` - Company tracking
   - `core/resources/views/templates/basic/partials/user-tracking.blade.php` - User tracking

2. **🔧 Admin Panel Features:**
   - Google Analytics ID configuration
   - Facebook Pixel ID (optional)
   - Tracking status toggle
   - Individual event tracking controls
   - Advanced settings (Enhanced Ecommerce, Custom Dimensions, Debug Mode, GDPR)
   - Real-time status display

3. **🎯 Tracking Events:**
   - Appointment tracking (booking, confirmation, cancellation, completion)
   - Company interactions (profile views, contact actions, ratings)
   - User actions (registration, login, logout, profile updates)
   - Search and engagement (search, scroll depth, time on page)
   - Form submissions and button clicks

## 🚀 **Cách sử dụng:**

### 1. **Truy cập Admin Panel:**
```
Admin Panel → System Setting → Analytics Settings
```

### 2. **Cấu hình Google Analytics:**
- **Google Analytics ID:** Nhập GA ID (G-XXXXXXXXXX)
- **Facebook Pixel ID:** Tùy chọn (123456789012345)
- **Tracking Status:** Bật/tắt tracking

### 3. **Cấu hình Tracking Events:**
- ✅ **Track Appointments:** Bật để track đặt lịch
- ✅ **Track Company Views:** Bật để track xem profile công ty
- ✅ **Track User Registration:** Bật để track đăng ký
- ✅ **Track Search:** Bật để track tìm kiếm
- ✅ **Track Scroll Depth:** Bật để track độ sâu cuộn

### 4. **Advanced Settings:**
- ✅ **Enhanced Ecommerce:** Track appointments như transactions
- ✅ **Custom Dimensions:** Track user_type, page_category, action_type
- ✅ **Debug Mode:** Log events to console (development)
- ✅ **GDPR Compliance:** Respect user privacy

## 📊 **Database Structure:**

### **Bảng general_settings cần có các cột:**
```sql
-- Google Analytics settings
google_analytics_id VARCHAR(50)
facebook_pixel_id VARCHAR(50)
analytics_enabled TINYINT(1)

-- Tracking settings
track_appointments TINYINT(1)
track_appointment_status TINYINT(1)
track_company_views TINYINT(1)
track_company_contacts TINYINT(1)
track_user_registration TINYINT(1)
track_user_login TINYINT(1)
track_search TINYINT(1)
track_scroll_depth TINYINT(1)

-- Advanced settings
enhanced_ecommerce TINYINT(1)
custom_dimensions TINYINT(1)
analytics_debug TINYINT(1)
gdpr_compliance TINYINT(1)
```

## 🔧 **Setup Instructions:**

### **Bước 1: Lấy Google Analytics ID**
1. Truy cập [Google Analytics](https://analytics.google.com)
2. Vào **Admin** → **Data Streams** → **Web Stream**
3. Copy **Measurement ID** (G-XXXXXXXXXX)

### **Bước 2: Cấu hình trong Admin**
1. Đăng nhập Admin Panel
2. Vào **System Setting** → **Analytics Settings**
3. Nhập Google Analytics ID
4. Bật các tracking options cần thiết
5. Lưu settings

### **Bước 3: Test Tracking**
1. Mở website trong browser
2. Mở Developer Tools (F12)
3. Vào tab Console
4. Thực hiện các hành động (click, scroll, form submit)
5. Kiểm tra console để xem tracking events

### **Bước 4: Verify trong Google Analytics**
1. Vào Google Analytics → **Real-time** → **Events**
2. Kiểm tra các events được gửi
3. Verify custom dimensions và parameters

## 🎯 **Data Attributes Usage:**

### **Form Tracking:**
```html
<!-- Track appointment form -->
<form data-track-type="appointment">
  <input name="appointment_date" />
  <input name="appointment_time" />
  <input name="company_id" />
</form>

<!-- Track registration form -->
<form data-track-type="registration" data-user-type="company">
  <input name="email" />
  <input name="password" />
</form>

<!-- Track search form -->
<form data-track-type="search">
  <input name="search" />
</form>
```

### **Button Tracking:**
```html
<!-- Track appointment actions -->
<button data-track-appointment-action="cancel" 
        data-appointment-id="123" 
        data-company-id="456">
  Hủy lịch hẹn
</button>

<!-- Track company contact -->
<button data-track-contact-method="phone" 
        data-company-id="456" 
        data-company-name="ABC Company">
  Gọi điện
</button>
```

### **Company Card Tracking:**
```html
<div data-track-company-card 
     data-company-id="456" 
     data-company-name="ABC Company">
  <h3>ABC Company</h3>
  <p>Dịch vụ điện nước</p>
</div>
```

## 📈 **Tracking Events:**

### **Appointment Events:**
- `appointment_booking_started` - Bắt đầu đặt lịch
- `appointment_booking_submitted` - Gửi form đặt lịch
- `appointment_confirmation` - Xác nhận lịch hẹn
- `appointment_cancellation` - Hủy lịch hẹn
- `appointment_completion` - Hoàn thành lịch hẹn
- `appointment_reschedule` - Đổi lịch hẹn

### **Company Events:**
- `company_card_click` - Click vào card công ty
- `company_profile_view` - Xem profile công ty
- `company_contact` - Liên hệ với công ty
- `company_rating` - Đánh giá công ty

### **User Events:**
- `user_registration` - Đăng ký tài khoản
- `user_login` - Đăng nhập
- `user_logout` - Đăng xuất
- `profile_update` - Cập nhật profile

### **Search & Engagement:**
- `search_submitted` - Gửi tìm kiếm
- `scroll_depth` - Độ sâu cuộn trang
- `time_on_page` - Thời gian trên trang
- `form_submit` - Gửi form

## 🔍 **Debug & Testing:**

### **Browser Console Commands:**
```javascript
// Test tracking function
trackEvent('test_event', {category: 'test', label: 'debug'});

// Check GA object
console.log(window.gtag);

// Check dataLayer
console.log(window.dataLayer);

// Test custom event
gtag('event', 'test', {category: 'test'});
```

### **Google Analytics Debugger:**
1. Cài đặt Google Analytics Debugger extension
2. Mở Developer Tools → Console
3. Kiểm tra các events được gửi

### **Real-time Reports:**
- Google Analytics → Real-time → Events
- Google Analytics → Real-time → Conversions

## 🚨 **Troubleshooting:**

### **Common Issues:**
1. **Events không hiển thị:** Kiểm tra GA ID và network
2. **Data attributes không work:** Kiểm tra JavaScript console
3. **Duplicate events:** Kiểm tra event listeners
4. **GDPR compliance:** Đảm bảo user đã accept cookies

### **Debug Steps:**
1. Kiểm tra GA ID trong admin panel
2. Verify tracking script được load
3. Test với browser console
4. Check Google Analytics real-time reports

## 📊 **Reports & Analytics:**

### **Key Metrics:**
- **Appointment Conversion Rate:** Tỷ lệ chuyển đổi đặt lịch
- **Company Profile Views:** Lượt xem profile công ty
- **Search Usage:** Sử dụng tính năng tìm kiếm
- **User Engagement:** Thời gian trên trang, scroll depth
- **Form Completion:** Tỷ lệ hoàn thành form

### **Custom Reports:**
```javascript
// Track conversion funnel
trackEvent('funnel_step', {
  category: 'conversion',
  label: 'step_1_view_company',
  step: 1
});
```

## 🎉 **Kết luận:**

Hệ thống Google Analytics tracking đã được tích hợp hoàn chỉnh vào admin panel với:

- ✅ **Admin Interface:** Dễ dàng cấu hình và quản lý
- ✅ **Flexible Tracking:** Bật/tắt từng loại tracking
- ✅ **Comprehensive Events:** Track tất cả điểm chạm quan trọng
- ✅ **Advanced Features:** Enhanced Ecommerce, Custom Dimensions
- ✅ **Debug Tools:** Console logging và real-time monitoring

**Bước tiếp theo:** Cấu hình GA ID và test tracking trên website! 