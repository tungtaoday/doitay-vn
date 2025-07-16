# 🧪 KỊCH BẢN TEST MANUAL - BECOME CONTRACTOR SYSTEM

## 📋 TỔNG QUAN
Tài liệu này mô tả chi tiết các kịch bản test manual cho hệ thống become-contractor, bao gồm tất cả các luồng từ landing page đến tạo hồ sơ thợ.

---

## 👥 DANH SÁCH USER TEST

### 🔐 **User đã có tài khoản - CHƯA có hồ sơ thợ**
- **Email:** `tho_moi@test.com`
- **Password:** `123456`
- **Mobile:** `0901234567`
- **Trạng thái:** Đã đăng ký, chưa tạo company

### 🏢 **User đã có tài khoản - ĐÃ có hồ sơ thợ (Pending)**
- **Email:** `tho_pending@test.com`
- **Password:** `123456`
- **Mobile:** `0901234568`
- **Company:** "Thợ Điện Minh An" (Status: PENDING)

### ✅ **User đã có tài khoản - ĐÃ có hồ sơ thợ (Approved)**
- **Email:** `tho_approved@test.com`
- **Password:** `123456`
- **Mobile:** `0901234569`
- **Company:** "Thợ Nước Chuyên Nghiệp" (Status: APPROVED)

### 🚫 **User đã có tài khoản - Hồ sơ thợ bị từ chối**
- **Email:** `tho_rejected@test.com`
- **Password:** `123456`
- **Mobile:** `0901234570`
- **Company:** "Thợ Test" (Status: REJECTED)

---

## 🎯 KỊCH BẢN TEST CHI TIẾT

## **SCENARIO 1: USER CHƯA ĐĂNG NHẬP**

### **Test Case 1.1: Truy cập Landing Page**
**Mục tiêu:** Kiểm tra giao diện và tính năng cơ bản

**Bước thực hiện:**
1. Mở browser, truy cập `http://localhost/become-contractor`
2. Kiểm tra trang load hoàn chỉnh
3. Scroll qua các sections: Hero, Why Choose Us, How It Works
4. Kiểm tra responsive trên mobile (F12 > Device Mode)

**Kết quả mong đợi:**
- ✅ Trang load nhanh, không lỗi
- ✅ Hero section hiển thị stats: totalJobs, activeContractors, averageEarning
- ✅ Tất cả sections hiển thị đúng
- ✅ Responsive tốt trên mobile

### **Test Case 1.2: Test Hero Buttons**
**Mục tiêu:** Kiểm tra chức năng buttons trong hero section

**Bước thực hiện:**
1. Mở Developer Tools (F12) > Console tab
2. Click button "Đăng ký ngay"
3. Quan sát console logs và scroll behavior
4. Click button "Tìm hiểu thêm"
5. Kiểm tra redirect

**Kết quả mong đợi:**
- ✅ Console log: "Scroll to form clicked"
- ✅ Smooth scroll xuống registration form
- ✅ "Tìm hiểu thêm" redirect về homepage
- ✅ Không có JavaScript errors

### **Test Case 1.3: Registration Form - Tab Switching**
**Mục tiêu:** Kiểm tra chức năng chuyển đổi tabs

**Bước thực hiện:**
1. Scroll xuống registration form
2. Kiểm tra tab mặc định: "Đăng ký tài khoản" (active)
3. Click tab "Đã có tài khoản"
4. Quan sát console logs
5. Click lại tab "Đăng ký tài khoản"

**Kết quả mong đợi:**
- ✅ Tab "Đăng ký tài khoản" active mặc định
- ✅ Console logs khi click tabs
- ✅ Form content thay đổi đúng
- ✅ Visual feedback (active state) rõ ràng

### **Test Case 1.4: Đăng ký tài khoản mới**
**Mục tiêu:** Test luồng đăng ký user mới

**Test Data:**
```
Họ tên: Nguyễn Văn Test
Email: test_new_user@gmail.com
Mobile: 0987654321
Password: 123456
```

**Bước thực hiện:**
1. Ở tab "Đăng ký tài khoản", điền form
2. Click "Đăng ký & Tạo hồ sơ thợ"
3. Quan sát loading state và response
4. Kiểm tra notification
5. Kiểm tra có auto-switch sang form tạo hồ sơ không

**Kết quả mong đợi:**
- ✅ Loading state hiển thị
- ✅ Success notification
- ✅ Auto login và chuyển sang form tạo hồ sơ thợ
- ✅ User được tạo trong database

