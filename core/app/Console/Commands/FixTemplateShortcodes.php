<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NotificationTemplate;

class FixTemplateShortcodes extends Command
{
    protected $signature = 'fix:template-shortcodes';
    protected $description = 'Fix notification template shortcodes to ensure they are manageable';

    public function handle()
    {
        $this->info('Fixing notification template shortcodes...');
        
        // Default shortcodes for templates
        $defaultShortcodes = [
            'fullname' => 'Full Name of User',
            'username' => 'Username of User', 
            'message' => 'Message',
            'email' => 'Email of User',
            'company_name' => 'Company Name',
            'site_name' => 'Site Name',
            'site_url' => 'Site URL'
        ];

        $templates = NotificationTemplate::all();
        $fixed = 0;
        
        foreach ($templates as $template) {
            $currentShortcodes = $template->shortcodes;
            $needsUpdate = false;
            
            // Check if shortcodes is empty, null, or invalid
            if (empty($currentShortcodes) || !is_array($currentShortcodes) && !is_object($currentShortcodes)) {
                $template->shortcodes = $defaultShortcodes;
                $needsUpdate = true;
                $this->line("Updated shortcodes for template: {$template->name} (ID: {$template->id})");
            } elseif (is_string($currentShortcodes)) {
                // Try to decode if it's a JSON string
                $decoded = json_decode($currentShortcodes, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Invalid JSON, replace with defaults
                    $template->shortcodes = $defaultShortcodes;
                    $needsUpdate = true;
                    $this->line("Fixed invalid JSON shortcodes for template: {$template->name} (ID: {$template->id})");
                } else {
                    $this->line("Template {$template->name} (ID: {$template->id}) already has valid shortcodes");
                }
            } else {
                $this->line("Template {$template->name} (ID: {$template->id}) already has valid shortcodes");
            }
            
            if ($needsUpdate) {
                $template->save();
                $fixed++;
            }
        }
        
        $this->info("Completed! Fixed {$fixed} templates.");
        $this->info("All templates should now be manageable from admin panel.");
        
        return Command::SUCCESS;
    }
}