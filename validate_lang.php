<?php

$base = __DIR__ . '/resources/lang';
$errors = [];

// 1) PHP syntax check all lang files
foreach (['ar', 'en'] as $locale) {
    $dir = "$base/$locale";
    foreach (glob("$dir/*.php") as $file) {
        exec("php -l " . escapeshellarg($file) . " 2>&1", $out, $code);
        if ($code !== 0) {
            $errors[] = "SYNTAX: $file => " . implode(' | ', $out);
        }
    }
}

// 2) key parity ar vs en
$modules = [];
foreach (['ar', 'en'] as $locale) {
    $dir = "$base/$locale";
    foreach (glob("$dir/*.php") as $file) {
        $module = basename($file, '.php');
        $arr = require $file;
        $flat = [];
        array_walk_recursive($arr, function ($v, $k) use (&$flat) { $flat[] = $k; });
        $modules[$module][$locale] = $flat;
    }
}
foreach ($modules as $module => $sets) {
    $diffAr = array_diff($sets['ar'] ?? [], $sets['en'] ?? []);
    $diffEn = array_diff($sets['en'] ?? [], $sets['ar'] ?? []);
    if ($diffAr) $errors[] = "KEY MISMATCH ar-only in $module: " . implode(',', $diffAr);
    if ($diffEn) $errors[] = "KEY MISMATCH en-only in $module: " . implode(',', $diffEn);
}

// 3) verify every @lang('...') reference resolves in the lang files
$langFiles = require_lang($base, 'ar');
$langFilesEn = require_lang($base, 'en');

$viewDir = __DIR__ . '/resources/views';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewDir));
$missing = [];
foreach ($rii as $file) {
    if (!$file->isFile() || substr($file->getFilename(), -10) !== '.blade.php') continue;
    $content = file_get_contents($file->getPathname());
    preg_match_all("/@lang\('([^']+)'|__\('([^']+)'|__\(\"([^\"]+)\"|@lang\(\"([^\"]+)\"/", $content, $m);
    $keys = array_filter(array_merge($m[1], $m[2], $m[3], $m[4]));
    foreach ($keys as $key) {
        $key = trim($key);
        if ($key === '') continue;
        if (str_starts_with($key, '$') || str_contains($key, '{{') || str_contains($key, '.')) {
            // could be dynamic or dotted; check dotted resolution
        }
        if (str_contains($key, '.')) {
            [$module, $rest] = explode('.', $key, 2);
            $path = $base . '/ar/' . $module . '.php';
            if (file_exists($path)) {
                $arr = require $path;
                $v = $arr;
                foreach (explode('.', $rest) as $seg) {
                    if (!is_array($v) || !array_key_exists($seg, $v)) { $v = null; break; }
                    $v = $v[$seg];
                }
                if ($v === null) {
                    $missing[] = "$key  (in {$file->getFilename()})";
                }
            } elseif (!isset($langFiles[$key])) {
                $missing[] = "$key  (in {$file->getFilename()})";
            }
        } elseif (!isset($langFiles[$key])) {
            $missing[] = "$key  (in {$file->getFilename()})";
        }
    }
}

// 4) verify no Arabic in views (already handled by agent) - recheck
$arabicFiles = [];
foreach ($rii as $file) {
    if (!$file->isFile() || substr($file->getFilename(), -10) !== '.blade.php') continue;
    $content = file_get_contents($file->getPathname());
    if (preg_match('/[\x{0600}-\x{06FF}]/u', $content)) {
        // ignore Arabic only inside PHP fallback defaults for school/app name (proper nouns)
        $stripped = preg_replace("/'[^']*إدبة[^']*'|\"[^\"]*إدبة[^\"]*\"/u", '', $content);
        if (preg_match('/[\x{0600}-\x{06FF}]/u', $stripped)) {
            $arabicFiles[] = $file->getFilename();
        }
    }
}

echo "=== LANG FILE VALIDATION ===\n";
if ($errors) { echo "ISSUES:\n"; foreach ($errors as $e) echo "  - $e\n"; }
else { echo "ar/en key parity + syntax: OK\n"; }
echo "\n=== MISSING KEYS ===\n";
if ($missing) { foreach (array_unique($missing) as $k) echo "  - $k\n"; }
else { echo "All @lang references resolve: OK\n"; }
echo "\n=== ARABIC REMAINING IN VIEWS ===\n";
if ($arabicFiles) { foreach (array_unique($arabicFiles) as $f) echo "  - $f\n"; }
else { echo "None (all clean)\n"; }

function require_lang($base, $locale) {
    $all = [];
    foreach (glob("$base/$locale/*.php") as $file) {
        $all[basename($file, '.php')] = require $file;
    }
    $jsonFile = "$base/$locale.json";
    if (file_exists($jsonFile)) {
        $all = json_decode(file_get_contents($jsonFile), true) + $all;
    }
    $flat = [];
    foreach ($all as $module => $arr) {
        if (is_array($arr)) {
            array_walk_recursive($arr, function ($v, $k) use ($module, &$flat) { $flat["$module.$k"] = $v; });
        } else {
            $flat[$module] = $arr;
        }
    }
    return $flat + $all;
}
