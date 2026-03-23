<?php

$dirs = [
    __DIR__ . '/resources/views',
    __DIR__ . '/resources/themes'
];

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

        // Catch formats: route('products', ['category_id' => $category['id'], 'data_from' => 'category', 'page' => 1])
        // and route('products', ['id' => $category->id, 'data_from' => 'category'])
        // DO NOT use /s modifier as it skips across lines!
        $content = preg_replace(
            '/route\s*\(\s*[\'"]products[\'"]\s*,\s*\[[^\]]{0,80}?[\'"]data_from[\'"]\s*=>\s*[\'"]category[\'"][^\]]{0,80}?[\'"](?:category_)?id[\'"]\s*=>\s*\$([a-zA-Z0-9_\-]+)(?:\[[\'"]id[\'"]\]|->id)[^\]]{0,80}?\]\s*\)/',
            '\$$1->list_url',
            $content
        );

        $content = preg_replace(
            '/route\s*\(\s*[\'"]products[\'"]\s*,\s*\[[^\]]{0,80}?[\'"](?:category_)?id[\'"]\s*=>\s*\$([a-zA-Z0-9_\-]+)(?:\[[\'"]id[\'"]\]|->id)[^\]]{0,80}?[\'"]data_from[\'"]\s*=>\s*[\'"]category[\'"][^\]]{0,80}?\]\s*\)/',
            '\$$1->list_url',
            $content
        );

        // Sometimes the string is unquoted if double quotes used
        $content = preg_replace_callback(
            '/route\s*\(\s*[\'"]products[\'"]\s*,\s*\[[^\]]+[\'"](?:category_)?id[\'"]\s*=>\s*\$([a-zA-Z0-9_\-]+)(?:\[[\'"]id[\'"]\]|->id)[^\]]+\]\s*\)/',
            function($m) {
                // if it has data_from => category
                if (strpos($m[0], 'category') !== false && strpos($m[0], 'data_from') !== false) {
                    return '$' . $m[1] . '->list_url';
                }
                return $m[0];
            },
            $content
        );


        if ($content !== $originalContent) {
            file_put_contents($filePath, $content);
            $filesChanged++;
        }
    }
}

echo "Files changed: $filesChanged\n";
