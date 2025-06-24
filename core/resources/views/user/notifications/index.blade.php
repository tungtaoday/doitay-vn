@extends('Template::layouts.frontend')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="notification-header-section mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="page-title">
                            <i class="fas fa-bell text-primary me-2"></i>
                            Thông Báo
                        </h2>
                        <p class="text-muted mb-0">Quản lý tất cả thông báo của bạn</p>
                    </div>
                    <div class="header-actions">
                        <button class="btn btn-outline-primary btn-sm" id="markAllReadBtn">
                            <i class="fas fa-check-double me-1"></i>
                            Đánh dấu tất cả đã đọc
                        </button>
                        <button class="btn btn-outline-danger btn-sm ms-2" id="deleteAllReadBtn">
                            <i class="fas fa-trash me-1"></i>
                            Xóa đã đọc
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card total">
                        <div class="stats-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="stats-content">
                            <h3>{{ $notifications->total() }}</h3>
                            <p>Tổng thông báo</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card unread">
                        <div class="stats-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stats-content">
                            <h3>{{ $notifications->where('is_read', false)->count() }}</h3>
                            <p>Chưa đọc</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card important">
                        <div class="stats-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-content">
                            <h3>{{ $notifications->where('is_important', true)->count() }}</h3>
                            <p>Quan trọng</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card today">
                        <div class="stats-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="stats-content">
                            <h3>{{ $notifications->where('created_at', '>=', today())->count() }}</h3>
                            <p>Hôm nay</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="notification-main-card">
                <!-- Filter Tabs -->
                <div class="filter-section">
                    <div class="nav nav-pills filter-tabs" id="notificationFilters" role="tablist">
                        <button class="nav-link active" id="all-tab" data-filter="all">
                            <i class="fas fa-list me-2"></i>
                            Tất cả
                        </button>
                        <button class="nav-link" id="unread-tab" data-filter="unread">
                            <i class="fas fa-envelope me-2"></i>
                            Chưa đọc
                        </button>
                        <button class="nav-link" id="appointment-tab" data-filter="appointment">
                            <i class="fas fa-calendar me-2"></i>
                            Cuộc hẹn
                        </button>
                        <button class="nav-link" id="lead-tab" data-filter="lead">
                            <i class="fas fa-briefcase me-2"></i>
                            Công việc
                        </button>
                        <button class="nav-link" id="campaign-tab" data-filter="campaign">
                            <i class="fas fa-bullhorn me-2"></i>
                            Khuyến mãi
                        </button>
                        <button class="nav-link" id="system-tab" data-filter="system">
                            <i class="fas fa-cog me-2"></i>
                            Hệ thống
                        </button>
                    </div>
                </div>

                <!-- Notification List -->
                <div class="notification-list-container">
                    @if($notifications->count() > 0)
                        <div id="notificationContainer">
                            @foreach($notifications as $notification)
                                <div class="notification-item {{ !$notification->is_read ? 'unread' : '' }} {{ $notification->is_important ? 'important' : '' }}" 
                                     data-id="{{ $notification->id }}" 
                                     data-type="{{ $notification->type }}"
                                     data-read="{{ $notification->is_read ? 'true' : 'false' }}">
                                    
                                    <div class="notification-content">
                                        <!-- Notification Icon -->
                                        <div class="notification-icon-wrapper">
                                            <div class="notification-icon {{ $notification->type }}">
                                                @switch($notification->type)
                                                    @case('appointment')
                                                        <i class="fas fa-calendar-check"></i>
                                                        @break
                                                    @case('lead')
                                                        <i class="fas fa-briefcase"></i>
                                                        @break
                                                    @case('campaign')
                                                        <i class="fas fa-bullhorn"></i>
                                                        @break
                                                    @case('system')
                                                        <i class="fas fa-cog"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-bell"></i>
                                                @endswitch
                                            </div>
                                        </div>

                                        <!-- Notification Content -->
                                        <div class="notification-body">
                                            <div class="notification-header">
                                                <h5 class="notification-title">
                                                    {{ $notification->title }}
                                                    @if($notification->is_important)
                                                        <span class="badge badge-important">
                                                            <i class="fas fa-star me-1"></i>Quan trọng
                                                        </span>
                                                    @endif
                                                    @if($notification->priority === 'high')
                                                        <span class="badge badge-priority">
                                                            <i class="fas fa-exclamation me-1"></i>Ưu tiên cao
                                                        </span>
                                                    @endif
                                                </h5>
                                                <div class="notification-time">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </div>
                                            </div>

                                            <div class="notification-message">
                                                {{ $notification->message }}
                                                
                                                @if($notification->type === 'lead' && isset($notification->data['lead_budget']))
                                                    <div class="notification-details mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $notification->data['lead_location'] ?? 'N/A' }}
                                                            @if($notification->data['lead_budget'])
                                                                &nbsp;&nbsp;
                                                                <i class="fas fa-dollar-sign me-1"></i>{{ $notification->data['lead_budget'] }}
                                                            @endif
                                                            @if($notification->data['category_name'])
                                                                &nbsp;&nbsp;
                                                                <i class="fas fa-tag me-1"></i>{{ $notification->data['category_name'] }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="notification-meta">
                                                <span class="notification-type-badge {{ $notification->type }}">
                                                    @switch($notification->type)
                                                        @case('appointment')
                                                            <i class="fas fa-calendar me-1"></i>Cuộc hẹn
                                                            @break
                                                        @case('lead')
                                                            <i class="fas fa-briefcase me-1"></i>Công việc
                                                            @break
                                                        @case('campaign')
                                                            <i class="fas fa-bullhorn me-1"></i>Khuyến mãi
                                                            @break
                                                        @case('system')
                                                            <i class="fas fa-cog me-1"></i>Hệ thống
                                                            @break
                                                        @default
                                                            <i class="fas fa-bell me-1"></i>Thông báo
                                                    @endswitch
                                                </span>
                                                @if($notification->expires_at)
                                                    <span class="notification-expires">
                                                        <i class="fas fa-hourglass-half me-1"></i>
                                                        Hết hạn: {{ $notification->expires_at->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Notification Actions -->
                                        <div class="notification-actions">
                                            @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-primary mark-read-btn" 
                                                        data-id="{{ $notification->id }}" 
                                                        title="Đánh dấu đã đọc">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary mark-unread-btn" 
                                                        data-id="{{ $notification->id }}" 
                                                        title="Đánh dấu chưa đọc">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif
                                            
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" 
                                                   class="btn btn-sm btn-info notification-action-link" 
                                                   data-id="{{ $notification->id }}"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-danger delete-btn" 
                                                    data-id="{{ $notification->id }}" 
                                                    title="Xóa thông báo">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <h4>Chưa có thông báo nào</h4>
                            <p>Khi bạn có thông báo mới, nó sẽ xuất hiện tại đây.</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="pagination-wrapper">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Grab-inspired Styles -->
<style>
:root {
    --primary-color: #00b14f;
    --secondary-color: #00a143;
    --success-color: #00b14f;
    --danger-color: #ff5722;
    --warning-color: #ff9800;
    --info-color: #2196f3;
    --light-bg: #f5f5f5;
    --border-color: #e0e0e0;
    --text-color: #212121;
    --text-muted: #757575;
    --shadow: 0 1px 3px rgba(0,0,0,0.12);
    --shadow-hover: 0 2px 8px rgba(0,0,0,0.15);
    --border-radius: 8px;
    --transition: all 0.2s ease;
}

.container-fluid {
    max-width: 1200px;
    margin: 0 auto;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.25rem;
}

.notification-header-section {
    background: white;
    color: var(--text-color);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.notification-header-section .page-title {
    color: var(--text-color);
}

.notification-header-section .text-muted {
    color: var(--text-muted) !important;
}

.header-actions .btn {
    background: white;
    border: 1px solid var(--border-color);
    color: var(--text-color);
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

.header-actions .btn:hover {
    background: var(--light-bg);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Stats Cards - Compact Grab Style */
.stats-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 1rem;
    display: flex;
    align-items: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
    margin-bottom: 0.75rem;
    border: 1px solid var(--border-color);
}

.stats-card:hover {
    box-shadow: var(--shadow-hover);
    border-color: var(--primary-color);
}

.stats-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-right: 0.75rem;
    background: var(--light-bg);
    color: var(--text-color);
}

.stats-card.total .stats-icon {
    background: rgba(0, 177, 79, 0.1);
    color: var(--primary-color);
}

.stats-card.unread .stats-icon {
    background: rgba(33, 150, 243, 0.1);
    color: var(--info-color);
}

.stats-card.important .stats-icon {
    background: rgba(255, 87, 34, 0.1);
    color: var(--danger-color);
}

.stats-card.today .stats-icon {
    background: rgba(255, 152, 0, 0.1);
    color: var(--warning-color);
}

.stats-content h3 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    color: var(--text-color);
}

.stats-content p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.8rem;
}

/* Main Card */
.notification-main-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
}

/* Filter Section - Clean Grab Style */
.filter-section {
    background: white;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.filter-tabs {
    background: var(--light-bg);
    border-radius: var(--border-radius);
    padding: 0.25rem;
    border: 1px solid var(--border-color);
}

.filter-tabs .nav-link {
    border: none;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    margin: 0 0.125rem;
    color: var(--text-muted);
    font-weight: 500;
    font-size: 0.875rem;
    transition: var(--transition);
}

.filter-tabs .nav-link:hover {
    background: rgba(0, 177, 79, 0.1);
    color: var(--primary-color);
}

.filter-tabs .nav-link.active {
    background: var(--primary-color);
    color: white;
    box-shadow: none;
}

/* Notification List - Compact Style */
.notification-list-container {
    max-height: 500px;
    overflow-y: auto;
}

.notification-item {
    border-bottom: 1px solid var(--border-color);
    transition: var(--transition);
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: var(--light-bg);
}

.notification-item.unread {
    background: rgba(0, 177, 79, 0.02);
    border-left: 3px solid var(--primary-color);
}

.notification-item.important {
    border-left: 3px solid var(--danger-color);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 1rem;
    width: 6px;
    height: 6px;
    background: var(--primary-color);
    border-radius: 50%;
}

.notification-content {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    gap: 0.75rem;
}

.notification-icon-wrapper {
    flex-shrink: 0;
}

.notification-icon {
    width: 32px;
    height: 32px;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    background: var(--light-bg);
    color: var(--text-color);
}

.notification-icon.appointment {
    background: rgba(0, 177, 79, 0.1);
    color: var(--success-color);
}

.notification-icon.campaign {
    background: rgba(255, 152, 0, 0.1);
    color: var(--warning-color);
}

.notification-icon.system {
    background: rgba(33, 150, 243, 0.1);
    color: var(--info-color);
}

.notification-icon:not(.appointment):not(.campaign):not(.system) {
    background: rgba(0, 177, 79, 0.1);
    color: var(--primary-color);
}

.notification-body {
    flex: 1;
}

.notification-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.25rem;
}

.notification-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-color);
    margin: 0;
    flex: 1;
    line-height: 1.4;
}

