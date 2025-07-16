# ✅ QUICK TEST CHECKLIST - BECOME CONTRACTOR

## 🚀 **PRE-TEST SETUP**
- [v] Database có test users (chạy setup_test_users.sql)
- [v] Laravel server running
- [v] Browser có Developer Tools mở
- [v] Categories table có data

---

## 📱 **BASIC FUNCTIONALITY**

### **Anonymous User**
- [ ] Landing page load < 3 seconds
- [v] Hero stats hiển thị đúng
- [v] Button "Đăng ký ngay" scroll smooth
- [v] Button "Tìm hiểu thêm" redirect home
- [v] Tabs switch correctly
- [v] Registration form works
- [v] Login form works

### **Authenticated User - No Company**
- [ ] No tabs shown (direct contractor form)
- [ ] Company creation form complete
- [ ] Form validation works
- [ ] Success redirect to edit page

### **Authenticated User - Has Company**
- [ ] PENDING: Yellow badge, correct message
- [ ] APPROVED: Green badge, correct message  
- [ ] REJECTED: Red badge, edit available

---

## 🔧 **TECHNICAL CHECKS**

### **JavaScript**
- [ ] Console logs present
- [ ] No JavaScript errors
- [ ] Tab switching works
- [ ] Form submissions work
- [ ] AJAX responses correct

### **CSS/UI**
- [ ] Buttons have hover effects
- [ ] Loading states visible
- [ ] Notifications appear
- [ ] Responsive on mobile
- [ ] Cross-browser compatible

### **Backend**
- [ ] User registration creates record
- [ ] Company creation works
- [ ] Status checks accurate
- [ ] Redirects correct
- [ ] Validation server-side

---

## 🐛 **ERROR SCENARIOS**

### **Validation**
- [ ] Empty forms rejected
- [ ] Invalid email rejected
- [ ] Short password rejected
- [ ] Short description rejected
- [ ] Duplicate email rejected

### **Edge Cases**
- [ ] Network error handling
- [ ] Missing CSRF token
- [ ] Invalid category selection
- [ ] Already has company check

---

## 📊 **PERFORMANCE & UX**

### **Performance**
- [ ] Page load < 3 seconds
- [ ] Smooth animations
- [ ] Fast form submissions
- [ ] No memory leaks

### **User Experience**
- [ ] Clear call-to-actions
- [ ] Intuitive navigation
- [ ] Helpful error messages
- [ ] Consistent design
- [ ] Mobile-friendly

---

## 🎯 **CRITICAL PATH TEST**

**End-to-End Happy Path:**
1. [ ] Anonymous user visits page
2. [ ] Clicks "Đăng ký ngay" 
3. [ ] Registers new account
4. [ ] Creates company profile
5. [ ] Redirects to edit page
6. [ ] Company status = PENDING

**Time to complete:** _____ minutes

---

## 📝 **QUICK NOTES**

### **Issues Found:**
```
1. ________________________________
2. ________________________________
3. ________________________________
```

### **Browser Tested:**
- [ ] Chrome
- [ ] Firefox  
- [ ] Edge
- [ ] Mobile Chrome
- [ ] Mobile Safari

### **Test Data Used:**
```
New User Email: ________________________
Test Company Name: _____________________
```

---

## 🚨 **STOP CRITERIA**

**Stop testing if:**
- [ ] Critical JavaScript errors
- [ ] Forms completely broken
- [ ] Database connection issues
- [ ] Server errors (500)

**Continue with caution if:**
- [ ] Minor UI issues
- [ ] Non-critical validation problems
- [ ] Performance slightly slow

---

## ✅ **FINAL SIGN-OFF**

**Tester:** _________________ **Date:** _________

**Overall Status:** 
- [ ] ✅ PASS - Ready for production
- [ ] ⚠️ PASS WITH ISSUES - Minor fixes needed
- [ ] ❌ FAIL - Major issues, needs rework

**Confidence Level:** ___/10

**Recommendation:** 
_________________________________________________
_________________________________________________ 