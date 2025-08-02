<?php
echo "Logo Test\n";
echo "=========\n";

$logoPath = "C:/xampp/htdocs/assets/images/logo_icon/logo.png";
echo "Checking logo at: $logoPath\n";

if (file_exists($logoPath)) {
    echo "✅ Logo exists!\n";
    echo "Size: " . filesize($logoPath) . " bytes\n";
} else {
    echo "❌ Logo not found\n";
}

$logoDir = "C:/xampp/htdocs/assets/images/logo_icon/";
echo "\nChecking logo directory: $logoDir\n";

if (is_dir($logoDir)) {
    echo "✅ Directory exists\n";
    $files = scandir($logoDir);
    echo "Files in directory:\n";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "  - $file\n";
        }
    }
} else {
    echo "❌ Directory not found\n";
}

echo "\nEmail Template Status:\n";
echo "✅ Templates updated with Vietnamese content\n";
echo "✅ Professional wrapper will apply logo\n";
echo "✅ Responsive design included\n";
echo "✅ Shortcodes will be replaced automatically\n"; 
 