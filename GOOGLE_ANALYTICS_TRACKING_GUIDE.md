# 🎯 Google Analytics Tracking Guide - Doitay.vn

## 📊 Tổng quan hệ thống tracking

Website đã được tích hợp Google Analytics tracking toàn diện cho từng điểm chạm. Hệ thống bao gồm:

### 🔧 **Core Tracking Features:**
- ✅ **Page Views:** Tự động track tất cả page views
- ✅ **User Types:** Track user_type (guest, user, company)
- ✅ **Page Categories:** Track loại trang (home, company, appointment)
- ✅ **Custom Events:** Track từng điểm chạm cụ thể
- ✅ **Enhanced Ecommerce:** Track appointment như transactions
- ✅ **Engagement:** Track scroll depth và time on page

## 🎯 **Các Events được Track:**

### 📅 **Appointment Events:**
- `appointment_booking_started` - Bắt đầu đặt lịch
- `appointment_booking_submitted` - Gửi form đặt lịch
- `appointment_confirmation` - Xác nhận lịch hẹn
- `appointment_cancellation` - Hủy lịch hẹn
- `appointment_completion` - Hoàn thành lịch hẹn
- `appointment_reschedule` - Đổi lịch hẹn
- `appointment_form_interaction` - Tương tác với form

### 🏢 **Company Events:**
- `company_card_click` - Click vào card công ty
- `company_profile_view` - Xem profile công ty
- `company_contact` - Liên hệ với công ty
- `company_rating` - Đánh giá công ty
- `service_category_click` - Click vào danh mục dịch vụ

### 👤 **User Events:**
- `user_registration` - Đăng ký tài khoản
- `user_login` - Đăng nhập
- `user_logout` - Đăng xuất
- `profile_update` - Cập nhật profile
- `password_change` - Đổi mật khẩu
- `email_verification` - Xác thực email

### 🔍 **Search & Engagement Events:**
- `search_submitted` - Gửi tìm kiếm
- `search_filter_changed` - Thay đổi bộ lọc
- `scroll_depth` - Độ sâu cuộn trang
- `time_on_page` - Thời gian trên trang
- `form_submit` - Gửi form
- `button_click` - Click button
- `link_click` - Click link

## 🛠️ **Cách sử dụng Data Attributes:**

### 📝 **Form Tracking:**
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

### 🔘 **Button Tracking:**
```html
<!-- Track appointment actions -->
<button data-track-appointment-action="cancel" 
        data-appointment-id="123" 
        data-company-id="456">
  Hủy lịch hẹn
</button>

<button data-track-appointment-action="confirm" 
        data-appointment-id="123" 
        data-company-id="456">
  Xác nhận
</button>

<!-- Track company contact -->
<button data-track-contact-method="phone" 
        data-company-id="456" 
        data-company-name="ABC Company">
  Gọi điện
</button>
```

### 🏢 **Company Card Tracking:**
```html
<div data-track-company-card 
     data-company-id="456" 
     data-company-name="ABC Company">
  <h3>ABC Company</h3>
  <p>Dịch vụ điện nước</p>
</div>
```

### 🔍 **Search & Filter Tracking:**
```html
<!-- Search form -->
<form data-track-type="search">
  <input name="search" placeholder="Tìm kiếm..." />
</form>

<!-- Filter dropdown -->
<select data-track-filter="category">
  <option value="electrical">Điện</option>
  <option value="plumbing">Nước</option>
</select>
```

## 📊 **Google Analytics Setup:**

### 1. **Cập nhật GA ID:**
```php
// Trong file setup_google_analytics.php
$gaId = 'G-XXXXXXXXXX'; // Thay thế bằng GA ID thật
```

### 2. **Chạy setup script:**
```bash
php setup_google_analytics.php
```

### 3. **Kiểm tra tracking:**
- Mở website
- Thực hiện các hành động (đặt lịch, tìm kiếm, etc.)
- Vào Google Analytics > Real-time > Events
- Kiểm tra các events được gửi

