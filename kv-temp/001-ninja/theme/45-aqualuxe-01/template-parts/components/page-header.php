<?php
/**
 * Template part for displaying page headers
 *
 * @package AquaLuxe
 */

// Get page header options
$show_page_header = get_theme_mod('aqualuxe_show_page_header', true);
$page_header_style = get_theme_mod('aqualuxe_page_header_style', 'default');
$page_header_alignment = get_theme_mod('aqualuxe_page_header_alignment', 'center');
$page_header_height = get_theme_mod('aqualuxe_page_header_height', 'medium');
$show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);

// Check if page header should be displayed
if (!$show_page_header) {
    return;
}

// Get page header background
$page_header_bg = '';
$page_header_bg_overlay = '';

// Check for page-specific header background
$page_header_bg_id = get_post_meta(get_the_ID(), '_aqualuxe_page_header_bg', true);
if (!empty($page_header_bg_id)) {
    $page_header_bg = wp_get_attachment_image_url($page_header_bg_id, 'full');
} else {
    // Use default header background from theme options
    $page_header_bg = get_theme_mod('aqualuxe_page_header_bg', '');
}

// Get page header overlay
$page_header_bg_overlay = get_theme_mod('aqualuxe_page_header_overlay', 'rgba(0,0,0,0.5)');

// Page header classes
$page_header_classes = array('page-header');
$page_header_classes[] = 'page-header-' . $page_header_style;
$page_header_classes[] = 'page-header-align-' . $page_header_alignment;
$page_header_classes[] = 'page-header-height-' . $page_header_height;

if (!empty($page_header_bg)) {
    $page_header_classes[] = 'has-background';
}

// Get page title
$page_title = '';
$page_subtitle = '';

if (is_home()) {
    $page_title = get_the_title(get_option('page_for_posts', true));
} elseif (is_archive()) {
    $page_title = get_the_archive_title();
    $page_subtitle = get_the_archive_description();
} elseif (is_search()) {
    /* translators: %s: search query. */
    $page_title = sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
} elseif (is_404()) {
    $page_title = esc_html__('Page Not Found', 'aqualuxe');
} else {
    $page_title = get_the_title();
    $page_subtitle = get_post_meta(get_the_ID(), '_aqualuxe_page_subtitle', true);
}

// Allow filtering of page title and subtitle
$page_title = apply_filters('aqualuxe_page_header_title', $page_title);
$page_subtitle = apply_filters('aqualuxe_page_header_subtitle', $page_subtitle);
?>

<div class="<?php echo esc_attr(implode(' ', $page_header_classes)); ?>">
    <?php if (!empty($page_header_bg)) : ?>
        <div class="page-header-bg" style="background-image: url('<?php echo esc_url($page_header_bg); ?>');">
            <div class="page-header-overlay" style="background-color: <?php echo esc_attr($page_header_bg_overlay); ?>;"></div>
        </div>
    <?php endif; ?>
    
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title"><?php echo wp_kses_post($page_title); ?></h1>
            
            <?php if (!empty($page_subtitle)) : ?>
                <div class="page-subtitle"><?php echo wp_kses_post($page_subtitle); ?></div>
            <?php endif; ?>
            
            <?php
            // Breadcrumbs
            if ($show_breadcrumbs) {
                aqualuxe_breadcrumbs();
            }
            ?>
        </div>
    </div>
</div>