<?php
/**
 * AquaLuxe Dark Mode Installation Script
 * 
 * This script updates the necessary files to implement dark mode functionality.
 */

// Define paths
$theme_dir = dirname(__FILE__);
$inc_dir = $theme_dir . '/inc';
$hooks_dir = $inc_dir . '/hooks';

// Create dark-mode.php if it doesn't exist
if (!file_exists($inc_dir . '/dark-mode.php')) {
    copy($theme_dir . '/inc/dark-mode.php', $inc_dir . '/dark-mode.php');
    echo "Created dark-mode.php\n";
}

// Update functions.php
if (file_exists($theme_dir . '/functions.php') && file_exists($theme_dir . '/functions-updated.php')) {
    copy($theme_dir . '/functions-updated.php', $theme_dir . '/functions.php');
    echo "Updated functions.php\n";
}

// Update header.php
if (file_exists($theme_dir . '/header.php') && file_exists($theme_dir . '/header-updated.php')) {
    copy($theme_dir . '/header-updated.php', $theme_dir . '/header.php');
    echo "Updated header.php\n";
}

// Update hooks.php
if (file_exists($hooks_dir . '/hooks.php') && file_exists($theme_dir . '/inc/hooks/hooks-updated.php')) {
    // Append the new hooks to the existing hooks.php file
    $new_hooks = file_get_contents($theme_dir . '/inc/hooks/hooks-updated.php');
    $existing_hooks = file_get_contents($hooks_dir . '/hooks.php');
    
    // Check if the new hooks are already in the file
    if (strpos($existing_hooks, 'aqualuxe_top_bar_right') === false) {
        file_put_contents($hooks_dir . '/hooks.php', $existing_hooks . "\n\n" . $new_hooks);
        echo "Updated hooks.php\n";
    } else {
        echo "hooks.php already contains the necessary hooks\n";
    }
}

echo "Dark mode installation complete!\n";
echo "Please check the following files:\n";
echo "- header.php\n";
echo "- functions.php\n";
echo "- inc/dark-mode.php\n";
echo "- inc/hooks/hooks.php\n";