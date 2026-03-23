<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

define('DOMAIN_POINTED_DIRECTORY', 'public');

$category = \App\Models\Category::first();
echo "Category ID: " . $category->id . "\n";
echo "Category Name: " . $category->name . "\n";
echo "List URL: " . $category->list_url . "\n";
