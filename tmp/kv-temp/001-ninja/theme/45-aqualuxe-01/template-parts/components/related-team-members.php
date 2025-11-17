<?php
/**
 * Template part for displaying related team members
 *
 * @package AquaLuxe
 */

// Get related team members options from theme customizer
$show_related_team = get_theme_mod('aqualuxe_show_related_team', true);
$related_team_title = get_theme_mod('aqualuxe_related_team_title', __('Other Team Members', 'aqualuxe'));
$related_team_count = get_theme_mod('aqualuxe_related_team_count', 4);
$related_team_orderby = get_theme_mod('aqualuxe_related_team_orderby', 'menu_order');
$related_team_order = get_theme_mod('aqualuxe_related_team_order', 'ASC');

// Check if related team members should be displayed
if (!$show_related_team) {
    return;
}

// Get current team member ID
$team_id = get_the_ID();

// Get team departments
$team_departments = get_the_terms($team_id, 'team_department');

// Set up query arguments
$args = array(
    'post_type'      => 'team',
    'posts_per_page' => $related_team_count,
    'orderby'        => $related_team_orderby,
    'order'          => $related_team_order,
    'post_status'    => 'publish',
    'post__not_in'   => array($team_id),
);

// If we have departments, filter by them
if (!empty($team_departments) && !is_wp_error($team_departments)) {
    $department_ids = array();
    foreach ($team_departments as $department) {
        $department_ids[] = $department->term_id;
    }
    
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'team_department',
            'field'    => 'term_id',
            'terms'    => $department_ids,
        ),
    );
}

// Get related team members
$related_team_query = new WP_Query($args);

// Check if we have related team members
if (!$related_team_query->have_posts()) {
    return;
}
?>

<div class="related-team-members section-padding">
    <div class="container">
        <?php if (!empty($related_team_title)) : ?>
            <div class="section-header text-center">
                <h2 class="section-title"><?php echo esc_html($related_team_title); ?></h2>
            </div>
        <?php endif; ?>
        
        <div class="related-team-wrapper">
            <div class="row">
                <?php
                // Loop through related team members
                while ($related_team_query->have_posts()) :
                    $related_team_query->the_post();
                    
                    // Get team member details
                    $position = get_post_meta(get_the_ID(), 'team_position', true);
                    $social_profiles = get_post_meta(get_the_ID(), 'team_social_profiles', true);
                    ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="team-member">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="team-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('aqualuxe-team', array('class' => 'img-fluid')); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="team-info">
                                <h3 class="team-name">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <?php if (!empty($position)) : ?>
                                    <p class="team-position"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($social_profiles)) : ?>
                                    <div class="team-social">
                                        <?php foreach ($social_profiles as $profile) : ?>
                                            <a href="<?php echo esc_url($profile['url']); ?>" target="_blank" rel="noopener noreferrer">
                                                <i class="<?php echo esc_attr($profile['icon']); ?>"></i>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?php echo esc_url(get_post_type_archive_link('team')); ?>" class="btn btn-outline-primary">
                <?php echo esc_html__('View All Team Members', 'aqualuxe'); ?>
            </a>
        </div>
    </div>
</div>