<?php
/**
 * Template Name: About
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <div class="page-description text-lg text-gray-600 max-w-3xl mx-auto">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="page-content">
            <?php
            // Display the page content first
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
            
            <!-- Our Story Section -->
            <?php
            $story_title = get_theme_mod('aqualuxe_about_story_title', __('Our Story', 'aqualuxe'));
            $story_content = get_theme_mod('aqualuxe_about_story_content', '');
            $story_image = get_theme_mod('aqualuxe_about_story_image', '');
            
            if ($story_title || $story_content) :
            ?>
                <section class="our-story-section mb-16">
                    <div class="flex flex-col lg:flex-row items-center gap-12">
                        <?php if ($story_image) : ?>
                            <div class="story-image lg:w-1/2">
                                <img src="<?php echo esc_url($story_image); ?>" alt="<?php echo esc_attr($story_title); ?>" class="rounded-lg shadow-lg w-full h-auto">
                            </div>
                        <?php endif; ?>
                        
                        <div class="story-content lg:w-1/2">
                            <?php if ($story_title) : ?>
                                <h2 class="section-title text-3xl font-bold mb-6"><?php echo esc_html($story_title); ?></h2>
                            <?php endif; ?>
                            
                            <?php if ($story_content) : ?>
                                <div class="prose max-w-none">
                                    <?php echo wp_kses_post(wpautop($story_content)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
            
            <!-- Mission & Vision Section -->
            <?php
            $mission_title = get_theme_mod('aqualuxe_about_mission_title', __('Our Mission & Vision', 'aqualuxe'));
            $mission_content = get_theme_mod('aqualuxe_about_mission_content', '');
            $vision_content = get_theme_mod('aqualuxe_about_vision_content', '');
            $values_content = get_theme_mod('aqualuxe_about_values_content', '');
            
            if ($mission_title || $mission_content || $vision_content || $values_content) :
            ?>
                <section class="mission-vision-section mb-16 bg-gray-50 p-8 rounded-lg">
                    <?php if ($mission_title) : ?>
                        <h2 class="section-title text-3xl font-bold mb-8 text-center"><?php echo esc_html($mission_title); ?></h2>
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php if ($mission_content) : ?>
                            <div class="mission-box bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-bold mb-4 text-primary"><?php esc_html_e('Our Mission', 'aqualuxe'); ?></h3>
                                <div class="prose">
                                    <?php echo wp_kses_post(wpautop($mission_content)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($vision_content) : ?>
                            <div class="vision-box bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-bold mb-4 text-primary"><?php esc_html_e('Our Vision', 'aqualuxe'); ?></h3>
                                <div class="prose">
                                    <?php echo wp_kses_post(wpautop($vision_content)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($values_content) : ?>
                            <div class="values-box bg-white p-6 rounded-lg shadow-md">
                                <h3 class="text-xl font-bold mb-4 text-primary"><?php esc_html_e('Our Values', 'aqualuxe'); ?></h3>
                                <div class="prose">
                                    <?php echo wp_kses_post(wpautop($values_content)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <!-- Team Section -->
            <?php
            $team_title = get_theme_mod('aqualuxe_about_team_title', __('Meet Our Team', 'aqualuxe'));
            $team_description = get_theme_mod('aqualuxe_about_team_description', '');
            $show_team = get_theme_mod('aqualuxe_about_show_team', true);
            
            // Team members (in a real theme, this would be a custom post type or repeater field)
            $team_members = array();
            
            for ($i = 1; $i <= 4; $i++) {
                $name = get_theme_mod('aqualuxe_team_name_' . $i, '');
                $role = get_theme_mod('aqualuxe_team_role_' . $i, '');
                $bio = get_theme_mod('aqualuxe_team_bio_' . $i, '');
                $image = get_theme_mod('aqualuxe_team_image_' . $i, '');
                
                if (!empty($name) && !empty($role)) {
                    $team_members[] = array(
                        'name' => $name,
                        'role' => $role,
                        'bio' => $bio,
                        'image' => $image,
                    );
                }
            }
            
            // If no custom team members, use defaults
            if (empty($team_members)) {
                $team_members = array(
                    array(
                        'name' => __('John Doe', 'aqualuxe'),
                        'role' => __('Founder & CEO', 'aqualuxe'),
                        'bio' => __('With over 20 years of experience in aquaculture, John leads our company with passion and expertise.', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/images/team-1.jpg',
                    ),
                    array(
                        'name' => __('Jane Smith', 'aqualuxe'),
                        'role' => __('Marine Biologist', 'aqualuxe'),
                        'bio' => __('Jane oversees our breeding programs and ensures the health and quality of all our aquatic species.', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/images/team-2.jpg',
                    ),
                    array(
                        'name' => __('Michael Johnson', 'aqualuxe'),
                        'role' => __('Export Manager', 'aqualuxe'),
                        'bio' => __('Michael handles our international shipping and ensures compliance with all export regulations.', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/images/team-3.jpg',
                    ),
                    array(
                        'name' => __('Sarah Williams', 'aqualuxe'),
                        'role' => __('Aquascaping Specialist', 'aqualuxe'),
                        'bio' => __('Sarah designs stunning aquascapes and provides consultation for custom aquarium installations.', 'aqualuxe'),
                        'image' => get_template_directory_uri() . '/assets/images/team-4.jpg',
                    ),
                );
            }
            
            if ($show_team && (!empty($team_title) || !empty($team_members))) :
            ?>
                <section class="team-section mb-16">
                    <?php if ($team_title) : ?>
                        <h2 class="section-title text-3xl font-bold mb-4 text-center"><?php echo esc_html($team_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($team_description) : ?>
                        <div class="team-description text-lg text-gray-600 max-w-3xl mx-auto text-center mb-8">
                            <?php echo wp_kses_post(wpautop($team_description)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="team-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php foreach ($team_members as $member) : ?>
                            <div class="team-member text-center">
                                <?php if (!empty($member['image'])) : ?>
                                    <div class="member-image mb-4">
                                        <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>" class="rounded-full w-48 h-48 object-cover mx-auto">
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="member-name text-xl font-bold mb-1"><?php echo esc_html($member['name']); ?></h3>
                                
                                <?php if (!empty($member['role'])) : ?>
                                    <p class="member-role text-primary mb-3"><?php echo esc_html($member['role']); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($member['bio'])) : ?>
                                    <p class="member-bio text-gray-600"><?php echo esc_html($member['bio']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <!-- Sustainability Section -->
            <?php
            $sustainability_title = get_theme_mod('aqualuxe_about_sustainability_title', __('Our Commitment to Sustainability', 'aqualuxe'));
            $sustainability_content = get_theme_mod('aqualuxe_about_sustainability_content', '');
            $sustainability_image = get_theme_mod('aqualuxe_about_sustainability_image', '');
            
            if ($sustainability_title || $sustainability_content) :
            ?>
                <section class="sustainability-section mb-16">
                    <div class="flex flex-col lg:flex-row-reverse items-center gap-12">
                        <?php if ($sustainability_image) : ?>
                            <div class="sustainability-image lg:w-1/2">
                                <img src="<?php echo esc_url($sustainability_image); ?>" alt="<?php echo esc_attr($sustainability_title); ?>" class="rounded-lg shadow-lg w-full h-auto">
                            </div>
                        <?php endif; ?>
                        
                        <div class="sustainability-content lg:w-1/2">
                            <?php if ($sustainability_title) : ?>
                                <h2 class="section-title text-3xl font-bold mb-6"><?php echo esc_html($sustainability_title); ?></h2>
                            <?php endif; ?>
                            
                            <?php if ($sustainability_content) : ?>
                                <div class="prose max-w-none">
                                    <?php echo wp_kses_post(wpautop($sustainability_content)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
            
            <!-- Certifications Section -->
            <?php
            $certifications_title = get_theme_mod('aqualuxe_about_certifications_title', __('Our Certifications', 'aqualuxe'));
            $certifications_content = get_theme_mod('aqualuxe_about_certifications_content', '');
            
            // Certifications (in a real theme, this would be a custom post type or repeater field)
            $certifications = array();
            
            for ($i = 1; $i <= 4; $i++) {
                $cert_name = get_theme_mod('aqualuxe_certification_name_' . $i, '');
                $cert_image = get_theme_mod('aqualuxe_certification_image_' . $i, '');
                
                if (!empty($cert_name) && !empty($cert_image)) {
                    $certifications[] = array(
                        'name' => $cert_name,
                        'image' => $cert_image,
                    );
                }
            }
            
            if ($certifications_title || $certifications_content || !empty($certifications)) :
            ?>
                <section class="certifications-section mb-16 bg-gray-50 p-8 rounded-lg">
                    <?php if ($certifications_title) : ?>
                        <h2 class="section-title text-3xl font-bold mb-4 text-center"><?php echo esc_html($certifications_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($certifications_content) : ?>
                        <div class="certifications-description text-lg text-gray-600 max-w-3xl mx-auto text-center mb-8">
                            <?php echo wp_kses_post(wpautop($certifications_content)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($certifications)) : ?>
                        <div class="certifications-grid grid grid-cols-2 md:grid-cols-4 gap-8">
                            <?php foreach ($certifications as $certification) : ?>
                                <div class="certification-item text-center">
                                    <?php if (!empty($certification['image'])) : ?>
                                        <div class="certification-image mb-3">
                                            <img src="<?php echo esc_url($certification['image']); ?>" alt="<?php echo esc_attr($certification['name']); ?>" class="h-24 w-auto mx-auto">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <h3 class="certification-name text-lg font-medium"><?php echo esc_html($certification['name']); ?></h3>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
            
            <!-- Call to Action Section -->
            <?php
            $cta_title = get_theme_mod('aqualuxe_about_cta_title', __('Ready to Work With Us?', 'aqualuxe'));
            $cta_content = get_theme_mod('aqualuxe_about_cta_content', __('Contact our team to learn more about our products and services.', 'aqualuxe'));
            $cta_button_text = get_theme_mod('aqualuxe_about_cta_button_text', __('Contact Us', 'aqualuxe'));
            $cta_button_url = get_theme_mod('aqualuxe_about_cta_button_url', get_permalink(get_page_by_path('contact')));
            $cta_secondary_button_text = get_theme_mod('aqualuxe_about_cta_secondary_button_text', __('View Products', 'aqualuxe'));
            $cta_secondary_button_url = get_theme_mod('aqualuxe_about_cta_secondary_button_url', class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#');
            
            if ($cta_title || $cta_content) :
            ?>
                <section class="cta-section bg-primary text-white p-12 rounded-lg text-center">
                    <?php if ($cta_title) : ?>
                        <h2 class="section-title text-3xl font-bold mb-4"><?php echo esc_html($cta_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($cta_content) : ?>
                        <div class="cta-content text-lg mb-8 max-w-2xl mx-auto">
                            <?php echo wp_kses_post(wpautop($cta_content)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="cta-buttons flex flex-wrap justify-center gap-4">
                        <?php if ($cta_button_text && $cta_button_url) : ?>
                            <a href="<?php echo esc_url($cta_button_url); ?>" class="btn bg-white text-primary hover:bg-gray-100">
                                <?php echo esc_html($cta_button_text); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($cta_secondary_button_text && $cta_secondary_button_url) : ?>
                            <a href="<?php echo esc_url($cta_secondary_button_url); ?>" class="btn bg-secondary text-white hover:bg-opacity-80">
                                <?php echo esc_html($cta_secondary_button_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();