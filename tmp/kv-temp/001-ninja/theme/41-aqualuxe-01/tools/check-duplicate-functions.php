<?php
/**
 * AquaLuxe Theme - Duplicate Function Checker
 * 
 * This script checks for duplicate function declarations across PHP files in the theme.
 * 
 * Usage: php check-duplicate-functions.php [directory]
 * Example: php check-duplicate-functions.php ../
 */

// Set default directory if not provided
$directory = isset($argv[1]) ? $argv[1] : __DIR__ . '/../';

echo "Checking for duplicate function declarations in: {$directory}\n";
echo "--------------------------------------------------------------\n\n";

// Get all PHP files
$files = findPhpFiles($directory);
echo "Found " . count($files) . " PHP files.\n\n";

// Extract functions from files
$functions = [];
$duplicates = [];

foreach ($files as $file) {
    $content = file_get_contents($file);
    $fileFunctions = extractFunctions($content);
    
    foreach ($fileFunctions as $function) {
        if (!isset($functions[$function])) {
            $functions[$function] = [];
        }
        
        $functions[$function][] = $file;
        
        if (count($functions[$function]) > 1) {
            $duplicates[$function] = $functions[$function];
        }
    }
}

// Display results
if (empty($duplicates)) {
    echo "No duplicate function declarations found. Great job!\n";
} else {
    echo "Found " . count($duplicates) . " duplicate function declarations:\n\n";
    
    foreach ($duplicates as $function => $files) {
        echo "Function '{$function}' is declared in " . count($files) . " files:\n";
        
        foreach ($files as $file) {
            $relativePath = str_replace($directory, '', $file);
            echo "  - {$relativePath}\n";
        }
        
        echo "\n";
    }
    
    echo "Please fix these duplicate declarations to avoid PHP parse errors.\n";
}

/**
 * Find all PHP files in a directory recursively
 *
 * @param string $directory Directory to search
 * @return array Array of file paths
 */
function findPhpFiles($directory) {
    $files = [];
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
    
    return $files;
}

/**
 * Extract function declarations from PHP content
 *
 * @param string $content PHP file content
 * @return array Array of function names
 */
function extractFunctions($content) {
    $functions = [];
    
    // Regular expression to match function declarations
    $pattern = '/function\s+([a-zA-Z0-9_]+)\s*\(/';
    
    if (preg_match_all($pattern, $content, $matches)) {
        $functions = $matches[1];
    }
    
    return $functions;
}