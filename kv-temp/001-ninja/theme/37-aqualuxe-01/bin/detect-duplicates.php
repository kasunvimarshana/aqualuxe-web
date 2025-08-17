#!/usr/bin/env php
<?php
/**
 * AquaLuxe Theme Duplicate Code Detector
 * This script detects duplicate code in PHP files
 */

// Configuration
$theme_dir = dirname(__DIR__);
$min_line_count = 5; // Minimum number of lines to consider as a duplicate
$min_similarity = 90; // Minimum similarity percentage to consider as a duplicate
$exclude_dirs = ['vendor', 'node_modules', 'tests', 'bin'];
$exclude_files = ['index.php'];

// ANSI color codes for terminal output
$colors = [
    'reset' => "\033[0m",
    'red' => "\033[31m",
    'green' => "\033[32m",
    'yellow' => "\033[33m",
    'blue' => "\033[34m",
    'magenta' => "\033[35m",
    'cyan' => "\033[36m",
    'white' => "\033[37m",
    'bold' => "\033[1m",
];

echo "{$colors['bold']}{$colors['blue']}AquaLuxe Theme Duplicate Code Detector{$colors['reset']}\n";
echo "{$colors['cyan']}Scanning for duplicate code in PHP files...{$colors['reset']}\n\n";

// Get all PHP files
$php_files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($theme_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $relative_path = str_replace($theme_dir . '/', '', $file->getPathname());
        
        // Skip excluded directories
        $skip = false;
        foreach ($exclude_dirs as $exclude_dir) {
            if (strpos($relative_path, $exclude_dir . '/') === 0) {
                $skip = true;
                break;
            }
        }
        
        // Skip excluded files
        if (!$skip && !in_array(basename($relative_path), $exclude_files)) {
            $php_files[] = $relative_path;
        }
    }
}

echo "{$colors['green']}Found " . count($php_files) . " PHP files to analyze{$colors['reset']}\n\n";

// Read file contents
$file_contents = [];
foreach ($php_files as $file) {
    $content = file_get_contents($theme_dir . '/' . $file);
    $lines = explode("\n", $content);
    $file_contents[$file] = $lines;
}

// Function to normalize code for comparison
function normalize_code($code) {
    // Remove comments
    $code = preg_replace('/(\/\/.*|\/\*[\s\S]*?\*\/|#.*)/', '', $code);
    
    // Remove whitespace
    $code = preg_replace('/\s+/', ' ', $code);
    
    // Trim
    return trim($code);
}

// Function to calculate similarity between two strings
function similarity($str1, $str2) {
    $len1 = strlen($str1);
    $len2 = strlen($str2);
    
    if ($len1 === 0 && $len2 === 0) {
        return 100;
    }
    
    if ($len1 === 0 || $len2 === 0) {
        return 0;
    }
    
    $distance = levenshtein($str1, $str2);
    $max_len = max($len1, $len2);
    
    return (1 - $distance / $max_len) * 100;
}

// Find duplicate code blocks
$duplicates = [];

// Compare each file with every other file
for ($i = 0; $i < count($php_files); $i++) {
    $file1 = $php_files[$i];
    $lines1 = $file_contents[$file1];
    
    for ($j = $i + 1; $j < count($php_files); $j++) {
        $file2 = $php_files[$j];
        $lines2 = $file_contents[$file2];
        
        // Compare line by line
        for ($line1 = 0; $line1 < count($lines1) - $min_line_count + 1; $line1++) {
            for ($line2 = 0; $line2 < count($lines2) - $min_line_count + 1; $line2++) {
                $duplicate_lines = 0;
                $duplicate_content = [];
                
                // Check consecutive lines
                for ($k = 0; $k < $min_line_count; $k++) {
                    $normalized1 = normalize_code($lines1[$line1 + $k]);
                    $normalized2 = normalize_code($lines2[$line2 + $k]);
                    
                    if (strlen($normalized1) < 5 || strlen($normalized2) < 5) {
                        continue; // Skip very short lines
                    }
                    
                    $sim = similarity($normalized1, $normalized2);
                    if ($sim >= $min_similarity) {
                        $duplicate_lines++;
                        $duplicate_content[] = $lines1[$line1 + $k];
                    } else {
                        break;
                    }
                }
                
                // If we found enough duplicate lines
                if ($duplicate_lines >= $min_line_count) {
                    $duplicates[] = [
                        'file1' => $file1,
                        'line1' => $line1 + 1,
                        'file2' => $file2,
                        'line2' => $line2 + 1,
                        'lines' => $duplicate_lines,
                        'content' => implode("\n", $duplicate_content),
                    ];
                    
                    // Skip ahead to avoid reporting the same duplicate multiple times
                    $line1 += $duplicate_lines - 1;
                    break;
                }
            }
        }
    }
}

// Report duplicates
if (count($duplicates) > 0) {
    echo "{$colors['yellow']}Found " . count($duplicates) . " potential code duplications:{$colors['reset']}\n\n";
    
    foreach ($duplicates as $index => $duplicate) {
        echo "{$colors['bold']}Duplication #" . ($index + 1) . "{$colors['reset']}\n";
        echo "{$colors['magenta']}File 1:{$colors['reset']} {$duplicate['file1']} (line {$duplicate['line1']})\n";
        echo "{$colors['magenta']}File 2:{$colors['reset']} {$duplicate['file2']} (line {$duplicate['line2']})\n";
        echo "{$colors['magenta']}Lines:{$colors['reset']} {$duplicate['lines']}\n";
        echo "{$colors['magenta']}Content:{$colors['reset']}\n";
        echo "{$colors['cyan']}" . str_repeat('-', 80) . "{$colors['reset']}\n";
        echo $duplicate['content'] . "\n";
        echo "{$colors['cyan']}" . str_repeat('-', 80) . "{$colors['reset']}\n\n";
    }
    
    echo "{$colors['yellow']}Recommendation: Review these duplications and consider refactoring into reusable functions or components.{$colors['reset']}\n";
} else {
    echo "{$colors['green']}No code duplications found! Your code is DRY.{$colors['reset']}\n";
}

echo "\n{$colors['blue']}Analysis complete.{$colors['reset']}\n";