<?php
/**
 * Template part for displaying related posts on single posts
 *
 * @package AquaLuxe
 */

// Get related posts options from theme customizer
$show_related_posts = get_theme_mod('aqualuxe_show_related_posts', true);
$related_posts_title = get_theme_mod('aqualuxe_related_posts_title', __('Related Posts', 'aqualuxe'));
$related_posts_count = get_theme_mod('aqualuxe_related_posts_count', 3);
$related_posts_columns = get_theme_mod('aqualuxe_related_posts_columns', 3);
$related_posts_orderby = get_theme_mod('aqualuxe_related_posts_orderby', 'date');
$related_posts_order = get_theme_mod('aqualuxe_related_posts_order', 'DESC');
$related_posts_by = get_theme_mod('aqualuxe_related_posts_by', 'category');

// Check if related posts should be displayed
if (!$show_related_posts) {
    return;
}

// Get current post ID
$post_id = get_the_ID();

// Get taxonomy terms
$terms = array();
$term_ids = array();

if ($related_posts_by === 'category' || $related_posts_by === 'both') {
    $categories = get_the_category($post_id);
    if (!empty($categories)) {
        foreach ($categories as $category) {
            $term_ids[] = $category->term_id;
        }
    }
}

if ($related_posts_by === 'tag' || $related_posts_by === 'both') {
    $tags = get_the_tags($post_id);
    if (!empty($tags)) {
        foreach ($tags as $tag) {
            $term_ids[] = $tag->term_id;
        }
    }
}

// If no terms found, return
if (empty($term_ids)) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $related_posts_count,
    'orderby'        => $related_posts_orderby,
    'order'          => $related_posts_order,
    'post_status'    => 'publish',
    'post__not_in'   => array($post_id),
);

// Add taxonomy query
if ($related_posts_by === 'category') {
    $args['category__in'] = $term_ids;
} elseif ($related_posts_by === 'tag') {
    $args['tag__in'] = $term_ids;
} else {
    // For 'both', we need to use tax_query
    $args['tax_query'] = array(
        'relation' => 'OR',
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $term_ids,
        ),
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'term_id',
            'terms'    => $term_ids,
        ),
    );
}

// Get related posts
$related_posts_query = new WP_Query($args);

// Check if we have related posts
if (!$related_posts_query->have_posts()) {
    return;
}

// Set up column classes
$column_class = 'col-lg-4 col-md-6';

switch ($related_posts_columns) {
    case 2:
        $column_class = 'col-lg-6 col-md-6';
        break;
    case 3:
        $column_class = 'col-lg-4 col-md-6';
        break;
    case 4:
        $column_class = 'col-lg-3 col-md-6';
        break;
}
?>

<div class="related-posts">
    <?php if (!empty($related_posts_title)) : ?>
        <h3 class="related-posts-title"><?php echo esc_html($related_posts_title); ?></h3>
    <?php endif; ?>
    
    <div class="row">
        <?php
        // Loop through related posts
        while ($related_posts_query->have_posts()) :
            $related_posts_query->the_post();
            ?>
            <div class="<?php echo esc_attr($column_class); ?>">
                <div class="related-post">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="related-post-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('aqualuxe-blog-thumbnail', array('class' => 'img-fluid')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="related-post-content">
                        <h4 class="related-post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="related-post-meta">
                            <span class="related-post-date">
                                <i class="far fa-calendar-alt"></i>
                                <?php echo get_the_date(); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
</div>