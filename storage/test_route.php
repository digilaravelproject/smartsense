<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

define('DOMAIN_POINTED_DIRECTORY', 'public');

$product = \App\Models\Product::first();
echo "Product ID: " . $product->id . "\n";
echo "Product Name: " . $product->name . "\n";
echo "Category Slug: " . $product->category_slug . "\n";
echo "Details URL: " . $product->details_url . "\n";