### **Test Case 1.5: Đăng nhập với tài khoản có sẵn**
**Mục tiêu:** Test luồng đăng nhập

**Test Data:** Sử dụng `tho_moi@test.com`

**Bước thực hiện:**
1. Click tab "Đã có tài khoản"
2. Nhập email/mobile và password
3. Click "Đăng nhập & Tạo hồ sơ thợ"
4. Kiểm tra response và redirect

**Kết quả mong đợi:**
- ✅ Đăng nhập thành công
- ✅ Chuyển sang form tạo hồ sơ thợ (vì chưa có company)
- ✅ Success notification

---

## **SCENARIO 2: USER ĐÃ ĐĂNG NHẬP - CHƯA CÓ HỒ SƠ THỢ**

### **Test Case 2.1: Truy cập trang khi đã login**
**Mục tiêu:** Kiểm tra giao diện cho user đã login nhưng chưa có company

**Bước thực hiện:**
1. Login với `tho_moi@test.com`
2. Truy cập `http://localhost/become-contractor`
3. Kiểm tra registration section

**Kết quả mong đợi:**
- ✅ Không hiển thị tabs đăng ký/đăng nhập
- ✅ Hiển thị trực tiếp form "Tạo hồ sơ thợ chuyên nghiệp"
- ✅ Form có đầy đủ fields: company_name, category_id, description

### **Test Case 2.2: Tạo hồ sơ thợ**
**Mục tiêu:** Test luồng tạo company profile

**Test Data:**
```
Tên công ty: Thợ Điện Chuyên Nghiệp
Chuyên môn: Điện (chọn từ dropdown)
Mô tả: Có 5 năm kinh nghiệm trong lĩnh vực điện dân dụng và công nghiệp. Chuyên sửa chữa, lắp đặt hệ thống điện an toàn, chất lượng cao.
```

**Bước thực hiện:**
1. Điền đầy đủ form
2. Click "Tạo hồ sơ thợ chuyên nghiệp"
3. Kiểm tra loading state
4. Kiểm tra response và redirect

**Kết quả mong đợi:**
- ✅ Form validation hoạt động
- ✅ Success notification
- ✅ Redirect đến `user.company.edit` để hoàn thiện
- ✅ Company được tạo với status PENDING

---

## **SCENARIO 3: USER ĐÃ CÓ HỒ SƠ THỢ**

### **Test Case 3.1: User có hồ sơ PENDING**
**Bước thực hiện:**
1. Login với `tho_pending@test.com`
2. Truy cập `http://localhost/become-contractor`

**Kết quả mong đợi:**
- ✅ Hiển thị thông báo "Bạn đã có hồ sơ thợ!"
- ✅ Hiển thị tên company và badge "Đang chờ duyệt"
- ✅ Có buttons "Xem hồ sơ" và "Chỉnh sửa"

### **Test Case 3.2: User có hồ sơ APPROVED**
**Bước thực hiện:**
1. Login với `tho_approved@test.com`
2. Truy cập `http://localhost/become-contractor`

**Kết quả mong đợi:**
- ✅ Badge "Đã phê duyệt" màu xanh
- ✅ Buttons hoạt động đúng

### **Test Case 3.3: User có hồ sơ REJECTED**
**Bước thực hiện:**
1. Login với `tho_rejected@test.com`
2. Truy cập `http://localhost/become-contractor`

**Kết quả mong đợi:**
- ✅ Badge "Bị từ chối" màu đỏ
- ✅ Có thể chỉnh sửa để submit lại

---

## **SCENARIO 4: VALIDATION & ERROR HANDLING**

### **Test Case 4.1: Form Validation**
**Mục tiêu:** Test validation cho tất cả forms

**Bước thực hiện:**
1. Submit form trống
2. Submit với email không hợp lệ
3. Submit với password quá ngắn
4. Submit với description quá ngắn

**Kết quả mong đợi:**
- ✅ Hiển thị error messages rõ ràng
- ✅ Form không submit khi có lỗi
- ✅ Focus vào field có lỗi đầu tiên

### **Test Case 4.2: Duplicate Registration**
**Bước thực hiện:**
1. Đăng ký với email đã tồn tại
2. Tạo company khi đã có company

**Kết quả mong đợi:**
- ✅ Error message phù hợp
- ✅ Không tạo duplicate records

