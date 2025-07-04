<?php

// Get all blade files in resources/views directory
function recursiveSearch($dir, &$results = []) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            recursiveSearch($path, $results);
        }
    }
    return $results;
}

$dir = __DIR__ . '/resources/views';
$files = recursiveSearch($dir);

$count = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, "@extends('layouts.app')") !== false) {
        echo "Updating file: $file\n";
        $newContent = str_replace("@extends('layouts.app')", "@extends('layouts.template')", $content);
        file_put_contents($file, $newContent);
        $count++;
    }
}

echo "Updated $count files to use template.blade.php instead of app.blade.php\n";
