<?php

use App\Models\BlogCategory;
use App\Models\Blog;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = BlogCategory::where('status', true)
    ->orderBy('id', 'asc')
    ->get(['id', 'name', 'slug', 'sort_order']);

echo "=== ALL CURRENT ACTIVE CATEGORIES & POST COUNTS ===\n\n";

$totalPostsCategorized = 0;

foreach ($categories as $index => $cat) {
    $count = Blog::where('category_id', $cat->id)->count();
    $totalPostsCategorized += $count;
    $num = $index + 1;
    echo "{$num}. ID: {$cat->id} | Name: {$cat->name} | Slug: {$cat->slug} | Posts: {$count}\n";
}

echo "\nTotal Active Categories: " . $categories->count() . "\n";
echo "Total Categorized Posts across all active categories: " . $totalPostsCategorized . "\n";
