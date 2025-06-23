<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationTemplate;
use App\Constants\Status;

class InitializeAppointmentFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flow:init-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize appointment email flow templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Khởi tạo Appointment Email Flow ===");
        
        // Danh sách các template appointments cần cập nhật
        $appointmentTemplates = [
            'NEW_APPOINTMENT' => [
                'name' => 'New Appointment Notification',
                'description' => 'Tự động gửi email khi có appointment mới được tạo'
            ],
            'APPOINTMENT_CONFIRMED' => [
                'name' => 'Appointment Confirmed',
                'description' => 'Tự động gửi email khi appointment được xác nhận'
            ],
            'APPOINTMENT_COMPLETED' => [
                'name' => 'Appointment Completed',
                'description' => 'Tự động gửi email khi appointment hoàn thành'
            ],
            'APPOINTMENT_CANCELED' => [
                'name' => 'Appointment Canceled',
                'description' => 'Tự động gửi email khi appointment bị hủy'
            ]
        ];

        $updated = 0;
        $created = 0;

        foreach ($appointmentTemplates as $act => $info) {
            $template = NotificationTemplate::where('act', $act)->first();
            
            if ($template) {
                // Cập nhật template hiện có
                $template->update([
                    'flow_type' => 'auto',
                    'flow_description' => $info['description'],
                    'priority' => 'high'
                ]);
                
                $this->line("✓ Đã cập nhật template: {$template->name}");
                $updated++;
            } else {
                // Tạo template mới nếu chưa có
                $emailBodies = [
                    'NEW_APPOINTMENT' => '
                        <div class="greeting">Hello {{user_name}},</div>
                        
                        <div class="success-box">
                            <h3>🎉 Your Appointment Has Been Successfully Created!</h3>
                            <p>We have received your appointment request and are excited to serve you.</p>
                        </div>
                        
                        <div class="appointment-details">
                            <h3>📅 Appointment Details</h3>
                            <div class="detail-row">
                                <span class="detail-label">Appointment ID:</span>
                                <span class="detail-value">#{{appointment_id}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">{{appointment_date}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Time:</span>
                                <span class="detail-value">{{appointment_time}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Service Provider:</span>
                                <span class="detail-value">{{company_name}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value" style="color: #f39c12; font-weight: bold;">⏳ Pending Confirmation</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <h3>📋 What happens next?</h3>
                            <ul>
                                <li>The service provider will review your request</li>
                                <li>You will receive a confirmation email within 24 hours</li>
                                <li>Please prepare any required documents or information</li>
                            </ul>
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{appointment_url}}" class="btn btn-primary">View Appointment Details</a>
                            <a href="{{contact_url}}" class="btn">Contact Support</a>
                        </div>
                        
                        <p>If you have any questions or need to make changes, please don\'t hesitate to contact us.</p>
                        
                        <p style="margin-top: 30px;">
                            <strong>Best regards,</strong><br>
                            The {{site_name}} Team
                        </p>
                    ',
                    'APPOINTMENT_CONFIRMED' => '
                        <div class="greeting">Dear {{user_name}},</div>
                        
                        <div class="success-box">
                            <h3>✅ Your Appointment Has Been Confirmed!</h3>
                            <p>Great news! {{company_name}} has confirmed your appointment.</p>
                        </div>
                        
                        <div class="appointment-details">
                            <h3>📅 Confirmed Appointment Details</h3>
                            <div class="detail-row">
                                <span class="detail-label">Appointment ID:</span>
                                <span class="detail-value">#{{appointment_id}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">{{appointment_date}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Time:</span>
                                <span class="detail-value">{{appointment_time}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Service Provider:</span>
                                <span class="detail-value">{{company_name}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Location:</span>
                                <span class="detail-value">{{company_address}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">✅ Confirmed</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <h3>📝 Important Reminders</h3>
                            <ul>
                                <li>Please arrive 10-15 minutes early</li>
                                <li>Bring a valid ID and any required documents</li>
                                <li>Contact the service provider if you need to reschedule</li>
                                <li>Check traffic conditions before your departure</li>
                            </ul>
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{appointment_url}}" class="btn btn-success">View Full Details</a>
                            <a href="{{reschedule_url}}" class="btn">Reschedule</a>
                        </div>
                        
                        <p>We look forward to serving you! If you have any questions, please contact us immediately.</p>
                        
                        <p style="margin-top: 30px;">
                            <strong>Best regards,</strong><br>
                            The {{site_name}} Team
                        </p>
                    ',
                    'APPOINTMENT_COMPLETED' => '
                        <div class="greeting">Hello {{user_name}},</div>
                        
                        <div class="success-box">
                            <h3>🎊 Thank You for Choosing {{site_name}}!</h3>
                            <p>Your appointment with {{company_name}} has been successfully completed.</p>
                        </div>
                        
                        <div class="appointment-details">
                            <h3>📋 Completed Appointment Summary</h3>
                            <div class="detail-row">
                                <span class="detail-label">Appointment ID:</span>
                                <span class="detail-value">#{{appointment_id}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Completed Date:</span>
                                <span class="detail-value">{{appointment_date}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Service Provider:</span>
                                <span class="detail-value">{{company_name}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">✅ Completed</span>
                            </div>
                        </div>
                        
                        <div class="highlight-box">
                            <h3>⭐ How was your experience?</h3>
                            <p>Your feedback helps us improve our services and helps other users make informed decisions.</p>
                            <a href="{{review_url}}" class="btn" style="background: white; color: #333; margin-top: 15px;">Leave a Review</a>
                        </div>
                        
                        <div class="info-box">
                            <h3>🚀 What\'s Next?</h3>
                            <ul>
                                <li>Rate your experience with {{company_name}}</li>
                                <li>Share your feedback to help other users</li>
                                <li>Book your next appointment if needed</li>
                                <li>Refer friends and family to {{site_name}}</li>
                            </ul>
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{book_again_url}}" class="btn btn-primary">Book Again</a>
                            <a href="{{browse_services_url}}" class="btn">Browse Services</a>
                        </div>
                        
                        <p>Thank you for trusting {{site_name}} with your service needs. We hope to serve you again soon!</p>
                        
                        <p style="margin-top: 30px;">
                            <strong>With appreciation,</strong><br>
                            The {{site_name}} Team
                        </p>
                    ',
                    'APPOINTMENT_CANCELED' => '
                        <div class="greeting">Dear {{user_name}},</div>
                        
                        <div class="warning-box">
                            <h3>⚠️ Your Appointment Has Been Canceled</h3>
                            <p>We regret to inform you that your appointment has been canceled.</p>
                        </div>
                        
                        <div class="appointment-details">
                            <h3>📅 Canceled Appointment Details</h3>
                            <div class="detail-row">
                                <span class="detail-label">Appointment ID:</span>
                                <span class="detail-value">#{{appointment_id}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Original Date:</span>
                                <span class="detail-value">{{appointment_date}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Original Time:</span>
                                <span class="detail-value">{{appointment_time}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Service Provider:</span>
                                <span class="detail-value">{{company_name}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Cancellation Reason:</span>
                                <span class="detail-value">{{cancellation_reason}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value" style="color: #e74c3c; font-weight: bold;">❌ Canceled</span>
                            </div>
                        </div>
                        
                        <div class="info-box">
                            <h3>💡 What you can do now:</h3>
                            <ul>
                                <li>Book a new appointment with the same or different provider</li>
                                <li>Contact {{company_name}} directly for rescheduling options</li>
                                <li>Browse our wide selection of alternative service providers</li>
                                <li>Contact our support team if you need assistance</li>
                            </ul>
                        </div>
                        
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{book_new_url}}" class="btn btn-primary">Book New Appointment</a>
                            <a href="{{contact_company_url}}" class="btn">Contact Provider</a>
                        </div>
                        
                        <p>We apologize for any inconvenience caused. Our team is here to help you find alternative solutions.</p>
                        
                        <p style="margin-top: 30px;">
                            <strong>Best regards,</strong><br>
                            The {{site_name}} Team
                        </p>
                    '
                ];
                
                $template = NotificationTemplate::create([
                    'act' => $act,
                    'name' => $info['name'],
                    'subject' => "{{site_name}} - " . $info['name'],
                    'email_body' => $emailBodies[$act],
                    'flow_type' => 'auto',
                    'flow_description' => $info['description'],
                    'priority' => 'high',
                    'email_status' => Status::ENABLE,
                    'sms_status' => Status::DISABLE,
                    'push_status' => Status::DISABLE,
                    'shortcodes' => json_encode([
                        'site_name', 'user_name', 'user_email', 
                        'appointment_id', 'appointment_date', 'appointment_time', 
                        'company_name'
                    ])
                ]);
                
                $this->line("✓ Đã tạo template mới: {$template->name}");
                $created++;
            }
        }

        $this->info("\n=== Kết quả ===");
        $this->line("Templates đã cập nhật: {$updated}");
        $this->line("Templates mới tạo: {$created}");
        $this->line("Tổng cộng: " . ($updated + $created) . " templates");

        // Kiểm tra xem có template nào đã được tích hợp auto flow chưa
        $autoFlowCount = NotificationTemplate::where('flow_type', 'auto')->count();
        $marketingFlowCount = NotificationTemplate::where('flow_type', 'marketing')->count();
        $systemFlowCount = NotificationTemplate::where('flow_type', 'system')
                                                ->orWhereNull('flow_type')
                                                ->count();

        $this->info("\n=== Thống kê Flow hiện tại ===");
        $this->line("Auto Flow templates: {$autoFlowCount}");
        $this->line("Marketing Flow templates: {$marketingFlowCount}");
        $this->line("System Flow templates: {$systemFlowCount}");

        $this->info("\n✅ Hoàn thành khởi tạo Appointment Email Flow!");
        
        return 0;
    }
} 