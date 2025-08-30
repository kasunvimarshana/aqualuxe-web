<?php
/**
 * AquaLuxe Theme - Add Inline Documentation Script
 *
 * This script adds or updates inline documentation for PHP files in the theme.
 * It scans PHP files and adds or updates DocBlocks for functions, classes, and methods.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Set the theme directory
$theme_dir = dirname(__DIR__);

// Files and directories to exclude
$exclude = array(
    'vendor',
    'node_modules',
    'tools',
    'demo',
    'docs',
    '.git',
);

// Get all PHP files in the theme directory
$files = get_php_files($theme_dir, $exclude);

// Process each file
foreach ($files as $file) {
    echo "Processing file: $file\n";
    process_file($file);
}

echo "\nDocumentation process completed!\n";

/**
 * Get all PHP files in a directory and its subdirectories
 *
 * @param string $dir      Directory to scan
 * @param array  $exclude  Directories to exclude
 * @return array Array of PHP file paths
 */
function get_php_files($dir, $exclude = array()) {
    $files = array();
    
    $dir_iterator = new RecursiveDirectoryIterator($dir);
    $iterator = new RecursiveIteratorIterator($dir_iterator);
    
    foreach ($iterator as $file) {
        // Skip directories and non-PHP files
        if ($file->isDir() || $file->getExtension() !== 'php') {
            continue;
        }
        
        $path = $file->getPathname();
        $relative_path = str_replace($dir . '/', '', $path);
        
        // Check if the file is in an excluded directory
        $exclude_file = false;
        foreach ($exclude as $excluded_dir) {
            if (strpos($relative_path, $excluded_dir . '/') === 0) {
                $exclude_file = true;
                break;
            }
        }
        
        if (!$exclude_file) {
            $files[] = $path;
        }
    }
    
    return $files;
}

/**
 * Process a PHP file to add or update documentation
 *
 * @param string $file  File path
 * @return void
 */
function process_file($file) {
    $content = file_get_contents($file);
    
    // Parse the file to get tokens
    $tokens = token_get_all($content);
    
    // Process the tokens
    $new_content = add_file_docblock($content, $file);
    $new_content = add_function_docblocks($new_content);
    $new_content = add_class_docblocks($new_content);
    
    // Write the updated content back to the file
    if ($new_content !== $content) {
        file_put_contents($file, $new_content);
        echo "  Updated documentation in $file\n";
    } else {
        echo "  No changes needed in $file\n";
    }
}

/**
 * Add or update the file DocBlock
 *
 * @param string $content  File content
 * @param string $file     File path
 * @return string Updated content
 */
function add_file_docblock($content, $file) {
    // Check if the file already has a DocBlock
    if (preg_match('/^<\?php\s+\/\*\*[\s\S]+?\*\//', $content)) {
        return $content;
    }
    
    // Get the file name without path
    $filename = basename($file);
    
    // Create a description based on the file name
    $description = create_description_from_filename($filename);
    
    // Create the DocBlock
    $docblock = "<?php\n/**\n";
    $docblock .= " * $description\n";
    $docblock .= " *\n";
    $docblock .= " * @package AquaLuxe\n";
    $docblock .= " * @since 1.0.0\n";
    $docblock .= " */\n\n";
    
    // Replace the PHP opening tag with the DocBlock
    $content = preg_replace('/^<\?php/', $docblock, $content, 1);
    
    return $content;
}

/**
 * Add or update DocBlocks for functions
 *
 * @param string $content  File content
 * @return string Updated content
 */
function add_function_docblocks($content) {
    // Regular expression to find functions without DocBlocks
    $pattern = '/(?<!\/\*\*[\s\S]+?\*\/\s+)function\s+([a-zA-Z0-9_]+)\s*\(([^)]*)\)/';
    
    // Find all functions without DocBlocks
    preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
    
    // Process matches in reverse order to avoid offset issues
    for ($i = count($matches[0]) - 1; $i >= 0; $i--) {
        $function_match = $matches[0][$i];
        $function_name = $matches[1][$i][0];
        $function_params = $matches[2][$i][0];
        
        // Create a description based on the function name
        $description = create_description_from_function_name($function_name);
        
        // Parse parameters
        $params = parse_function_params($function_params);
        
        // Create the DocBlock
        $docblock = "/**\n";
        $docblock .= " * $description\n";
        $docblock .= " *\n";
        
        // Add parameter documentation
        foreach ($params as $param) {
            $docblock .= " * @param {$param['type']} \${$param['name']} {$param['description']}\n";
        }
        
        // Add return documentation
        $docblock .= " * @return void\n";
        $docblock .= " */\n";
        
        // Insert the DocBlock before the function
        $position = $function_match[1];
        $content = substr_replace($content, $docblock, $position, 0);
    }
    
    return $content;
}

/**
 * Add or update DocBlocks for classes and methods
 *
 * @param string $content  File content
 * @return string Updated content
 */
