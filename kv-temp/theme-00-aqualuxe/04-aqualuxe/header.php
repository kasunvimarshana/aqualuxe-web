<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

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

    <header id="masthead" class="site-header" role="banner">
        <div class="col-full">
            <?php
            // Site branding
            if (function_exists('storefront_site_branding')) {
                storefront_site_branding();
            } else {
                ?>
                <div class="site-branding">
                    <?php aqualuxe_site_logo(); ?>
                    <?php aqualuxe_site_title(); ?>
                    <?php aqualuxe_site_description(); ?>
                </div>
                <?php
            }
            
            // Primary navigation
            if (function_exists('storefront_primary_navigation')) {
                storefront_primary_navigation();
            } else {
                aqualuxe_primary_navigation();
            }
            ?>
        </div>
        
        <?php
        // Secondary navigation
        if (function_exists('storefront_secondary_navigation')) {
            storefront_secondary_navigation();
        } else {
            aqualuxe_secondary_navigation();
        }
        ?>
    </header><!-- #masthead -->

    <?php
    // Breadcrumbs
    aqualuxe_breadcrumbs();
    ?>

    <div id="content" class="site-content" tabindex="-1">
        <div class="col-full">