<!-- Zalo Chat Widget - Direct Version (No gs() dependency) -->
<div id="zalo-chat-widget" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: Arial, sans-serif;
">
    <!-- Chat Button -->
    <div id="zalo-chat-button" style="
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
        color: white;
        font-size: 24px;
        animation: zalo-pulse 2s infinite;
    " onclick="toggleZaloChat()">
        💬
    </div>

    <!-- Chat Window -->
    <div id="zalo-chat-window" style="
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
    ">
        <!-- Chat Header -->
        <div style="
            background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%);
            color: white;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        ">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: #00A6FF;
                    font-size: 20px;
                ">
                    👤
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 16px;">Tư vấn viên</div>
                    <div style="font-size: 12px; opacity: 0.9;">Đang hoạt động</div>
                </div>
            </div>
            <button onclick="toggleZaloChat()" style="
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 4px;
                font-size: 20px;
            ">✕</button>
        </div>

        <!-- Chat Messages -->
        <div id="zalo-chat-messages" style="
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            background: #f8f9fa;
        ">
            <div style="
                background: white;
                padding: 12px 16px;
                border-radius: 18px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                margin-bottom: 12px;
                max-width: 80%;
            ">
                <p style="margin: 0 0 4px 0; line-height: 1.4;">Xin chào! Tôi có thể giúp gì cho bạn?</p>
                <span style="font-size: 11px; opacity: 0.7;">{{ now()->format('H:i') }}</span>
            </div>
        </div>

        <!-- Chat Input -->
        <div style="
            padding: 16px;
            background: white;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 8px;
            align-items: center;
        ">
            <input type="text" id="zalo-message-input" placeholder="Nhập tin nhắn..." style="
                flex: 1;
                border: 1px solid #e9ecef;
                border-radius: 20px;
                padding: 10px 16px;
                font-size: 14px;
                outline: none;
            ">
            <button onclick="sendZaloMessage()" style="
                background: #00A6FF;
                border: none;
                color: white;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 16px;
            ">📤</button>
        </div>

        <!-- Quick Actions -->
        <div style="
            padding: 12px 16px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 8px;
        ">
            <button onclick="openZaloApp('0901234567')" style="
                flex: 1;
                background: white;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 8px 12px;
                font-size: 12px;
                cursor: pointer;
            ">📱 Mở Zalo</button>
            <button onclick="copyZaloPhone('0901234567')" style="
                flex: 1;
                background: white;
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 8px 12px;
                font-size: 12px;
                cursor: pointer;
            ">📋 Copy số</button>
        </div>
    </div>
</div>

<style>
@keyframes zalo-pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 4px 20px rgba(0, 166, 255, 0.3);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 6px 25px rgba(0, 166, 255, 0.4);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 4px 20px rgba(0, 166, 255, 0.3);
    }
}

#zalo-chat-button:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(0, 166, 255, 0.4);
}

@media (max-width: 768px) {
    #zalo-chat-widget {
        bottom: 10px;
        right: 10px;
    }
    
    #zalo-chat-window {
        width: calc(100vw - 20px);
        right: -10px;
        bottom: 70px;
    }
}
</style>

<script>
let zaloChatOpen = false;

// Toggle chat window
function toggleZaloChat() {
    const chatWindow = document.getElementById('zalo-chat-window');
    const chatButton = document.getElementById('zalo-chat-button');
    
    if (zaloChatOpen) {
        chatWindow.style.display = 'none';
        chatButton.style.transform = 'scale(1)';
    } else {
        chatWindow.style.display = 'flex';
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
    
    const currentTime = new Date().toLocaleTimeString('vi-VN', {
        hour: '2-digit',
        minute: '2-digit'
    });
    
    messageDiv.style.cssText = `
        background: ${type === 'sent' ? '#00A6FF' : 'white'};
        color: ${type === 'sent' ? 'white' : 'black'};
        padding: 12px 16px;
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 12px;
        max-width: 80%;
        margin-left: ${type === 'sent' ? 'auto' : '0'};
        margin-right: ${type === 'sent' ? '0' : 'auto'};
    `;
    
    messageDiv.innerHTML = `
        <p style="margin: 0 0 4px 0; line-height: 1.4;">${message}</p>
        <span style="font-size: 11px; opacity: 0.7;">${currentTime}</span>
    `;
    
    messagesContainer.appendChild(messageDiv);
    
    // Scroll to bottom
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Open Zalo app
function openZaloApp(phone) {
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
            alert('Đã copy số điện thoại!');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = phone;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Đã copy số điện thoại!');
    }
}

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

console.log('🎉 Zalo Chat Widget Direct đã được load!');
console.log('📱 Widget sẽ hiển thị ở góc phải dưới màn hình');
</script> 