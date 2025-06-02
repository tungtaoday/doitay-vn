/// <reference types="cypress" />

describe('Customer Lead Creation Flow', () => {
  beforeEach(() => {
    // Setup test data
    cy.task('db:seed');
    cy.login('customer@test.com', 'password123');
  });

  afterEach(() => {
    // Cleanup
    cy.task('db:cleanup');
  });

  describe('TC-001: Lead Creation - Happy Path', () => {
    it('should create lead successfully with all required fields', () => {
      // Navigate to create lead page
      cy.visit('/user/dashboard');
      cy.contains('Tạo yêu cầu dịch vụ').click();
      cy.url().should('include', '/customer/leads/create');

      // Fill form with valid data
      cy.get('[name="category_id"]').select('Sửa chữa điện');
      cy.get('[name="title"]').type('Sửa chữa điện nước tại nhà');
      cy.get('[name="description"]').type('Cần sửa ổ cắm và thay bóng đèn LED cho phòng khách');
      cy.get('[name="district"]').type('Quận 1');
      cy.get('[name="ward"]').type('Phường Bến Nghé');
      cy.get('[name="address"]').type('123 Nguyễn Huệ');
      cy.get('[name="budget_min"]').type('200000');
      cy.get('[name="budget_max"]').type('500000');
      cy.get('[name="urgency"]').select('medium');
      
      // Set needed_by to tomorrow
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      cy.get('[name="needed_by"]').type(tomorrow.toISOString().split('T')[0]);

      // Add requirements
      cy.get('[name="requirements[]"]').first().type('Có kinh nghiệm tối thiểu 2 năm');
      cy.get('.add-requirement').click();
      cy.get('[name="requirements[]"]').last().type('Bảo hành công việc 6 tháng');

      // Upload attachment
      cy.get('[name="attachments[]"]').selectFile('cypress/fixtures/sample_image.jpg');

      // Submit form
      cy.contains('Tạo yêu cầu').click();

      // Verify success
      cy.url().should('include', '/customer/leads/show/');
      cy.contains('Lead đã được tạo thành công!').should('be.visible');
      cy.contains('Sửa chữa điện nước tại nhà').should('be.visible');
      cy.contains('Quận 1, Phường Bến Nghé').should('be.visible');
      cy.contains('200,000 - 500,000 VNĐ').should('be.visible');

      // Verify lead appears in customer's list
      cy.visit('/user/customer/leads');
      cy.contains('Sửa chữa điện nước tại nhà').should('be.visible');
      cy.get('[data-status="active"]').should('be.visible');
    });

    it('should send notifications to relevant contractors', () => {
      // Create lead (using API for faster execution)
      cy.request('POST', '/api/customer/leads', {
        category_id: 1,
        title: 'Test Lead for Notifications',
        description: 'Test description',
        district: 'Quận 1',
        ward: 'Phường Bến Nghé',
        address: '123 Test Street',
        budget_min: 200000,
        budget_max: 500000,
        urgency: 'medium'
      }).then((response) => {
        expect(response.status).to.eq(201);
        const leadId = response.body.lead_id;

        // Verify notifications were sent
        cy.request('GET', `/api/admin/leads/${leadId}/notifications`)
          .then((notifResponse) => {
            expect(notifResponse.body.notifications_sent).to.be.greaterThan(0);
          });

        // Login as contractor and check notifications
        cy.logout();
        cy.login('contractor@test.com', 'password123');
        cy.visit('/user/dashboard');
        
        // Check notification bell
        cy.get('.notification-bell .notification-badge').should('be.visible');
        cy.get('.notification-bell').click();
        cy.contains('Test Lead for Notifications').should('be.visible');
      });
    });
  });

  describe('TC-002: Form Validation', () => {
    beforeEach(() => {
      cy.visit('/user/customer/leads/create');
    });

    it('should show validation errors for empty required fields', () => {
      cy.contains('Tạo yêu cầu').click();

      // Check validation messages
      cy.contains('Category is required').should('be.visible');
      cy.contains('Title is required').should('be.visible');
      cy.contains('Description is required').should('be.visible');
      cy.contains('District is required').should('be.visible');
      cy.contains('Ward is required').should('be.visible');
      cy.contains('Address is required').should('be.visible');

      // Form should not submit
      cy.url().should('include', '/create');
    });

    it('should validate budget range (max >= min)', () => {
      // Fill required fields
      cy.fillBasicLeadForm();
      
      // Set invalid budget (max < min)
      cy.get('[name="budget_min"]').type('500000');
      cy.get('[name="budget_max"]').type('200000');
      
      // Trigger validation
      cy.get('[name="budget_max"]').blur();
      
      // Check validation message
      cy.contains('Ngân sách tối đa phải lớn hơn ngân sách tối thiểu').should('be.visible');
      cy.get('[name="budget_max"]').should('have.class', 'is-invalid');
    });

    it('should reject past dates for needed_by field', () => {
      cy.fillBasicLeadForm();
      
      // Set past date
      const yesterday = new Date();
      yesterday.setDate(yesterday.getDate() - 1);
      cy.get('[name="needed_by"]').type(yesterday.toISOString().split('T')[0]);
      
      cy.contains('Tạo yêu cầu').click();
      cy.contains('Date must be in the future').should('be.visible');
    });

    it('should reject files larger than 5MB', () => {
      cy.fillBasicLeadForm();
      
      // Try to upload large file
      cy.get('[name="attachments[]"]').selectFile('cypress/fixtures/large_file.pdf');
      
      cy.contains('Tạo yêu cầu').click();
      cy.contains('File size exceeds 5MB limit').should('be.visible');
    });
  });

  describe('TC-003: Lead Management', () => {
    beforeEach(() => {
      // Create test lead
      cy.createTestLead().as('testLead');
    });

    it('should allow customer to edit active lead', () => {
      cy.get('@testLead').then((lead) => {
        cy.visit(`/user/customer/leads/show/${lead.id}`);
        cy.contains('Chỉnh sửa').click();
        
        // Update title
        cy.get('[name="title"]').clear().type('Updated Lead Title');
        cy.get('[name="description"]').clear().type('Updated description');
        
        cy.contains('Cập nhật').click();
        
        // Verify update
        cy.contains('Lead đã được cập nhật thành công!').should('be.visible');
        cy.contains('Updated Lead Title').should('be.visible');
      });
    });

    it('should allow customer to close active lead', () => {
      cy.get('@testLead').then((lead) => {
        cy.visit(`/user/customer/leads/show/${lead.id}`);
        cy.contains('Đóng lead').click();
        
        // Confirm in modal
        cy.get('#closeLeadModal').should('be.visible');
        cy.contains('Bạn có chắc muốn đóng lead này không?').should('be.visible');
        cy.get('#closeLeadModal').within(() => {
          cy.contains('Đóng lead').click();
        });
        
        // Verify closure
        cy.contains('Lead đã được đóng thành công!').should('be.visible');
        cy.get('[data-status="closed"]').should('be.visible');
      });
    });

    it('should show lead progress when contractors purchase', () => {
      cy.get('@testLead').then((lead) => {
        // Simulate contractor purchases
        cy.request('POST', `/api/contractor/leads/${lead.id}/purchase`, {
          contractor_id: 1
        });
        cy.request('POST', `/api/contractor/leads/${lead.id}/purchase`, {
          contractor_id: 2
        });

        cy.visit(`/user/customer/leads/show/${lead.id}`);
        
        // Check progress display
        cy.contains('2 thợ quan tâm').should('be.visible');
        cy.get('.progress-bar').should('be.visible');
        cy.contains('Tiến độ tìm thợ').should('be.visible');
        
        // Should show interested contractors
        cy.contains('Chọn thợ').should('be.visible');
      });
    });
  });

  describe('TC-008: Real-time Features', () => {
    it('should update notification count in real-time', () => {
      // Start with no notifications
      cy.get('.notification-badge').should('not.be.visible');
      
      // Create a lead that will generate notifications
      cy.createTestLead();
      
      // Check that notification appears (simulated real-time)
      cy.wait(2000); // Wait for notification processing
      cy.reload(); // In real app, this would be automatic
      
      cy.get('.notification-badge').should('be.visible');
    });

    it('should show countdown timer for contractor decisions', () => {
      // Login as contractor
      cy.logout();
      cy.login('contractor@test.com', 'password123');
      
      // Create a lead for this contractor's category
      cy.createTestLeadForCategory(1); // Assuming category 1 matches contractor
      
      cy.visit('/user/leads');
      cy.contains('Xem chi tiết').first().click();
      
      // Should show countdown timer
      cy.get('.countdown-timer').should('be.visible');
      cy.get('#countdownHours').should('contain.text', '23'); // ~24 hours remaining
    });
  });
});

