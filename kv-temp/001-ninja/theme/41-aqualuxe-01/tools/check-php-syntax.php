<?php
/**
 * AquaLuxe Theme - PHP Syntax Checker
 * 
 * This script checks for PHP syntax errors in all PHP files in the theme.
 * 
 * Usage: php check-php-syntax.php [directory]
 * Example: php check-php-syntax.php ../
 */

// Set default directory if not provided
$directory = isset($argv[1]) ? $argv[1] : __DIR__ . '/../';

echo "Checking PHP syntax in: {$directory}\n";
echo "--------------------------------------------------------------\n\n";

// Get all PHP files
$files = findPhpFiles($directory);
echo "Found " . count($files) . " PHP files.\n\n";

// Check syntax of each file
$errors = [];
$count = 0;

foreach ($files as $file) {
    $count++;
    $relativePath = str_replace($directory, '', $file);
    
    echo "Checking [{$count}/" . count($files) . "]: {$relativePath}";
    
    $output = [];
    $returnVar = 0;
    
    exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnVar);
    
    if ($returnVar !== 0) {
        $errors[$file] = implode("\n", $output);
        echo " - ERROR\n";
    } else {
        echo " - OK\n";
    }
}

echo "\n";

// Display results
if (empty($errors)) {
    echo "No PHP syntax errors detected. Great job!\n";
} else {
    echo "Found " . count($errors) . " files with PHP syntax errors:\n\n";
    
    foreach ($errors as $file => $error) {
        $relativePath = str_replace($directory, '', $file);
        echo "File: {$relativePath}\n";
        echo "Error: {$error}\n\n";
    }
    
    echo "Please fix these syntax errors before deploying the theme.\n";
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