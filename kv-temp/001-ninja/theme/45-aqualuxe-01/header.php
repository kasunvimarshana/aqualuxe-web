<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
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
    // Get the header style from customizer
    $header_style = get_theme_mod('aqualuxe_header_style', 'default');
    
    // Load the appropriate header template part
    get_template_part('template-parts/header/header', $header_style);
    
    // Display announcement bar if enabled
    if (get_theme_mod('aqualuxe_announcement_bar_enable', false)) {
        get_template_part('template-parts/header/announcement-bar');
    }
    ?>

    <div id="content" class="site-content">
        <?php
        // Display breadcrumbs if enabled and not on the front page
        if (get_theme_mod('aqualuxe_breadcrumbs_enable', true) && !is_front_page()) {
            get_template_part('template-parts/components/breadcrumbs');
        }
        ?>