.notification-time {
    color: var(--text-muted);
    font-size: 0.75rem;
    margin-left: 0.75rem;
    white-space: nowrap;
}

.notification-message {
    color: var(--text-muted);
    line-height: 1.4;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.notification-type-badge {
    padding: 0.125rem 0.5rem;
    border-radius: var(--border-radius);
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--text-muted);
    background: var(--light-bg);
    border: 1px solid var(--border-color);
}

.notification-type-badge.appointment {
    background: rgba(0, 177, 79, 0.1);
    color: var(--success-color);
    border-color: rgba(0, 177, 79, 0.2);
}

.notification-type-badge.campaign {
    background: rgba(255, 152, 0, 0.1);
    color: var(--warning-color);
    border-color: rgba(255, 152, 0, 0.2);
}

.notification-type-badge.system {
    background: rgba(33, 150, 243, 0.1);
    color: var(--info-color);
    border-color: rgba(33, 150, 243, 0.2);
}

.notification-type-badge:not(.appointment):not(.campaign):not(.system) {
    background: rgba(0, 177, 79, 0.1);
    color: var(--primary-color);
    border-color: rgba(0, 177, 79, 0.2);
}

.notification-expires {
    color: var(--text-muted);
    font-size: 0.7rem;
}

.badge-important {
    background: rgba(255, 87, 34, 0.1);
    color: var(--danger-color);
    padding: 0.125rem 0.375rem;
    border-radius: var(--border-radius);
    font-size: 0.65rem;
    margin-left: 0.375rem;
    border: 1px solid rgba(255, 87, 34, 0.2);
}

