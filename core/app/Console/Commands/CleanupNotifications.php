<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CleanupNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup {--days=30 : Number of days to keep read notifications}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old notifications to keep database optimized';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up notifications older than {$days} days...");

        try {
            $result = NotificationService::cleanupOldNotifications($days);
            
            $this->info("✅ Cleanup completed:");
            $this->line("   - Deleted {$result['deleted_old']} old read notifications");
            $this->line("   - Deleted {$result['deleted_expired']} expired notifications");
            $this->line("   - Total: " . ($result['deleted_old'] + $result['deleted_expired']) . " notifications removed");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Error during cleanup: " . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
} 