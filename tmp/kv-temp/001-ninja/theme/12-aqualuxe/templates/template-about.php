<?php
/**
 * Template Name: About Page
 *
 * The template for displaying the about page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Page header
    if (get_theme_mod('aqualuxe_page_header_enable', true)) :
        $page_title = get_the_title();
        $page_subtitle = get_post_meta(get_the_ID(), 'page_subtitle', true);
        $page_header_bg = get_post_meta(get_the_ID(), 'page_header_bg', true);
        
        if (empty($page_header_bg)) {
            $page_header_bg = get_theme_mod('aqualuxe_page_header_bg', '');
        }
    ?>
        <section class="page-header-wrapper" <?php if (!empty($page_header_bg)) : ?>style="background-image: url('<?php echo esc_url($page_header_bg); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="page-header text-center">
                    <h1 class="page-title"><?php echo esc_html($page_title); ?></h1>
                    
                    <?php if (!empty($page_subtitle)) : ?>
                        <div class="page-subtitle"><?php echo esc_html($page_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php
                    if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                        aqualuxe_breadcrumbs();
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    // About Section
    $about_title = get_post_meta(get_the_ID(), 'about_title', true);
    $about_subtitle = get_post_meta(get_the_ID(), 'about_subtitle', true);
    $about_image = get_post_meta(get_the_ID(), 'about_image', true);
    
    if (empty($about_title)) {
        $about_title = get_theme_mod('aqualuxe_about_title', __('About Our Aquatic Farm', 'aqualuxe'));
    }
    
    if (empty($about_subtitle)) {
        $about_subtitle = get_theme_mod('aqualuxe_about_subtitle', __('Our Story', 'aqualuxe'));
    }
    
    if (empty($about_image)) {
        $about_image = get_theme_mod('aqualuxe_about_image', '');
    }
    ?>
    
    <section class="about-section section">
        <div class="container">
            <div class="row align-items-center">
                <?php if (!empty($about_image)) : ?>
                <div class="col-lg-6">
                    <div class="about-image" data-aos="fade-right">
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="img-fluid">
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="col-lg-<?php echo !empty($about_image) ? '6' : '12'; ?>">
                    <div class="about-content" data-aos="fade-left">
                        <?php if (!empty($about_subtitle)) : ?>
                            <div class="subtitle"><?php echo esc_html($about_subtitle); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($about_title)) : ?>
                            <h2 class="section-title"><?php echo esc_html($about_title); ?></h2>
                        <?php endif; ?>
                        
                        <div class="about-text">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php
    // Vision & Mission Section
    $vision_title = get_post_meta(get_the_ID(), 'vision_title', true);
    $vision_text = get_post_meta(get_the_ID(), 'vision_text', true);
    $mission_title = get_post_meta(get_the_ID(), 'mission_title', true);
    $mission_text = get_post_meta(get_the_ID(), 'mission_text', true);
    
    if (empty($vision_title)) {
        $vision_title = get_theme_mod('aqualuxe_vision_title', __('Our Vision', 'aqualuxe'));
    }
    
    if (empty($vision_text)) {
        $vision_text = get_theme_mod('aqualuxe_vision_text', '');
    }
    
    if (empty($mission_title)) {
        $mission_title = get_theme_mod('aqualuxe_mission_title', __('Our Mission', 'aqualuxe'));
    }
    
    if (empty($mission_text)) {
        $mission_text = get_theme_mod('aqualuxe_mission_text', '');
    }
    
    if (!empty($vision_text) || !empty($mission_text)) :
    ?>
        <section class="vision-mission-section section bg-light">
            <div class="container">
                <div class="row">
                    <?php if (!empty($vision_text)) : ?>
                    <div class="col-lg-6">
                        <div class="vision-content" data-aos="fade-up">
                            <?php if (!empty($vision_title)) : ?>
                                <h3 class="vision-title"><?php echo esc_html($vision_title); ?></h3>
                            <?php endif; ?>
                            
                            <div class="vision-text">
                                <?php echo wp_kses_post($vision_text); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($mission_text)) : ?>
                    <div class="col-lg-6">
                        <div class="mission-content" data-aos="fade-up" data-aos-delay="100">
                            <?php if (!empty($mission_title)) : ?>
                                <h3 class="mission-title"><?php echo esc_html($mission_title); ?></h3>
                            <?php endif; ?>
                            
                            <div class="mission-text">
                                <?php echo wp_kses_post($mission_text); ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // Features Section
    $features_title = get_post_meta(get_the_ID(), 'features_title', true);
    $features_subtitle = get_post_meta(get_the_ID(), 'features_subtitle', true);
    
    if (empty($features_title)) {
        $features_title = get_theme_mod('aqualuxe_features_title', __('Why Choose Us', 'aqualuxe'));
    }
    
    if (empty($features_subtitle)) {
        $features_subtitle = get_theme_mod('aqualuxe_features_subtitle', __('Our Advantages', 'aqualuxe'));
    }
    
    // Get features from theme mods
    $features_count = get_theme_mod('aqualuxe_features_count', 6);
    $features_items = array();
    
    for ($i = 1; $i <= $features_count; $i++) {
        $feature = array(
            'icon' => get_theme_mod('aqualuxe_feature_icon_' . $i, 'fas fa-fish'),
            'title' => get_theme_mod('aqualuxe_feature_title_' . $i, ''),
            'text' => get_theme_mod('aqualuxe_feature_text_' . $i, ''),
        );
        
        if (!empty($feature['title'])) {
            $features_items[] = $feature;
        }
    }
    
    if (!empty($features_items)) :
    ?>
        <section class="features-section section">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($features_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($features_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($features_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($features_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <?php 
                    $delay = 200;
                    foreach ($features_items as $feature) : 
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="feature-item" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                                <div class="feature-icon">
                                    <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                </div>
                                <div class="feature-content">
                                    <h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
                                    <div class="feature-text"><?php echo wp_kses_post($feature['text']); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $delay += 100;
                    endforeach; 
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // History Section
    $history_title = get_post_meta(get_the_ID(), 'history_title', true);
    $history_subtitle = get_post_meta(get_the_ID(), 'history_subtitle', true);
    
    if (empty($history_title)) {
        $history_title = get_theme_mod('aqualuxe_history_title', __('Our History', 'aqualuxe'));
    }
    
    if (empty($history_subtitle)) {
        $history_subtitle = get_theme_mod('aqualuxe_history_subtitle', __('How We Started', 'aqualuxe'));
    }
    
    // Get history items from theme mods
    $history_count = get_theme_mod('aqualuxe_history_count', 4);
    $history_items = array();
    
    for ($i = 1; $i <= $history_count; $i++) {
        $history = array(
            'year' => get_theme_mod('aqualuxe_history_year_' . $i, ''),
            'title' => get_theme_mod('aqualuxe_history_title_' . $i, ''),
            'text' => get_theme_mod('aqualuxe_history_text_' . $i, ''),
        );
        
        if (!empty($history['year']) && !empty($history['title'])) {
            $history_items[] = $history;
        }
    }
    
    if (!empty($history_items)) :
    ?>
        <section class="history-section section bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($history_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($history_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($history_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($history_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="history-timeline" data-aos="fade-up" data-aos-delay="200">
                    <?php foreach ($history_items as $index => $history) : ?>
                        <div class="history-item <?php echo ($index % 2 == 0) ? 'left' : 'right'; ?>">
                            <div class="history-marker">
                                <span class="history-year"><?php echo esc_html($history['year']); ?></span>
                            </div>
                            <div class="history-content">
                                <h3 class="history-title"><?php echo esc_html($history['title']); ?></h3>
                                <div class="history-text"><?php echo wp_kses_post($history['text']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // Team Section
    if (post_type_exists('team')) :
        $team_title = get_post_meta(get_the_ID(), 'team_title', true);
        $team_subtitle = get_post_meta(get_the_ID(), 'team_subtitle', true);
        
        if (empty($team_title)) {
            $team_title = get_theme_mod('aqualuxe_team_title', __('Meet Our Team', 'aqualuxe'));
        }
        
        if (empty($team_subtitle)) {
            $team_subtitle = get_theme_mod('aqualuxe_team_subtitle', __('Our Experts', 'aqualuxe'));
        }
        
        $team_count = get_theme_mod('aqualuxe_team_count', 8);
        
        $team_args = array(
            'post_type' => 'team',
            'posts_per_page' => $team_count,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
        
        $team_query = new WP_Query($team_args);
        
        if ($team_query->have_posts()) :
    ?>
        <section class="team-section section">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($team_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($team_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($team_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <?php 
                    $delay = 200;
                    while ($team_query->have_posts()) : 
                        $team_query->the_post();
                        
                        // Get team member meta
                        $position = get_post_meta(get_the_ID(), 'position', true);
                        $email = get_post_meta(get_the_ID(), 'email', true);
                        $facebook = get_post_meta(get_the_ID(), 'facebook', true);
                        $twitter = get_post_meta(get_the_ID(), 'twitter', true);
                        $linkedin = get_post_meta(get_the_ID(), 'linkedin', true);
                        $instagram = get_post_meta(get_the_ID(), 'instagram', true);
                    ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="team-card" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="team-image">
                                        <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        
                                        <?php if (!empty($facebook) || !empty($twitter) || !empty($linkedin) || !empty($instagram)) : ?>
                                            <div class="team-social">
                                                <?php if (!empty($facebook)) : ?>
                                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($twitter)) : ?>
                                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($linkedin)) : ?>
                                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($instagram)) : ?>
                                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-content">
                                    <h3 class="team-name"><?php the_title(); ?></h3>
                                    
                                    <?php if (!empty($position)) : ?>
                                        <div class="team-position"><?php echo esc_html($position); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($email)) : ?>
                                        <div class="team-email">
                                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $delay += 100;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Testimonials Section
    if (post_type_exists('testimonial')) :
        $testimonials_title = get_post_meta(get_the_ID(), 'testimonials_title', true);
        $testimonials_subtitle = get_post_meta(get_the_ID(), 'testimonials_subtitle', true);
        
        if (empty($testimonials_title)) {
            $testimonials_title = get_theme_mod('aqualuxe_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
        }
        
        if (empty($testimonials_subtitle)) {
            $testimonials_subtitle = get_theme_mod('aqualuxe_testimonials_subtitle', __('Testimonials', 'aqualuxe'));
        }
        
        $testimonials_count = get_theme_mod('aqualuxe_testimonials_count', 6);
        
        $testimonials_args = array(
            'post_type' => 'testimonial',
            'posts_per_page' => $testimonials_count,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $testimonials_query = new WP_Query($testimonials_args);
        
        if ($testimonials_query->have_posts()) :
    ?>
        <section class="testimonials-section section bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($testimonials_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($testimonials_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($testimonials_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($testimonials_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="testimonials-slider" data-aos="fade-up" data-aos-delay="200">
                    <?php 
                    while ($testimonials_query->have_posts()) : 
                        $testimonials_query->the_post();
                        
                        // Get testimonial meta
                        $client_name = get_post_meta(get_the_ID(), 'client_name', true);
                        $client_position = get_post_meta(get_the_ID(), 'client_position', true);
                        $client_company = get_post_meta(get_the_ID(), 'client_company', true);
                        $rating = get_post_meta(get_the_ID(), 'rating', true);
                    ?>
                        <div class="testimonial-item">
                            <div class="testimonial-card">
                                <?php if (!empty($rating)) : ?>
                                    <div class="testimonial-rating">
                                        <?php 
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<i class="fas fa-star"></i>';
                                            } else {
                                                echo '<i class="far fa-star"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-content">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-author">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="testimonial-author-image">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded-circle')); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="testimonial-author-info">
                                        <?php if (!empty($client_name)) : ?>
                                            <h4 class="testimonial-author-name"><?php echo esc_html($client_name); ?></h4>
                                        <?php else : ?>
                                            <h4 class="testimonial-author-name"><?php the_title(); ?></h4>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($client_position) || !empty($client_company)) : ?>
                                            <div class="testimonial-author-position">
                                                <?php 
                                                if (!empty($client_position)) {
                                                    echo esc_html($client_position);
                                                    
                                                    if (!empty($client_company)) {
                                                        echo ', ';
                                                    }
                                                }
                                                
                                                if (!empty($client_company)) {
                                                    echo esc_html($client_company);
                                                }
                                                ?>
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
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Partners Section
    $partners_title = get_post_meta(get_the_ID(), 'partners_title', true);
    $partners_subtitle = get_post_meta(get_the_ID(), 'partners_subtitle', true);
    
    if (empty($partners_title)) {
        $partners_title = get_theme_mod('aqualuxe_partners_title', __('Our Partners', 'aqualuxe'));
    }
    
    if (empty($partners_subtitle)) {
        $partners_subtitle = get_theme_mod('aqualuxe_partners_subtitle', __('Trusted By', 'aqualuxe'));
    }
    
    // Get partners from theme mods
    $partners_count = get_theme_mod('aqualuxe_partners_count', 6);
    $partners_items = array();
    
    for ($i = 1; $i <= $partners_count; $i++) {
        $partner = array(
            'logo' => get_theme_mod('aqualuxe_partner_logo_' . $i, ''),
            'name' => get_theme_mod('aqualuxe_partner_name_' . $i, ''),
            'url' => get_theme_mod('aqualuxe_partner_url_' . $i, ''),
        );
        
        if (!empty($partner['logo'])) {
            $partners_items[] = $partner;
        }
    }
    
    if (!empty($partners_items)) :
    ?>
        <section class="partners-section section">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($partners_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($partners_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($partners_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($partners_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="partners-wrapper" data-aos="fade-up" data-aos-delay="200">
                    <?php foreach ($partners_items as $partner) : ?>
                        <div class="partner-item">
                            <?php if (!empty($partner['url'])) : ?>
                                <a href="<?php echo esc_url($partner['url']); ?>" target="_blank" title="<?php echo esc_attr($partner['name']); ?>">
                                    <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" class="img-fluid">
                                </a>
                            <?php else : ?>
                                <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" class="img-fluid">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // CTA Section
    $cta_title = get_post_meta(get_the_ID(), 'cta_title', true);
    $cta_text = get_post_meta(get_the_ID(), 'cta_text', true);
    $cta_button_text = get_post_meta(get_the_ID(), 'cta_button_text', true);
    $cta_button_url = get_post_meta(get_the_ID(), 'cta_button_url', true);
    $cta_bg_image = get_post_meta(get_the_ID(), 'cta_bg_image', true);
    
    if (empty($cta_title)) {
        $cta_title = get_theme_mod('aqualuxe_cta_title', __('Ready to Get Started?', 'aqualuxe'));
    }
    
    if (empty($cta_text)) {
        $cta_text = get_theme_mod('aqualuxe_cta_text', __('Contact us today to learn more about our products and services.', 'aqualuxe'));
    }
    
    if (empty($cta_button_text)) {
        $cta_button_text = get_theme_mod('aqualuxe_cta_button_text', __('Contact Us', 'aqualuxe'));
    }
    
    if (empty($cta_button_url)) {
        $cta_button_url = get_theme_mod('aqualuxe_cta_button_url', '#');
    }
    
    if (empty($cta_bg_image)) {
        $cta_bg_image = get_theme_mod('aqualuxe_cta_bg_image', '');
    }
    
    if (!empty($cta_title) || !empty($cta_text)) :
    ?>
        <section class="cta-section" <?php if (!empty($cta_bg_image)) : ?>style="background-image: url('<?php echo esc_url($cta_bg_image); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="cta-content text-center" data-aos="fade-up">
                            <?php if (!empty($cta_title)) : ?>
                                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
                            <?php endif; ?>
                            
                            <?php if (!empty($cta_text)) : ?>
                                <div class="cta-text"><?php echo wp_kses_post($cta_text); ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($cta_button_text) && !empty($cta_button_url)) : ?>
                                <div class="cta-button">
                                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-white"><?php echo esc_html($cta_button_text); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();