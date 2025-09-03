<?php
?><!doctype html>
<html <?php if ( function_exists('language_attributes') ) { call_user_func('language_attributes'); } ?> >
<head>
    <meta charset="<?php
        if ( function_exists('get_bloginfo') ) {
            $cs = call_user_func('get_bloginfo', 'charset');
            echo function_exists('esc_attr') ? call_user_func('esc_attr', $cs ) : $cs;
        } else { echo 'UTF-8'; }
    ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( function_exists('wp_head') ) { call_user_func('wp_head'); } ?>
</head>
<body <?php if ( function_exists('body_class') ) { call_user_func('body_class'); } ?> >
<?php if ( function_exists('wp_body_open') ) { call_user_func('wp_body_open'); } ?>
<a class="skip-link screen-reader-text" href="#primary"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Skip to content', 'aqualuxe') : 'Skip to content'; ?></a>
<header class="site-header" role="banner">
    <div class="container mx-auto px-4 py-4 flex items-center justify-between">
        <div class="logo flex items-center gap-3">
            <?php if ( function_exists('the_custom_logo') ) { call_user_func('the_custom_logo'); } ?>
            <a href="<?php $h = function_exists('home_url') ? call_user_func('home_url','/') : '/'; echo function_exists('esc_url') ? call_user_func('esc_url', $h ) : $h; ?>" class="text-xl font-bold">AquaLuxe</a>
        </div>
        <button type="button" class="md:hidden btn btn-ghost" aria-controls="primary-menu" aria-expanded="false" data-toggle-nav>
            <span><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Menu','aqualuxe') : 'Menu'; ?></span>
        </button>
        <nav class="primary-nav hidden md:block" id="primary-menu" role="navigation" aria-label="Primary" aria-hidden="true">
            <?php if ( function_exists('wp_nav_menu') ) { call_user_func('wp_nav_menu', [
                'theme_location' => 'primary',
                'menu_class'     => 'flex flex-col md:flex-row gap-4 md:gap-6',
                'container'      => false,
            ] ); } ?>
        </nav>
        <div class="header-actions flex items-center gap-4">
            <?php if ( function_exists('aqualuxe_is_mini_cart_enabled') && aqualuxe_is_mini_cart_enabled() && function_exists('aqualuxe_cart_link_html') ) { echo aqualuxe_cart_link_html(); } ?>
        </div>
    </div>
</header>
<?php if ( class_exists('AquaLuxe\\Template\\Helpers') ) { AquaLuxe\Template\Helpers::breadcrumbs(); } ?>
<?php if ( function_exists('aqualuxe_is_mini_cart_enabled') && aqualuxe_is_mini_cart_enabled() ) : ?>
<!-- Mini Cart Drawer -->
<div id="alx-mini-cart" class="alx-mini-cart fixed inset-0 z-50 hidden" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="alx-mini-cart-title">
    <div class="alx-mini-cart__backdrop absolute inset-0 bg-black/50" data-minicart-close></div>
    <div class="alx-mini-cart__panel absolute right-0 top-0 h-full w-11/12 sm:w-96 bg-white dark:bg-dark shadow-xl p-4 pb-28 overflow-y-auto transform transition-transform duration-300 translate-x-full">
        <div class="flex items-center justify-between mb-4">
            <h2 id="alx-mini-cart-title" class="text-lg font-semibold"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Your Cart','aqualuxe') : 'Your Cart'; ?></h2>
            <button type="button" class="btn btn-ghost" aria-label="<?php echo function_exists('esc_attr__') ? call_user_func('esc_attr__','Close cart','aqualuxe') : 'Close cart'; ?>" data-minicart-close>&times;</button>
        </div>
        <?php if ( function_exists('aqualuxe_mini_cart_progress_html') ) { echo aqualuxe_mini_cart_progress_html(); } ?>
        <?php if ( function_exists('aqualuxe_mini_cart_content_html') ) { echo aqualuxe_mini_cart_content_html(); } ?>
        <?php if ( function_exists('aqualuxe_mini_cart_summary_html') ) { echo aqualuxe_mini_cart_summary_html(); } ?>
    </div>
    <span class="sr-only" aria-live="polite"></span>
</div>
<?php endif; ?>
<div id="content" class="site-content">
