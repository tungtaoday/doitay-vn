<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=t_review_db', 'root', 'Vuivui@123');
    $stmt = $pdo->prepare('SELECT firstname, lastname, mobile FROM users WHERE id = 127');
    $stmt->execute();
    $user = $stmt->fetch();
    echo "Customer: {$user['firstname']} {$user['lastname']}\n";
    echo "Mobile: {$user['mobile']}\n";
    echo "✅ Fix should work - customer has phone: {$user['mobile']}\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 