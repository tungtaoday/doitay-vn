@php
    // Zalo configuration - bạn có thể thay đổi các giá trị này
    $zaloPhone = gs('zalo_phone') ?? '0901234567'; // Số điện thoại Zalo của bạn
    $zaloName = gs('zalo_name') ?? 'Tư vấn viên'; // Tên hiển thị
    $zaloMessage = gs('zalo_message') ?? 'Xin chào! Tôi có thể giúp gì cho bạn?'; // Tin nhắn mặc định
    $zaloAvatar = gs('zalo_avatar') ?? asset('components/zalo-avatar.svg'); // Avatar Zalo
    $zaloOnline = gs('zalo_online') ?? true; // Trạng thái online
@endphp

<!-- Zalo Chat Widget -->
<div id="zalo-chat-widget" class="zalo-chat-widget">
    <!-- Chat Button -->
    <div class="zalo-chat-button" id="zalo-chat-button" onclick="toggleZaloChat()">
        <div class="zalo-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/>
            </svg>
        </div>
        <div class="zalo-pulse"></div>
        <span class="zalo-label">Chat Zalo</span>
    </div>

    <!-- Chat Window -->
    <div class="zalo-chat-window" id="zalo-chat-window">
        <!-- Chat Header -->
        <div class="zalo-chat-header">
            <div class="zalo-user-info">
                <div class="zalo-avatar">
                    <img src="{{ $zaloAvatar }}" alt="Zalo Avatar" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMjAiIGZpbGw9IiMwMEE2RkYiLz4KPHBhdGggZD0iTTIwIDEwQzE1LjU4IDEwIDEyIDEzLjU4IDEyIDE4QzEyIDIyLjQyIDE1LjU4IDI2IDIwIDI2QzI0LjQyIDI2IDI4IDIyLjQyIDI4IDE4QzI4IDEzLjU4IDI0LjQyIDEwIDIwIDEwWk0yMCAyNEMxNi42OSAyNCAxNCAyMS4zMSAxNCAxOEMxNCAxNC42OSAxNi42OSAxMiAyMCAxMkMyMy4zMSAxMiAyNiAxNC42OSAyNiAxOEMyNiAyMS4zMSAyMy4zMSAyNCAyMCAyNFoiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0yMCAxNkMxOC4zNCAxNiAxNyAxNy4zNCAxNyAxOUMxNyAyMC42NiAxOC4zNCAyMiAyMCAyMkMyMS42NiAyMiAyMyAyMC42NiAyMyAxOUMyMyAxNy4zNCAyMS42NiAxNiAyMCAxNloiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo='">
                </div>
                <div class="zalo-user-details">
                    <div class="zalo-name">{{ $zaloName }}</div>
                    <div class="zalo-status {{ $zaloOnline ? 'online' : 'offline' }}">
                        <span class="status-dot"></span>
                        {{ $zaloOnline ? 'Đang hoạt động' : 'Không hoạt động' }}
                    </div>
                </div>
            </div>
            <button class="zalo-close-btn" onclick="toggleZaloChat()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 5L5 15M5 5L15 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <!-- Chat Messages -->
        <div class="zalo-chat-messages" id="zalo-chat-messages">
            <div class="zalo-message zalo-message-received">
                <div class="zalo-message-content">
                    <p>{{ $zaloMessage }}</p>
                    <span class="zalo-message-time">{{ now()->format('H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="zalo-chat-input">
            <input type="text" id="zalo-message-input" placeholder="Nhập tin nhắn..." maxlength="500">
            <button class="zalo-send-btn" onclick="sendZaloMessage()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" fill="currentColor"/>
                </svg>
            </button>
        </div>

        <!-- Quick Actions -->
        <div class="zalo-quick-actions">
            <button class="zalo-quick-btn" onclick="openZaloApp('{{ $zaloPhone }}')">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 1C4.13 1 1 4.13 1 8s3.13 7 7 7 7-3.13 7-7-3.13-7-7-7zM6 12V8l4 2-4 2z" fill="currentColor"/>
                </svg>
                Mở Zalo
            </button>
            <button class="zalo-quick-btn" onclick="copyZaloPhone('{{ $zaloPhone }}')">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 2h8a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2z" fill="currentColor"/>
                    <path d="M12 6V4a2 2 0 00-2-2H6v2h6z" fill="currentColor"/>
                </svg>
                Copy số
            </button>
        </div>
    </div>
</div>

<!-- Zalo Chat Styles -->
<style>
.zalo-chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Chat Button */
.zalo-chat-button {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 166, 255, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.zalo-chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0, 166, 255, 0.4);
}

.zalo-chat-button .zalo-icon {
    color: white;
    font-size: 24px;
}

.zalo-chat-button .zalo-label {
    position: absolute;
    right: 70px;
    background: #333;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 14px;
    white-space: nowrap;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.zalo-chat-button:hover .zalo-label {
    opacity: 1;
    transform: translateX(0);
}

.zalo-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(0, 166, 255, 0.3);
    animation: zalo-pulse 2s infinite;
}

@keyframes zalo-pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

/* Chat Window */
.zalo-chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 320px;
    height: 450px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: zalo-slide-up 0.3s ease;
}

.zalo-chat-window.active {
    display: flex;
}

@keyframes zalo-slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Chat Header */
.zalo-chat-header {
    background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%);
    color: white;
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.zalo-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.zalo-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.zalo-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.zalo-name {
    font-weight: 600;
    font-size: 16px;
}

.zalo-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    opacity: 0.9;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4CAF50;
}

