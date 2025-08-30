<?php
/**
 * Template part for displaying single post headers
 *
 * @package AquaLuxe
 */

// Get single header options
$show_single_header = get_theme_mod('aqualuxe_show_single_header', true);
$single_header_style = get_theme_mod('aqualuxe_single_header_style', 'default');
$single_header_alignment = get_theme_mod('aqualuxe_single_header_alignment', 'center');
$single_header_height = get_theme_mod('aqualuxe_single_header_height', 'medium');
$show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);

// Check if single header should be displayed
if (!$show_single_header) {
    return;
}

// Get single header background
$single_header_bg = '';
$single_header_bg_overlay = '';

// Check for post-specific header background
$single_header_bg_id = get_post_meta(get_the_ID(), '_aqualuxe_single_header_bg', true);
if (!empty($single_header_bg_id)) {
    $single_header_bg = wp_get_attachment_image_url($single_header_bg_id, 'full');
} elseif (has_post_thumbnail()) {
    // Use featured image as header background
    $single_header_bg = get_the_post_thumbnail_url(get_the_ID(), 'full');
} else {
    // Use default header background from theme options
    $single_header_bg = get_theme_mod('aqualuxe_single_header_bg', '');
}

// Get single header overlay
$single_header_bg_overlay = get_theme_mod('aqualuxe_single_header_overlay', 'rgba(0,0,0,0.5)');

// Single header classes
$single_header_classes = array('single-header');
$single_header_classes[] = 'single-header-' . $single_header_style;
$single_header_classes[] = 'single-header-align-' . $single_header_alignment;
$single_header_classes[] = 'single-header-height-' . $single_header_height;

if (!empty($single_header_bg)) {
    $single_header_classes[] = 'has-background';
}

// Get post title and subtitle
$post_title = get_the_title();
$post_subtitle = get_post_meta(get_the_ID(), '_aqualuxe_post_subtitle', true);

// Allow filtering of post title and subtitle
$post_title = apply_filters('aqualuxe_single_header_title', $post_title);
$post_subtitle = apply_filters('aqualuxe_single_header_subtitle', $post_subtitle);
?>

<div class="<?php echo esc_attr(implode(' ', $single_header_classes)); ?>">
    <?php if (!empty($single_header_bg)) : ?>
        <div class="single-header-bg" style="background-image: url('<?php echo esc_url($single_header_bg); ?>');">
            <div class="single-header-overlay" style="background-color: <?php echo esc_attr($single_header_bg_overlay); ?>;"></div>
        </div>
    <?php endif; ?>
    
    <div class="container">
        <div class="single-header-content">
            <?php
            // Post Categories
            if ('post' === get_post_type() && get_theme_mod('aqualuxe_show_single_header_categories', true)) {
                $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
                if ($categories_list) {
                    ?>
                    <div class="entry-categories">
                        <span class="cat-links"><?php echo wp_kses_post($categories_list); ?></span>
                    </div>
                    <?php
                }
            }
            ?>
            
            <h1 class="entry-title"><?php echo wp_kses_post($post_title); ?></h1>
            
            <?php if (!empty($post_subtitle)) : ?>
                <div class="entry-subtitle"><?php echo wp_kses_post($post_subtitle); ?></div>
            <?php endif; ?>
            
            <?php
            // Post Meta
            if ('post' === get_post_type() && get_theme_mod('aqualuxe_show_single_header_meta', true)) {
                ?>
                <div class="entry-meta">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    
                    if (get_theme_mod('aqualuxe_show_single_header_comments', true)) {
                        aqualuxe_comments_link();
                    }
                    ?>
                </div><!-- .entry-meta -->
                <?php
            }
            
            // Breadcrumbs
            if ($show_breadcrumbs) {
                aqualuxe_breadcrumbs();
            }
            ?>
        </div>
    </div>
</div>