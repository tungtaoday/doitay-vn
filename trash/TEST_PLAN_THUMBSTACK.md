# 🧪 TEST PLAN TOÀN DIỆN - THUMBSTACK PLATFORM

## 📋 **Tổng quan Test Plan**

### Mục tiêu Testing:
- ✅ Đảm bảo 100% flowchart UX hoạt động chính xác
- ✅ Kiểm tra tất cả tương tác user-system
- ✅ Verify performance và UX requirements
- ✅ Test end-to-end user journeys

### Phạm vi Testing:
- **Frontend**: All UI components, responsive design
- **Backend**: API endpoints, database operations
- **Integration**: Third-party services, notifications
- **Performance**: Load times, concurrent users
- **Security**: Authentication, authorization, data protection

---

## 🎯 **TEST SCENARIOS BY USER JOURNEY**

### **JOURNEY 1: CUSTOMER TẠO LEAD** 

#### **TC-001: Lead Creation Flow - Happy Path**
```
Preconditions:
- User đã đăng nhập như Customer
- Categories có data
- Location services hoạt động

Test Steps:
1. Navigate to Dashboard
2. Click "Tạo yêu cầu dịch vụ" button
3. Fill form với valid data:
   - Category: "Sửa chữa điện"
   - Title: "Sửa chữa điện nước tại nhà"
   - Description: "Cần sửa ổ cắm và thay bóng đèn"
   - District: "Quận 1"
   - Ward: "Phường Bến Nghé"
   - Address: "123 Nguyễn Huệ"
   - Budget: 200,000 - 500,000 VNĐ
   - Urgency: "Medium"
   - Needed by: Tomorrow
4. Upload attachment (optional)
5. Submit form

Expected Results:
✅ Form validation passes
✅ Lead created successfully
✅ Redirect to lead detail page
✅ Success notification displayed
✅ Relevant contractors receive notifications
✅ Lead appears in customer's lead list
✅ Lead status = "active"
```

#### **TC-002: Lead Creation - Validation Errors**
```
Test Data Matrix:
- Empty required fields
- Invalid budget range (max < min)
- Past date for "needed_by"
- File size > 5MB
- Invalid file types

Expected Results:
❌ Validation errors displayed
❌ Form submission blocked
✅ User-friendly error messages
✅ No partial data saved
```

#### **TC-003: Lead Display & Notification System**
```
Test Steps:
1. Create lead (from TC-001)
2. Check contractor notifications
3. Verify countdown timer (24h)
4. Check lead visibility

Expected Results:
✅ Real-time notifications sent
✅ Countdown timer displays correctly
✅ Lead visible to relevant contractors
✅ Notification bell updates
✅ Email notifications sent
```

---

### **JOURNEY 2: CUSTOMER TÌM CONTRACTOR TRỰC TIẾP**

#### **TC-004: Direct Contractor Search**
```
Test Steps:
1. Access contractor search page
2. Apply filters:
   - Category
   - Location
   - Rating
   - Price range
3. View contractor profiles
4. Select contractor
5. Create booking/lead for specific contractor

Expected Results:
✅ Filter results accurate
✅ Contractor profiles complete
✅ Booking process smooth
✅ Lead auto-assigned to selected contractor
```

#### **TC-005: Contractor Profile & Selection**
```
Test Verification Points:
- Profile completeness
- Rating display accuracy
- Portfolio images load
- Contact information visible
- Availability status
- Previous work examples

Expected UX:
✅ Profile loads < 2 seconds
✅ Images optimized and clear
✅ Easy comparison between contractors
✅ Clear CTA buttons
```

---

### **JOURNEY 3: CONTRACTOR LEAD PURCHASE FLOW**

