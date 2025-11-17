<!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php echo esc_attr(aqualuxe_is_module_enabled('dark-mode') && isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true' ? 'dark' : ''); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
    <a class="skip-link screen-reader-text sr-only focus:not-sr-only focus:absolute focus:top-0 focus:left-0 bg-primary text-white p-2 z-50" href="#main">
        <?php esc_html_e('Skip to content', 'aqualuxe'); ?>
    </a>

    <?php aqualuxe_header(); ?>

    <main id="main" class="site-main flex-1">
        <?php aqualuxe_hero(); ?>
        <?php aqualuxe_page_header(); ?>
