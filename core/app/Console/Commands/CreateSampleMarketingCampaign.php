<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationTemplate;
use App\Constants\Status;

class CreateSampleMarketingCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flow:create-marketing-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample marketing campaign templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("=== Tạo Marketing Campaign Templates mẫu ===");
        
        // Danh sách marketing campaigns mẫu
        $marketingCampaigns = [
            'WELCOME_CAMPAIGN' => [
                'name' => 'Welcome New Users Campaign',
                'subject' => '🎉 Welcome to {{site_name}} - Your Journey Starts Here!',
                'description' => 'Chào mừng người dùng mới đăng ký',
                'body' => '
                    <div class="greeting">Hello {{user_name}},</div>
                    
                    <div class="highlight-box">
                        <h3>🎉 Welcome to the {{site_name}} Family!</h3>
                        <p>We\'re thrilled to have you join thousands of satisfied customers who trust us with their service needs.</p>
                    </div>
                    
                    <div class="content-section">
                        <h2>🚀 Get Started in 3 Easy Steps</h2>
                        
                        <div class="info-box">
                            <h3>Step 1: Complete Your Profile</h3>
                            <p>Add your details to get personalized service recommendations.</p>
                            <a href="{{profile_url}}" class="btn btn-primary">Complete Profile</a>
                        </div>
                        
                        <div class="info-box">
                            <h3>Step 2: Browse Our Services</h3>
                            <p>Discover hundreds of verified service providers in your area.</p>
                            <a href="{{services_url}}" class="btn btn-primary">Browse Services</a>
                        </div>
                        
                        <div class="info-box">
                            <h3>Step 3: Book Your First Appointment</h3>
                            <p>Choose your provider and schedule your appointment in minutes.</p>
                            <a href="{{book_url}}" class="btn btn-success">Book Now</a>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <h2>💎 Why Choose {{site_name}}?</h2>
                        <ul>
                            <li>✅ <strong>Verified Providers:</strong> All service providers are background-checked</li>
                            <li>⚡ <strong>Instant Booking:</strong> Get confirmed appointments in minutes</li>
                            <li>🛡️ <strong>Secure Platform:</strong> Your data and payments are protected</li>
                            <li>⭐ <strong>Quality Guarantee:</strong> Read reviews and ratings from real customers</li>
                            <li>📞 <strong>24/7 Support:</strong> Our team is here to help anytime</li>
                        </ul>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{get_started_url}}" class="btn btn-primary" style="font-size: 18px; padding: 15px 40px;">Start Exploring Services</a>
                    </div>
                    
                    <div class="success-box">
                        <h3>🎁 Special Welcome Offer</h3>
                        <p>Get <strong>10% OFF</strong> your first service booking! Use code: <strong>WELCOME10</strong></p>
                        <p><em>Valid for the next 30 days</em></p>
                    </div>
                    
                    <p>Need help getting started? Our friendly support team is ready to assist you every step of the way.</p>
                    
                    <p style="margin-top: 30px;">
                        <strong>Welcome aboard!</strong><br>
                        The {{site_name}} Team
                    </p>
                ',
                'criteria' => [
                    'user_type' => '',
                    'registered_after' => date('Y-m-d', strtotime('-7 days'))
                ]
            ],
            'MONTHLY_NEWSLETTER' => [
                'name' => 'Monthly Newsletter',
                'subject' => '📰 {{site_name}} Monthly Update - New Features & Success Stories',
                'description' => 'Bản tin hàng tháng cho tất cả người dùng',
                'body' => '
                    <div class="greeting">Hello {{user_name}},</div>
                    
                    <div class="highlight-box">
                        <h3>📰 Your Monthly {{site_name}} Update</h3>
                        <p>Discover what\'s new, trending, and exciting in our community this month!</p>
                    </div>
                    
                    <div class="content-section">
                        <h2>🆕 What\'s New This Month</h2>
                        
                        <div class="success-box">
                            <h3>🚀 Enhanced Appointment Booking</h3>
                            <p>Our new booking system is 50% faster with real-time availability and instant confirmations.</p>
                        </div>
                        
                        <div class="info-box">
                            <h3>📊 Improved User Dashboard</h3>
                            <p>Track your appointments, reviews, and service history with our redesigned dashboard.</p>
                            <a href="{{dashboard_url}}" class="btn btn-primary">Visit Dashboard</a>
                        </div>
                        
                        <div class="info-box">
                            <h3>⭐ Advanced Rating System</h3>
                            <p>More detailed reviews help you make better choices and help providers improve their services.</p>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <h2>📈 Community Growth</h2>
                        <p>This month our community achieved amazing milestones:</p>
                        
                        <div class="appointment-details">
                            <h3>📊 Monthly Statistics</h3>
                            <div class="detail-row">
                                <span class="detail-label">✅ Appointments Completed:</span>
                                <span class="detail-value">{{monthly_appointments}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">🏢 New Service Providers:</span>
                                <span class="detail-value">{{new_companies}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">👥 New Members Joined:</span>
                                <span class="detail-value">{{new_users}}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">⭐ Average Rating:</span>
                                <span class="detail-value">4.8/5.0</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <h2>🌟 Success Stories</h2>
                        <div class="success-box">
                            <p><em>"{{site_name}} helped me find the perfect contractor for my home renovation. The whole process was seamless!"</em></p>
                            <p><strong>- Sarah M., Happy Customer</strong></p>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <h2>💡 Tips & Tricks</h2>
                        <ul>
                            <li>📱 Download our mobile app for booking on-the-go</li>
                            <li>🔔 Enable notifications to never miss appointment updates</li>
                            <li>💬 Leave reviews to help other community members</li>
                            <li>🎯 Use filters to find exactly what you\'re looking for</li>
                        </ul>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{browse_url}}" class="btn btn-primary">Explore New Services</a>
                        <a href="{{app_download_url}}" class="btn btn-success">Download App</a>
                    </div>
                    
                    <p>Thank you for being an amazing part of the {{site_name}} community. Here\'s to another month of great connections and excellent service!</p>
                    
                    <p style="margin-top: 30px;">
                        <strong>Best regards,</strong><br>
                        The {{site_name}} Team
                    </p>
                ',
                'criteria' => [
                    'user_type' => '',
                    'has_appointments' => true
                ]
            ],
            'COMPANY_PROMOTION' => [
                'name' => 'Promote Your Company',
                'subject' => '🚀 Boost Your Business Revenue with {{site_name}} Premium',
                'description' => 'Khuyến khích công ty nâng cấp dịch vụ',
                'body' => '
                    <div class="greeting">Dear {{user_name}},</div>
                    
                    <div class="highlight-box">
                        <h3>🚀 Ready to Take Your Business to the Next Level?</h3>
                        <p>Join thousands of successful service providers who have grown their business with {{site_name}} Premium.</p>
                    </div>
                    
                    <div class="content-section">
                        <h2>💰 Premium Benefits That Drive Results</h2>
                        
                        <div class="success-box">
                            <h3>🥇 Priority Placement</h3>
                            <p><strong>3x more visibility</strong> with top search result placement and featured listings.</p>
                        </div>
                        
                        <div class="info-box">
                            <h3>📊 Advanced Analytics Dashboard</h3>
                            <ul>
                                <li>Track customer engagement and booking patterns</li>
                                <li>Monitor competitor performance</li>
                                <li>Optimize your pricing and availability</li>
                                <li>Get detailed revenue reports</li>
                            </ul>
                        </div>
                        
                        <div class="info-box">
                            <h3>🎨 Custom Branding & Profile</h3>
                            <ul>
                                <li>Upload your company logo and brand colors</li>
                                <li>Create custom service packages</li>
                                <li>Add photo galleries and portfolio</li>
                                <li>Showcase customer testimonials</li>
                            </ul>
                        </div>
                        
                        <div class="info-box">
                            <h3>🎯 Marketing & Promotion Tools</h3>
                            <ul>
                                <li>Send targeted promotions to past customers</li>
                                <li>Create seasonal discount campaigns</li>
                                <li>Access to premium badge and verification</li>
                                <li>Social media integration tools</li>
                            </ul>
                        </div>
                        
                        <div class="info-box">
                            <h3>🏆 VIP Customer Support</h3>
                            <ul>
                                <li>Dedicated account manager</li>
                                <li>Priority technical support</li>
                                <li>Monthly business consultation calls</li>
                                <li>24/7 emergency support hotline</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="content-section">
                        <h2>📈 Real Results from Premium Members</h2>
                        
                        <div class="appointment-details">
                            <h3>📊 Average Premium Member Benefits</h3>
                            <div class="detail-row">
                                <span class="detail-label">📈 Booking Increase:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">+180%</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">💰 Revenue Growth:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">+220%</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">⭐ Customer Rating:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">4.9/5.0</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">🔄 Repeat Customers:</span>
                                <span class="detail-value" style="color: #27ae60; font-weight: bold;">+150%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="warning-box" style="background: linear-gradient(135deg, #ff7675 0%, #fd79a8 100%); color: white; border: none;">
                        <h3>⏰ Limited Time Offer - Save 40%!</h3>
                        <p style="font-size: 18px;"><strong>Get your first 3 months for just $59/month</strong></p>
                        <p style="font-size: 14px;"><em>Regular price: $99/month. Offer valid until the end of this month!</em></p>
                        <p style="margin-top: 15px;">
                            Use code: <strong style="font-size: 20px; background: rgba(255,255,255,0.2); padding: 5px 10px; border-radius: 5px;">GROW40</strong>
                        </p>
                    </div>
                    
                    <div style="text-align: center; margin: 30px 0;">
                        <a href="{{upgrade_url}}" class="btn btn-success" style="font-size: 18px; padding: 15px 40px;">🚀 Upgrade to Premium Now</a>
                        <br><br>
                        <a href="{{demo_url}}" class="btn btn-primary">📺 Watch Demo</a>
                        <a href="{{consultation_url}}" class="btn">📞 Free Consultation</a>
                    </div>
                    
                    <div class="info-box">
                        <h3>💡 Still Have Questions?</h3>
                        <p>Our business development team is here to help you choose the right plan and maximize your growth potential.</p>
                        <p>📞 Call us: <strong>{{support_phone}}</strong><br>
                        📧 Email: <strong>{{business_email}}</strong></p>
                    </div>
                    
                    <p>Don\'t let your competitors get ahead. Join the Premium community and start growing your business today!</p>
                    
                    <p style="margin-top: 30px;">
                        <strong>To your success,</strong><br>
                        The {{site_name}} Business Team
                    </p>
                ',
                'criteria' => [
                    'user_type' => 'companies',
                    'registered_after' => date('Y-m-d', strtotime('-30 days'))
                ]
            ]
        ];

        $created = 0;

        foreach ($marketingCampaigns as $act => $info) {
            $existing = NotificationTemplate::where('act', $act)->first();
            
            if (!$existing) {
                $template = NotificationTemplate::create([
                    'act' => $act,
                    'name' => $info['name'],
                    'subject' => $info['subject'],
                    'email_body' => $info['body'],
                    'flow_type' => 'marketing',
                    'flow_description' => $info['description'],
                    'priority' => 'normal',
                    'recipient_criteria' => $info['criteria'],
                    'email_status' => Status::ENABLE,
                    'sms_status' => Status::DISABLE,
                    'push_status' => Status::DISABLE,
                    'shortcodes' => json_encode([
                        'site_name', 'user_name', 'user_email', 
                        'month', 'year', 'monthly_appointments', 
                        'new_companies', 'new_users'
                    ])
                ]);
                
                $this->line("✓ Đã tạo marketing campaign: {$template->name}");
                $created++;
            } else {
                $this->line("- Đã tồn tại: {$info['name']}");
            }
        }

        $this->info("\n=== Kết quả ===");
        $this->line("Marketing campaigns đã tạo: {$created}");

        // Cập nhật thống kê
        $autoFlowCount = NotificationTemplate::where('flow_type', 'auto')->count();
        $marketingFlowCount = NotificationTemplate::where('flow_type', 'marketing')->count();
        $systemFlowCount = NotificationTemplate::where('flow_type', 'system')
                                                ->orWhereNull('flow_type')
                                                ->count();

        $this->info("\n=== Thống kê Flow sau khi tạo ===");
        $this->line("Auto Flow templates: {$autoFlowCount}");
        $this->line("Marketing Flow templates: {$marketingFlowCount}");
        $this->line("System Flow templates: {$systemFlowCount}");

        $this->info("\n✅ Hoàn thành tạo Marketing Campaign Templates mẫu!");
        
        return 0;
    }
} 