#### **TC-006: Lead Notification & Purchase - Happy Path**
```
Preconditions:
- Contractor logged in
- Has sufficient wallet balance
- Lead matches contractor's categories

Test Steps:
1. Receive lead notification
2. View notification details
3. Click "Xem chi tiết Lead"
4. Review lead information
5. Click "Mua Lead" (within 24h)
6. Confirm payment
7. Access customer contact info

Expected Results:
✅ Notification received instantly
✅ Countdown timer accurate
✅ Lead details complete
✅ Payment processed successfully
✅ Customer info unlocked
✅ Lead marked as "purchased"
✅ Wallet balance updated
```

#### **TC-007: Lead Purchase - Edge Cases**
```
Test Scenarios:
A. Insufficient wallet balance
B. Lead expired (>24h)
C. Lead already purchased by max contractors
D. Contractor not in matching category

Expected Results:
❌ Purchase blocked with clear error
✅ Appropriate error messages
✅ Suggested actions provided
✅ No partial transactions
```

#### **TC-008: Countdown Timer Accuracy**
```
Test Steps:
1. Create lead at specific time
2. Monitor countdown across multiple contractors
3. Test timer behavior at critical points:
   - 23 hours remaining
   - 1 hour remaining
   - 30 minutes remaining
   - 1 minute remaining
   - Expiry

Expected Results:
✅ Timer synced across all users
✅ Urgent animations at <1 hour
✅ Auto-expiry at 0:00:00
✅ Lead status updated to "expired"
✅ No purchases allowed after expiry
```

---

### **JOURNEY 4: LEAD ASSIGNMENT & CONTACT**

#### **TC-009: Customer Contractor Selection**
```
Test Steps:
1. Customer views lead with multiple interested contractors
2. Compare contractor profiles
3. Select preferred contractor
4. Confirm selection
5. Close lead for other contractors

Expected Results:
✅ Contractor comparison UI clear
✅ Selection process intuitive
✅ Confirmation dialog appears
✅ Selected contractor notified
✅ Other contractors notified of rejection
✅ Lead status = "closed"
```

#### **TC-010: Contractor-Customer Communication**
```
Test Steps:
1. Contractor contacts customer (post-purchase)
2. Exchange messages/calls
3. Schedule appointment
4. Confirm work details

Expected UX Requirements:
✅ Contact info displayed securely
✅ Communication tools available
✅ Privacy protection maintained
✅ Appointment scheduling integrated
```

---

### **JOURNEY 5: JOB COMPLETION & PAYMENT**

#### **TC-011: Work Completion Flow**
```
Test Steps:
1. Contractor marks work as "completed"
2. Customer receives completion notification
3. Customer confirms/disputes completion
4. Payment processing
5. Review prompts sent

Expected Results:
✅ Status updates real-time
✅ Payment processed securely
✅ Both parties notified
✅ Review requests sent
✅ Transaction recorded
```

#### **TC-012: Payment & Dispute Handling**
```
Test Scenarios:
A. Customer confirms completion → Auto payment
B. Customer disputes → Escalation process
C. No response from customer → Auto-payment after X days

Expected Results:
✅ Payment flows work correctly
✅ Dispute resolution process clear
✅ Timeouts handled properly
✅ All parties notified appropriately
```

---

### **JOURNEY 6: REVIEW & RATING SYSTEM**

#### **TC-013: Review Submission**
```
Test Steps:
1. Complete job (from TC-011)
2. Receive review prompt
3. Submit rating (1-5 stars)
4. Write detailed review
5. Submit feedback

Expected Results:
✅ Review form user-friendly
✅ Both customer & contractor can review
✅ Reviews display publicly
✅ Ratings affect contractor profile
✅ Inappropriate content filtered
```

#### **TC-014: Review Display & Impact**
```
Verification Points:
- Review visibility on profiles
- Rating calculation accuracy
- Review sorting options
- Response to reviews
- Review authenticity measures

Expected UX:
✅ Reviews load quickly
✅ Easy to read and scan
✅ Filtering options available
✅ Helpful/unhelpful voting
```

---

### **JOURNEY 7: SUPPORT SYSTEM TESTING**

