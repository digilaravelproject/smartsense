<?php

$dirs = [
    __DIR__ . '/resources/views',
    __DIR__ . '/resources/themes'
];

$replacementsCount = 0;
$filesChanged = 0;

foreach ($dirs as $dirPath) {
    if (!is_dir($dirPath)) continue;
    $dir = new RecursiveDirectoryIterator($dirPath);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

    foreach ($files as $file) {
        $filePath = $file[0];
        $content = file_get_contents($filePath);
        $originalContent = $content;

        // route('product',[$product['slug']]) -> $product['details_url']
        $content = preg_replace('/route\s*\(\s*[\'"]product[\'"]\s*,\s*\[\s*\$([a-zA-Z0-9_\-]+)\[[\'"]slug[\'"]\]\s*\]\s*\)/', '\$$1[\'details_url\']', $content);

        // route('product', [$product->slug]) -> $product->details_url
        $content = preg_replace('/route\s*\(\s*[\'"]product[\'"]\s*,\s*\[\s*\$([a-zA-Z0-9_\-]+)->slug\s*\]\s*\)/', '\$$1->details_url', $content);

        // route('product', $product['slug']) -> $product['details_url']
        $content = preg_replace('/route\s*\(\s*[\'"]product[\'"]\s*,\s*\$([a-zA-Z0-9_\-]+)\[[\'"]slug[\'"]\]\s*\)/', '\$$1[\'details_url\']', $content);

        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            $filesChanged++;
        }
    }
}

echo "Files changed round 2: $filesChanged\n";
