<?php
$host = 'localhost';
$dbname = 't_review_db';
$username = 'root';
$password = 'Vuivui@123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    echo "=== COMPANY ID 61 ===\n";
    $stmt = $pdo->prepare("SELECT id, name, email, address, tags, services, business_hours FROM companies WHERE id = 61");
    $stmt->execute();
    $company = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($company) {
        echo "ID: " . $company['id'] . "\n";
        echo "Name: " . $company['name'] . "\n";
        echo "Email: " . $company['email'] . "\n";
        echo "Address: " . $company['address'] . "\n";
        echo "Tags length: " . strlen($company['tags']) . "\n";
        echo "Tags preview: " . substr($company['tags'], 0, 200) . "...\n";
        echo "Services: " . $company['services'] . "\n";
        echo "Business Hours: " . $company['business_hours'] . "\n";
        
        // Kiểm tra JSON
        if ($company['tags']) {
            $decoded = json_decode($company['tags'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                echo "Tags JSON: OK\n";
                if (is_array($decoded)) {
                    echo "Tags is array with " . count($decoded) . " items\n";
                }
            } else {
                echo "Tags JSON ERROR: " . json_last_error_msg() . "\n";
            }
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 