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
    // Get header layout
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'standard');
    $sticky_header = get_theme_mod('aqualuxe_sticky_header', true);
    $show_search = get_theme_mod('aqualuxe_show_search', true);
    
    // Header classes
    $header_classes = [
        'site-header',
        'bg-white',
        'dark:bg-gray-900',
        'border-b',
        'border-gray-200',
        'dark:border-gray-700',
    ];
    
    if ($sticky_header) {
        $header_classes[] = 'sticky';
        $header_classes[] = 'top-0';
        $header_classes[] = 'z-50';
    }
    ?>

    <header id="masthead" class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
        <?php
        // Top bar
        get_template_part('templates/header/top-bar');
        
        // Load header layout
        get_template_part('templates/header/header', $header_layout);
        ?>
    </header><!-- #masthead -->

    <?php
    // Display breadcrumbs
    if (!is_front_page()) {
        aqualuxe_breadcrumbs();
    }
    ?>

    <div id="content" class="site-content">