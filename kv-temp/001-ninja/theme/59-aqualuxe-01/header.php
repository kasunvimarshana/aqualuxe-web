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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
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

    <?php
    /**
     * Hook: aqualuxe_before_header
     *
     * @hooked aqualuxe_top_bar - 10
     * @hooked aqualuxe_announcement_bar - 20
     */
    do_action('aqualuxe_before_header');
    ?>

    <?php get_template_part('templates/parts/header'); ?>

    <div id="content" class="site-content">
        <?php
        /**
         * Hook: aqualuxe_before_main_content
         *
         * @hooked aqualuxe_page_header - 10
         * @hooked aqualuxe_breadcrumbs - 20
         */
        do_action('aqualuxe_before_main_content');
        ?>