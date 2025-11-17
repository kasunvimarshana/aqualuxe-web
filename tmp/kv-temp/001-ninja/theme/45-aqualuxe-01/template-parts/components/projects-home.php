<?php
/**
 * Template part for displaying projects on the homepage
 *
 * @package AquaLuxe
 */

// Get projects options from theme customizer
$show_projects = get_theme_mod('aqualuxe_show_home_projects', true);
$projects_title = get_theme_mod('aqualuxe_home_projects_title', __('Our Projects', 'aqualuxe'));
$projects_subtitle = get_theme_mod('aqualuxe_home_projects_subtitle', __('Explore our latest aquatic installations', 'aqualuxe'));
$projects_count = get_theme_mod('aqualuxe_home_projects_count', 6);
$projects_columns = get_theme_mod('aqualuxe_home_projects_columns', 3);
$projects_style = get_theme_mod('aqualuxe_home_projects_style', 'grid');
$projects_category = get_theme_mod('aqualuxe_home_projects_category', 0);
$projects_orderby = get_theme_mod('aqualuxe_home_projects_orderby', 'date');
$projects_order = get_theme_mod('aqualuxe_home_projects_order', 'DESC');
$projects_button_text = get_theme_mod('aqualuxe_home_projects_button_text', __('View All Projects', 'aqualuxe'));
$projects_button_url = get_theme_mod('aqualuxe_home_projects_button_url', get_post_type_archive_link('project'));
$show_filters = get_theme_mod('aqualuxe_show_home_project_filters', true);

// Check if projects should be displayed
if (!$show_projects) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'project',
    'posts_per_page' => $projects_count,
    'orderby'        => $projects_orderby,
    'order'          => $projects_order,
);

// Add category filter
if ($projects_category > 0) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'project_category',
            'field'    => 'term_id',
            'terms'    => $projects_category,
        ),
    );
}

// Get projects
$projects_query = new WP_Query($args);

// Check if we have projects
if (!$projects_query->have_posts()) {
    return;
}

// Set up column classes
$column_class = 'col-lg-4 col-md-6';

switch ($projects_columns) {
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

// Project item classes
$project_item_class = 'project-item';
$project_item_class .= ' project-style-' . $projects_style;

// Get project categories for filters
$project_categories = array();
if ($show_filters) {
    $project_categories = get_terms(array(
        'taxonomy'   => 'project_category',
        'hide_empty' => true,
    ));
}
?>

<div class="projects-section section-padding">
    <div class="container">
        <div class="section-header text-center">
            <?php if (!empty($projects_title)) : ?>
                <h2 class="section-title"><?php echo esc_html($projects_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($projects_subtitle)) : ?>
                <div class="section-subtitle"><?php echo esc_html($projects_subtitle); ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ($show_filters && !empty($project_categories) && !is_wp_error($project_categories)) : ?>
            <div class="projects-filter">
                <ul class="filter-buttons">
                    <li class="active"><a href="#" data-filter="*"><?php echo esc_html__('All', 'aqualuxe'); ?></a></li>
                    
                    <?php foreach ($project_categories as $category) : ?>
                        <li><a href="#" data-filter=".<?php echo esc_attr('project-category-' . $category->term_id); ?>"><?php echo esc_html($category->name); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="projects-wrapper">
            <?php if ($projects_style === 'carousel') : ?>
                <div class="projects-carousel">
                    <?php
                    // Loop through projects
                    while ($projects_query->have_posts()) :
                        $projects_query->the_post();
                        
                        // Get project categories for filtering
                        $project_category_classes = '';
                        $project_terms = get_the_terms(get_the_ID(), 'project_category');
                        
                        if (!empty($project_terms) && !is_wp_error($project_terms)) {
                            foreach ($project_terms as $term) {
                                $project_category_classes .= ' project-category-' . $term->term_id;
                            }
                        }
                        
                        // Get project details
                        $client = get_post_meta(get_the_ID(), 'project_client', true);
                        $location = get_post_meta(get_the_ID(), 'project_location', true);
                        ?>
                        <div class="<?php echo esc_attr($project_item_class . $project_category_classes); ?>">
                            <div class="project-inner">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="project-image">
                                        <?php the_post_thumbnail('aqualuxe-project-thumbnail', array('class' => 'img-fluid')); ?>
                                        
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
                                
                                <div class="project-content">
                                    <h3 class="project-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="project-meta">
                                        <?php if (!empty($client)) : ?>
                                            <div class="project-client">
                                                <i class="fas fa-user"></i>
                                                <span><?php echo esc_html($client); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($location)) : ?>
                                            <div class="project-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?php echo esc_html($location); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php
                                        // Project categories
                                        if (!empty($project_terms) && !is_wp_error($project_terms)) {
                                            echo '<div class="project-categories">';
                                            echo '<i class="fas fa-folder"></i>';
                                            
                                            $category_names = array();
                                            foreach ($project_terms as $term) {
                                                $category_names[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                            }
                                            
                                            echo '<span>' . implode(', ', $category_names) . '</span>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <div class="row">
                    <?php
                    // Loop through projects
                    while ($projects_query->have_posts()) :
                        $projects_query->the_post();
                        
                        // Get project categories for filtering
                        $project_category_classes = '';
                        $project_terms = get_the_terms(get_the_ID(), 'project_category');
                        
                        if (!empty($project_terms) && !is_wp_error($project_terms)) {
                            foreach ($project_terms as $term) {
                                $project_category_classes .= ' project-category-' . $term->term_id;
                            }
                        }
                        
                        // Get project details
                        $client = get_post_meta(get_the_ID(), 'project_client', true);
                        $location = get_post_meta(get_the_ID(), 'project_location', true);
                        ?>
                        <div class="<?php echo esc_attr($column_class); ?>">
                            <div class="<?php echo esc_attr($project_item_class . $project_category_classes); ?>">
                                <div class="project-inner">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="project-image">
                                            <?php the_post_thumbnail('aqualuxe-project-thumbnail', array('class' => 'img-fluid')); ?>
                                            
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
                                    
                                    <div class="project-content">
                                        <h3 class="project-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="project-meta">
                                            <?php if (!empty($client)) : ?>
                                                <div class="project-client">
                                                    <i class="fas fa-user"></i>
                                                    <span><?php echo esc_html($client); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($location)) : ?>
                                                <div class="project-location">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span><?php echo esc_html($location); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php
                                            // Project categories
                                            if (!empty($project_terms) && !is_wp_error($project_terms)) {
                                                echo '<div class="project-categories">';
                                                echo '<i class="fas fa-folder"></i>';
                                                
                                                $category_names = array();
                                                foreach ($project_terms as $term) {
                                                    $category_names[] = '<a href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
                                                }
                                                
                                                echo '<span>' . implode(', ', $category_names) . '</span>';
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($projects_button_text) && !empty($projects_button_url)) : ?>
            <div class="text-center mt-5">
                <a href="<?php echo esc_url($projects_button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($projects_button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>