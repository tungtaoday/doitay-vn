<?php
echo "Testing database connection...\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=t_review_db", "root", "Vuivui@123");
    echo "Connected to database successfully!\n";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM frontends WHERE data_keys = 'blog.element'");
    $result = $stmt->fetch();
    echo "Total blogs: " . $result['count'] . "\n";
    
    // Check specific blog
    $stmt = $pdo->query("SELECT * FROM frontends WHERE id = 7");
    $blog = $stmt->fetch();
    if ($blog) {
        echo "Blog ID 7 found:\n";
        echo "Slug: " . $blog['slug'] . "\n";
        echo "Data Keys: " . $blog['data_keys'] . "\n";
    } else {
        echo "Blog ID 7 not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 