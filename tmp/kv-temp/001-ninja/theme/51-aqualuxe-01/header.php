<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <?php
    // Check if this is a distraction-free checkout page
    $is_distraction_free_checkout = false;
    
    if (aqualuxe_is_woocommerce_active()) {
        if (is_checkout() && aqualuxe_get_option('checkout_distraction_free', false)) {
            $is_distraction_free_checkout = true;
        }
    }
    
    // Display header if not a distraction-free checkout page
    if (!$is_distraction_free_checkout) :
    ?>
    
    <header id="masthead" class="site-header aqualuxe-header">
        <?php do_action('aqualuxe_header'); ?>
    </header><!-- #masthead -->
    
    <?php endif; ?>

    <div id="content" class="site-content">
        <?php do_action('aqualuxe_content_top'); ?>