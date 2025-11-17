<?php
/**
 * The template for displaying single project posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get project details
$client = get_post_meta(get_the_ID(), 'project_client', true);
$location = get_post_meta(get_the_ID(), 'project_location', true);
$completion_date = get_post_meta(get_the_ID(), 'project_completion_date', true);
$budget = get_post_meta(get_the_ID(), 'project_budget', true);
$services = get_post_meta(get_the_ID(), 'project_services', true);
$gallery = get_post_meta(get_the_ID(), 'project_gallery', true);
$video_url = get_post_meta(get_the_ID(), 'project_video_url', true);
$testimonial = get_post_meta(get_the_ID(), 'project_testimonial', true);
$challenges = get_post_meta(get_the_ID(), 'project_challenges', true);
$solutions = get_post_meta(get_the_ID(), 'project_solutions', true);
$results = get_post_meta(get_the_ID(), 'project_results', true);
?>

<main id="primary" class="site-main">
    <?php
    // Single Project Header
    get_template_part('template-parts/components/single-header', 'project');
    ?>

    <div class="container">
        <div class="project-single">
            <?php
            // Display gallery if available
            if (!empty($gallery)) :
            ?>
            <div class="project-gallery">
                <div class="project-gallery-slider">
                    <?php
                    foreach ($gallery as $image_id) :
                        $image_url = wp_get_attachment_image_url($image_id, 'full');
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        ?>
                        <div class="gallery-item">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                
                <div class="project-gallery-thumbs">
                    <?php
                    foreach ($gallery as $image_id) :
                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        ?>
                        <div class="thumb-item">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>
            <?php
            // If no gallery, display featured image
            elseif (has_post_thumbnail()) :
            ?>
            <div class="project-featured-image">
                <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
            </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="project-content">
                        <?php
                        while (have_posts()) :
                            the_post();
                            ?>
                            
                            <div class="project-description">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php
                        endwhile; // End of the loop.
                        ?>
                        
                        <?php
                        // Display video if available
                        if (!empty($video_url)) :
                        ?>
                        <div class="project-video">
                            <h3><?php echo esc_html__('Project Video', 'aqualuxe'); ?></h3>
                            <div class="embed-responsive embed-responsive-16by9">
                                <?php
                                // Check if YouTube or Vimeo URL
                                if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                    echo wp_oembed_get($video_url);
                                } elseif (strpos($video_url, 'vimeo.com') !== false) {
                                    echo wp_oembed_get($video_url);
                                } else {
                                    echo do_shortcode('[video src="' . esc_url($video_url) . '"]');
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display challenges and solutions if available
                        if (!empty($challenges) || !empty($solutions)) :
                        ?>
                        <div class="project-challenges-solutions">
                            <div class="row">
                                <?php if (!empty($challenges)) : ?>
                                    <div class="col-md-6">
                                        <div class="project-challenges">
                                            <h3><?php echo esc_html__('Challenges', 'aqualuxe'); ?></h3>
                                            <div class="challenges-content">
                                                <?php echo wp_kses_post($challenges); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($solutions)) : ?>
                                    <div class="col-md-6">
                                        <div class="project-solutions">
                                            <h3><?php echo esc_html__('Solutions', 'aqualuxe'); ?></h3>
                                            <div class="solutions-content">
                                                <?php echo wp_kses_post($solutions); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display results if available
                        if (!empty($results)) :
                        ?>
                        <div class="project-results">
                            <h3><?php echo esc_html__('Results', 'aqualuxe'); ?></h3>
                            <div class="results-content">
                                <?php echo wp_kses_post($results); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display testimonial if available
                        if (!empty($testimonial)) :
                        ?>
                        <div class="project-testimonial">
                            <blockquote>
                                <div class="testimonial-content">
                                    <?php echo wp_kses_post($testimonial['content']); ?>
                                </div>
                                
                                <?php if (!empty($testimonial['author'])) : ?>
                                    <cite>
                                        <?php echo esc_html($testimonial['author']); ?>
                                        <?php if (!empty($testimonial['position'])) : ?>
                                            <span><?php echo esc_html($testimonial['position']); ?></span>
                                        <?php endif; ?>
                                    </cite>
                                <?php endif; ?>
                            </blockquote>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="project-sidebar">
                        <div class="project-info-card">
                            <h3><?php echo esc_html__('Project Details', 'aqualuxe'); ?></h3>
                            
                            <div class="project-meta">
                                <?php if (!empty($client)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Client', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($client); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($location)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Location', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($location); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($completion_date)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="far fa-calendar-check"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Completion Date', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($completion_date); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($budget)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Budget', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($budget); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display project categories
                                $project_categories = get_the_terms(get_the_ID(), 'project_category');
                                if (!empty($project_categories) && !is_wp_error($project_categories)) :
                                ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Category', 'aqualuxe'); ?></span>
                                            <span class="meta-value">
                                                <?php
                                                $category_names = array();
                                                foreach ($project_categories as $category) {
                                                    $category_names[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                                                }
                                                echo implode(', ', $category_names);
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($services)) : ?>
                                <div class="project-services">
                                    <h4><?php echo esc_html__('Services Provided', 'aqualuxe'); ?></h4>
                                    <ul class="services-list">
                                        <?php foreach ($services as $service) : ?>
                                            <li>
                                                <i class="fas fa-check-circle"></i>
                                                <span><?php echo esc_html($service); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-share">
                                <h4><?php echo esc_html__('Share This Project', 'aqualuxe'); ?></h4>
                                <?php aqualuxe_social_sharing(); ?>
                            </div>
                        </div>
                        
                        <?php
                        // Display contact form
                        get_template_part('template-parts/components/project-inquiry-form');
                        ?>
                        
                        <?php
                        // Display related projects
                        get_template_part('template-parts/components/related-projects');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();