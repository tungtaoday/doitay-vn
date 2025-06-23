<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\Appointment;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-appointment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminder notifications to users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Sending appointment reminders...');

        try {
            $count = NotificationService::sendAppointmentReminders();
            
            $this->info("✅ Sent {$count} appointment reminder notifications");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error sending reminders: " . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 