<?php
/**
 * Template part for displaying site branding
 *
 * @package AquaLuxe
 */

?>

<div class="site-branding flex items-center">
    <?php if (has_custom_logo()) : ?>
        <div class="site-logo">
            <?php the_custom_logo(); ?>
        </div>
    <?php else : ?>
        <div class="site-title-wrapper">
            <?php if (is_front_page() && is_home()) : ?>
                <h1 class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-primary-600 transition-colors duration-200">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
            <?php else : ?>
                <p class="site-title text-2xl font-bold text-gray-900 dark:text-white">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="hover:text-primary-600 transition-colors duration-200">
                        <?php bloginfo('name'); ?>
                    </a>
                </p>
            <?php endif; ?>
            
            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) :
            ?>
                <p class="site-description text-sm text-gray-600 dark:text-gray-400 mt-1">
                    <?php echo $description; ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>