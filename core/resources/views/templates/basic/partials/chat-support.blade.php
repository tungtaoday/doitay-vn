<!-- Chat Support Widget -->
<div class="chat-support-widget" id="chatSupportWidget">
    <!-- Chat Toggle Button -->
    <button class="chat-toggle-btn" id="chatToggleBtn">
        <i class="las la-comments" id="chatIcon"></i>
        <i class="las la-times" id="closeIcon" style="display: none;"></i>
        <span class="chat-notification-dot" id="chatNotificationDot" style="display: none;"></span>
    </button>

    <!-- Chat Box -->
    <div class="chat-box" id="chatBox" style="display: none;">
        <div class="chat-header">
            <div class="d-flex align-items-center">
                <div class="support-avatar me-2">
                    <i class="las la-headset"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0">Hỗ trợ khách hàng</h6>
                    <small class="text-success">
                        <span class="status-dot"></span>
                        Đang hoạt động
                    </small>
                </div>
                <button class="btn btn-sm btn-link text-white p-0" id="minimizeChat">
                    <i class="las la-minus"></i>
                </button>
            </div>
        </div>

        <!-- Chat Tabs -->
        <div class="chat-tabs">
            <button class="chat-tab active" data-tab="chat">
                <i class="las la-comment me-1"></i>Chat
            </button>
            <button class="chat-tab" data-tab="tickets">
                <i class="las la-ticket-alt me-1"></i>Yêu cầu
            </button>
        </div>

        <!-- Chat Content -->
        <div class="chat-content" id="chatContent">
            <!-- Chat Tab -->
            <div class="chat-tab-content active" id="chatTabContent">
                <div class="chat-messages" id="chatMessages">
                    <div class="message support-message">
                        <div class="message-avatar">
                            <i class="las la-headset"></i>
                        </div>
                        <div class="message-content">
                            <p>Xin chào! Tôi có thể giúp gì cho bạn?</p>
                            <small class="message-time">Vừa xong</small>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="quick-action-item" data-action="find-contractor">
                        <i class="las la-search"></i>
                        <span>Tìm thợ</span>
                    </div>
                    <div class="quick-action-item" data-action="create-lead">
                        <i class="las la-plus"></i>
                        <span>Tạo Lead</span>
                    </div>
                    <div class="quick-action-item" data-action="payment-help">
                        <i class="las la-credit-card"></i>
                        <span>Thanh toán</span>
                    </div>
                    <div class="quick-action-item" data-action="technical-support">
                        <i class="las la-cog"></i>
                        <span>Kỹ thuật</span>
                    </div>
                </div>

                <!-- Chat Input -->
                <div class="chat-input-container">
                    <div class="input-group">
                        <input type="text" class="form-control" id="chatInput" 
                               placeholder="Nhập tin nhắn...">
                        <button class="btn btn-primary" id="sendChatBtn">
                            <i class="las la-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tickets Tab -->
            <div class="chat-tab-content" id="ticketsTabContent">
                <div class="support-form">
                    <form id="supportTicketForm">
                        <div class="mb-3">
                            <label class="form-label">Loại yêu cầu</label>
                            <select class="form-select" name="category" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="technical">Hỗ trợ kỹ thuật</option>
                                <option value="payment">Thanh toán</option>
                                <option value="account">Tài khoản</option>
                                <option value="complaint">Khiếu nại</option>
                                <option value="suggestion">Góp ý</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mức độ ưu tiên</label>
                            <select class="form-select" name="priority" required>
                                <option value="low">Thấp</option>
                                <option value="medium" selected>Trung bình</option>
                                <option value="high">Cao</option>
                                <option value="urgent">Khẩn cấp</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="subject" 
                                   placeholder="Mô tả ngắn vấn đề..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea class="form-control" name="description" rows="4" 
                                      placeholder="Mô tả chi tiết vấn đề bạn gặp phải..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File đính kèm (tùy chọn)</label>
                            <input type="file" class="form-control" name="attachments[]" multiple
                                   accept="image/*,.pdf,.doc,.docx">
                            <small class="form-text text-muted">Hỗ trợ hình ảnh, PDF, Word. Tối đa 5MB.</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="las la-paper-plane me-1"></i>Gửi yêu cầu
                        </button>
                    </form>
                </div>

                <!-- Recent Tickets -->
                <div class="recent-tickets mt-3">
                    <h6 class="mb-2">Yêu cầu gần đây</h6>
                    <div id="recentTicketsList">
                        <div class="text-center text-muted py-3">
                            <i class="las la-inbox"></i>
                            <p class="mb-0 small">Chưa có yêu cầu nào</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-support-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1050;
}

