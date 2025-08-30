<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Determine which sidebar to use
$sidebar = 'sidebar-1';

// Use shop sidebar for WooCommerce pages
if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
    $sidebar = 'sidebar-shop';
}

// Don't show sidebar if it's not active or if we're on a full-width template
if (!is_active_sidebar($sidebar) || is_page_template('templates/full-width.php') || is_page_template('templates/homepage.php')) {
    return;
}
?>

<aside id="secondary" class="widget-area">
    <?php dynamic_sidebar($sidebar); ?>
</aside><!-- #secondary -->