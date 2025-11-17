<?php
/**
 * Header template.
 *
 * @package Aqualuxe
 */
if (!defined('ABSPATH')) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a class="screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
<header class="site-header" role="banner">
	<div class="container">
		<?php if (has_custom_logo()) { the_custom_logo(); } else { ?>
			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
		<?php } ?>
		<nav class="primary-nav" role="navigation" aria-label="<?php esc_attr_e('Primary', 'aqualuxe'); ?>">
			<?php wp_nav_menu(['theme_location' => 'primary', 'container' => false]); ?>
		</nav>
	</div>
</header>
<div id="content" class="site-content">