#### **TC-015: Live Chat Support**
```
Test Steps:
1. Click chat widget
2. Send message
3. Test quick actions
4. Switch between chat/tickets
5. Submit support ticket

Expected Results:
✅ Chat opens instantly
✅ Messages sent/received quickly
✅ Quick actions work
✅ Ticket submission successful
✅ Support responses timely
```

#### **TC-016: Support Ticket Management**
```
Test Scenarios:
- Create different priority tickets
- Upload attachments
- Track ticket status
- Receive updates
- Close resolved tickets

Expected Results:
✅ Tickets created successfully
✅ Priority routing works
✅ Status tracking accurate
✅ File uploads work
✅ Communication maintained
```

---

## 🎨 **UX/UI TESTING SCENARIOS**

### **TC-017: Responsive Design Testing**
```
Test Devices:
- Mobile: iPhone 12, Samsung Galaxy S21
- Tablet: iPad, Android tablet
- Desktop: 1920x1080, 1366x768
- Large screen: 2560x1440

Verification Points:
✅ All elements visible and functional
✅ Touch targets appropriate size (min 44px)
✅ Text readable without zooming
✅ Navigation intuitive on all devices
✅ Performance consistent across devices
```

### **TC-018: Accessibility Testing**
```
Test Requirements:
- Screen reader compatibility
- Keyboard navigation
- Color contrast ratios
- Alt text for images
- ARIA labels

Expected Results:
✅ WCAG 2.1 AA compliance
✅ Screen reader friendly
✅ Keyboard accessible
✅ High contrast mode support
```

### **TC-019: Performance Testing**
```
Performance Metrics:
- Page load time < 3 seconds
- First contentful paint < 1.5 seconds
- Time to interactive < 5 seconds
- Core Web Vitals pass

Load Testing:
- 100 concurrent users
- 500 concurrent users
- Peak load scenarios

Expected Results:
✅ All performance metrics met
✅ No degradation under load
✅ Graceful handling of errors
```

---

## 🔒 **SECURITY TESTING**

### **TC-020: Authentication & Authorization**
```
Test Scenarios:
- Login/logout functionality
- Password security requirements
- Session management
- Role-based access control
- Data privacy

Security Checks:
✅ SQL injection prevention
✅ XSS protection
✅ CSRF protection
✅ Secure data transmission (HTTPS)
✅ PII data protection
```

### **TC-021: Payment Security**
```
Test Points:
- Payment processing encryption
- PCI compliance
- Wallet security
- Transaction logging
- Fraud prevention

Expected Results:
✅ All payments encrypted
✅ No sensitive data stored
✅ Audit trails complete
✅ Fraud detection active
```

---

## 📊 **TEST DATA REQUIREMENTS**

### **Customer Test Data:**
```json
{
  "customers": [
    {
      "id": 1,
      "name": "Nguyễn Văn A",
      "email": "customer1@test.com",
      "phone": "0901234567",
      "district": "Quận 1",
      "ward": "Phường Bến Nghé",
      "verified": true
    }
  ]
}
```

### **Contractor Test Data:**
```json
{
  "contractors": [
    {
      "id": 1,
      "company_name": "Điện Lạnh ABC",
      "email": "contractor1@test.com",
      "phone": "0901234568",
      "categories": ["Điện", "Điện lạnh"],
      "district": "Quận 1",
      "rating": 4.5,
      "wallet_balance": 500000,
      "verified": true
    }
  ]
}
```

### **Lead Test Data:**
```json
{
  "leads": [
    {
      "title": "Sửa chữa điện nước",
      "description": "Cần thợ sửa ổ cắm và thay bóng đèn",
      "category_id": 1,
      "customer_id": 1,
      "budget_min": 200000,
      "budget_max": 500000,
      "urgency": "medium",
      "district": "Quận 1",
      "ward": "Phường Bến Nghé",
      "status": "active"
    }
  ]
}
```

---

## 🚀 **AUTOMATION TEST PLAN**