.chat-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3);
    transition: all 0.3s ease;
    position: relative;
    cursor: pointer;
}

.chat-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0, 123, 255, 0.4);
}

.chat-notification-dot {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 12px;
    height: 12px;
    background: #dc3545;
    border-radius: 50%;
    border: 2px solid white;
    animation: pulse 2s infinite;
}

.chat-box {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    padding: 1rem;
}

.support-avatar {
    width: 35px;
    height: 35px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background: #28a745;
    border-radius: 50%;
    margin-right: 5px;
    animation: blink 2s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

.chat-tabs {
    display: flex;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.chat-tab {
    flex: 1;
    background: none;
    border: none;
    padding: 0.75rem;
    font-size: 0.875rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.chat-tab.active {
    background: white;
    color: #007bff;
    border-bottom: 2px solid #007bff;
}

.chat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-tab-content {
    display: none;
    flex: 1;
    flex-direction: column;
}

.chat-tab-content.active {
    display: flex;
}

.chat-messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    max-height: 250px;
}

.message {
    display: flex;
    margin-bottom: 1rem;
    align-items: flex-start;
}

.message-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #007bff;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    margin-right: 0.5rem;
    flex-shrink: 0;
}

.user-message .message-avatar {
    background: #28a745;
    order: 2;
    margin-left: 0.5rem;
    margin-right: 0;
}

.message-content {
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 15px;
    max-width: 80%;
}

.user-message .message-content {
    background: #007bff;
    color: white;
    margin-left: auto;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 0.25rem;
    display: block;
}

.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
    padding: 1rem;
    background: #f8f9fa;
}

.quick-action-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #495057;
}

.quick-action-item:hover {
    background: #007bff;
    color: white;
    transform: translateY(-2px);
}

.quick-action-item i {
    font-size: 1.2rem;
    margin-bottom: 0.25rem;
    display: block;
}

.quick-action-item span {
    font-size: 0.75rem;
    font-weight: 500;
}

.chat-input-container {
    padding: 1rem;
    background: white;
    border-top: 1px solid #e9ecef;
}

.support-form {
    padding: 1rem;
    flex: 1;
    overflow-y: auto;
}

.recent-tickets {
    border-top: 1px solid #e9ecef;
    padding: 1rem;
    background: #f8f9fa;
}

