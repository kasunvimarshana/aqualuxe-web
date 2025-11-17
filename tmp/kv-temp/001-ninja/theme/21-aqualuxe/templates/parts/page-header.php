<?php
/**
 * Template part for displaying page header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get the header image for the current page/post
$header_image = '';
if (is_singular() && has_post_thumbnail()) {
    $header_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
} elseif (is_archive() || is_search()) {
    // For archives and search, use the default header image
    $header_image = get_header_image();
} else {
    // Default header image
    $header_image = get_header_image();
}

// Get the title
if (is_home()) {
    $title = get_the_title(get_option('page_for_posts', true));
} elseif (is_archive()) {
    $title = get_the_archive_title();
} elseif (is_search()) {
    /* translators: %s: search query. */
    $title = sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
} elseif (is_404()) {
    $title = esc_html__('Page Not Found', 'aqualuxe');
} else {
    $title = get_the_title();
}

// Get subtitle or description
$subtitle = '';
if (is_archive()) {
    $subtitle = get_the_archive_description();
} elseif (is_singular('post') || is_singular('page')) {
    // For posts and pages, you might want to use a custom field for subtitle
    $subtitle = get_post_meta(get_the_ID(), 'page_subtitle', true);
}
?>

<div class="page-header relative bg-blue-900 text-white">
    <?php if ($header_image) : ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo esc_url($header_image); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-cover opacity-30">
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 py-16 md:py-24 relative z-10">
        <div class="max-w-4xl">
            <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4"><?php echo wp_kses_post($title); ?></h1>
            
            <?php if ($subtitle) : ?>
                <div class="page-description text-lg text-teal-200">
                    <?php echo wp_kses_post($subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if (function_exists('yoast_breadcrumb') && !is_front_page()) : ?>
                <div class="breadcrumbs mt-6 text-sm text-teal-100">
                    <?php yoast_breadcrumb(); ?>
                </div>
            <?php elseif (!is_front_page()) : ?>
                <div class="breadcrumbs mt-6 text-sm text-teal-100">
                    <?php aqualuxe_breadcrumbs(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>