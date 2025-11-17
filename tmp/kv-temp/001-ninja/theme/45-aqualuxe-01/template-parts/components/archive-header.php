<?php
/**
 * Template part for displaying archive headers
 *
 * @package AquaLuxe
 */

// Get archive header options
$show_archive_header = get_theme_mod('aqualuxe_show_archive_header', true);
$archive_header_style = get_theme_mod('aqualuxe_archive_header_style', 'default');
$archive_header_alignment = get_theme_mod('aqualuxe_archive_header_alignment', 'center');
$archive_header_height = get_theme_mod('aqualuxe_archive_header_height', 'medium');
$show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);

// Check if archive header should be displayed
if (!$show_archive_header) {
    return;
}

// Get archive header background
$archive_header_bg = get_theme_mod('aqualuxe_archive_header_bg', '');
$archive_header_bg_overlay = get_theme_mod('aqualuxe_archive_header_overlay', 'rgba(0,0,0,0.5)');

// Archive header classes
$archive_header_classes = array('archive-header');
$archive_header_classes[] = 'archive-header-' . $archive_header_style;
$archive_header_classes[] = 'archive-header-align-' . $archive_header_alignment;
$archive_header_classes[] = 'archive-header-height-' . $archive_header_height;

if (!empty($archive_header_bg)) {
    $archive_header_classes[] = 'has-background';
}

// Get archive title and description
$archive_title = get_the_archive_title();
$archive_description = get_the_archive_description();

// Remove "Category:", "Tag:", etc. from the archive title
$archive_title = preg_replace('/(Category:|Tag:|Author:|Archives:)/i', '', $archive_title);

// Allow filtering of archive title and description
$archive_title = apply_filters('aqualuxe_archive_header_title', $archive_title);
$archive_description = apply_filters('aqualuxe_archive_header_description', $archive_description);

// Get term image if available
$term_image = '';
if (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
        $term_id = $term->term_id;
        $taxonomy = $term->taxonomy;
        
        // Check for ACF term image
        if (function_exists('get_field')) {
            $term_image_id = get_field('term_image', $taxonomy . '_' . $term_id);
            if (!empty($term_image_id)) {
                $term_image = wp_get_attachment_image_url($term_image_id, 'full');
            }
        }
        
        // Check for other term meta
        if (empty($term_image)) {
            $term_image_id = get_term_meta($term_id, 'thumbnail_id', true);
            if (!empty($term_image_id)) {
                $term_image = wp_get_attachment_image_url($term_image_id, 'full');
            }
        }
    }
}

// Use term image if available, otherwise use default
if (!empty($term_image)) {
    $archive_header_bg = $term_image;
}
?>

<div class="<?php echo esc_attr(implode(' ', $archive_header_classes)); ?>">
    <?php if (!empty($archive_header_bg)) : ?>
        <div class="archive-header-bg" style="background-image: url('<?php echo esc_url($archive_header_bg); ?>');">
            <div class="archive-header-overlay" style="background-color: <?php echo esc_attr($archive_header_bg_overlay); ?>;"></div>
        </div>
    <?php endif; ?>
    
    <div class="container">
        <div class="archive-header-content">
            <h1 class="archive-title"><?php echo wp_kses_post($archive_title); ?></h1>
            
            <?php if (!empty($archive_description)) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post($archive_description); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Breadcrumbs
            if ($show_breadcrumbs) {
                aqualuxe_breadcrumbs();
            }
            ?>
            
            <?php
            // Category/Term Filter for archives
            if (is_archive() && !is_author() && !is_date()) {
                $taxonomy = '';
                
                if (is_category()) {
                    $taxonomy = 'category';
                } elseif (is_tag()) {
                    $taxonomy = 'post_tag';
                } elseif (is_tax()) {
                    $term = get_queried_object();
                    $taxonomy = $term->taxonomy;
                }
                
                if (!empty($taxonomy)) {
                    $terms = get_terms(array(
                        'taxonomy'   => $taxonomy,
                        'hide_empty' => true,
                    ));
                    
                    if (!empty($terms) && !is_wp_error($terms)) {
                        ?>
                        <div class="archive-filter">
                            <ul class="term-filter">
                                <li class="<?php echo is_archive() && !is_tax() && !is_category() && !is_tag() ? 'active' : ''; ?>">
                                    <a href="<?php echo esc_url(get_post_type_archive_link(get_post_type())); ?>">
                                        <?php echo esc_html__('All', 'aqualuxe'); ?>
                                    </a>
                                </li>
                                
                                <?php foreach ($terms as $term) : ?>
                                    <li class="<?php echo is_tax($taxonomy, $term->slug) || is_category($term->slug) || is_tag($term->slug) ? 'active' : ''; ?>">
                                        <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                            <?php echo esc_html($term->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</div>