## 📈 **Enhanced Ecommerce Tracking:**

### 💰 **Appointment as Transactions:**
```javascript
// Track appointment creation
trackAppointmentCreation({
  id: 123,
  company_name: 'ABC Company',
  value: 500000
});

// Track appointment confirmation
trackAppointmentConfirmation({
  id: 123,
  company_name: 'ABC Company',
  value: 500000
});
```

## 🎯 **Custom Dimensions:**

### 📊 **Available Dimensions:**
- `user_type` - Loại user (guest, user, company)
- `page_category` - Loại trang (home, company, appointment)
- `action_type` - Loại hành động (click, submit, view)

### 📝 **Custom Events với Parameters:**
```javascript
// Track custom event
trackEvent('custom_event', {
  category: 'engagement',
  label: 'button_click',
  value: 1,
  custom_parameter: 'value'
});
```

## 🔍 **Debugging & Testing:**

### 1. **Browser Console:**
```javascript
// Kiểm tra GA object
console.log(window.gtag);

// Test custom event
trackEvent('test_event', {
  category: 'test',
  label: 'debug'
});
```

### 2. **Google Analytics Debugger:**
- Cài đặt Google Analytics Debugger extension
- Mở Developer Tools > Console
- Kiểm tra các events được gửi

### 3. **Real-time Reports:**
- Google Analytics > Real-time > Events
- Google Analytics > Real-time > Conversions

## 📊 **Reports & Analytics:**

### 📈 **Key Metrics to Track:**
- **Appointment Conversion Rate:** Tỷ lệ chuyển đổi đặt lịch
- **Company Profile Views:** Lượt xem profile công ty
- **Search Usage:** Sử dụng tính năng tìm kiếm
- **User Engagement:** Thời gian trên trang, scroll depth
- **Form Completion:** Tỷ lệ hoàn thành form

### 📋 **Custom Reports:**
```javascript
// Track conversion funnel
trackEvent('funnel_step', {
  category: 'conversion',
  label: 'step_1_view_company',
  step: 1
});

trackEvent('funnel_step', {
  category: 'conversion',
  label: 'step_2_contact_company',
  step: 2
});

trackEvent('funnel_step', {
  category: 'conversion',
  label: 'step_3_book_appointment',
  step: 3
});
```

## 🚀 **Performance Optimization:**

### ⚡ **Best Practices:**
- ✅ Sử dụng async loading cho GA script
- ✅ Debounce scroll và time tracking events
- ✅ Batch events khi cần thiết
- ✅ Sử dụng data attributes thay vì inline JavaScript

### 🔧 **Configuration:**
```javascript
// GA Configuration
gtag('config', 'GA_ID', {
  'custom_map': {
    'dimension1': 'user_type',
    'dimension2': 'page_category',
    'dimension3': 'action_type'
  },
  'send_page_view': false // Disable default page view
});
```

## 📞 **Support & Troubleshooting:**

### ❓ **Common Issues:**
1. **Events không hiển thị:** Kiểm tra GA ID và network
2. **Data attributes không work:** Kiểm tra JavaScript console
3. **Duplicate events:** Kiểm tra event listeners

### 🔧 **Debug Commands:**
```javascript
// Test tracking function
trackEvent('test', {category: 'test'});

// Check GA object
console.log(window.gtag);

// Check dataLayer
console.log(window.dataLayer);
```

---

## 🎉 **Kết luận:**

Hệ thống Google Analytics tracking đã được tích hợp toàn diện cho website Doitay.vn. Tất cả các điểm chạm quan trọng đều được track để phân tích hành vi người dùng và tối ưu hóa trải nghiệm.

**Bước tiếp theo:**
1. Cập nhật GA ID thật
2. Test tracking trên website
3. Tạo custom reports trong Google Analytics
 
 