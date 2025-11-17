<?php
/**
 * Template part for displaying the dark mode toggle
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Only show if dark mode is enabled in theme settings
if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
    return;
}

// Get current color scheme
$color_scheme = aqualuxe_get_color_scheme();
?>

<button id="dark-mode-toggle" class="dark-mode-toggle fixed bottom-8 left-8 z-50 bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>" data-current-mode="<?php echo esc_attr($color_scheme); ?>">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sun-icon <?php echo $color_scheme === 'dark' ? '' : 'hidden'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 moon-icon <?php echo $color_scheme === 'light' ? '' : 'hidden'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>