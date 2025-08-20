<?php
// ========================================
// FIX BLADE TEMPLATE FEATURES INDEX
// ========================================

echo "Fixing features index blade template...\n";

$filePath = 'core/resources/views/admin/features/index.blade.php';

if (!file_exists($filePath)) {
    echo "❌ File not found: $filePath\n";
    exit(1);
}

// Read current content
$content = file_get_contents($filePath);

// Find and replace the problematic line
$oldLine = 'data-category-name="{{ $feature->category->name }}"';
$newLine = 'data-category-name="{{ $feature->category?->name ?? \'N/A\' }}"';

if (strpos($content, $oldLine) !== false) {
    $content = str_replace($oldLine, $newLine, $content);
    
    // Write back to file
    file_put_contents($filePath, $content);
    
    echo "✅ Fixed line 62: Added null safety check\n";
    echo "   Changed: $oldLine\n";
    echo "   To:      $newLine\n";
} else {
    echo "⚠️ Original line not found, checking current content around line 62...\n";
    
    $lines = explode("\n", $content);
    for ($i = 57; $i <= 67; $i++) {
        if (isset($lines[$i-1])) {
            echo "Line $i: " . trim($lines[$i-1]) . "\n";
        }
    }
}

echo "\n🔍 Current status of line 62:\n";
$lines = explode("\n", $content);
if (isset($lines[61])) {
    echo "Line 62: " . trim($lines[61]) . "\n";
}

echo "\n✅ Template fix completed!\n";
echo "📝 This will prevent 'Attempt to read property name on null' error\n";
?> 