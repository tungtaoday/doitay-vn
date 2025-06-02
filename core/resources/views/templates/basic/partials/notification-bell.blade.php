<!-- Notification Bell Component -->
<div class="notification-bell-container">
    <div class="dropdown">
        <button class="btn btn-link notification-bell p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="las la-bell fs-4"></i>
            <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
        </button>
        
        <div class="dropdown-menu dropdown-menu-end notification-dropdown" style="width: 350px;">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Thông báo</h6>
                <button class="btn btn-sm btn-link text-primary p-0" id="markAllRead">
                    Đánh dấu đã đọc
                </button>
            </div>
            
            <div class="notification-list" id="notificationList">
                <div class="text-center py-4 text-muted">
                    <i class="las la-bell-slash fs-2"></i>
                    <p class="mb-0">Không có thông báo mới</p>
                </div>
            </div>
            
            <div class="dropdown-footer text-center">
                <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-outline-primary">
                    Xem tất cả thông báo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Countdown Timer Modal -->
<div class="modal fade" id="leadCountdownModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="las la-clock me-2"></i>Lead sắp hết hạn!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div class="countdown-timer mb-3">
                    <div class="timer-display">
                        <span id="countdownHours">00</span>:
                        <span id="countdownMinutes">00</span>:
                        <span id="countdownSeconds">00</span>
                    </div>
                    <small class="text-muted d-block">Thời gian còn lại để quyết định</small>
                </div>
                
                <p class="lead-title mb-3" id="leadTitle">Lead: Sửa chữa điện nước</p>
                
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-success" id="purchaseLeadBtn">
                        <i class="las la-credit-card me-1"></i>Mua Lead
                    </button>
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="las la-times me-1"></i>Bỏ qua
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.notification-bell-container {
    position: relative;
}

.notification-bell {
    position: relative;
    color: #6c757d;
    border: none;
    background: none;
    transition: color 0.3s ease;
}

.notification-bell:hover {
    color: var(--bs-primary);
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: #dc3545;
    color: white;
    border-radius: 50%;
    font-size: 0.75rem;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.notification-dropdown {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    padding: 0;
}

.dropdown-header {
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    padding: 1rem;
}

.notification-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #e3f2fd;
    border-left: 4px solid var(--bs-primary);
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
}

.notification-icon.lead { background: #28a745; }
.notification-icon.appointment { background: #007bff; }
.notification-icon.general { background: #6c757d; }

.countdown-timer {
    background: linear-gradient(135deg, #ff6b6b 0%, #ffa500 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin: 1rem 0;
}

.timer-display {
    font-size: 2.5rem;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.dropdown-footer {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    padding: 0.75rem 1rem;
}

.lead-urgent {
    animation: shake 0.5s infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let notificationCount = 0;
    let countdownInterval = null;
    
    // Load notifications
    function loadNotifications() {
        fetch('{{ route("user.notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count);
                renderNotifications(data.notifications);
            })
            .catch(error => console.error('Error loading notifications:', error));
    }
    
    function updateNotificationBadge(count) {
        const badge = document.getElementById('notificationCount');
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
    
    function renderNotifications(notifications) {
        const container = document.getElementById('notificationList');
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4 text-muted">
                    <i class="las la-bell-slash fs-2"></i>
                    <p class="mb-0">Không có thông báo mới</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = notifications.map(notification => `
            <div class="notification-item unread" data-id="${notification.id}">
                <div class="d-flex align-items-start">
                    <div class="notification-icon ${notification.type} me-3">
                        <i class="las ${getNotificationIcon(notification.type)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${notification.title}</h6>
                        <p class="mb-1 small text-muted">${notification.message}</p>
                        <small class="text-muted">${notification.time}</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" data-bs-toggle="dropdown">
                            <i class="las la-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="${notification.action_url}">Xem chi tiết</a></li>
                            <li><button class="dropdown-item text-danger" onclick="deleteNotification('${notification.id}')">Xóa</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    function getNotificationIcon(type) {
        const icons = {
            'lead': 'la-bullhorn',
            'appointment': 'la-calendar',
            'general': 'la-info-circle'
        };
        return icons[type] || 'la-bell';
    }
    
    function startCountdown(endTime, leadId, leadTitle) {
        const modal = new bootstrap.Modal(document.getElementById('leadCountdownModal'));
        document.getElementById('leadTitle').textContent = `Lead: ${leadTitle}`;
        
        countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const timeLeft = endTime - now;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                modal.hide();
                loadNotifications(); // Refresh notifications
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            document.getElementById('countdownHours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('countdownMinutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('countdownSeconds').textContent = seconds.toString().padStart(2, '0');
            
            // Add urgency effects
            if (timeLeft <= 3600000) { // 1 hour
                document.querySelector('.countdown-timer').classList.add('lead-urgent');
            }
        }, 1000);
        
        modal.show();
    }
    
    // Mark all notifications as read
    document.getElementById('markAllRead').addEventListener('click', function() {
        fetch('{{ route("user.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        });
    });
    
    // Delete notification function
    window.deleteNotification = function(notificationId) {
        fetch(`{{ route("user.notifications.delete", "") }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        });
    };
    
    // Load notifications on page load
    loadNotifications();
    
    // Auto-refresh every 30 seconds
    setInterval(loadNotifications, 30000);
    
    // Check for lead countdown triggers (this would be triggered by WebSocket or polling)
    // Example: startCountdown(new Date().getTime() + 24*60*60*1000, 123, 'Sửa chữa điện nước');
});
</script> 