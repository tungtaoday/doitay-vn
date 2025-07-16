<?php
require_once 'core/bootstrap/app.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Checking lead_purchases table structure ===\n";

try {
    $columns = DB::select("DESCRIBE lead_purchases");
    
    echo "Current columns in lead_purchases:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type}) - {$column->Null} - {$column->Default}\n";
    }
    
    echo "\n=== Checking leads table structure ===\n";
    $leadColumns = DB::select("DESCRIBE leads");
    
    echo "Current columns in leads:\n";
    foreach ($leadColumns as $column) {
        echo "- {$column->Field} ({$column->Type}) - {$column->Null} - {$column->Default}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 