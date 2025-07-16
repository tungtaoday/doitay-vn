# 🧪 MANUAL TEST PLAN - BECOME CONTRACTOR

## 🚀 SETUP TRƯỚC KHI TEST

### 1. **Chuẩn bị Database**
```sql
-- Chạy file setup_test_users.sql để tạo test users
mysql -u root -p your_database < setup_test_users.sql
```

### 2. **Kiểm tra Environment**
- ✅ Laravel server đang chạy: `http://localhost`
- ✅ Database connection hoạt động
- ✅ Categories table có data
- ✅ Browser có Developer Tools

### 3. **Test Users & Passwords**
| Email | Password | Trạng thái |
|-------|----------|------------|
| `tho_moi@test.com` | `password` | Chưa có hồ sơ thợ |
| `tho_pending@test.com` | `password` | Hồ sơ PENDING |
| `tho_approved@test.com` | `password` | Hồ sơ APPROVED |
| `tho_rejected@test.com` | `password` | Hồ sơ REJECTED |

---

## 📋 TEST EXECUTION

## **TEST SUITE 1: ANONYMOUS USER (Chưa đăng nhập)**

### **TC1.1: Landing Page Load**
**⏱️ Thời gian:** 5 phút

**Bước thực hiện:**
1. Mở browser mới (Incognito mode)
2. Truy cập: `http://localhost/become-contractor`
3. Đợi trang load hoàn toàn
4. Mở F12 > Console tab
5. Kiểm tra console logs

**✅ Pass Criteria:**
- Trang load < 3 giây
- Console log: "Become contractor page loaded"
- Không có JavaScript errors (màu đỏ)
- Hero section hiển thị số liệu thống kê
- Tất cả images load

**📝 Ghi chú:**
```
[ ] Page load time: _____ seconds
[ ] Console errors: Yes/No
[ ] Stats displayed: totalJobs=___, activeContractors=___, averageEarning=___
```

### **TC1.2: Hero Buttons Functionality**
**⏱️ Thời gian:** 3 phút

**Bước thực hiện:**
1. Scroll lên đầu trang
2. Click button "Đăng ký ngay"
3. Quan sát scroll behavior
4. Scroll lên lại
5. Click button "Tìm hiểu thêm"

**✅ Pass Criteria:**
- Console log: "Scroll to form clicked"
- Smooth scroll xuống registration form
- "Tìm hiểu thêm" redirect về homepage
- Buttons có hover effects

**📝 Ghi chú:**
```
[ ] Scroll to form: Working/Not working
[ ] Redirect to home: Working/Not working
[ ] Console logs: Present/Missing
```

### **TC1.3: Registration Tabs**
**⏱️ Thời gian:** 5 phút

**Bước thực hiện:**
1. Truy cập lại: `http://localhost/become-contractor`
2. Scroll xuống registration form
3. Kiểm tra tab active mặc định
4. Click tab "Đã có tài khoản"
5. Quan sát form content thay đổi
6. Click lại tab "Đăng ký tài khoản"

**✅ Pass Criteria:**
- Tab "Đăng ký tài khoản" active mặc định
- Console logs khi click tabs
- Form content thay đổi đúng
- Visual feedback rõ ràng

**📝 Ghi chú:**
```
[ ] Default tab: Correct/Incorrect
[ ] Tab switching: Smooth/Jerky
[ ] Console logs: Present/Missing
```

### **TC1.4: User Registration**
**⏱️ Thời gian:** 10 phút

**Test Data:**
```
Họ tên: Test User Manual
Email: manual_test_user@gmail.com
Mobile: 0999888777
Password: 123456
```

**Bước thực hiện:**
1. Ở tab "Đăng ký tài khoản"
2. Điền form với test data
3. Click "Đăng ký & Tạo hồ sơ thợ"
4. Quan sát loading state
5. Kiểm tra notification
6. Kiểm tra có chuyển form không

**✅ Pass Criteria:**
- Loading state hiển thị
- Success notification xuất hiện
- Form chuyển sang tạo hồ sơ thợ
- User được tạo trong database