// Custom commands for reusability
Cypress.Commands.add('fillBasicLeadForm', () => {
  cy.get('[name="category_id"]').select('Sửa chữa điện');
  cy.get('[name="title"]').type('Test Lead Title');
  cy.get('[name="description"]').type('Test lead description');
  cy.get('[name="district"]').type('Quận 1');
  cy.get('[name="ward"]').type('Phường Test');
  cy.get('[name="address"]').type('123 Test Street');
  cy.get('[name="urgency"]').select('medium');
});

Cypress.Commands.add('createTestLead', () => {
  return cy.request('POST', '/api/customer/leads', {
    category_id: 1,
    title: 'Cypress Test Lead',
    description: 'Test lead created by Cypress',
    district: 'Quận 1',
    ward: 'Phường Test',
    address: '123 Cypress Street',
    budget_min: 200000,
    budget_max: 500000,
    urgency: 'medium',
    max_contractors: 5
  }).then((response) => {
    return response.body;
  });
});

Cypress.Commands.add('login', (email, password) => {
  cy.request('POST', '/api/auth/login', {
    email: email,
    password: password
  }).then((response) => {
    window.localStorage.setItem('authToken', response.body.token);
  });
});

Cypress.Commands.add('logout', () => {
  window.localStorage.removeItem('authToken');
}); 