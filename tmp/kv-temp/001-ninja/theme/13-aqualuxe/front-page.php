<?php
/**
 * The template for displaying the front page
 *
 * This is the template that displays on the front page only.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="hero-section relative bg-gradient-to-r from-blue-900 to-teal-800 text-white">
        <div class="absolute inset-0 overflow-hidden">
            <div class="bubbles-animation absolute inset-0 opacity-20"></div>
        </div>
        <div class="container mx-auto px-4 py-24 relative z-10">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <?php echo esc_html(get_theme_mod('aqualuxe_hero_title', __('Premium Ornamental Fish for Collectors', 'aqualuxe'))); ?>
                </h1>
                <p class="text-xl mb-8 text-blue-100">
                    <?php echo esc_html(get_theme_mod('aqualuxe_hero_subtitle', __('Discover rare and exotic aquatic species sourced from around the world', 'aqualuxe'))); ?>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_hero_primary_button_url', '/shop')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                        <?php echo esc_html(get_theme_mod('aqualuxe_hero_primary_button_text', __('Shop Collection', 'aqualuxe'))); ?>
                    </a>
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_hero_secondary_button_url', '/about')); ?>" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-300">
                        <?php echo esc_html(get_theme_mod('aqualuxe_hero_secondary_button_text', __('Learn More', 'aqualuxe'))); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_theme_mod('aqualuxe_featured_categories_title', __('Explore Our Collection', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (class_exists('WooCommerce')) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    $featured_categories = get_theme_mod('aqualuxe_featured_categories', array());
                    
                    if (empty($featured_categories)) {
                        // Default categories if none are selected
                        $product_categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'number' => 3,
                        ));
                    } else {
                        $product_categories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'include' => $featured_categories,
                            'hide_empty' => false,
                        ));
                    }

                    if (!empty($product_categories) && !is_wp_error($product_categories)) {
                        foreach ($product_categories as $category) {
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image = wp_get_attachment_url($thumbnail_id);
                            ?>
                            <div class="category-card bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="block">
                                    <div class="relative h-64">
                                        <?php if ($image) : ?>
                                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="w-full h-full object-cover">
                                        <?php else : ?>
                                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end">
                                            <div class="p-6 w-full">
                                                <h3 class="text-xl font-bold text-white"><?php echo esc_html($category->name); ?></h3>
                                                <p class="text-blue-100 text-sm mt-1"><?php echo esc_html(sprintf(_n('%s product', '%s products', $category->count, 'aqualuxe'), $category->count)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php else : ?>
                <div class="text-center text-gray-600 dark:text-gray-400">
                    <p><?php esc_html_e('WooCommerce is not active. Please install and activate WooCommerce to display product categories.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Featured Products -->
    <?php if (class_exists('WooCommerce')) : ?>
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_theme_mod('aqualuxe_featured_products_title', __('Featured Products', 'aqualuxe'))); ?>
            </h2>
            
            <?php
            $featured_products = new WP_Query(array(
                'post_type' => 'product',
                'posts_per_page' => 4,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                    ),
                ),
            ));

            if ($featured_products->have_posts()) : ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php while ($featured_products->have_posts()) : $featured_products->the_post(); 
                        global $product; ?>
                        <div class="product-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <div class="relative h-64">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('woocommerce_thumbnail', array('class' => 'w-full h-full object-cover')); ?>
                                    <?php else : ?>
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($product->is_on_sale()) : ?>
                                        <span class="absolute top-4 right-4 bg-primary text-white text-xs font-bold px-2 py-1 rounded">
                                            <?php esc_html_e('Sale', 'aqualuxe'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php the_title(); ?></h3>
                                    <div class="mt-2 text-primary dark:text-primary-dark font-bold">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                </div>
                            </a>
                            <div class="p-4 pt-0 flex justify-between items-center">
                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" data-quantity="1" class="add_to_cart_button ajax_add_to_cart inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300" data-product_id="<?php echo esc_attr($product->get_id()); ?>" data-product_sku="<?php echo esc_attr($product->get_sku()); ?>">
                                    <?php esc_html_e('Add to Cart', 'aqualuxe'); ?>
                                </a>
                                <button class="aqualuxe-quick-view p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-300" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                        <?php esc_html_e('View All Products', 'aqualuxe'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="text-center text-gray-600 dark:text-gray-400">
                    <p><?php esc_html_e('No featured products found. Mark some products as featured to display them here.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- About Section -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="about-image rounded-lg overflow-hidden shadow-xl">
                    <?php 
                    $about_image = get_theme_mod('aqualuxe_about_image');
                    if ($about_image) : ?>
                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php esc_attr_e('About AquaLuxe', 'aqualuxe'); ?>" class="w-full h-auto">
                    <?php else : ?>
                        <div class="bg-gray-200 dark:bg-gray-700 h-96 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="about-content">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html(get_theme_mod('aqualuxe_about_title', __('About AquaLuxe', 'aqualuxe'))); ?>
                    </h2>
                    <div class="prose dark:prose-invert max-w-none mb-8">
                        <?php echo wp_kses_post(get_theme_mod('aqualuxe_about_content', __('AquaLuxe is a premium ornamental fish farming business dedicated to providing rare and exotic aquatic species to collectors and enthusiasts worldwide. With over 20 years of experience in breeding and caring for ornamental fish, we have established ourselves as leaders in the industry.', 'aqualuxe'))); ?>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="feature-item flex items-start">
                            <div class="feature-icon mr-4 text-primary dark:text-primary-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-1 text-gray-900 dark:text-white"><?php esc_html_e('Ethically Sourced', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('All our fish are ethically sourced and bred in our state-of-the-art facilities.', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="feature-item flex items-start">
                            <div class="feature-icon mr-4 text-primary dark:text-primary-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-1 text-gray-900 dark:text-white"><?php esc_html_e('Expert Care', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Our team of experts ensures the highest standards of care for all our aquatic species.', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="feature-item flex items-start">
                            <div class="feature-icon mr-4 text-primary dark:text-primary-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-1 text-gray-900 dark:text-white"><?php esc_html_e('Global Shipping', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('We ship worldwide with specialized packaging to ensure safe arrival.', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="feature-item flex items-start">
                            <div class="feature-icon mr-4 text-primary dark:text-primary-dark">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium mb-1 text-gray-900 dark:text-white"><?php esc_html_e('Satisfaction Guarantee', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('We stand behind the quality of our fish with a 100% satisfaction guarantee.', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo esc_url(get_theme_mod('aqualuxe_about_button_url', '/about')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                        <?php echo esc_html(get_theme_mod('aqualuxe_about_button_text', __('Learn More About Us', 'aqualuxe'))); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_theme_mod('aqualuxe_testimonials_title', __('What Our Customers Say', 'aqualuxe'))); ?>
            </h2>
            
            <div class="testimonials-slider grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Get testimonials from theme mod or use defaults
                $testimonials = get_theme_mod('aqualuxe_testimonials', array());
                
                if (empty($testimonials)) {
                    // Default testimonials
                    $testimonials = array(
                        array(
                            'name' => __('John Smith', 'aqualuxe'),
                            'role' => __('Aquarium Enthusiast', 'aqualuxe'),
                            'content' => __('The rare Discus fish I purchased from AquaLuxe arrived in perfect condition. Their packaging and shipping methods are exceptional, ensuring the fish arrived stress-free.', 'aqualuxe'),
                            'image' => '',
                        ),
                        array(
                            'name' => __('Emily Johnson', 'aqualuxe'),
                            'role' => __('Professional Collector', 'aqualuxe'),
                            'content' => __('As a professional collector, I demand the highest quality. AquaLuxe consistently delivers healthy, vibrant specimens that exceed my expectations.', 'aqualuxe'),
                            'image' => '',
                        ),
                        array(
                            'name' => __('Michael Chen', 'aqualuxe'),
                            'role' => __('Aquascaping Artist', 'aqualuxe'),
                            'content' => __('The plants and fish I\'ve purchased from AquaLuxe have transformed my aquascaping projects. Their customer service is as exceptional as their products.', 'aqualuxe'),
                            'image' => '',
                        ),
                    );
                }
                
                foreach ($testimonials as $testimonial) :
                ?>
                    <div class="testimonial-card bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="testimonial-quote text-primary dark:text-primary-dark mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="testimonial-author">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php echo esc_html($testimonial['name']); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo esc_html($testimonial['role']); ?></p>
                            </div>
                        </div>
                        <div class="testimonial-content text-gray-700 dark:text-gray-300">
                            <p><?php echo esc_html($testimonial['content']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Latest Blog Posts -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_theme_mod('aqualuxe_blog_title', __('Latest From Our Blog', 'aqualuxe'))); ?>
            </h2>
            
            <?php
            $latest_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'ignore_sticky_posts' => true,
            ));

            if ($latest_posts->have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($latest_posts->have_posts()) : $latest_posts->the_post(); ?>
                        <article class="blog-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                                </a>
                            <?php endif; ?>
                            <div class="p-6">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <?php echo get_the_date(); ?>
                                </div>
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary dark:hover:text-primary-dark transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
                <div class="text-center mt-12">
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                        <?php esc_html_e('View All Posts', 'aqualuxe'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <div class="text-center text-gray-600 dark:text-gray-400">
                    <p><?php esc_html_e('No blog posts found. Create some posts to display them here.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">
                    <?php echo esc_html(get_theme_mod('aqualuxe_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg mb-8 text-blue-100">
                    <?php echo esc_html(get_theme_mod('aqualuxe_newsletter_subtitle', __('Stay updated with our latest rare fish arrivals, care tips, and exclusive offers.', 'aqualuxe'))); ?>
                </p>
                <form class="newsletter-form flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                    <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="flex-grow px-4 py-3 rounded-md border-0 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-900">
                    <button type="submit" class="px-6 py-3 bg-white text-primary hover:bg-blue-50 rounded-md font-medium transition-colors duration-300 whitespace-nowrap">
                        <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                    </button>
                </form>
                <p class="text-sm mt-4 text-blue-100">
                    <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?>
                </p>
            </div>
        </div>
    </section>

    <?php
    // If the page has content, display it
    while (have_posts()) :
        the_post();
        
        if ('' !== get_the_content()) :
    ?>
        <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
            <div class="container mx-auto px-4">
                <div class="prose dark:prose-invert max-w-none mx-auto">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php
        endif;
    endwhile;
    ?>
</main><!-- #main -->

<?php
get_footer();