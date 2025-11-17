<?php
/**
 * The header template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>
    
    <header id="masthead" class="site-header">
        <?php get_template_part('templates/partials/header/navigation'); ?>
        
        <?php if (is_front_page() && has_header_image()) : ?>
            <?php get_template_part('templates/partials/header/hero'); ?>
        <?php endif; ?>
    </header>
    
    <div id="content" class="site-content">