.zalo-status.offline .status-dot {
    background: #9E9E9E;
}

.zalo-close-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background 0.2s ease;
}

.zalo-close-btn:hover {
    background: rgba(255, 255, 255, 0.1);
}

/* Chat Messages */
.zalo-chat-messages {
    flex: 1;
    padding: 16px;
    overflow-y: auto;
    background: #f8f9fa;
}

.zalo-message {
    margin-bottom: 12px;
    display: flex;
}

.zalo-message-received {
    justify-content: flex-start;
}

.zalo-message-sent {
    justify-content: flex-end;
}

.zalo-message-content {
    max-width: 80%;
    background: white;
    padding: 12px 16px;
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.zalo-message-received .zalo-message-content {
    background: white;
    border-bottom-left-radius: 6px;
}

.zalo-message-sent .zalo-message-content {
    background: #00A6FF;
    color: white;
    border-bottom-right-radius: 6px;
}

.zalo-message-content p {
    margin: 0 0 4px 0;
    line-height: 1.4;
}

.zalo-message-time {
    font-size: 11px;
    opacity: 0.7;
    display: block;
}

/* Chat Input */
.zalo-chat-input {
    padding: 16px;
    background: white;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 8px;
    align-items: center;
}

.zalo-chat-input input {
    flex: 1;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s ease;
}

.zalo-chat-input input:focus {
    border-color: #00A6FF;
}

.zalo-send-btn {
    background: #00A6FF;
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
}

.zalo-send-btn:hover {
    background: #0088CC;
}

/* Quick Actions */
.zalo-quick-actions {
    padding: 12px 16px;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 8px;
}

.zalo-quick-btn {
    flex: 1;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.zalo-quick-btn:hover {
    background: #00A6FF;
    color: white;
    border-color: #00A6FF;
}

/* Responsive Design */
@media (max-width: 768px) {
    .zalo-chat-widget {
        bottom: 10px;
        right: 10px;
    }
    
    .zalo-chat-window {
        width: calc(100vw - 20px);
        right: -10px;
        bottom: 70px;
    }
    
    .zalo-chat-button .zalo-label {
        display: none;
    }
}

@media (max-width: 480px) {
    .zalo-chat-window {
        width: calc(100vw - 20px);
        height: 400px;
    }
}

/* Animation for new messages */
.zalo-message.new {
    animation: zalo-message-slide 0.3s ease;
}

@keyframes zalo-message-slide {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading state */
.zalo-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    color: #666;
}

.zalo-loading::after {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #e9ecef;
    border-top: 2px solid #00A6FF;
    border-radius: 50%;
    animation: zalo-spin 1s linear infinite;
    margin-left: 8px;
}

@keyframes zalo-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<!-- Zalo Chat JavaScript -->
<script>
let zaloChatOpen = false;
let zaloMessageCount = 0;

// Toggle chat window
function toggleZaloChat() {
    const chatWindow = document.getElementById('zalo-chat-window');
    const chatButton = document.getElementById('zalo-chat-button');
    
    if (zaloChatOpen) {
        chatWindow.classList.remove('active');
        chatButton.style.transform = 'scale(1)';
    } else {
        chatWindow.classList.add('active');
        chatButton.style.transform = 'scale(1.1)';
        
        // Focus on input
        setTimeout(() => {
            document.getElementById('zalo-message-input').focus();
        }, 300);
    }
    
    zaloChatOpen = !zaloChatOpen;
}

// Send message
function sendZaloMessage() {
    const input = document.getElementById('zalo-message-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add message to chat
    addZaloMessage(message, 'sent');
    
    // Clear input
    input.value = '';
    
    // Simulate reply after 1-3 seconds
    setTimeout(() => {
        const replies = [
            'Cảm ơn bạn đã liên hệ! Tôi sẽ phản hồi sớm nhất có thể.',
            'Tôi đã nhận được tin nhắn của bạn. Bạn có thể cho tôi biết thêm thông tin không?',
            'Xin chào! Tôi có thể giúp gì cho bạn?',
            'Cảm ơn bạn! Tôi sẽ xem xét yêu cầu của bạn.',
            'Bạn có thể gọi điện trực tiếp cho tôi nếu cần hỗ trợ khẩn cấp.'
        ];
        
        const randomReply = replies[Math.floor(Math.random() * replies.length)];
        addZaloMessage(randomReply, 'received');
    }, 1000 + Math.random() * 2000);
}

// Add message to chat
function addZaloMessage(message, type) {
    const messagesContainer = document.getElementById('zalo-chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `zalo-message zalo-message-${type} new`;
    
    const currentTime = new Date().toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    messageDiv.innerHTML = `
        <div class="zalo-message-content">
            <p>${message}</p>
            <span class="zalo-message-time">${currentTime}</span>
        </div>
    `;
    
    messagesContainer.appendChild(messageDiv);
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    // Remove new class after animation
    setTimeout(() => {
        messageDiv.classList.remove('new');
    }, 300);
    
    zaloMessageCount++;
}

// Open Zalo app
function openZaloApp(phone) {
    // Try to open Zalo app
    const zaloUrl = `zalo://chat?phone=${phone}`;
    const webUrl = `https://zalo.me/${phone}`;
    
    // Try to open Zalo app first
    window.location.href = zaloUrl;
    
    // Fallback to web after a short delay
    setTimeout(() => {
        window.open(webUrl, '_blank');
    }, 1000);
}

// Copy Zalo phone number
function copyZaloPhone(phone) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(phone).then(() => {
            showZaloNotification('Đã copy số điện thoại!');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = phone;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showZaloNotification('Đã copy số điện thoại!');
    }
}

// Show notification
function showZaloNotification(message) {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #00A6FF;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        animation: zalo-notification-slide 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'zalo-notification-slide-out 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add notification animations
const style = document.createElement('style');
style.textContent = `
    @keyframes zalo-notification-slide {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes zalo-notification-slide-out {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
`;
document.head.appendChild(style);

// Handle Enter key in input
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('zalo-message-input');
    if (input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendZaloMessage();
            }
        });
    }
});

// Close chat when clicking outside
document.addEventListener('click', function(e) {
    const chatWidget = document.getElementById('zalo-chat-widget');
    const chatButton = document.getElementById('zalo-chat-button');
    const chatWindow = document.getElementById('zalo-chat-window');
    
    if (zaloChatOpen && 
        !chatWidget.contains(e.target) && 
        !chatButton.contains(e.target) && 
        !chatWindow.contains(e.target)) {
        toggleZaloChat();
    }
});

// Auto-hide chat after inactivity (optional)
let chatTimeout;
function resetChatTimeout() {
    clearTimeout(chatTimeout);
    if (zaloChatOpen) {
        chatTimeout = setTimeout(() => {
            toggleZaloChat();
        }, 300000); // 5 minutes
    }
}

// Reset timeout on user interaction
document.addEventListener('mousemove', resetChatTimeout);
document.addEventListener('keypress', resetChatTimeout);
document.addEventListener('click', resetChatTimeout);
</script> 