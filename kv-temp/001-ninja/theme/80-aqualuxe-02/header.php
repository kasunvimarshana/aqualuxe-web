<?php ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100'); ?>>
<?php wp_body_open(); ?>
<?php do_action('aqualuxe/header'); ?>
<main id="content" tabindex="-1">
