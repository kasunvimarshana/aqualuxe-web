<?php
/**
 * Template part for displaying site branding
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="site-branding flex items-center">
    <?php if (has_custom_logo()) : ?>
        <div class="site-logo mr-4">
            <?php the_custom_logo(); ?>
        </div>
    <?php endif; ?>
    
    <div class="site-info">
        <?php if (is_front_page() && is_home()) : ?>
            <h1 class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
        <?php else : ?>
            <p class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <?php bloginfo('name'); ?>
                </a>
            </p>
        <?php endif; ?>
        
        <?php
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) :
        ?>
            <p class="site-description text-sm text-gray-600 dark:text-gray-300 mt-1">
                <?php echo esc_html($description); ?>
            </p>
        <?php endif; ?>
    </div>
</div>