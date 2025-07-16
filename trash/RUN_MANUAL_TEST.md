# Quick Guide: Running Manual Test for Leads Journey

## 🚀 Chuẩn bị môi trường test

### 1. Setup Database
```bash
# Import test data
mysql -u root -p your_database < TEST_DATA_SAMPLE.sql

# Hoặc sử dụng phpMyAdmin để import file SQL
```

### 2. Verify Test Accounts
Sau khi import data, bạn sẽ có:

**Customer Account:**
- Email: `customer.test@gmail.com`
- Password: `123456789`

**Contractor Account:**
- Email: `contractor.test@gmail.com`  
- Password: `123456789`

**Admin Account:**
- Email: `admin@example.com`
- Password: `admin123`

---

## 📝 Quick Test Execution

### Step 1: Test Lead Creation (5 phút)
1. Mở `http://localhost`
2. Fill form với data:
   - Service: "Sửa chữa điện nước"
   - Title: "Sửa chữa điện nước tại nhà"
   - Budget: 500k - 1M VNĐ
   - Location: TP.HCM > Quận 1 > Phường Bến Nghé
   - Contact: Như trong test data
3. ✅ **Check**: Lead được tạo thành công

### Step 2: Test Contractor Login (2 phút)
1. Login với contractor account
2. Navigate đến "Leads" section
3. ✅ **Check**: Lead hiển thị trong danh sách

### Step 3: Test Lead Purchase (3 phút)
1. Click vào lead để xem detail
2. Click "Mua Lead"
3. Confirm purchase
4. ✅ **Check**: 
   - Wallet balance giảm 50k
   - Customer contact được hiển thị
   - Lead chuyển sang "My Purchases"

### Step 4: Test Appointment Creation (3 phút)
1. Từ contractor account, tạo appointment mới
2. Fill thông tin customer từ lead
3. Set appointment cho ngày mai 9AM
4. ✅ **Check**: Appointment được tạo thành công

### Step 5: Test Customer Confirmation (2 phút)
1. Login customer account
2. Navigate đến "Appointments"
3. Confirm appointment
4. ✅ **Check**: Status chuyển thành "confirmed"

### Step 6: Test Completion & Review (3 phút)
1. Contractor marks appointment as completed
2. Customer submits 5-star review
3. ✅ **Check**: Review được recorded

---

## 🔍 Verification Points

### Database Checks:
```sql
-- Check lead created
SELECT * FROM leads WHERE email = 'customer.test@gmail.com';

-- Check wallet transaction
SELECT * FROM company_wallet_transactions WHERE company_id = (SELECT id FROM companies WHERE email = 'contractor.test@gmail.com');

-- Check notifications
SELECT * FROM user_notifications WHERE user_id IN (SELECT id FROM users WHERE email IN ('customer.test@gmail.com', 'contractor.test@gmail.com'));

-- Check appointment
SELECT * FROM appointments WHERE recipient_email = 'customer.test@gmail.com';
```

### UI Checks:
- [ ] Lead creation form validation works
- [ ] Lead detail page displays correctly
- [ ] Purchase confirmation appears
- [ ] Notification bell shows updates
- [ ] Mobile responsive design works

---

## 🐛 Common Issues & Quick Fixes

### Issue: Lead không hiển thị cho contractor
**Fix**: Check category matching và location data

### Issue: Email không được gửi
**Fix**: Check SMTP settings trong `.env`

### Issue: Wallet balance không update
**Fix**: Check company_wallets table và transaction logs

### Issue: Notification không hoạt động
**Fix**: Check JavaScript console và notification routes

---

## 📊 Expected Results Summary

Sau khi hoàn thành test, bạn sẽ có:

1. ✅ **Lead lifecycle hoàn chỉnh**: Create → Match → Purchase → Contact → Appointment → Complete → Review
2. ✅ **Wallet system hoạt động**: Balance giảm khi mua lead
3. ✅ **Notification system**: Thông báo real-time cho cả 2 bên
4. ✅ **Email system**: Confirmation emails được gửi
5. ✅ **Review system**: Customer có thể đánh giá contractor
6. ✅ **Mobile compatibility**: Tất cả features hoạt động trên mobile

---

## 🧹 Cleanup After Test

```sql
-- Remove test data
DELETE FROM leads WHERE email = 'customer.test@gmail.com';
DELETE FROM appointments WHERE recipient_email = 'customer.test@gmail.com';
DELETE FROM reviews WHERE user_id = (SELECT id FROM users WHERE email = 'customer.test@gmail.com');
DELETE FROM user_notifications WHERE user_id IN (SELECT id FROM users WHERE email IN ('customer.test@gmail.com', 'contractor.test@gmail.com'));
DELETE FROM companies WHERE email = 'contractor.test@gmail.com';
DELETE FROM users WHERE email IN ('customer.test@gmail.com', 'contractor.test@gmail.com');

-- Reset wallet balance
UPDATE company_wallets SET balance = 500000, total_spent = 0 WHERE company_id = (SELECT id FROM companies WHERE email = 'contractor.test@gmail.com');
```

---

**Total Test Time: ~20 phút**
**Complexity: Medium**
**Prerequisites: Basic understanding of the system** 