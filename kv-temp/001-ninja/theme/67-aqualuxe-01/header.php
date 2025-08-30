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
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Display preloader if enabled
aqualuxe_preloader();
?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <header id="masthead" class="site-header header-layout-<?php echo esc_attr( aqualuxe_get_header_layout() ); ?>">
        <?php
        /**
         * Functions hooked into aqualuxe_header action
         *
         * @hooked aqualuxe_header_before - 5
         * @hooked aqualuxe_header_top - 10
         * @hooked aqualuxe_header_main - 20
         * @hooked aqualuxe_header_bottom - 30
         * @hooked aqualuxe_header_after - 35
         */
        do_action( 'aqualuxe_header' );
        ?>
    </header><!-- #masthead -->

    <?php
    // Display breadcrumbs
    aqualuxe_breadcrumbs();
    
    // Display page title
    aqualuxe_page_title();
    ?>

    <div id="content" class="site-content">
        <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
            <div class="content-wrapper">
                <?php do_action( 'aqualuxe_content_before' ); ?>