**📝 Ghi chú:**
```
[ ] Loading state: Present/Missing
[ ] Success notification: Present/Missing
[ ] Form transition: Working/Not working
[ ] Database record: Created/Not created
```

### **TC1.5: User Login**
**⏱️ Thời gian:** 5 phút

**Bước thực hiện:**
1. Logout (nếu đã login từ TC1.4)
2. Truy cập: `http://localhost/become-contractor`
3. Click tab "Đã có tài khoản"
4. Login với: `tho_moi@test.com` / `password`
5. Click "Đăng nhập & Tạo hồ sơ thợ"

**✅ Pass Criteria:**
- Đăng nhập thành công
- Chuyển sang form tạo hồ sơ thợ
- Success notification

**📝 Ghi chú:**
```
[ ] Login success: Yes/No
[ ] Form transition: Working/Not working
[ ] Notification: Present/Missing
```

---

## **TEST SUITE 2: AUTHENTICATED USER - NO COMPANY**

### **TC2.1: Authenticated Landing Page**
**⏱️ Thời gian:** 3 phút

**Bước thực hiện:**
1. Đảm bảo đã login với `tho_moi@test.com`
2. Truy cập: `http://localhost/become-contractor`
3. Kiểm tra registration section

**✅ Pass Criteria:**
- Không hiển thị tabs đăng ký/đăng nhập
- Hiển thị form "Tạo hồ sơ thợ chuyên nghiệp"
- Form có đầy đủ fields

**📝 Ghi chú:**
```
[ ] Tabs hidden: Yes/No
[ ] Contractor form visible: Yes/No
[ ] All fields present: Yes/No
```

### **TC2.2: Create Company Profile**
**⏱️ Thời gian:** 8 phút

**Test Data:**
```
Tên công ty: Thợ Manual Test
Chuyên môn: [Chọn từ dropdown]
Mô tả: Đây là test manual cho hệ thống tạo hồ sơ thợ. Có kinh nghiệm 3 năm trong lĩnh vực sửa chữa và bảo trì.
```

**Bước thực hiện:**
1. Điền form với test data
2. Click "Tạo hồ sơ thợ chuyên nghiệp"
3. Quan sát loading state
4. Kiểm tra notification
5. Kiểm tra redirect

**✅ Pass Criteria:**
- Form validation hoạt động
- Success notification
- Redirect đến edit page
- Company tạo với status PENDING

**📝 Ghi chú:**
```
[ ] Validation: Working/Not working
[ ] Success notification: Present/Missing
[ ] Redirect: Correct/Incorrect
[ ] Database status: PENDING/Other
```

---

## **TEST SUITE 3: USERS WITH EXISTING COMPANIES**

### **TC3.1: User with PENDING Company**
**⏱️ Thời gian:** 3 phút

**Bước thực hiện:**
1. Logout current user
2. Login với: `tho_pending@test.com` / `password`
3. Truy cập: `http://localhost/become-contractor`

**✅ Pass Criteria:**
- Thông báo "Bạn đã có hồ sơ thợ!"
- Badge "Đang chờ duyệt" màu vàng
- Buttons "Xem hồ sơ" và "Chỉnh sửa"

**📝 Ghi chú:**
```
[ ] Correct message: Yes/No
[ ] Badge color: Yellow/Other
[ ] Buttons present: Yes/No
```

### **TC3.2: User with APPROVED Company**
**⏱️ Thời gian:** 3 phút

**Bước thực hiện:**
1. Logout current user
2. Login với: `tho_approved@test.com` / `password`
3. Truy cập: `http://localhost/become-contractor`

**✅ Pass Criteria:**
- Badge "Đã phê duyệt" màu xanh
- Buttons hoạt động đúng

**📝 Ghi chú:**
```
[ ] Badge color: Green/Other
[ ] Buttons functional: Yes/No
```

### **TC3.3: User with REJECTED Company**
**⏱️ Thời gian:** 3 phút

**Bước thực hiện:**
1. Logout current user
2. Login với: `tho_rejected@test.com` / `password`
3. Truy cập: `http://localhost/become-contractor`