.badge-priority {
    background: rgba(255, 152, 0, 0.1);
    color: var(--warning-color);
    padding: 0.125rem 0.375rem;
    border-radius: var(--border-radius);
    font-size: 0.65rem;
    margin-left: 0.375rem;
    border: 1px solid rgba(255, 152, 0, 0.2);
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex-shrink: 0;
    align-items: flex-start;
}

.notification-actions .btn {
    width: 28px;
    height: 28px;
    border-radius: var(--border-radius);
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    font-size: 0.75rem;
}

.notification-actions .btn:hover {
    transform: none;
    opacity: 0.8;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h4 {
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

/* Pagination */
.pagination-wrapper {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .notification-header-section {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .header-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .header-actions .btn {
        width: 100%;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .filter-tabs {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-tabs .nav-link {
        text-align: center;
        margin: 0;
    }
    
    .notification-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .notification-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .notification-time {
        margin-left: 0;
    }
    
    .notification-actions {
        flex-direction: row;
        justify-content: flex-end;
    }
    
    .notification-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* Smooth Animations */
.notification-item {
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom Scrollbar */
.notification-list-container::-webkit-scrollbar {
    width: 6px;
}

.notification-list-container::-webkit-scrollbar-track {
    background: var(--light-bg);
}

.notification-list-container::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.notification-list-container::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}
</style>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show loading state
    function showLoading(element) {
        element.style.opacity = '0.6';
        element.style.pointerEvents = 'none';
    }
    
    function hideLoading(element) {
        element.style.opacity = '1';
        element.style.pointerEvents = 'auto';
    }
    
    // Show toast notification
    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification ${type}`;
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>
            ${message}
        `;
        
        // Add toast styles
        const style = document.createElement('style');
        style.textContent = `
            .toast-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 9999;
                animation: slideInRight 0.3s ease;
            }
            .toast-notification.success {
                background: linear-gradient(135deg, #28a745, #20c997);
            }
            .toast-notification.error {
                background: linear-gradient(135deg, #dc3545, #e74c3c);
            }
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideInRight 0.3s ease reverse';
            setTimeout(() => {
                document.body.removeChild(toast);
                document.head.removeChild(style);
            }, 300);
        }, 3000);
    }

    // Event listeners
    document.addEventListener('click', function(e) {
        if (e.target.closest('.mark-read-btn')) {
            const btn = e.target.closest('.mark-read-btn');
            const id = btn.dataset.id;
            const item = document.querySelector(`[data-id="${id}"]`);
            showLoading(item);
            markAsRead(id);
        }
        
        if (e.target.closest('.mark-unread-btn')) {
            const btn = e.target.closest('.mark-unread-btn');
            const id = btn.dataset.id;
            const item = document.querySelector(`[data-id="${id}"]`);
            showLoading(item);
            markAsUnread(id);
        }
        
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            const id = btn.dataset.id;
            const item = document.querySelector(`[data-id="${id}"]`);
            
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này không?')) {
                showLoading(item);
                deleteNotification(id);
            }
        }

        // Handle notification action link clicks - mark as read before redirect
        if (e.target.closest('.notification-action-link')) {
            e.preventDefault();
            const link = e.target.closest('.notification-action-link');
            const id = link.dataset.id;
            const url = link.href;
            const item = document.querySelector(`[data-id="${id}"]`);
            
            // Mark as read if unread, then redirect
            if (item.dataset.read === 'false') {
                markAsReadAndRedirect(id, url);
            } else {
                // Already read, just redirect
                window.location.href = url;
            }
        }
    });

    // Mark all as read
    document.getElementById('markAllReadBtn').addEventListener('click', function() {
        if (confirm('Đánh dấu tất cả thông báo là đã đọc?')) {
            showLoading(document.getElementById('notificationContainer'));
            markAllAsRead();
        }
    });

    // Delete all read
    document.getElementById('deleteAllReadBtn').addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn xóa tất cả thông báo đã đọc không?')) {
            showLoading(document.getElementById('notificationContainer'));
            deleteAllRead();
        }
    });

    // Filter tabs
    document.querySelectorAll('.filter-tabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.filter-tabs .nav-link').forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            filterNotifications(filter);
        });
    });

    // Functions
    function markAsRead(id) {
        fetch(`{{ route('user.notifications.read', '') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            const item = document.querySelector(`[data-id="${id}"]`);
            hideLoading(item);
            
            if (data.success) {
                item.classList.remove('unread');
                item.dataset.read = 'true';
                
                const btn = item.querySelector('.mark-read-btn');
                if (btn) {
                    btn.className = 'btn btn-sm btn-secondary mark-unread-btn';
                    btn.innerHTML = '<i class="fas fa-undo"></i>';
                    btn.title = 'Đánh dấu chưa đọc';
                }
                
                showToast('Đã đánh dấu thông báo là đã đọc');
            } else {
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        }).catch(error => {
            hideLoading(document.querySelector(`[data-id="${id}"]`));
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }

    function markAsReadAndRedirect(id, url) {
        fetch(`{{ route('user.notifications.read', '') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            if (data.success) {
                // Successfully marked as read, now redirect
                window.location.href = url;
            } else {
                // Error marking as read, but still redirect
                showToast('Không thể đánh dấu đã đọc, nhưng vẫn chuyển trang', 'error');
                setTimeout(() => {
                    window.location.href = url;
                }, 1000);
            }
        }).catch(error => {
            // Error, but still redirect
            showToast('Có lỗi xảy ra, nhưng vẫn chuyển trang', 'error');
            setTimeout(() => {
                window.location.href = url;
            }, 1000);
        });
    }

    function markAsUnread(id) {
        fetch(`{{ route('user.notifications.unread', '') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            const item = document.querySelector(`[data-id="${id}"]`);
            hideLoading(item);
            
            if (data.success) {
                item.classList.add('unread');
                item.dataset.read = 'false';
                
                const btn = item.querySelector('.mark-unread-btn');
                if (btn) {
                    btn.className = 'btn btn-sm btn-primary mark-read-btn';
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.title = 'Đánh dấu đã đọc';
                }
                
                showToast('Đã đánh dấu thông báo là chưa đọc');
            } else {
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        }).catch(error => {
            hideLoading(document.querySelector(`[data-id="${id}"]`));
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }

    function markAllAsRead() {
        fetch('{{ route('user.notifications.read.all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            hideLoading(document.getElementById('notificationContainer'));
            
            if (data.success) {
                showToast('Đã đánh dấu tất cả thông báo là đã đọc');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        }).catch(error => {
            hideLoading(document.getElementById('notificationContainer'));
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }

    function deleteNotification(id) {
        fetch(`{{ route('user.notifications.delete', '') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            const item = document.querySelector(`[data-id="${id}"]`);
            
            if (data.success) {
                item.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    item.remove();
                }, 300);
                showToast('Đã xóa thông báo');
            } else {
                hideLoading(item);
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        }).catch(error => {
            hideLoading(document.querySelector(`[data-id="${id}"]`));
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }

    function deleteAllRead() {
        fetch('{{ route('user.notifications.delete.all.read') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json()).then(data => {
            hideLoading(document.getElementById('notificationContainer'));
            
            if (data.success) {
                showToast('Đã xóa tất cả thông báo đã đọc');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        }).catch(error => {
            hideLoading(document.getElementById('notificationContainer'));
            showToast('Có lỗi xảy ra, vui lòng thử lại', 'error');
        });
    }

    function filterNotifications(filter) {
        const items = document.querySelectorAll('.notification-item');
        
        items.forEach(item => {
            item.style.display = 'block';
            
            switch(filter) {
                case 'unread':
                    if (item.dataset.read === 'true') {
                        item.style.display = 'none';
                    }
                    break;
                case 'appointment':
                case 'campaign':
                case 'system':
                    if (item.dataset.type !== filter) {
                        item.style.display = 'none';
                    }
                    break;
            }
        });
    }
});

// Add slide out animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-100%);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection 