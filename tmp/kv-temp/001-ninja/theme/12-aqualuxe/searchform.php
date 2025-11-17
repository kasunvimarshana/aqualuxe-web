<?php
/**
 * Custom search form template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$unique_id = wp_unique_id('search-form-');
$aria_label = !empty($args['aria_label']) ? 'aria-label="' . esc_attr($args['aria_label']) . '"' : '';
?>

<form role="search" <?php echo $aria_label; ?> method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form-inner">
        <label for="<?php echo esc_attr($unique_id); ?>" class="screen-reader-text"><?php echo esc_html_x('Search for:', 'label', 'aqualuxe'); ?></label>
        <input type="search" id="<?php echo esc_attr($unique_id); ?>" class="search-field" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x('Search', 'submit button', 'aqualuxe'); ?>">
            <i class="fas fa-search"></i>
            <span class="screen-reader-text"><?php echo esc_html_x('Search', 'submit button', 'aqualuxe'); ?></span>
        </button>
    </div>
    
    <?php if (class_exists('WooCommerce') && get_theme_mod('aqualuxe_search_product_only', false)) : ?>
        <input type="hidden" name="post_type" value="product" />
    <?php endif; ?>
</form>