# Email Flow Integration - Notification Templates

## Tổng quan

Hệ thống email flow đã được tích hợp thành công vào interface `/admin/notification/templates` hiện có, bổ sung 2 loại flow chính:

### 1. **Auto Flow** (Luồng Tự Động)
- Tự động gửi email khi có sự kiện appointment
- Tích hợp sẵn với hệ thống appointment booking
- Priority cao, được xử lý ngay lập tức

### 2. **Marketing Flow** (Luồng Marketing)
- Gửi campaigns thủ công
- Hỗ trợ lên lịch gửi
- Lọc người nhận theo tiêu chí

### 3. **System Flow** (Luồng Hệ thống)
- Notifications hệ thống mặc định
- Tương thích với templates cũ

## 🎯 Tính năng đã triển khai

### Interface Management
- ✅ **Flow Type Tabs**: Filter templates theo loại flow
- ✅ **Enhanced Table**: Hiển thị flow type, priority, sent count
- ✅ **Flow Settings**: Modal để thay đổi flow type
- ✅ **Quick Actions**: Tạo nhanh auto/marketing templates
- ✅ **Campaign Management**: Gửi marketing campaigns từ interface

### Backend Integration
- ✅ **Database Schema**: Thêm flow fields vào `notification_templates`
- ✅ **Controller Methods**: Flow management trong `NotificationController`
- ✅ **Routes**: Tất cả routes cần thiết đã được đăng ký
- ✅ **Model Updates**: `NotificationTemplate` với scopes và relationships

### Appointment Integration  
- ✅ **Auto Triggers**: Appointment controllers gửi email qua auto flow
- ✅ **Template Creation**: 4 appointment templates đã được tạo
- ✅ **Status Updates**: Tự động cập nhật sent count và last_sent_at

## 📊 Thống kê hiện tại

```
Auto Flow templates: 4
- NEW_APPOINTMENT
- APPOINTMENT_CONFIRMED  
- APPOINTMENT_COMPLETED
- APPOINTMENT_CANCELED

Marketing Flow templates: 3
- WELCOME_CAMPAIGN
- MONTHLY_NEWSLETTER
- COMPANY_PROMOTION

System Flow templates: 12 (existing templates)
```

## 🚀 Cách sử dụng

### 1. Truy cập Email Flow Management
```
URL: /admin/notification/templates
```

### 2. Filter theo Flow Type
- Click tab **Auto Flow** để xem appointment notifications
- Click tab **Marketing Flow** để xem campaigns  
- Click tab **System Flow** để xem system notifications

### 3. Tạo Auto Flow Template
- Click "Create Auto Flow"
- Nhập template code (VD: `USER_REGISTERED`)
- Chọn priority: high/normal/low
- Template sẽ tự động trigger khi có event

### 4. Tạo Marketing Campaign
- Click "Create Marketing Campaign"
- Thiết lập recipient criteria
- Có thể lên lịch gửi
- Gửi manual từ interface

### 5. Thay đổi Flow Type
- Click dropdown "Actions" → "Change Flow Type"
- Chọn flow type mới
- Cập nhật priority và description

## 🛠️ Commands hỗ trợ

### Khởi tạo Appointment Flow
```bash
php artisan flow:init-appointments
```

### Tạo Marketing Templates mẫu
```bash
php artisan flow:create-marketing-samples
```

## 📋 File Structure

### Controllers
- `app/Http/Controllers/Admin/NotificationController.php` - Flow management methods
- `app/Http/Controllers/Admin/EmailFlowController.php` - Standalone flow controller (legacy)

### Views
- `resources/views/admin/notification/template/index.blade.php` - Main interface với flow tabs
- `resources/views/admin/notification/template/flow_edit.blade.php` - Flow settings editor
- `resources/views/admin/notification/template/flow_create.blade.php` - Create flow templates

### Models
- `app/Models/NotificationTemplate.php` - Enhanced với flow fields và scopes

### Database
- `database/migrations/2025_01_20_000000_add_notification_flow_fields.php` - Flow schema

### Commands
- `app/Console/Commands/InitializeAppointmentFlow.php` - Setup appointment templates
- `app/Console/Commands/CreateSampleMarketingCampaign.php` - Sample marketing campaigns

## 🔧 API Routes

