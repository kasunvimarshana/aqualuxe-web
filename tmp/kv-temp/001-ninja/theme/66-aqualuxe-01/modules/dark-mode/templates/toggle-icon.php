<?php
/**
 * Dark Mode Toggle Icon Template
 *
 * @package AquaLuxe\Modules\DarkMode
 */

// Get module instance
$theme = \AquaLuxe\Theme::get_instance();
$module = $theme->get_active_modules()['dark-mode'] ?? null;

if (!$module) {
    return;
}

// Get current mode
$current_mode = $module->get_current_mode();
?>

<div class="dark-mode-toggle dark-mode-toggle--icon">
    <div class="dark-mode-toggle__icon">
        <span class="dark-mode-toggle__icon-light" <?php echo $current_mode !== 'light' ? 'style="display:none;"' : ''; ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
        </span>
        <span class="dark-mode-toggle__icon-dark" <?php echo $current_mode !== 'dark' ? 'style="display:none;"' : ''; ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </span>
        <span class="dark-mode-toggle__icon-auto" <?php echo $current_mode !== 'auto' ? 'style="display:none;"' : ''; ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 3v18"></path>
                <path d="M3 12h18"></path>
            </svg>
        </span>
    </div>
</div>