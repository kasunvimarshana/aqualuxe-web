<?php
/**
 * Template Name: Homepage
 *
 * The template for displaying the homepage.
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
    // Hero Slider Section
    if (get_theme_mod('aqualuxe_homepage_hero_enable', true)) :
        $slider_count = get_theme_mod('aqualuxe_homepage_hero_count', 3);
        $slider_items = array();
        
        for ($i = 1; $i <= $slider_count; $i++) {
            $slide = array(
                'image' => get_theme_mod('aqualuxe_homepage_hero_image_' . $i, ''),
                'title' => get_theme_mod('aqualuxe_homepage_hero_title_' . $i, ''),
                'subtitle' => get_theme_mod('aqualuxe_homepage_hero_subtitle_' . $i, ''),
                'text' => get_theme_mod('aqualuxe_homepage_hero_text_' . $i, ''),
                'button_text' => get_theme_mod('aqualuxe_homepage_hero_button_text_' . $i, ''),
                'button_url' => get_theme_mod('aqualuxe_homepage_hero_button_url_' . $i, ''),
            );
            
            if (!empty($slide['image'])) {
                $slider_items[] = $slide;
            }
        }
        
        if (!empty($slider_items)) :
    ?>
        <section class="hero-section">
            <div class="hero-slider">
                <?php foreach ($slider_items as $slide) : ?>
                    <div class="hero-slide" style="background-image: url('<?php echo esc_url($slide['image']); ?>');">
                        <div class="hero-content">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-8 col-md-10">
                                        <?php if (!empty($slide['subtitle'])) : ?>
                                            <div class="hero-subtitle" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($slide['subtitle']); ?></div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['title'])) : ?>
                                            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200"><?php echo esc_html($slide['title']); ?></h1>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['text'])) : ?>
                                            <div class="hero-text" data-aos="fade-up" data-aos-delay="300"><?php echo wp_kses_post($slide['text']); ?></div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($slide['button_text']) && !empty($slide['button_url'])) : ?>
                                            <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                                                <a href="<?php echo esc_url($slide['button_url']); ?>" class="btn btn-primary"><?php echo esc_html($slide['button_text']); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Features Section
    if (get_theme_mod('aqualuxe_homepage_features_enable', true)) :
        $features_title = get_theme_mod('aqualuxe_homepage_features_title', __('Why Choose Us', 'aqualuxe'));
        $features_subtitle = get_theme_mod('aqualuxe_homepage_features_subtitle', __('Our Advantages', 'aqualuxe'));
        $features_count = get_theme_mod('aqualuxe_homepage_features_count', 4);
        $features_items = array();
        
        for ($i = 1; $i <= $features_count; $i++) {
            $feature = array(
                'icon' => get_theme_mod('aqualuxe_homepage_features_icon_' . $i, 'fas fa-fish'),
                'title' => get_theme_mod('aqualuxe_homepage_features_title_' . $i, ''),
                'text' => get_theme_mod('aqualuxe_homepage_features_text_' . $i, ''),
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
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-item text-center" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                                <div class="feature-icon">
                                    <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                </div>
                                <h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
                                <div class="feature-text"><?php echo wp_kses_post($feature['text']); ?></div>
                            </div>
                        </div>
                    <?php 
                        $delay += 100;
                    endforeach; 
                    ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // About Section
    if (get_theme_mod('aqualuxe_homepage_about_enable', true)) :
        $about_title = get_theme_mod('aqualuxe_homepage_about_title', __('About Our Aquatic Farm', 'aqualuxe'));
        $about_subtitle = get_theme_mod('aqualuxe_homepage_about_subtitle', __('Our Story', 'aqualuxe'));
        $about_text = get_theme_mod('aqualuxe_homepage_about_text', '');
        $about_image = get_theme_mod('aqualuxe_homepage_about_image', '');
        $about_button_text = get_theme_mod('aqualuxe_homepage_about_button_text', __('Learn More', 'aqualuxe'));
        $about_button_url = get_theme_mod('aqualuxe_homepage_about_button_url', '#');
        
        if (!empty($about_title) || !empty($about_text)) :
    ?>
        <section class="about-section section bg-light">
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
                            
                            <?php if (!empty($about_text)) : ?>
                                <div class="about-text">
                                    <?php echo wp_kses_post($about_text); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($about_button_text) && !empty($about_button_url)) : ?>
                                <div class="about-button">
                                    <a href="<?php echo esc_url($about_button_url); ?>" class="btn btn-primary"><?php echo esc_html($about_button_text); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Featured Species Section
    if (get_theme_mod('aqualuxe_homepage_species_enable', true) && post_type_exists('species')) :
        $species_title = get_theme_mod('aqualuxe_homepage_species_title', __('Featured Species', 'aqualuxe'));
        $species_subtitle = get_theme_mod('aqualuxe_homepage_species_subtitle', __('Our Collection', 'aqualuxe'));
        $species_count = get_theme_mod('aqualuxe_homepage_species_count', 8);
        $species_button_text = get_theme_mod('aqualuxe_homepage_species_button_text', __('View All Species', 'aqualuxe'));
        $species_button_url = get_theme_mod('aqualuxe_homepage_species_button_url', '#');
        
        $species_args = array(
            'post_type' => 'species',
            'posts_per_page' => $species_count,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $species_query = new WP_Query($species_args);
        
        if ($species_query->have_posts()) :
    ?>
        <section class="species-section section">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($species_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($species_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($species_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($species_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="species-slider" data-aos="fade-up" data-aos-delay="200">
                    <?php 
                    while ($species_query->have_posts()) : 
                        $species_query->the_post();
                        
                        // Get species meta
                        $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
                        $origin = get_post_meta(get_the_ID(), 'origin', true);
                        $habitat = get_post_meta(get_the_ID(), 'habitat', true);
                    ?>
                        <div class="species-item">
                            <div class="species-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="species-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="species-content">
                                    <h3 class="species-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    
                                    <?php if (!empty($scientific_name)) : ?>
                                        <div class="species-scientific-name"><?php echo esc_html($scientific_name); ?></div>
                                    <?php endif; ?>
                                    
                                    <div class="species-meta">
                                        <?php if (!empty($origin)) : ?>
                                            <span class="species-origin"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($origin); ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($habitat)) : ?>
                                            <span class="species-habitat"><i class="fas fa-water"></i> <?php echo esc_html($habitat); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="species-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                </div>
                            </div>
                        </div>
                    <?php 
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                
                <?php if (!empty($species_button_text) && !empty($species_button_url)) : ?>
                    <div class="text-center mt-lg">
                        <a href="<?php echo esc_url($species_button_url); ?>" class="btn btn-primary"><?php echo esc_html($species_button_text); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Featured Products Section
    if (get_theme_mod('aqualuxe_homepage_products_enable', true) && class_exists('WooCommerce')) :
        $products_title = get_theme_mod('aqualuxe_homepage_products_title', __('Featured Products', 'aqualuxe'));
        $products_subtitle = get_theme_mod('aqualuxe_homepage_products_subtitle', __('Our Collection', 'aqualuxe'));
        $products_count = get_theme_mod('aqualuxe_homepage_products_count', 8);
        $products_button_text = get_theme_mod('aqualuxe_homepage_products_button_text', __('View All Products', 'aqualuxe'));
        $products_button_url = get_theme_mod('aqualuxe_homepage_products_button_url', get_permalink(wc_get_page_id('shop')));
    ?>
        <section class="products-section section bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($products_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($products_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($products_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($products_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="product-slider" data-aos="fade-up" data-aos-delay="200">
                    <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $products_count,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_visibility',
                                'field'    => 'name',
                                'terms'    => 'featured',
                                'operator' => 'IN',
                            ),
                        ),
                    );
                    
                    $products = new WP_Query($args);
                    
                    if ($products->have_posts()) :
                        while ($products->have_posts()) : $products->the_post();
                            wc_get_template_part('content', 'product');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
                
                <?php if (!empty($products_button_text) && !empty($products_button_url)) : ?>
                    <div class="text-center mt-lg">
                        <a href="<?php echo esc_url($products_button_url); ?>" class="btn btn-primary"><?php echo esc_html($products_button_text); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // Testimonials Section
    if (get_theme_mod('aqualuxe_homepage_testimonials_enable', true) && post_type_exists('testimonial')) :
        $testimonials_title = get_theme_mod('aqualuxe_homepage_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
        $testimonials_subtitle = get_theme_mod('aqualuxe_homepage_testimonials_subtitle', __('Testimonials', 'aqualuxe'));
        $testimonials_count = get_theme_mod('aqualuxe_homepage_testimonials_count', 6);
        
        $testimonials_args = array(
            'post_type' => 'testimonial',
            'posts_per_page' => $testimonials_count,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $testimonials_query = new WP_Query($testimonials_args);
        
        if ($testimonials_query->have_posts()) :
    ?>
        <section class="testimonials-section section">
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
    
    // Team Section
    if (get_theme_mod('aqualuxe_homepage_team_enable', true) && post_type_exists('team')) :
        $team_title = get_theme_mod('aqualuxe_homepage_team_title', __('Meet Our Team', 'aqualuxe'));
        $team_subtitle = get_theme_mod('aqualuxe_homepage_team_subtitle', __('Our Experts', 'aqualuxe'));
        $team_count = get_theme_mod('aqualuxe_homepage_team_count', 4);
        
        $team_args = array(
            'post_type' => 'team',
            'posts_per_page' => $team_count,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
        
        $team_query = new WP_Query($team_args);
        
        if ($team_query->have_posts()) :
    ?>
        <section class="team-section section bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($team_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($team_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($team_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($team_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="team-slider" data-aos="fade-up" data-aos-delay="200">
                    <?php 
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
                        <div class="team-item">
                            <div class="team-card">
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
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // Blog Section
    if (get_theme_mod('aqualuxe_homepage_blog_enable', true)) :
        $blog_title = get_theme_mod('aqualuxe_homepage_blog_title', __('Latest News', 'aqualuxe'));
        $blog_subtitle = get_theme_mod('aqualuxe_homepage_blog_subtitle', __('From Our Blog', 'aqualuxe'));
        $blog_count = get_theme_mod('aqualuxe_homepage_blog_count', 3);
        $blog_button_text = get_theme_mod('aqualuxe_homepage_blog_button_text', __('View All Posts', 'aqualuxe'));
        $blog_button_url = get_theme_mod('aqualuxe_homepage_blog_button_url', get_permalink(get_option('page_for_posts')));
        
        $blog_args = array(
            'post_type' => 'post',
            'posts_per_page' => $blog_count,
            'ignore_sticky_posts' => true,
        );
        
        $blog_query = new WP_Query($blog_args);
        
        if ($blog_query->have_posts()) :
    ?>
        <section class="blog-section section">
            <div class="container">
                <div class="section-header text-center">
                    <?php if (!empty($blog_subtitle)) : ?>
                        <div class="subtitle" data-aos="fade-up"><?php echo esc_html($blog_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($blog_title)) : ?>
                        <h2 class="section-title" data-aos="fade-up" data-aos-delay="100"><?php echo esc_html($blog_title); ?></h2>
                    <?php endif; ?>
                </div>
                
                <div class="row">
                    <?php 
                    $delay = 200;
                    while ($blog_query->have_posts()) : 
                        $blog_query->the_post();
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="blog-card" data-aos="fade-up" data-aos-delay="<?php echo esc_attr($delay); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="blog-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="blog-content">
                                    <div class="blog-meta">
                                        <span class="blog-date"><i class="far fa-calendar-alt"></i> <?php echo get_the_date(); ?></span>
                                        <span class="blog-author"><i class="far fa-user"></i> <?php the_author(); ?></span>
                                    </div>
                                    
                                    <h3 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    
                                    <div class="blog-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'aqualuxe'); ?> <i class="fas fa-long-arrow-alt-right"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $delay += 100;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                
                <?php if (!empty($blog_button_text) && !empty($blog_button_url)) : ?>
                    <div class="text-center mt-lg">
                        <a href="<?php echo esc_url($blog_button_url); ?>" class="btn btn-primary"><?php echo esc_html($blog_button_text); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php 
        endif;
    endif;
    
    // CTA Section
    if (get_theme_mod('aqualuxe_homepage_cta_enable', true)) :
        $cta_title = get_theme_mod('aqualuxe_homepage_cta_title', __('Ready to Get Started?', 'aqualuxe'));
        $cta_text = get_theme_mod('aqualuxe_homepage_cta_text', __('Contact us today to learn more about our products and services.', 'aqualuxe'));
        $cta_button_text = get_theme_mod('aqualuxe_homepage_cta_button_text', __('Contact Us', 'aqualuxe'));
        $cta_button_url = get_theme_mod('aqualuxe_homepage_cta_button_url', '#');
        $cta_bg_image = get_theme_mod('aqualuxe_homepage_cta_bg_image', '');
        
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
    <?php 
        endif;
    endif;
    
    // Page content
    while (have_posts()) :
        the_post();
        
        if (get_the_content()) :
    ?>
        <section class="page-content-section section">
            <div class="container">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endwhile;
    ?>

</main><!-- #primary -->

<?php
get_footer();