function add_class_docblocks($content) {
    // Regular expression to find classes without DocBlocks
    $class_pattern = '/(?<!\/\*\*[\s\S]+?\*\/\s+)class\s+([a-zA-Z0-9_]+)(?:\s+extends\s+([a-zA-Z0-9_\\\\]+))?(?:\s+implements\s+([a-zA-Z0-9_\\\\,\s]+))?\s*\{/';
    
    // Find all classes without DocBlocks
    preg_match_all($class_pattern, $content, $class_matches, PREG_OFFSET_CAPTURE);
    
    // Process class matches in reverse order to avoid offset issues
    for ($i = count($class_matches[0]) - 1; $i >= 0; $i--) {
        $class_match = $class_matches[0][$i];
        $class_name = $class_matches[1][$i][0];
        
        // Create a description based on the class name
        $description = create_description_from_class_name($class_name);
        
        // Create the DocBlock
        $docblock = "/**\n";
        $docblock .= " * $description\n";
        $docblock .= " *\n";
        $docblock .= " * @package AquaLuxe\n";
        $docblock .= " * @since 1.0.0\n";
        $docblock .= " */\n";
        
        // Insert the DocBlock before the class
        $position = $class_match[1];
        $content = substr_replace($content, $docblock, $position, 0);
    }
    
    // Regular expression to find methods without DocBlocks
    $method_pattern = '/(?<!\/\*\*[\s\S]+?\*\/\s+)(?:public|protected|private)\s+(?:static\s+)?function\s+([a-zA-Z0-9_]+)\s*\(([^)]*)\)/';
    
    // Find all methods without DocBlocks
    preg_match_all($method_pattern, $content, $method_matches, PREG_OFFSET_CAPTURE);
    
    // Process method matches in reverse order to avoid offset issues
    for ($i = count($method_matches[0]) - 1; $i >= 0; $i--) {
        $method_match = $method_matches[0][$i];
        $method_name = $method_matches[1][$i][0];
        $method_params = $method_matches[2][$i][0];
        
        // Create a description based on the method name
        $description = create_description_from_function_name($method_name);
        
        // Parse parameters
        $params = parse_function_params($method_params);
        
        // Create the DocBlock
        $docblock = "/**\n";
        $docblock .= " * $description\n";
        $docblock .= " *\n";
        
        // Add parameter documentation
        foreach ($params as $param) {
            $docblock .= " * @param {$param['type']} \${$param['name']} {$param['description']}\n";
        }
        
        // Add return documentation
        $docblock .= " * @return void\n";
        $docblock .= " */\n";
        
        // Insert the DocBlock before the method
        $position = $method_match[1];
        $content = substr_replace($content, $docblock, $position, 0);
    }
    
    return $content;
}

/**
 * Create a description from a file name
 *
 * @param string $filename  File name
 * @return string Description
 */
function create_description_from_filename($filename) {
    // Remove file extension
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Convert hyphens and underscores to spaces
    $name = str_replace(array('-', '_'), ' ', $name);
    
    // Capitalize words
    $name = ucwords($name);
    
    return "AquaLuxe Theme - $name";
}

/**
 * Create a description from a function name
 *
 * @param string $function_name  Function name
 * @return string Description
 */
function create_description_from_function_name($function_name) {
    // Remove 'aqualuxe_' prefix if present
    $name = preg_replace('/^aqualuxe_/', '', $function_name);
    
    // Convert underscores to spaces
    $name = str_replace('_', ' ', $name);
    
    // Capitalize words
    $name = ucwords($name);
    
    return $name;
}

/**
 * Create a description from a class name
 *
 * @param string $class_name  Class name
 * @return string Description
 */
function create_description_from_class_name($class_name) {
    // Remove 'AquaLuxe_' prefix if present
    $name = preg_replace('/^AquaLuxe_/', '', $class_name);
    
    // Add spaces before capital letters
    $name = preg_replace('/([a-z])([A-Z])/', '$1 $2', $name);
    
    return $name;
}

/**
 * Parse function parameters
 *
 * @param string $params_string  Parameter string
 * @return array Array of parameter information
 */
function parse_function_params($params_string) {
    $params = array();
    
    // Split the parameters string by commas
    $param_parts = explode(',', $params_string);
    
    foreach ($param_parts as $param) {
        $param = trim($param);
        
        if (empty($param)) {
            continue;
        }
        
        // Extract parameter name and default value
        $param_info = explode('=', $param);
        $param_name_part = trim($param_info[0]);
        
        // Extract type hint if present
        $type_hint = 'mixed';
        $param_name = $param_name_part;
        
        if (strpos($param_name_part, ' ') !== false) {
            list($type_hint, $param_name) = explode(' ', $param_name_part, 2);
        }
        
        // Remove $ from parameter name if present
        $param_name = ltrim($param_name, '$');
        
        // Create a description based on the parameter name
        $description = create_description_from_param_name($param_name);
        
        $params[] = array(
            'name' => $param_name,
            'type' => $type_hint,
            'description' => $description,
        );
    }
    
    return $params;
}

/**
 * Create a description from a parameter name
 *
 * @param string $param_name  Parameter name
 * @return string Description
 */
function create_description_from_param_name($param_name) {
    // Convert underscores to spaces
    $name = str_replace('_', ' ', $param_name);
    
    // Capitalize first letter
    $name = ucfirst($name);
    
    return $name;
}