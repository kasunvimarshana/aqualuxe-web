<?php
/**
 * PHP compatibility check
 *
 * @package AquaLuxe
 */

/**
 * Display PHP compatibility notice
 *
 * @return void
 */
function aqualuxe_php_compat_notice() {
    ?>
    <div class="notice notice-error">
        <p>
            <?php
            printf(
                /* translators: %1$s: Theme name, %2$s: Required PHP version, %3$s: Current PHP version */
                esc_html__('%1$s requires PHP version %2$s or higher. You are running version %3$s. Please upgrade PHP or use a different theme.', 'aqualuxe'),
                '<strong>AquaLuxe</strong>',
                '<strong>7.4</strong>',
                '<strong>' . PHP_VERSION . '</strong>'
            );
            ?>
        </p>
    </div>
    <?php
}

// Add admin notice
add_action('admin_notices', 'aqualuxe_php_compat_notice');

// Switch to default theme
if (is_admin()) {
    add_action('after_switch_theme', 'aqualuxe_switch_theme');
}

/**
 * Switch to default theme
 *
 * @return void
 */
function aqualuxe_switch_theme() {
    switch_theme(WP_DEFAULT_THEME);
    unset($_GET['activated']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    add_action('admin_notices', 'aqualuxe_upgrade_notice');
}

/**
 * Display upgrade notice
 *
 * @return void
 */
function aqualuxe_upgrade_notice() {
    ?>
    <div class="notice notice-warning">
        <p>
            <?php
            printf(
                /* translators: %1$s: Theme name, %2$s: Required PHP version, %3$s: Current PHP version */
                esc_html__('%1$s requires PHP version %2$s or higher. You are running version %3$s. The theme has been auto-deactivated. Please upgrade PHP or use a different theme.', 'aqualuxe'),
                '<strong>AquaLuxe</strong>',
                '<strong>7.4</strong>',
                '<strong>' . PHP_VERSION . '</strong>'
            );
            ?>
        </p>
    </div>
    <?php
}