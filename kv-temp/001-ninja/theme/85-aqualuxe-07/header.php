<?php
/**
 * Header template loader
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php if (function_exists('wp_body_open')) { wp_body_open(); } ?>
<a class="skip-link screen-reader-text" href="#primary"><?php _e('Skip to content', 'aqualuxe'); ?></a>
<?php get_template_part('templates/parts/header'); ?>
