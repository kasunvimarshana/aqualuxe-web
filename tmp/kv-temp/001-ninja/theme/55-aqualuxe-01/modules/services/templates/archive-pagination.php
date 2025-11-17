<?php
/**
 * Services archive pagination template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get module instance
global $aqualuxe_theme;
$module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

// Get pagination type
$pagination_type = $module ? $module->get_option('pagination_type', 'numbered') : 'numbered';

if ($pagination_type === 'load_more') {
    // Load more pagination
    global $wp_query;
    
    if ($wp_query->max_num_pages > 1) {
        // Encode the current query for AJAX
        $current_query = json_encode($wp_query->query_vars);
        ?>
        <div class="aqualuxe-services-pagination aqualuxe-services-load-more-wrapper">
            <button class="aqualuxe-services-load-more" data-page="2" data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>" data-query="<?php echo esc_attr($current_query); ?>">
                <?php esc_html_e('Load More', 'aqualuxe'); ?>
            </button>
        </div>
        <?php
    }
} elseif ($pagination_type === 'infinite_scroll') {
    // Infinite scroll pagination
    global $wp_query;
    
    if ($wp_query->max_num_pages > 1) {
        // Encode the current query for AJAX
        $current_query = json_encode($wp_query->query_vars);
        ?>
        <div class="aqualuxe-services-pagination aqualuxe-services-infinite-scroll" data-page="2" data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>" data-query="<?php echo esc_attr($current_query); ?>">
            <div class="aqualuxe-services-infinite-scroll-loader">
                <span class="aqualuxe-services-infinite-scroll-loader-icon"></span>
                <span class="aqualuxe-services-infinite-scroll-loader-text"><?php esc_html_e('Loading...', 'aqualuxe'); ?></span>
            </div>
        </div>
        <?php
    }
} else {
    // Numbered pagination
    the_posts_pagination([
        'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous page', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'aqualuxe') . '</span>',
        'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'aqualuxe') . ' </span>',
    ]);
}