### **Test Case 4.3: Network Error Handling**
**Bước thực hiện:**
1. Tắt internet/server
2. Submit form
3. Kiểm tra error handling

**Kết quả mong đợi:**
- ✅ Error notification: "Có lỗi kết nối, vui lòng thử lại"
- ✅ Button trở về trạng thái ban đầu

---

## **SCENARIO 5: INTEGRATION TESTING**

### **Test Case 5.1: End-to-End Flow**
**Mục tiêu:** Test toàn bộ luồng từ đầu đến cuối

**Bước thực hiện:**
1. User mới truy cập landing page
2. Đăng ký tài khoản
3. Tạo hồ sơ thợ
4. Redirect đến edit page
5. Hoàn thiện thông tin
6. Kiểm tra trong admin panel

**Kết quả mong đợi:**
- ✅ Toàn bộ luồng hoạt động mượt mà
- ✅ Data consistency across tables
- ✅ Proper status transitions

### **Test Case 5.2: Cross-browser Testing**
**Browsers:** Chrome, Firefox, Safari, Edge

**Bước thực hiện:**
1. Test trên từng browser
2. Kiểm tra responsive
3. Test JavaScript functionality

**Kết quả mong đợi:**
- ✅ Consistent behavior across browsers
- ✅ No browser-specific issues

---

## 📊 **CHECKLIST TỔNG HỢP**

### **🎨 UI/UX Testing**
- [ ] Landing page load < 3 seconds
- [ ] All images load properly
- [ ] Responsive design works on mobile/tablet
- [ ] Buttons have hover effects
- [ ] Forms have proper styling
- [ ] Loading states are clear
- [ ] Success/error notifications are visible

### **⚡ Functionality Testing**
- [ ] Hero buttons work (scroll & redirect)
- [ ] Tab switching works smoothly
- [ ] Form submissions work
- [ ] Validation works properly
- [ ] Authentication flow works
- [ ] Company creation works
- [ ] Status display is accurate
- [ ] Redirects work correctly

### **🔒 Security Testing**
- [ ] CSRF protection works
- [ ] Input sanitization works
- [ ] Authentication required for protected actions
- [ ] No sensitive data in client-side
- [ ] Proper error messages (no system info leak)

### **📱 Performance Testing**
- [ ] Page load time acceptable
- [ ] JavaScript execution smooth
- [ ] No memory leaks
- [ ] Smooth animations
- [ ] Fast form submissions

---

## 🐛 **BUG REPORT TEMPLATE**

```markdown
**Bug Title:** [Mô tả ngắn gọn]

**Severity:** Critical/High/Medium/Low

**Steps to Reproduce:**
1. 
2. 
3. 

**Expected Result:**
[Kết quả mong đợi]

**Actual Result:**
[Kết quả thực tế]

**Environment:**
- Browser: 
- OS: 
- Screen Resolution: 
- User Account: 

**Screenshots/Console Logs:**
[Attach if applicable]
```

---

## 📈 **METRICS TO TRACK**

### **Success Metrics**
- [ ] Registration completion rate > 90%
- [ ] Form submission success rate > 95%
- [ ] Page load time < 3 seconds
- [ ] Zero critical bugs
- [ ] Cross-browser compatibility 100%

### **User Experience Metrics**
- [ ] Intuitive navigation (no confusion)
- [ ] Clear call-to-actions
- [ ] Helpful error messages
- [ ] Smooth transitions
- [ ] Mobile-friendly experience

---

## 🚀 **EXECUTION PLAN**

### **Phase 1: Basic Functionality (Day 1)**
- Test Cases 1.1 - 1.5 (User chưa đăng nhập)
- Test Cases 2.1 - 2.2 (User đã login, chưa có hồ sơ)

### **Phase 2: Advanced Scenarios (Day 2)**
- Test Cases 3.1 - 3.3 (User đã có hồ sơ)
- Test Cases 4.1 - 4.3 (Validation & Error handling)

### **Phase 3: Integration & Performance (Day 3)**
- Test Cases 5.1 - 5.2 (End-to-end & Cross-browser)
- Performance testing
- Security testing

### **Phase 4: Bug Fixes & Retesting (Day 4)**
- Fix identified bugs
- Regression testing
- Final acceptance testing

---

**📝 Note:** Thực hiện test theo thứ tự ưu tiên, document tất cả bugs và track progress bằng checklist trên. 