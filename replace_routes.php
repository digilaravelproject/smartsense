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

        // Pattern 1: route('product', $obj->slug) -> $obj->details_url
        $content = preg_replace('/route\s*\(\s*[\'"]product[\'"]\s*,\s*\$([a-zA-Z0-9_\-]+)->slug\s*\)/', '\$$1->details_url', $content);

        // Pattern 2: route('product', $obj['slug']) -> $obj['details_url']
        $content = preg_replace('/route\s*\(\s*[\'"]product[\'"]\s*,\s*\$([a-zA-Z0-9_\-]+)\[[\'"]slug[\'"]\]\s*\)/', '\$$1[\'details_url\']', $content);

        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            $filesChanged++;
            $replacementsCount += preg_match_all('/route\s*\(\s*[\'"]product[\'"]/', $originalContent, $matches) - preg_match_all('/route\s*\(\s*[\'"]product[\'"]/', $content, $matches2);
        }
    }
}

echo "Files changed: $filesChanged\n";
echo "Approx Replacements made: $replacementsCount\n";
