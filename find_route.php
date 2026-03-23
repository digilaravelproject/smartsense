<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
$matchesCount = 0;
$uniqueFormats = [];

foreach ($files as $file) {
    $content = file_get_contents($file[0]);
    if (preg_match_all('/route\s*\(\s*[\'"]product[\'"]\s*,\s*([^)]+)\)/', $content, $matches)) {
        foreach ($matches[1] as $match) {
            $uniqueFormats[trim($match)] = ($uniqueFormats[trim($match)] ?? 0) + 1;
            $matchesCount++;
        }
    }
}

echo "Total route('product', ...) calls: $matchesCount\n";
echo "Unique variables passed:\n";
print_r($uniqueFormats);
