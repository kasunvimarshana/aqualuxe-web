<?php
/**
 * The front page template file
 *
 * This is the template that displays the homepage.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Hero Section
    get_template_part('templates/parts/hero');
    ?>

    <!-- Featured Products Section -->
    <section class="featured-products py-16 bg-gray-50 dark:bg-dark-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-4">
                    <?php echo esc_html(get_theme_mod('featured_products_title', __('Featured Products', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('featured_products_subtitle', __('Discover our selection of premium aquatic life and accessories', 'aqualuxe'))); ?>
                </p>
            </div>

            <?php
            // Featured Products
            if (class_exists('WooCommerce')) :
                $featured_products_count = get_theme_mod('featured_products_count', 8);
                
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => $featured_products_count,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'name',
                            'terms'    => 'featured',
                        ),
                    ),
                );
                
                $featured_query = new WP_Query($args);
                
                if ($featured_query->have_posts()) :
            ?>
                <div class="featured-products-slider swiper">
                    <div class="swiper-wrapper">
                        <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                            <div class="swiper-slide">
                                <?php wc_get_template_part('content', 'product'); ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-pagination featured-products-pagination mt-8"></div>
                </div>
                <div class="slider-navigation flex justify-center mt-6 gap-4">
                    <button class="featured-products-prev slider-nav-btn">
                        <span class="sr-only"><?php esc_html_e('Previous', 'aqualuxe'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M15 18l-6-6 6-6"></path>
                        </svg>
                    </button>
                    <button class="featured-products-next slider-nav-btn">
                        <span class="sr-only"><?php esc_html_e('Next', 'aqualuxe'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"></path>
                        </svg>
                    </button>
                </div>
            <?php
                endif;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-4">
                    <?php echo esc_html(get_theme_mod('categories_title', __('Shop by Category', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('categories_subtitle', __('Browse our wide selection of categories', 'aqualuxe'))); ?>
                </p>
            </div>

            <?php
            // Product Categories
            if (class_exists('WooCommerce')) :
                $args = array(
                    'taxonomy'     => 'product_cat',
                    'orderby'      => 'name',
                    'show_count'   => 0,
                    'pad_counts'   => 0,
                    'hierarchical' => 1,
                    'title_li'     => '',
                    'hide_empty'   => 0,
                    'parent'       => 0,
                    'number'       => 6,
                );
                
                $categories = get_terms($args);
                
                if (!empty($categories)) :
            ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">
                    <?php foreach ($categories as $category) : ?>
                        <?php
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image = wp_get_attachment_url($thumbnail_id);
                        ?>
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-card group">
                            <div class="relative overflow-hidden rounded-xl shadow-soft transition-all duration-300 group-hover:shadow-water h-64">
                                <?php if ($image) : ?>
                                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <?php else : ?>
                                    <div class="w-full h-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary-600 dark:text-primary-400">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-900/70 to-transparent flex items-end p-6">
                                    <div class="text-white">
                                        <h3 class="text-xl font-bold mb-1"><?php echo esc_html($category->name); ?></h3>
                                        <span class="inline-block bg-primary-600 text-white text-sm py-1 px-3 rounded-full transition-all duration-300 group-hover:bg-primary-500">
                                            <?php echo esc_html__('Shop Now', 'aqualuxe'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php
                endif;
            endif;
            ?>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section py-16 bg-gray-50 dark:bg-dark-800">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="about-image">
                    <?php
                    $about_image = get_theme_mod('about_section_image');
                    if ($about_image) :
                    ?>
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr(get_theme_mod('about_section_title')); ?>" class="rounded-xl shadow-soft">
                    <?php else : ?>
                        <div class="bg-primary-100 dark:bg-primary-900 rounded-xl h-96 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary-600 dark:text-primary-400">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="about-content">
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-6">
                        <?php echo esc_html(get_theme_mod('about_section_title', __('About Our Company', 'aqualuxe'))); ?>
                    </h2>
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <?php echo wp_kses_post(get_theme_mod('about_section_content', __('We are dedicated to providing the highest quality aquatic life and accessories for enthusiasts and professionals alike. With years of experience in the industry, we pride ourselves on our expertise and commitment to customer satisfaction.', 'aqualuxe'))); ?>
                    </div>
                    <?php if (get_theme_mod('about_section_button_text')) : ?>
                        <a href="<?php echo esc_url(get_theme_mod('about_section_button_url', '#')); ?>" class="btn btn-primary mt-8">
                            <?php echo esc_html(get_theme_mod('about_section_button_text', __('Learn More', 'aqualuxe'))); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <?php
    $services_query = new WP_Query(array(
        'post_type'      => 'service',
        'posts_per_page' => 3,
    ));
    
    if ($services_query->have_posts()) :
    ?>
    <section class="services-section py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-4">
                    <?php echo esc_html(get_theme_mod('services_title', __('Our Services', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('services_subtitle', __('Professional aquarium services for all your needs', 'aqualuxe'))); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($services_query->have_posts()) : $services_query->the_post(); ?>
                    <div class="service-card bg-white dark:bg-dark-700 rounded-xl shadow-soft overflow-hidden transition-all duration-300 hover:shadow-water">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="service-image h-48 overflow-hidden">
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover')); ?>
                            </div>
                        <?php endif; ?>
                        <div class="service-content p-6">
                            <h3 class="text-xl font-bold text-dark-800 dark:text-white mb-3">
                                <?php the_title(); ?>
                            </h3>
                            <div class="text-dark-600 dark:text-dark-200 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <?php if (get_theme_mod('services_button_text')) : ?>
                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(get_theme_mod('services_button_url', '#')); ?>" class="btn btn-primary">
                        <?php echo esc_html(get_theme_mod('services_button_text', __('View All Services', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    endif;
    wp_reset_postdata();
    ?>

    <!-- Testimonials Section -->
    <?php
    $testimonials_query = new WP_Query(array(
        'post_type'      => 'testimonial',
        'posts_per_page' => 6,
    ));
    
    if ($testimonials_query->have_posts()) :
    ?>
    <section class="testimonials-section py-16 bg-gray-50 dark:bg-dark-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-4">
                    <?php echo esc_html(get_theme_mod('testimonials_title', __('What Our Customers Say', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('testimonials_subtitle', __('Read testimonials from our satisfied customers', 'aqualuxe'))); ?>
                </p>
            </div>

            <div class="testimonials-slider swiper">
                <div class="swiper-wrapper">
                    <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post(); ?>
                        <div class="swiper-slide">
                            <div class="testimonial-card bg-white dark:bg-dark-700 rounded-xl shadow-soft p-6 h-full flex flex-col">
                                <div class="testimonial-rating flex text-accent-500 mb-4">
                                    <?php
                                    $rating = get_post_meta(get_the_ID(), 'testimonial_rating', true);
                                    $rating = $rating ? intval($rating) : 5;
                                    
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="mr-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        } else {
                                            echo '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="testimonial-content text-dark-600 dark:text-dark-200 italic mb-6 flex-grow">
                                    <?php the_content(); ?>
                                </div>
                                <div class="testimonial-author flex items-center">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="testimonial-avatar w-12 h-12 rounded-full overflow-hidden mr-4">
                                            <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-full object-cover')); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="font-bold text-dark-800 dark:text-white">
                                            <?php the_title(); ?>
                                        </h4>
                                        <?php
                                        $position = get_post_meta(get_the_ID(), 'testimonial_position', true);
                                        if ($position) :
                                        ?>
                                            <p class="text-sm text-dark-500 dark:text-dark-300">
                                                <?php echo esc_html($position); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="swiper-pagination testimonials-pagination mt-8"></div>
            </div>
            <div class="slider-navigation flex justify-center mt-6 gap-4">
                <button class="testimonials-prev slider-nav-btn">
                    <span class="sr-only"><?php esc_html_e('Previous', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6"></path>
                    </svg>
                </button>
                <button class="testimonials-next slider-nav-btn">
                    <span class="sr-only"><?php esc_html_e('Next', 'aqualuxe'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"></path>
                    </svg>
                </button>
            </div>
        </div>
    </section>
    <?php
    endif;
    wp_reset_postdata();
    ?>

    <!-- Latest Blog Posts Section -->
    <?php
    $blog_query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 3,
    ));
    
    if ($blog_query->have_posts()) :
    ?>
    <section class="blog-section py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-dark-800 dark:text-white mb-4">
                    <?php echo esc_html(get_theme_mod('blog_title', __('Latest Articles', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                    <?php echo esc_html(get_theme_mod('blog_subtitle', __('Stay updated with our latest news and articles', 'aqualuxe'))); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
                    <article class="blog-card bg-white dark:bg-dark-700 rounded-xl shadow-soft overflow-hidden transition-all duration-300 hover:shadow-water">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block h-48 overflow-hidden">
                                <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-full object-cover transition-transform duration-500 hover:scale-110')); ?>
                            </a>
                        <?php endif; ?>
                        <div class="p-6">
                            <div class="flex items-center text-sm text-dark-500 dark:text-dark-300 mb-3">
                                <span class="flex items-center mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?php the_author(); ?>
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-dark-800 dark:text-white mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            <div class="text-dark-600 dark:text-dark-200 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if (get_theme_mod('blog_button_text')) : ?>
                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(get_theme_mod('blog_button_url', get_permalink(get_option('page_for_posts')))); ?>" class="btn btn-primary">
                        <?php echo esc_html(get_theme_mod('blog_button_text', __('View All Articles', 'aqualuxe'))); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    endif;
    wp_reset_postdata();
    ?>

    <!-- CTA Section -->
    <section class="cta-section py-16 bg-primary-600 dark:bg-primary-800 text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="cta-content">
                    <h2 class="text-3xl md:text-4xl font-display font-bold mb-6">
                        <?php echo esc_html(get_theme_mod('cta_title', __('Ready to Transform Your Aquatic Experience?', 'aqualuxe'))); ?>
                    </h2>
                    <p class="text-lg text-white/80 mb-8">
                        <?php echo esc_html(get_theme_mod('cta_content', __('Join thousands of satisfied customers who have elevated their aquatic setups with our premium products and expert services.', 'aqualuxe'))); ?>
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="<?php echo esc_url(get_theme_mod('cta_primary_button_url', '#')); ?>" class="btn btn-white">
                            <?php echo esc_html(get_theme_mod('cta_primary_button_text', __('Shop Now', 'aqualuxe'))); ?>
                        </a>
                        <?php if (get_theme_mod('cta_secondary_button_text')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('cta_secondary_button_url', '#')); ?>" class="btn btn-outline-white">
                                <?php echo esc_html(get_theme_mod('cta_secondary_button_text', __('Contact Us', 'aqualuxe'))); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="cta-image">
                    <?php
                    $cta_image = get_theme_mod('cta_image');
                    if ($cta_image) :
                    ?>
                        <img src="<?php echo esc_url($cta_image); ?>" alt="<?php echo esc_attr(get_theme_mod('cta_title')); ?>" class="rounded-xl">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

</main><!-- #main -->

<?php
get_footer();