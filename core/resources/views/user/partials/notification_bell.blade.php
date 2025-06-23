<!-- Notification Bell Component -->
<div class="notification-bell" id="notificationBell">
    <div class="dropdown">
        <a class="notification-toggle" href="#" id="notificationToggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
        </a>
        
        <div class="dropdown-menu notification-dropdown" aria-labelledby="notificationToggle">
            <div class="notification-header">
                <h6>Notifications</h6>
                <div class="notification-actions">
                    <button class="btn btn-sm btn-link" id="markAllReadBtn">Mark all read</button>
                    <a href="{{ route('user.notifications.index') }}" class="btn btn-sm btn-link">View all</a>
                </div>
            </div>
            
            <div class="notification-list" id="notificationList">
                <div class="notification-loading text-center py-3">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
            
            <div class="notification-footer">
                <a href="{{ route('user.notifications.index') }}" class="btn btn-primary btn-sm w-100">
                    View All Notifications
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.notification-bell {
    position: relative;
}

.notification-toggle {
    position: relative;
    padding: 8px 12px;
    color: #6c757d;
    text-decoration: none;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.notification-toggle:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

/* Mobile positioning fix */
@media (max-width: 768px) {
    .notification-bell {
        order: 10;
        margin-right: 10px;
    }
    
    .header-actions {
        display: flex;
        align-items: center;
    }
    
    .mobile-menu-trigger {
        order: 11;
    }
}

/* Mobile notification button in menu */
.mobile-notification-btn {
    position: relative;
    padding: 8px;
    color: #007bff;
    text-decoration: none;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.1);
    transition: all 0.3s ease;
    margin-left: auto;
}

.mobile-notification-btn:hover {
    background: rgba(0, 123, 255, 0.2);
    color: #0056b3;
}

.mobile-notification-badge {
    position: absolute;
    top: 2px;
    right: 2px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 5px;
    font-size: 10px;
    font-weight: bold;
    min-width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

.mobile-user-info {
    display: flex;
    align-items: center;
    padding: 20px;
    background: rgba(0, 123, 255, 0.1);
    border-radius: 10px;
    margin-bottom: 20px;
}

.notification-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: bold;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.notification-dropdown {
    width: 350px;
    max-height: 400px;
    border: none;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-radius: 10px;
    overflow: hidden;
    right: 0;
    left: auto;
}

.notification-header {
    padding: 15px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-weight: 600;
    font-size: 16px;
}

.notification-actions {
    display: flex;
    gap: 10px;
}

.notification-actions .btn-link {
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
    padding: 0;
    text-decoration: none;
}

.notification-actions .btn-link:hover {
    color: white;
}

.notification-list {
    max-height: 250px;
    overflow-y: auto;
    padding: 0;
}

.notification-item {
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background-color 0.2s ease;
    position: relative;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 20px;
    width: 8px;
    height: 8px;
    background-color: #007bff;
    border-radius: 50%;
}

.notification-content {
    margin-left: 15px;
}

.notification-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.notification-message {
    color: #666;
    font-size: 13px;
    line-height: 1.4;
    margin-bottom: 5px;
}

.notification-time {
    color: #999;
    font-size: 11px;
}

.notification-icon {
    font-size: 16px;
}

.notification-important {
    border-left: 4px solid #dc3545;
}

.notification-footer {
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.notification-empty {
    text-align: center;
    padding: 30px 20px;
    color: #666;
}

.notification-empty i {
    font-size: 24px;
    margin-bottom: 10px;
    color: #ccc;
}

/* Scrollbar styling */
.notification-list::-webkit-scrollbar {
    width: 4px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 4px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #999;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .notification-dropdown {
        width: 300px;
        right: -50px;
    }
    
    .notification-header {
        padding: 12px 15px;
    }
    
    .notification-item {
        padding: 12px 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = {
        init() {
            this.loadNotifications();
            this.bindEvents();
            this.startPolling();
        },

        loadNotifications() {
            fetch('{{ route("user.notifications.header.data") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.updateBadge(data.unread_count);
                        this.renderNotifications(data.notifications);
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        },

        updateBadge(count) {
            const badge = document.getElementById('notificationBadge');
            const mobileBadge = document.getElementById('mobileNotificationBadge');
            
            if (count > 0) {
                const displayCount = count > 99 ? '99+' : count;
                badge.textContent = displayCount;
                badge.style.display = 'flex';
                
                // Update mobile badge too
                if (mobileBadge) {
                    mobileBadge.textContent = displayCount;
                    mobileBadge.style.display = 'flex';
                }
            } else {
                badge.style.display = 'none';
                if (mobileBadge) {
                    mobileBadge.style.display = 'none';
                }
            }
        },

        renderNotifications(notifications) {
            const listContainer = document.getElementById('notificationList');
            
            if (notifications.length === 0) {
                listContainer.innerHTML = `
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash"></i>
                        <div>No notifications yet</div>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = notifications.map(notification => `
                <div class="notification-item ${!notification.is_read ? 'unread' : ''}" 
                     data-id="${notification.id}"
                     onclick="notificationBell.handleClick(${notification.id}, '${notification.action_url || ''}')">
                    <div class="notification-content">
                        <div class="notification-title">
                            <span class="notification-icon">${notification.icon || '🔔'}</span>
                            ${notification.title}
                        </div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${notification.time_ago}</div>
                    </div>
                </div>
            `).join('');
        },

        handleClick(notificationId, actionUrl) {
            // Mark as read
            fetch(`{{ route("user.notifications.read", "") }}/${notificationId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                // Update UI
                const item = document.querySelector(`[data-id="${notificationId}"]`);
                if (item) {
                    item.classList.remove('unread');
                }
                
                // Update badge count
                const badge = document.getElementById('notificationBadge');
                const currentCount = parseInt(badge.textContent) || 0;
                if (currentCount > 0) {
                    this.updateBadge(currentCount - 1);
                }
                
                // Navigate if action URL exists
                if (actionUrl) {
                    window.location.href = actionUrl;
                }
            });
        },

        markAllAsRead() {
            fetch('{{ route("user.notifications.read.all") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('unread');
                    });
                    this.updateBadge(0);
                }
            });
        },

        bindEvents() {
            document.getElementById('markAllReadBtn').addEventListener('click', (e) => {
                e.preventDefault();
                this.markAllAsRead();
            });
        },

        startPolling() {
            // Poll for new notifications every 30 seconds
            setInterval(() => {
                this.loadNotifications();
            }, 30000);
        }
    };

    // Initialize notification bell
    notificationBell.init();
    
    // Make it globally accessible
    window.notificationBell = notificationBell;
});
</script> 