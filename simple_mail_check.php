<?php

echo "=== SIMPLE MAIL CHECK ===\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db;charset=utf8mb4", 'root', 'Vuivui@123');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT en, mail_config FROM general_settings LIMIT 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Email enabled: " . ($settings['en'] ? 'YES' : 'NO') . "\n";
    echo "Mail config: " . ($settings['mail_config'] ?: 'EMPTY') . "\n";
    
    if ($settings['mail_config']) {
        $config = json_decode($settings['mail_config'], true);
        if ($config && isset($config['name'])) {
            echo "Mail method: " . $config['name'] . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 