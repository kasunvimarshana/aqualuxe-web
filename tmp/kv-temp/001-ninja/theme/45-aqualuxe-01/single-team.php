<?php
/**
 * The template for displaying single team member posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get team member details
$position = get_post_meta(get_the_ID(), 'team_position', true);
$email = get_post_meta(get_the_ID(), 'team_email', true);
$phone = get_post_meta(get_the_ID(), 'team_phone', true);
$social_profiles = get_post_meta(get_the_ID(), 'team_social_profiles', true);
$education = get_post_meta(get_the_ID(), 'team_education', true);
$experience = get_post_meta(get_the_ID(), 'team_experience', true);
$skills = get_post_meta(get_the_ID(), 'team_skills', true);
$gallery = get_post_meta(get_the_ID(), 'team_gallery', true);
?>

<main id="primary" class="site-main">
    <?php
    // Single Team Header
    get_template_part('template-parts/components/single-header', 'team');
    ?>

    <div class="container">
        <div class="team-single">
            <div class="row">
                <div class="col-lg-4">
                    <div class="team-sidebar">
                        <div class="team-image">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('large', array('class' => 'img-fluid'));
                            }
                            ?>
                        </div>
                        
                        <div class="team-info-card">
                            <h2 class="team-name"><?php the_title(); ?></h2>
                            
                            <?php if (!empty($position)) : ?>
                                <p class="team-position"><?php echo esc_html($position); ?></p>
                            <?php endif; ?>
                            
                            <div class="team-meta">
                                <?php if (!empty($email)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="far fa-envelope"></i>
                                        </div>
                                        <div class="meta-content">
                                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($phone)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-phone-alt"></i>
                                        </div>
                                        <div class="meta-content">
                                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display team departments
                                $team_departments = get_the_terms(get_the_ID(), 'team_department');
                                if (!empty($team_departments) && !is_wp_error($team_departments)) :
                                ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="meta-content">
                                            <?php
                                            $department_names = array();
                                            foreach ($team_departments as $department) {
                                                $department_names[] = '<a href="' . esc_url(get_term_link($department)) . '">' . esc_html($department->name) . '</a>';
                                            }
                                            echo implode(', ', $department_names);
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($social_profiles)) : ?>
                                <div class="team-social">
                                    <?php foreach ($social_profiles as $profile) : ?>
                                        <a href="<?php echo esc_url($profile['url']); ?>" target="_blank" rel="noopener noreferrer" class="social-icon">
                                            <i class="<?php echo esc_attr($profile['icon']); ?>"></i>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                        // Display skills if available
                        if (!empty($skills)) :
                        ?>
                        <div class="team-skills">
                            <h3><?php echo esc_html__('Skills & Expertise', 'aqualuxe'); ?></h3>
                            <div class="skills-list">
                                <?php foreach ($skills as $skill) : ?>
                                    <?php if (!empty($skill['name']) && isset($skill['level'])) : ?>
                                        <div class="skill-item">
                                            <div class="skill-info">
                                                <span class="skill-name"><?php echo esc_html($skill['name']); ?></span>
                                                <span class="skill-percentage"><?php echo esc_html($skill['level']); ?>%</span>
                                            </div>
                                            <div class="skill-bar">
                                                <div class="skill-progress" style="width: <?php echo esc_attr($skill['level']); ?>%"></div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-8">
                    <div class="team-content">
                        <?php
                        while (have_posts()) :
                            the_post();
                            ?>
                            
                            <div class="team-biography">
                                <h3><?php echo esc_html__('Biography', 'aqualuxe'); ?></h3>
                                <?php the_content(); ?>
                            </div>
                            
                            <?php
                        endwhile; // End of the loop.
                        ?>
                        
                        <?php
                        // Display education if available
                        if (!empty($education)) :
                        ?>
                        <div class="team-education">
                            <h3><?php echo esc_html__('Education', 'aqualuxe'); ?></h3>
                            <div class="timeline">
                                <?php foreach ($education as $edu) : ?>
                                    <?php if (!empty($edu['degree']) && !empty($edu['institution'])) : ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4 class="timeline-title"><?php echo esc_html($edu['degree']); ?></h4>
                                                <p class="timeline-info"><?php echo esc_html($edu['institution']); ?></p>
                                                <?php if (!empty($edu['year'])) : ?>
                                                    <p class="timeline-period"><?php echo esc_html($edu['year']); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($edu['description'])) : ?>
                                                    <div class="timeline-description">
                                                        <?php echo wp_kses_post($edu['description']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display experience if available
                        if (!empty($experience)) :
                        ?>
                        <div class="team-experience">
                            <h3><?php echo esc_html__('Experience', 'aqualuxe'); ?></h3>
                            <div class="timeline">
                                <?php foreach ($experience as $exp) : ?>
                                    <?php if (!empty($exp['position']) && !empty($exp['company'])) : ?>
                                        <div class="timeline-item">
                                            <div class="timeline-marker">
                                                <i class="fas fa-briefcase"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <h4 class="timeline-title"><?php echo esc_html($exp['position']); ?></h4>
                                                <p class="timeline-info"><?php echo esc_html($exp['company']); ?></p>
                                                <?php if (!empty($exp['period'])) : ?>
                                                    <p class="timeline-period"><?php echo esc_html($exp['period']); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($exp['description'])) : ?>
                                                    <div class="timeline-description">
                                                        <?php echo wp_kses_post($exp['description']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display gallery if available
                        if (!empty($gallery)) :
                        ?>
                        <div class="team-gallery">
                            <h3><?php echo esc_html__('Gallery', 'aqualuxe'); ?></h3>
                            <div class="row">
                                <?php
                                foreach ($gallery as $image_id) :
                                    $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                    $image_full_url = wp_get_attachment_image_url($image_id, 'full');
                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                    ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="gallery-item">
                                            <a href="<?php echo esc_url($image_full_url); ?>" class="gallery-lightbox">
                                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                                            </a>
                                        </div>
                                    </div>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display contact form
                        get_template_part('template-parts/components/team-contact-form');
                        ?>
                    </div>
                </div>
            </div>
            
            <?php
            // Display related team members
            get_template_part('template-parts/components/related-team-members');
            ?>
        </div>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();