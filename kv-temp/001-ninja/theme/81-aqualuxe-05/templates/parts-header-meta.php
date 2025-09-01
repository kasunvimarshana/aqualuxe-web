<?php if (!defined('ABSPATH')) exit; ?>
<meta name="description" content="<?php echo esc_attr( get_bloginfo('description') ); ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo esc_url( home_url( $_SERVER['REQUEST_URI'] ?? '/' ) ); ?>">
<meta property="og:title" content="<?php echo esc_attr( wp_get_document_title() ); ?>">
<meta property="og:description" content="<?php echo esc_attr( get_bloginfo('description') ); ?>">