.ticket-item {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ticket-item:hover {
    border-color: #007bff;
}

.ticket-status {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.ticket-status.open { background: #d1ecf1; color: #0c5460; }
.ticket-status.in-progress { background: #fff3cd; color: #856404; }
.ticket-status.resolved { background: #d4edda; color: #155724; }
.ticket-status.closed { background: #f8d7da; color: #721c24; }

@media (max-width: 768px) {
    .chat-box {
        width: calc(100vw - 40px);
        right: 20px;
        left: 20px;
        bottom: 80px;
    }
    
    .chat-support-widget {
        right: 20px;
        bottom: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatWidget = document.getElementById('chatSupportWidget');
    const chatToggleBtn = document.getElementById('chatToggleBtn');
    const chatBox = document.getElementById('chatBox');
    const chatIcon = document.getElementById('chatIcon');
    const closeIcon = document.getElementById('closeIcon');
    const chatInput = document.getElementById('chatInput');
    const sendChatBtn = document.getElementById('sendChatBtn');
    const chatMessages = document.getElementById('chatMessages');
    
    let isChatOpen = false;
    
    // Toggle chat box
    chatToggleBtn.addEventListener('click', function() {
        isChatOpen = !isChatOpen;
        
        if (isChatOpen) {
            chatBox.style.display = 'flex';
            chatIcon.style.display = 'none';
            closeIcon.style.display = 'block';
            document.getElementById('chatNotificationDot').style.display = 'none';
        } else {
            chatBox.style.display = 'none';
            chatIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    });
    
    // Chat tabs
    document.querySelectorAll('.chat-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Update active tab
            document.querySelectorAll('.chat-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Update active content
            document.querySelectorAll('.chat-tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(targetTab + 'TabContent').classList.add('active');
        });
    });
    
    // Send chat message
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        chatInput.value = '';
        
        // Simulate bot response
        setTimeout(() => {
            const responses = [
                'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ hỗ trợ bạn ngay.',
                'Tôi đã hiểu vấn đề của bạn. Cho tôi vài phút để kiểm tra.',
                'Bạn có thể cung cấp thêm thông tin chi tiết không?',
                'Tôi sẽ chuyển yêu cầu này cho đội chuyên môn để hỗ trợ tốt hơn.'
            ];
            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            addMessage(randomResponse, 'support');
        }, 1000);
    }
    
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = sender === 'user' ? '<i class="las la-user"></i>' : '<i class="las la-headset"></i>';
        
        const content = document.createElement('div');
        content.className = 'message-content';
        content.innerHTML = `
            <p>${text}</p>
            <small class="message-time">Vừa xong</small>
        `;
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(content);
        chatMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    sendChatBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Quick actions
    document.querySelectorAll('.quick-action-item').forEach(item => {
        item.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            handleQuickAction(action);
        });
    });
    
    function handleQuickAction(action) {
        const actions = {
            'find-contractor': 'Tôi muốn tìm thợ chuyên nghiệp cho công việc của mình.',
            'create-lead': 'Tôi muốn tạo yêu cầu dịch vụ mới.',
            'payment-help': 'Tôi cần hỗ trợ về thanh toán.',
            'technical-support': 'Tôi gặp vấn đề kỹ thuật cần hỗ trợ.'
        };
        
        if (actions[action]) {
            addMessage(actions[action], 'user');
            
            // Switch to chat tab
            document.querySelector('.chat-tab[data-tab="chat"]').click();
            
            setTimeout(() => {
                addMessage('Tôi sẽ hướng dẫn bạn chi tiết. Bạn có thể mô tả cụ thể hơn không?', 'support');
            }, 1000);
        }
    }
    
    // Support ticket form
    document.getElementById('supportTicketForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Simulate form submission
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="las la-spinner la-spin me-1"></i>Đang gửi...';
        submitBtn.disabled = true;
        
        setTimeout(() => {
            submitBtn.innerHTML = '<i class="las la-check me-1"></i>Đã gửi!';
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-success');
            
            setTimeout(() => {
                this.reset();
                submitBtn.innerHTML = originalText;
                submitBtn.classList.remove('btn-success');
                submitBtn.classList.add('btn-primary');
                submitBtn.disabled = false;
                
                // Show success message
                addTicketToRecent({
                    id: Date.now(),
                    subject: formData.get('subject'),
                    status: 'open',
                    created_at: 'Vừa xong'
                });
            }, 2000);
        }, 2000);
    });
    
    function addTicketToRecent(ticket) {
        const ticketsList = document.getElementById('recentTicketsList');
        
        // Remove "no tickets" message if exists
        if (ticketsList.innerHTML.includes('Chưa có yêu cầu nào')) {
            ticketsList.innerHTML = '';
        }
        
        const ticketDiv = document.createElement('div');
        ticketDiv.className = 'ticket-item';
        ticketDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-1">
                <h6 class="mb-0 small">${ticket.subject}</h6>
                <span class="ticket-status ${ticket.status}">${getStatusText(ticket.status)}</span>
            </div>
            <small class="text-muted">#${ticket.id} • ${ticket.created_at}</small>
        `;
        
        ticketsList.insertBefore(ticketDiv, ticketsList.firstChild);
    }
    
    function getStatusText(status) {
        const statusMap = {
            'open': 'Mở',
            'in-progress': 'Đang xử lý',
            'resolved': 'Đã giải quyết',
            'closed': 'Đã đóng'
        };
        return statusMap[status] || status;
    }
    
    // Show notification dot occasionally to attract attention
    setInterval(() => {
        if (!isChatOpen) {
            document.getElementById('chatNotificationDot').style.display = 'block';
        }
    }, 30000); // Every 30 seconds
});
</script> 