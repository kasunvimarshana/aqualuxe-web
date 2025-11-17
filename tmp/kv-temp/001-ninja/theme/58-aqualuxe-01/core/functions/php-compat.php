<?php
/**
 * PHP compatibility functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display PHP compatibility notice
 */
function aqualuxe_php_compatibility_notice() {
    ?>
    <div class="error">
        <p>
            <?php
            printf(
                /* translators: %1$s: Theme name, %2$s: Required PHP version, %3$s: Current PHP version */
                esc_html__('%1$s theme requires PHP version %2$s or higher. Your current PHP version is %3$s. Please upgrade PHP to use this theme.', 'aqualuxe'),
                'AquaLuxe',
                '7.4',
                PHP_VERSION
            );
            ?>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'aqualuxe_php_compatibility_notice');

/**
 * Switch to default theme
 */
function aqualuxe_switch_theme() {
    switch_theme(WP_DEFAULT_THEME);
    unset($_GET['activated']);
    add_action('admin_notices', 'aqualuxe_upgrade_notice');
}
add_action('after_switch_theme', 'aqualuxe_switch_theme');

/**
 * Display upgrade notice
 */
function aqualuxe_upgrade_notice() {
    ?>
    <div class="error">
        <p>
            <?php
            printf(
                /* translators: %1$s: Theme name, %2$s: Required PHP version, %3$s: Current PHP version */
                esc_html__('%1$s theme requires PHP version %2$s or higher. Your current PHP version is %3$s. The theme has been switched back to the default theme.', 'aqualuxe'),
                'AquaLuxe',
                '7.4',
                PHP_VERSION
            );
            ?>
        </p>
    </div>
    <?php
}