```php
// Flow management trong notification controller
Route::get('notification/template/flow/edit/{id}', 'editFlow');
Route::post('notification/template/flow/update/{id}', 'updateFlow');
Route::post('notification/template/flow/change', 'changeFlowType');
Route::get('notification/template/flow/create', 'createFlow');
Route::post('notification/template/flow/store', 'storeFlow');
Route::get('notification/template/flow/initialize', 'initializeAppointmentFlow');
Route::post('notification/template/campaign/send', 'sendCampaign');
```

## 🎯 Integration Points

### 1. Appointment Controllers
```php
// Trong AppointmentController.php
notify($user, 'NEW_APPOINTMENT', [
    'appointment_id' => $appointment->id,
    'appointment_date' => $appointment->date,
    'appointment_time' => $appointment->time,
    'company_name' => $appointment->company->company_name
]);
```

### 2. Marketing Campaigns
```php
// Gửi campaign từ interface hoặc code
notify($user, 'WELCOME_CAMPAIGN', [
    'user_name' => $user->name,
    'site_name' => gs('site_name')
]);
```

## ✅ Test Checklist

- [x] Flow tabs filtering hoạt động
- [x] Create auto flow templates
- [x] Create marketing campaigns
- [x] Change flow types
- [x] Send marketing campaigns
- [x] Appointment integration
- [x] Statistics display
- [x] Template editing với flow settings

## 🔮 Tính năng mở rộng

### Scheduled Campaigns
- Marketing campaigns có thể được lên lịch
- Sử dụng Laravel Queue để gửi đúng thời gian

### Advanced Filtering
- Filter recipients theo nhiều tiêu chí
- A/B testing cho campaigns
- Performance analytics

### Automation Rules
- Trigger chains: sau event A gửi B
- Delay emails: gửi sau X ngày
- Smart segmentation

## 💡 Lưu ý quan trọng

1. **Backward Compatibility**: Tất cả templates cũ vẫn hoạt động bình thường
2. **Performance**: Flow type được index để query nhanh
3. **Scalability**: Hỗ trợ queue cho campaigns lớn
4. **Security**: Validation đầy đủ cho campaign recipients

## 🎨 Professional Email Templates (NEW)

### ✨ Design Features Mới:
- **Modern Responsive Design**: Tối ưu cho mobile và desktop
- **Professional Layout**: Header với logo, content area, footer chuyên nghiệp
- **Brand Colors**: Gradient backgrounds và consistent color scheme
- **Typography**: Modern fonts với proper hierarchy
- **Interactive Elements**: Styled buttons với hover effects và animations

### 📧 Email Components:
- **Header Section**: Logo tự động, company name, tagline
- **Content Boxes**: Success, info, warning, highlight boxes với màu sắc phân biệt
- **Appointment Details**: Structured detail rows dễ đọc
- **Call-to-Action Buttons**: Prominent, styled buttons với multiple styles
- **Footer**: Social links, unsubscribe, copyright

### 🖼️ Logo Integration:
- **Automatic Logo Detection**: Tìm logo từ multiple locations
- **Fallback Text Logo**: Stylized text nếu không có logo image
- **Responsive Sizing**: Logo tự động scale theo device
- **Multiple Format Support**: PNG, JPG, SVG logos

### 📱 Email Preview & Testing:
```bash
# Preview professional email templates
/email-preview                    # New Appointment template
/email-preview/WELCOME_CAMPAIGN   # Welcome campaign template
```

### 🔧 Technical Implementation:

#### EmailTemplateService.php
- Wrapper service cho professional email formatting
- Automatic logo detection và fallback
- Shortcode processing với appointment data
- Responsive template integration

#### Professional Wrapper Template
- `core/resources/views/email_templates/professional_wrapper.blade.php`
- Complete HTML email template với CSS inline
- Mobile-first responsive design
- Cross-email-client compatibility

#### Updated Templates:
```
Auto Flow Templates (Professional Format):
✅ NEW_APPOINTMENT - Appointment creation confirmation
✅ APPOINTMENT_CONFIRMED - Provider confirmation
✅ APPOINTMENT_COMPLETED - Service completion 
✅ APPOINTMENT_CANCELED - Cancellation notice

Marketing Flow Templates (Professional Format):
✅ WELCOME_CAMPAIGN - New user onboarding
✅ MONTHLY_NEWSLETTER - Monthly updates
✅ COMPANY_PROMOTION - Business upgrade campaigns
```

### 📊 Email Analytics Ready:
- Sent count tracking
- Open rate compatibility
- Click tracking ready
- Unsubscribe management

---

**Status**: ✅ HOÀN THÀNH với Professional Email Design - Ready for production use
**Version**: 2.0 (Professional Templates)
**Date**: January 2025 