<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Determine which sidebar to display
if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
    $sidebar = 'sidebar-shop';
} else {
    $sidebar = 'sidebar-1';
}

// If the sidebar has no widgets, then exit
if (!is_active_sidebar($sidebar)) {
    return;
}
?>

<aside id="secondary" class="widget-area bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6">
    <?php dynamic_sidebar($sidebar); ?>
</aside><!-- #secondary -->