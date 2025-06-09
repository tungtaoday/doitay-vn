<?php

require_once 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;

echo "Checking blog data...\n";

$blogs = Frontend::where('data_keys', 'blog.element')->latest()->take(5)->get();

echo "Found " . $blogs->count() . " blogs\n\n";

foreach($blogs as $blog) {
    echo "Blog ID: " . ($blog->id ?? 'NULL') . "\n";
    echo "Blog Slug: " . ($blog->slug ?? 'NULL') . "\n";
    echo "Blog Title: " . (isset($blog->data_values->title) ? $blog->data_values->title : 'NULL') . "\n";
    echo "Created: " . $blog->created_at . "\n";
    echo "---\n";
}

?> 