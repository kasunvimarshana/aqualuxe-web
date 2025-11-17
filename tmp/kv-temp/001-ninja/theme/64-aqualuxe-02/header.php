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
<html <?php language_attributes(); ?> <?php aqualuxe_html_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <?php
    /**
     * Hook: aqualuxe_before_header
     */
    aqualuxe_do_before_header();
    ?>

    <header id="masthead" class="site-header" <?php aqualuxe_attr( 'header' ); ?>>
        <?php
        /**
         * Hook: aqualuxe_header_top
         */
        aqualuxe_do_header_top();
        ?>

        <?php
        /**
         * Hook: aqualuxe_header_main
         */
        aqualuxe_do_header_main();
        ?>

        <?php
        /**
         * Hook: aqualuxe_header_bottom
         */
        aqualuxe_do_header_bottom();
        ?>
    </header><!-- #masthead -->

    <?php
    /**
     * Hook: aqualuxe_after_header
     */
    aqualuxe_do_after_header();
    ?>

    <?php
    /**
     * Hook: aqualuxe_before_content
     */
    aqualuxe_do_before_content();
    ?>

    <div id="content" class="site-content">
        <div class="container">
            <div class="site-content-inner">
                <?php
                /**
                 * Hook: aqualuxe_content_top
                 */
                aqualuxe_do_content_top();
                ?>