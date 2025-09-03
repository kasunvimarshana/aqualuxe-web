<?php
/**
 * The header for the theme.
 *
 * @package Aqualuxe
 */
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

<header id="masthead" class="site-header" role="banner">
    <div class="container">
        <div class="branding">
            <?php if ( has_custom_logo() ) {
                the_custom_logo();
            } else { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title"><?php bloginfo( 'name' ); ?></a>
            <?php } ?>
        </div>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="Primary">
            <?php wp_nav_menu( [ 'theme_location' => 'primary', 'menu_class' => 'menu', 'container' => false ] ); ?>
        </nav>
    </div>
</header>

<div id="content" class="site-content">
