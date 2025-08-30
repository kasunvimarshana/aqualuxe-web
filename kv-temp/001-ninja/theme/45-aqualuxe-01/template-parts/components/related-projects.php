<?php
/**
 * Template part for displaying related projects
 *
 * @package AquaLuxe
 */

// Get related projects options from theme customizer
$show_related_projects = get_theme_mod('aqualuxe_show_related_projects', true);
$related_projects_title = get_theme_mod('aqualuxe_related_projects_title', __('Related Projects', 'aqualuxe'));
$related_projects_count = get_theme_mod('aqualuxe_related_projects_count', 3);
$related_projects_orderby = get_theme_mod('aqualuxe_related_projects_orderby', 'date');
$related_projects_order = get_theme_mod('aqualuxe_related_projects_order', 'DESC');

// Check if related projects should be displayed
if (!$show_related_projects) {
    return;
}

// Get current project ID
$project_id = get_the_ID();

// Get project categories
$project_categories = get_the_terms($project_id, 'project_category');

// If no categories found, return
if (empty($project_categories) || is_wp_error($project_categories)) {
    return;
}

// Get category IDs
$category_ids = array();
foreach ($project_categories as $category) {
    $category_ids[] = $category->term_id;
}

// Set up query arguments
$args = array(
    'post_type'      => 'project',
    'posts_per_page' => $related_projects_count,
    'orderby'        => $related_projects_orderby,
    'order'          => $related_projects_order,
    'post_status'    => 'publish',
    'post__not_in'   => array($project_id),
    'tax_query'      => array(
        array(
            'taxonomy' => 'project_category',
            'field'    => 'term_id',
            'terms'    => $category_ids,
        ),
    ),
);

// Get related projects
$related_projects_query = new WP_Query($args);

// Check if we have related projects
if (!$related_projects_query->have_posts()) {
    return;
}
?>

<div class="related-projects">
    <?php if (!empty($related_projects_title)) : ?>
        <h3 class="related-projects-title"><?php echo esc_html($related_projects_title); ?></h3>
    <?php endif; ?>
    
    <div class="related-projects-carousel">
        <?php
        // Loop through related projects
        while ($related_projects_query->have_posts()) :
            $related_projects_query->the_post();
            
            // Get project details
            $client = get_post_meta(get_the_ID(), 'project_client', true);
            $location = get_post_meta(get_the_ID(), 'project_location', true);
            ?>
            <div class="related-project-item">
                <div class="related-project-inner">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="related-project-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('aqualuxe-project-thumbnail', array('class' => 'img-fluid')); ?>
                            </a>
                            
                            <div class="project-overlay">
                                <div class="project-actions">
                                    <a href="<?php the_permalink(); ?>" class="project-link">
                                        <i class="fas fa-link"></i>
                                    </a>
                                    
                                    <a href="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" class="project-zoom" data-fancybox="gallery">
                                        <i class="fas fa-search-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="related-project-content">
                        <h4 class="related-project-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="related-project-meta">
                            <?php if (!empty($client)) : ?>
                                <div class="related-project-client">
                                    <i class="fas fa-user"></i>
                                    <span><?php echo esc_html($client); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($location)) : ?>
                                <div class="related-project-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo esc_html($location); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
    
    <div class="related-projects-more">
        <a href="<?php echo esc_url(get_post_type_archive_link('project')); ?>" class="btn btn-outline-primary btn-sm">
            <?php echo esc_html__('View All Projects', 'aqualuxe'); ?>
        </a>
    </div>
</div>