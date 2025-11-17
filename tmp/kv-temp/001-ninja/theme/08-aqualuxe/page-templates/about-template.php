<?php
/**
 * Template Name: About Page
 *
 * This is the template for displaying the About page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main about-page">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'aqualuxe_about_hero_image', true);
    $hero_title = get_post_meta(get_the_ID(), 'aqualuxe_about_hero_title', true) ?: get_the_title();
    $hero_subtitle = get_post_meta(get_the_ID(), 'aqualuxe_about_hero_subtitle', true);
    
    if (empty($hero_image)) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    ?>
    
    <section class="about-hero" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
            <?php aqualuxe_breadcrumbs(); ?>
        <?php endif; ?>

        <div class="about-content">
            <div class="about-main-content">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php
    // Our Story Section
    $story_title = get_post_meta(get_the_ID(), 'aqualuxe_about_story_title', true) ?: __('Our Story', 'aqualuxe');
    $story_content = get_post_meta(get_the_ID(), 'aqualuxe_about_story_content', true);
    $story_image = get_post_meta(get_the_ID(), 'aqualuxe_about_story_image', true);
    
    if ($story_content) :
    ?>
    <section class="about-story">
        <div class="container">
            <div class="story-wrapper">
                <div class="story-content">
                    <h2 class="section-title"><?php echo esc_html($story_title); ?></h2>
                    <div class="story-text">
                        <?php echo wp_kses_post(wpautop($story_content)); ?>
                    </div>
                </div>
                <?php if ($story_image) : ?>
                <div class="story-image">
                    <img src="<?php echo esc_url($story_image); ?>" alt="<?php echo esc_attr($story_title); ?>">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Mission & Vision Section
    $mission_title = get_post_meta(get_the_ID(), 'aqualuxe_about_mission_title', true) ?: __('Our Mission & Vision', 'aqualuxe');
    $mission_content = get_post_meta(get_the_ID(), 'aqualuxe_about_mission_content', true);
    $vision_content = get_post_meta(get_the_ID(), 'aqualuxe_about_vision_content', true);
    
    if ($mission_content || $vision_content) :
    ?>
    <section class="about-mission-vision">
        <div class="container">
            <h2 class="section-title"><?php echo esc_html($mission_title); ?></h2>
            <div class="mission-vision-wrapper">
                <?php if ($mission_content) : ?>
                <div class="mission-box">
                    <h3><?php esc_html_e('Our Mission', 'aqualuxe'); ?></h3>
                    <div class="mission-content">
                        <?php echo wp_kses_post(wpautop($mission_content)); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($vision_content) : ?>
                <div class="vision-box">
                    <h3><?php esc_html_e('Our Vision', 'aqualuxe'); ?></h3>
                    <div class="vision-content">
                        <?php echo wp_kses_post(wpautop($vision_content)); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Team Section
    $team_title = get_post_meta(get_the_ID(), 'aqualuxe_about_team_title', true) ?: __('Meet Our Team', 'aqualuxe');
    $team_description = get_post_meta(get_the_ID(), 'aqualuxe_about_team_description', true);
    $team_count = get_post_meta(get_the_ID(), 'aqualuxe_about_team_count', true) ?: 4;
    
    // Check if Team Members CPT exists
    if (post_type_exists('team_members')) :
    ?>
    <section class="about-team">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($team_title); ?></h2>
                <?php if ($team_description) : ?>
                    <p class="section-description"><?php echo esc_html($team_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="team-members">
                <?php
                $args = array(
                    'post_type' => 'team_members',
                    'posts_per_page' => intval($team_count),
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                );
                
                $team_query = new WP_Query($args);
                
                if ($team_query->have_posts()) :
                    echo '<div class="team-grid">';
                    
                    while ($team_query->have_posts()) :
                        $team_query->the_post();
                        
                        // Get team member meta
                        $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                        $email = get_post_meta(get_the_ID(), 'aqualuxe_team_email', true);
                        $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                        $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                        ?>
                        <div class="team-member">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="team-member-image">
                                    <?php the_post_thumbnail('aqualuxe-card'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="team-member-info">
                                <h3 class="team-member-name"><?php the_title(); ?></h3>
                                <?php if ($position) : ?>
                                    <p class="team-member-position"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>
                                
                                <div class="team-member-bio">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <div class="team-member-social">
                                    <?php if ($email) : ?>
                                        <a href="mailto:<?php echo esc_attr($email); ?>" class="team-social-link email" aria-label="<?php esc_attr_e('Email', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="team-social-link linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="team-social-link twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    echo '<p class="no-team-members">' . esc_html__('No team members found.', 'aqualuxe') . '</p>';
                endif;
                ?>
            </div>
            
            <?php
            // View all team members link
            $team_archive_url = get_post_type_archive_link('team_members');
            if ($team_archive_url) :
            ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($team_archive_url); ?>" class="btn btn-primary"><?php esc_html_e('View All Team Members', 'aqualuxe'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Testimonials Section
    $testimonials_title = get_post_meta(get_the_ID(), 'aqualuxe_about_testimonials_title', true) ?: __('What Our Customers Say', 'aqualuxe');
    $testimonials_description = get_post_meta(get_the_ID(), 'aqualuxe_about_testimonials_description', true);
    $testimonials_count = get_post_meta(get_the_ID(), 'aqualuxe_about_testimonials_count', true) ?: 3;
    
    // Check if Testimonials CPT exists
    if (post_type_exists('testimonials')) :
    ?>
    <section class="about-testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($testimonials_title); ?></h2>
                <?php if ($testimonials_description) : ?>
                    <p class="section-description"><?php echo esc_html($testimonials_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="testimonials-wrapper">
                <?php
                $args = array(
                    'post_type' => 'testimonials',
                    'posts_per_page' => intval($testimonials_count),
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                
                $testimonials_query = new WP_Query($args);
                
                if ($testimonials_query->have_posts()) :
                    echo '<div class="testimonials-slider">';
                    
                    while ($testimonials_query->have_posts()) :
                        $testimonials_query->the_post();
                        
                        // Get testimonial meta data
                        $client_name = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_client_name', true);
                        $client_position = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_client_position', true);
                        $rating = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_rating', true);
                        ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content">
                                <?php if ($rating && $rating > 0) : ?>
                                    <div class="testimonial-rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="star filled">★</span>';
                                            } else {
                                                echo '<span class="star">☆</span>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-author">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="testimonial-author-image">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'author-avatar')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-author-info">
                                        <?php if ($client_name) : ?>
                                            <h4 class="author-name"><?php echo esc_html($client_name); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if ($client_position) : ?>
                                            <p class="author-position"><?php echo esc_html($client_position); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    echo '<p class="no-testimonials">' . esc_html__('No testimonials found.', 'aqualuxe') . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Call to Action Section
    $cta_title = get_post_meta(get_the_ID(), 'aqualuxe_about_cta_title', true);
    $cta_content = get_post_meta(get_the_ID(), 'aqualuxe_about_cta_content', true);
    $cta_button_text = get_post_meta(get_the_ID(), 'aqualuxe_about_cta_button_text', true);
    $cta_button_url = get_post_meta(get_the_ID(), 'aqualuxe_about_cta_button_url', true);
    $cta_background = get_post_meta(get_the_ID(), 'aqualuxe_about_cta_background', true);
    
    if ($cta_title && $cta_content) :
    ?>
    <section class="about-cta" <?php if ($cta_background) : ?>style="background-image: url('<?php echo esc_url($cta_background); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
                <div class="cta-text">
                    <?php echo wp_kses_post(wpautop($cta_content)); ?>
                </div>
                <?php if ($cta_button_text && $cta_button_url) : ?>
                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-accent btn-large"><?php echo esc_html($cta_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();