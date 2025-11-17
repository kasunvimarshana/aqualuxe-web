<?php
// Minimal bootstrap: load Composer autoload if present, else rely on theme autoloader in functions.php is not available here.
$composer = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composer)) {
	require_once $composer;
}

// Load theme PSR-4 mapping manually to test App without WP
spl_autoload_register(static function ($class) {
	$prefix = 'Aqualuxe\\';
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) { return; }
	$relative = substr($class, $len);
	$relativePath = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';
	if (file_exists($relativePath)) { require_once $relativePath; }
});
