
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
<!DOCTYPE html>
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

    <header id="masthead" class="site-header">
        <?php
        /**
         * Functions hooked into aqualuxe_header action
         *
         * @hooked aqualuxe_header_top_bar - 10
         * @hooked aqualuxe_header_main - 20
         * @hooked aqualuxe_header_navigation - 30
         */
        do_action('aqualuxe_header');
        ?>
    </header><!-- #masthead -->

    <?php
    /**
     * Functions hooked into aqualuxe_before_content action
     *
     * @hooked aqualuxe_page_header - 10
     * @hooked aqualuxe_breadcrumbs - 20
     */
    do_action('aqualuxe_before_content');
    ?>

    <div id="content" class="site-content">
        <div class="container">
            <div class="row">
