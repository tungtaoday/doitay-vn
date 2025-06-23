<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use App\Models\User;
use App\Models\Company;
use App\Models\Appointment;
use App\Constants\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class EmailFlowController extends Controller
{
    // ========== AUTO FLOW MANAGEMENT ==========
    
    /**
     * Display auto flow templates (appointment related)
     */
    public function autoFlow()
    {
        $pageTitle = 'Auto Email Flow - Appointment Notifications';
        $templates = NotificationTemplate::where('flow_type', 'auto')
                    ->orWhereIn('act', ['NEW_APPOINTMENT', 'APPOINTMENT_CONFIRMED', 'APPOINTMENT_COMPLETED', 'APPOINTMENT_CANCELED'])
                    ->orderBy('name')->get();
        
        return view('admin.email_flow.auto_flow.index', compact('pageTitle', 'templates'));
    }

    /**
     * Update appointment templates to auto flow
     */
    public function updateAppointmentTemplatesFlow()
    {
        $appointmentActs = ['NEW_APPOINTMENT', 'APPOINTMENT_CONFIRMED', 'APPOINTMENT_COMPLETED', 'APPOINTMENT_CANCELED'];
        
        NotificationTemplate::whereIn('act', $appointmentActs)->update([
            'flow_type' => 'auto',
            'flow_description' => 'Tự động gửi email khi có sự kiện appointment',
            'priority' => 'high'
        ]);

        $notify[] = ['success', 'Đã tích hợp appointment templates vào auto flow'];
        return back()->withNotify($notify);
    }

    /**
     * Create new auto flow template
     */
    public function createAutoTemplate()
    {
        $pageTitle = 'Create Auto Flow Template';
        return view('admin.email_flow.auto_flow.create', compact('pageTitle'));
    }

    /**
     * Store auto flow template
     */
    public function storeAutoTemplate(Request $request)
    {
        $request->validate([
            'act' => 'required|string|unique:notification_templates,act',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'email_body' => 'required',
            'flow_description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high'
        ]);

        NotificationTemplate::create([
            'act' => $request->act,
            'name' => $request->name,
            'subject' => $request->subject,
            'email_body' => $request->email_body,
            'flow_type' => 'auto',
            'flow_description' => $request->flow_description,
            'priority' => $request->priority,
            'email_status' => Status::ENABLE,
            'sms_status' => Status::DISABLE,
            'push_status' => Status::DISABLE,
            'shortcodes' => json_encode([])
        ]);

        $notify[] = ['success', 'Auto flow template created successfully'];
        return redirect()->route('admin.email.flow.auto')->withNotify($notify);
    }

    // ========== MARKETING FLOW MANAGEMENT ==========
    
    /**
     * Display marketing flow templates
     */
    public function marketingFlow()
    {
        $pageTitle = 'Marketing Email Flow - Campaigns';
        $templates = NotificationTemplate::marketingFlow()->orderBy('name')->get();
        
        return view('admin.email_flow.marketing_flow.index', compact('pageTitle', 'templates'));
    }

    /**
     * Create marketing campaign template
     */
    public function createMarketingTemplate()
    {
        $pageTitle = 'Create Marketing Campaign';
        return view('admin.email_flow.marketing_flow.create', compact('pageTitle'));
    }

    /**
     * Store marketing campaign template
     */
    public function storeMarketingTemplate(Request $request)
    {
        $request->validate([
            'act' => 'required|string|unique:notification_templates,act',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'email_body' => 'required',
            'flow_description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high',
            'is_scheduled' => 'boolean',
            'scheduled_at' => 'nullable|date',
            'recipient_criteria' => 'nullable|array'
        ]);

        $recipientCriteria = [];
        if ($request->recipient_criteria) {
            $recipientCriteria = $request->recipient_criteria;
        }

        NotificationTemplate::create([
            'act' => $request->act,
            'name' => $request->name,
            'subject' => $request->subject,
            'email_body' => $request->email_body,
            'flow_type' => 'marketing',
            'flow_description' => $request->flow_description,
            'priority' => $request->priority,
            'is_scheduled' => $request->is_scheduled ?? false,
            'scheduled_at' => $request->scheduled_at,
            'recipient_criteria' => $recipientCriteria,
            'email_status' => Status::ENABLE,
            'sms_status' => Status::DISABLE,
            'push_status' => Status::DISABLE,
            'shortcodes' => json_encode([])
        ]);

        $notify[] = ['success', 'Marketing template created successfully'];
        return redirect()->route('admin.email.flow.marketing')->withNotify($notify);
    }

    /**
     * Send marketing campaign
     */
    public function sendMarketingCampaign(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);
        
        if ($template->flow_type !== 'marketing') {
            $notify[] = ['error', 'This template is not a marketing campaign'];
            return back()->withNotify($notify);
        }

        // Get recipients based on criteria
        $recipients = $this->getMarketingRecipients($template->recipient_criteria);
        
        $sentCount = 0;
        foreach ($recipients as $user) {
            try {
                // Send email notification
                notify($user, $template->act, [
                    'user_name' => $user->name,
                    'user_email' => $user->email
                ]);
                
                // Send in-app notification for marketing campaigns
                NotificationService::sendCampaignNotification($user, $template);
                
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Marketing campaign failed for user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        // Update sent statistics
        $template->update([
            'sent_count' => $template->sent_count + $sentCount,
            'last_sent_at' => now()
        ]);

        $notify[] = ['success', "Marketing campaign sent to {$sentCount} recipients"];
        return back()->withNotify($notify);
    }

    /**
     * Get recipients for marketing campaigns
     */
    private function getMarketingRecipients($criteria)
    {
        $query = User::where('status', Status::ENABLE);

        if (!empty($criteria)) {
            // Filter by user type
            if (isset($criteria['user_type'])) {
                if ($criteria['user_type'] === 'customers') {
                    $query->whereDoesntHave('company');
                } elseif ($criteria['user_type'] === 'companies') {
                    $query->whereHas('company');
                }
            }

            // Filter by registration date
            if (isset($criteria['registered_after'])) {
                $query->where('created_at', '>=', $criteria['registered_after']);
            }

            // Filter by activity
            if (isset($criteria['has_appointments']) && $criteria['has_appointments']) {
                $query->whereHas('appointments');
            }
        }

        return $query->get();
    }

    // ========== FLOW STATISTICS ==========
    
    /**
     * Display flow statistics dashboard
     */
    public function flowStatistics()
    {
        $pageTitle = 'Email Flow Statistics';
        
        $autoFlowStats = [
            'total_templates' => NotificationTemplate::autoFlow()->count(),
            'active_templates' => NotificationTemplate::autoFlow()->where('email_status', Status::ENABLE)->count(),
            'total_sent' => NotificationTemplate::autoFlow()->sum('sent_count')
        ];

        $marketingFlowStats = [
            'total_campaigns' => NotificationTemplate::marketingFlow()->count(),
            'active_campaigns' => NotificationTemplate::marketingFlow()->where('email_status', Status::ENABLE)->count(),
            'total_sent' => NotificationTemplate::marketingFlow()->sum('sent_count'),
            'scheduled_campaigns' => NotificationTemplate::marketingFlow()->scheduled()->count()
        ];

        $recentAppointments = Appointment::with(['user', 'company'])
                            ->latest()
                            ->limit(10)
                            ->get();

        return view('admin.email_flow.statistics', compact(
            'pageTitle', 
            'autoFlowStats', 
            'marketingFlowStats',
            'recentAppointments'
        ));
    }

    /**
     * Edit flow template
     */
    public function editFlowTemplate($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $pageTitle = 'Edit ' . ucfirst($template->flow_type) . ' Flow Template';
        
        if ($template->flow_type === 'marketing') {
            return view('admin.email_flow.marketing_flow.edit', compact('template', 'pageTitle'));
        } else {
            return view('admin.email_flow.auto_flow.edit', compact('template', 'pageTitle'));
        }
    }

    /**
     * Update flow template
     */
    public function updateFlowTemplate(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'email_body' => 'required',
            'flow_description' => 'nullable|string',
            'priority' => 'required|in:low,normal,high'
        ]);

        $updateData = [
            'name' => $request->name,
            'subject' => $request->subject,
            'email_body' => $request->email_body,
            'flow_description' => $request->flow_description,
            'priority' => $request->priority,
            'email_status' => $request->email_status ? Status::ENABLE : Status::DISABLE
        ];

        // Additional fields for marketing templates
        if ($template->flow_type === 'marketing') {
            $updateData['is_scheduled'] = $request->is_scheduled ?? false;
            $updateData['scheduled_at'] = $request->scheduled_at;
            $updateData['recipient_criteria'] = $request->recipient_criteria ?? [];
        }

        $template->update($updateData);

        $notify[] = ['success', 'Template updated successfully'];
        return back()->withNotify($notify);
    }
} 