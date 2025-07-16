# Test Execution Checklist
## Hành trình Leads Journey - Manual Test

### 📋 Pre-Test Setup
- [ ] Database backup completed
- [ ] Test data imported successfully
- [ ] Test accounts verified
- [ ] Email service configured
- [ ] Browser cleared (cache/cookies)

---

### 🎯 Main Test Scenarios

#### ✅ SCENARIO 1: Customer Creates Lead
- [ ] Homepage loads correctly
- [ ] Service dropdown works
- [ ] Form validation functions
- [ ] Location dropdowns populate
- [ ] File upload works (optional)
- [ ] Lead submission successful
- [ ] Confirmation email sent
- [ ] Redirect to lead detail page

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

#### ✅ SCENARIO 2: System Matching
- [ ] Lead appears in admin panel
- [ ] Contractor receives notification
- [ ] Matching algorithm correct
- [ ] Email notification sent

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

#### ✅ SCENARIO 3: Contractor Purchase
- [ ] Contractor login successful
- [ ] Lead visible in leads list
- [ ] Lead detail page complete
- [ ] Purchase button works
- [ ] Wallet balance deducted
- [ ] Customer contact revealed
- [ ] Lead added to "My Purchases"

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

#### ✅ SCENARIO 4: Contact & Appointment
- [ ] Customer contact accessible
- [ ] Appointment form works
- [ ] Appointment created successfully
- [ ] Customer notification sent
- [ ] Appointment appears in calendar

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

#### ✅ SCENARIO 5: Customer Confirmation
- [ ] Customer receives notification
- [ ] Appointment confirmation works
- [ ] Status updates correctly
- [ ] Both parties notified

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

#### ✅ SCENARIO 6: Completion & Review
- [ ] Contractor can mark completed
- [ ] Customer receives completion notice
- [ ] Review form works
- [ ] Rating submitted successfully
- [ ] Contractor rating updated

**Result**: ⭐ Pass / ❌ Fail  
**Notes**: ________________________

---

### 🧪 Edge Cases Testing

#### A. Error Handling
- [ ] Insufficient wallet balance handled
- [ ] Duplicate purchase blocked
- [ ] Invalid data validation
- [ ] Network error handling

#### B. Notification System
- [ ] Email templates correct
- [ ] In-app notifications work
- [ ] Read/unread status accurate
- [ ] Notification bell updates

#### C. Mobile Testing
- [ ] Lead creation on mobile
- [ ] Contractor dashboard mobile
- [ ] Responsive design working
- [ ] Touch interactions smooth

---

### 🔍 Technical Verification

#### Database Integrity
- [ ] Lead record complete
- [ ] User notifications created
- [ ] Wallet transactions logged
- [ ] Appointment data accurate
- [ ] Review data stored

#### Performance
- [ ] Page load times acceptable
- [ ] Form submission responsive
- [ ] Database queries efficient
- [ ] No JavaScript errors

#### Security
- [ ] Authentication working
- [ ] Authorization proper
- [ ] Data validation secure
- [ ] CSRF protection active

---

### 📊 Final Results

**Overall Test Status**: ⭐ Pass / ❌ Fail / ⚠️ Partial

**Summary**:
- Total Scenarios: 6
- Passed: ___/6
- Failed: ___/6
- Critical Issues: ___
- Minor Issues: ___

**Critical Issues Found**:
1. ________________________________
2. ________________________________
3. ________________________________

**Recommendations**:
1. ________________________________
2. ________________________________
3. ________________________________

---

### 📝 Test Execution Info

**Tester**: ________________________  
**Date**: ________________________  
**Environment**: Local / Staging / Production  
**Browser**: Chrome / Firefox / Safari  
**Device**: Desktop / Mobile / Tablet  
**Duration**: ______ minutes  

**Sign-off**: ________________________  
**Date**: ________________________ 