<?php

use App\Models\BlogCategory;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$categories = BlogCategory::all(['id', 'name', 'slug']);

echo "=== ALL BLOG CATEGORIES IN DATABASE ===\n\n";
foreach ($categories as $cat) {
    echo "ID: {$cat->id} | Slug: {$cat->slug} | Name: {$cat->name}\n";
}
