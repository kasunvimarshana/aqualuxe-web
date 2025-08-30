<?php
/**
 * Template Name: About Page
 *
 * The template for displaying the about page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Page Header
    get_template_part('template-parts/components/page-header');
    ?>

    <div class="container">
        <div class="about-page-content">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <div class="about-intro">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="about-content">
                                <?php
                                // Display the content
                                the_content();
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="about-image">
                                <?php
                                if (has_post_thumbnail()) {
                                    the_post_thumbnail('full', array('class' => 'img-fluid'));
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            endwhile; // End of the loop.
            ?>

            <?php
            // About Features Section
            $features = get_post_meta(get_the_ID(), 'about_features', true);
            if (!empty($features)) :
            ?>
            <div class="about-features">
                <div class="row">
                    <?php foreach ($features as $feature) : ?>
                        <div class="col-md-4">
                            <div class="feature-box">
                                <?php if (!empty($feature['icon'])) : ?>
                                    <div class="feature-icon">
                                        <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['title'])) : ?>
                                    <h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($feature['description'])) : ?>
                                    <div class="feature-description">
                                        <?php echo wp_kses_post($feature['description']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Team Members Section
            $team_args = array(
                'post_type'      => 'team',
                'posts_per_page' => 4,
                'orderby'        => 'menu_order',
                'order'          => 'ASC',
            );
            
            $team_query = new WP_Query($team_args);
            
            if ($team_query->have_posts()) :
            ?>
            <div class="about-team">
                <div class="section-header text-center">
                    <h2><?php echo esc_html__('Meet Our Team', 'aqualuxe'); ?></h2>
                    <p><?php echo esc_html__('Our experts are passionate about aquatic life and dedicated to providing exceptional service.', 'aqualuxe'); ?></p>
                </div>
                
                <div class="row">
                    <?php
                    while ($team_query->have_posts()) :
                        $team_query->the_post();
                        
                        // Get team member details
                        $position = get_post_meta(get_the_ID(), 'team_position', true);
                        $social_profiles = get_post_meta(get_the_ID(), 'team_social_profiles', true);
                        ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="team-member">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="team-image">
                                        <?php the_post_thumbnail('aqualuxe-team', array('class' => 'img-fluid')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-info">
                                    <h3 class="team-name"><?php the_title(); ?></h3>
                                    
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
                
                <div class="text-center mt-4">
                    <a href="<?php echo esc_url(get_post_type_archive_link('team')); ?>" class="btn btn-primary">
                        <?php echo esc_html__('View All Team Members', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php
            // Testimonials Section
            $testimonial_args = array(
                'post_type'      => 'testimonial',
                'posts_per_page' => 3,
                'orderby'        => 'rand',
            );
            
            $testimonial_query = new WP_Query($testimonial_args);
            
            if ($testimonial_query->have_posts()) :
            ?>
            <div class="about-testimonials">
                <div class="section-header text-center">
                    <h2><?php echo esc_html__('What Our Clients Say', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="testimonials-slider">
                    <?php
                    while ($testimonial_query->have_posts()) :
                        $testimonial_query->the_post();
                        
                        // Get testimonial details
                        $client_name = get_post_meta(get_the_ID(), 'testimonial_client_name', true);
                        $client_company = get_post_meta(get_the_ID(), 'testimonial_client_company', true);
                        $client_rating = get_post_meta(get_the_ID(), 'testimonial_rating', true);
                        ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content">
                                <?php if (!empty($client_rating)) : ?>
                                    <div class="testimonial-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $client_rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-client">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="client-image">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded-circle')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="client-info">
                                        <?php if (!empty($client_name)) : ?>
                                            <h4 class="client-name"><?php echo esc_html($client_name); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($client_company)) : ?>
                                            <p class="client-company"><?php echo esc_html($client_company); ?></p>
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
            </div>
            <?php endif; ?>

            <?php
            // Call to Action Section
            get_template_part('template-parts/components/cta', 'about');
            ?>
        </div><!-- .about-page-content -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();