**✅ Pass Criteria:**
- Badge "Bị từ chối" màu đỏ
- Có thể chỉnh sửa

**📝 Ghi chú:**
```
[ ] Badge color: Red/Other
[ ] Edit available: Yes/No
```

---

## **TEST SUITE 4: VALIDATION & ERROR HANDLING**

### **TC4.1: Form Validation**
**⏱️ Thời gian:** 10 phút

**Bước thực hiện:**
1. Logout và truy cập trang
2. Test các trường hợp:
   - Submit form trống
   - Email không hợp lệ: `invalid-email`
   - Password quá ngắn: `123`
   - Description quá ngắn: `abc`

**✅ Pass Criteria:**
- Error messages rõ ràng
- Form không submit khi có lỗi
- Focus vào field lỗi đầu tiên

**📝 Ghi chú:**
```
[ ] Empty form validation: Working/Not working
[ ] Email validation: Working/Not working
[ ] Password validation: Working/Not working
[ ] Description validation: Working/Not working
```

### **TC4.2: Duplicate Registration**
**⏱️ Thời gian:** 5 phút

**Bước thực hiện:**
1. Đăng ký với email đã tồn tại: `tho_moi@test.com`
2. Kiểm tra error message

**✅ Pass Criteria:**
- Error message: "Email đã được sử dụng"
- Không tạo duplicate records

**📝 Ghi chú:**
```
[ ] Error message: Correct/Incorrect
[ ] No duplicates: Confirmed/Not confirmed
```

---

## **TEST SUITE 5: RESPONSIVE & CROSS-BROWSER**

### **TC5.1: Mobile Responsive**
**⏱️ Thời gian:** 10 phút

**Bước thực hiện:**
1. F12 > Device Mode
2. Test trên iPhone, iPad, Android
3. Kiểm tra tất cả functions

**✅ Pass Criteria:**
- Layout responsive
- Buttons clickable
- Forms usable

**📝 Ghi chú:**
```
[ ] iPhone: Good/Issues
[ ] iPad: Good/Issues
[ ] Android: Good/Issues
```

### **TC5.2: Cross-Browser**
**⏱️ Thời gian:** 15 phút

**Browsers:** Chrome, Firefox, Edge

**Bước thực hiện:**
1. Test core functionality trên mỗi browser
2. Kiểm tra JavaScript compatibility

**✅ Pass Criteria:**
- Consistent behavior
- No browser-specific issues

**📝 Ghi chú:**
```
[ ] Chrome: Working/Issues
[ ] Firefox: Working/Issues
[ ] Edge: Working/Issues
```

---

## 📊 **TEST SUMMARY REPORT**

### **Overall Results**
```
Total Test Cases: ___
Passed: ___
Failed: ___
Blocked: ___
Pass Rate: ___%
```

### **Critical Issues Found**
```
1. [Issue description]
   Severity: Critical/High/Medium/Low
   Steps to reproduce: ...
   
2. [Issue description]
   Severity: Critical/High/Medium/Low
   Steps to reproduce: ...
```

### **Recommendations**
```
1. [Recommendation 1]
2. [Recommendation 2]
3. [Recommendation 3]
```

---

## 🔧 **DEBUGGING TIPS**

### **Common Issues & Solutions**
1. **Buttons không click được**
   - Check console errors
   - Verify CSS z-index
   - Test với inline onclick

2. **Forms không submit**
   - Check CSRF token
   - Verify network requests
   - Check validation errors

3. **Tabs không switch**
   - Check JavaScript loading
   - Verify element IDs
   - Check console logs

### **Console Commands for Debugging**
```javascript
// Check if elements exist
document.querySelector('.hero-actions')
document.querySelector('#registration-form')

// Test scroll function
scrollToForm({preventDefault: () => {}})

// Check tab mapping
console.log(document.querySelectorAll('.tab-content'))
```

---

**📝 Lưu ý:** Thực hiện test theo thứ tự, ghi chú kết quả chi tiết, và báo cáo ngay khi phát hiện bugs nghiêm trọng. 