### **API Testing (Postman/Newman):**
```javascript
// Lead Creation API Test
pm.test("Create Lead - Success", function () {
    pm.response.to.have.status(201);
    pm.expect(pm.response.json()).to.have.property('lead_id');
    pm.expect(pm.response.json().status).to.eql('active');
});

// Notification API Test
pm.test("Send Notifications", function () {
    pm.response.to.have.status(200);
    pm.expect(pm.response.json().notifications_sent).to.be.above(0);
});
```

### **E2E Testing (Cypress):**
```javascript
// Customer Lead Creation E2E
describe('Customer Lead Creation', () => {
  it('should create lead successfully', () => {
    cy.login('customer1@test.com', 'password');
    cy.visit('/user/customer/leads/create');
    cy.fillLeadForm({
      category: 'Điện',
      title: 'Sửa chữa điện nước',
      description: 'Test description',
      district: 'Quận 1'
    });
    cy.contains('Tạo yêu cầu').click();
    cy.url().should('include', '/show/');
    cy.contains('Lead đã được tạo thành công');
  });
});
```

---

## 📈 **TEST EXECUTION MATRIX**

| Test Case | Priority | Environment | Automation | Status |
|-----------|----------|-------------|------------|---------|
| TC-001 | High | All | Yes | ⏳ |
| TC-002 | High | All | Yes | ⏳ |
| TC-003 | Critical | All | Partial | ⏳ |
| TC-004 | Medium | All | Yes | ⏳ |
| TC-005 | Medium | All | No | ⏳ |
| TC-006 | Critical | All | Yes | ⏳ |
| TC-007 | High | All | Yes | ⏳ |
| TC-008 | Critical | All | Partial | ⏳ |
| TC-009 | High | All | Yes | ⏳ |
| TC-010 | Medium | All | No | ⏳ |
| TC-011 | High | All | Yes | ⏳ |
| TC-012 | High | All | Yes | ⏳ |
| TC-013 | Medium | All | Yes | ⏳ |
| TC-014 | Medium | All | No | ⏳ |
| TC-015 | High | All | Partial | ⏳ |
| TC-016 | Medium | All | Yes | ⏳ |
| TC-017 | High | All | Yes | ⏳ |
| TC-018 | Medium | All | Manual | ⏳ |
| TC-019 | Critical | Prod-like | Yes | ⏳ |
| TC-020 | Critical | All | Yes | ⏳ |
| TC-021 | Critical | All | Manual | ⏳ |

---

## 🎯 **SUCCESS CRITERIA**

### **Functional Testing:**
- ✅ 100% test cases pass
- ✅ All user journeys complete successfully
- ✅ No critical/high severity bugs

### **Performance Testing:**
- ✅ Page load times < 3 seconds
- ✅ System handles 500 concurrent users
- ✅ 99.9% uptime achieved

### **UX Testing:**
- ✅ User satisfaction score > 4.5/5
- ✅ Task completion rate > 95%
- ✅ User error rate < 5%

### **Security Testing:**
- ✅ No security vulnerabilities found
- ✅ All security scans pass
- ✅ Compliance requirements met

---

## 📋 **TEST REPORTING**

### **Daily Test Reports:**
- Test execution progress
- Bug discovery trends
- Blocker issues
- Performance metrics

### **Final Test Report:**
- Overall test summary
- Risk assessment
- Recommendation for go-live
- Known issues and workarounds

---

## 🔄 **REGRESSION TEST PLAN**

### **Smoke Test Suite** (30 minutes):
- User login/logout
- Lead creation basic flow
- Contractor search
- Payment processing
- Notifications

### **Regression Test Suite** (4 hours):
- All critical user journeys
- Integration points
- API endpoints
- Database operations

### **Full Test Suite** (8 hours):
- Complete test case execution
- Performance validation
- Security verification
- UX/UI testing

---

**📅 Test Execution Timeline: 2 weeks**
**👥 Test Team: 3 testers + 1 automation engineer**
**🏆 Success Rate Target: 98%** 