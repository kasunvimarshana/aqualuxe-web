<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main min-h-screen bg-gray-50">
    
    <!-- 404 Section -->
    <section class="error-404 py-20">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="text-center">
                
                <!-- Error Illustration -->
                <div class="error-illustration mb-12">
                    <div class="mx-auto w-80 h-80 bg-gradient-to-br from-aqua-400 to-ocean-500 rounded-full flex items-center justify-center relative overflow-hidden">
                        <!-- Fish Swimming Animation -->
                        <div class="fish-container absolute inset-0">
                            <div class="fish absolute top-1/3 left-1/4 w-8 h-6 text-white opacity-60 animate-swim">
                                🐠
                            </div>
                            <div class="fish absolute top-1/2 right-1/4 w-6 h-4 text-white opacity-40 animate-swim-reverse" style="animation-delay: 1s;">
                                🐟
                            </div>
                            <div class="fish absolute bottom-1/3 left-1/3 w-4 h-3 text-white opacity-50 animate-swim" style="animation-delay: 2s;">
                                🐡
                            </div>
                        </div>
                        
                        <!-- Bubbles -->
                        <div class="bubbles-container absolute inset-0">
                            <div class="bubble absolute bottom-10 left-10 w-3 h-3 bg-white bg-opacity-30 rounded-full animate-bubble"></div>
                            <div class="bubble absolute bottom-20 right-20 w-2 h-2 bg-white bg-opacity-20 rounded-full animate-bubble" style="animation-delay: 0.5s;"></div>
                            <div class="bubble absolute bottom-16 left-1/3 w-4 h-4 bg-white bg-opacity-25 rounded-full animate-bubble" style="animation-delay: 1s;"></div>
                        </div>
                        
                        <!-- 404 Text -->
                        <div class="error-number relative z-10">
                            <span class="text-8xl font-bold text-white drop-shadow-lg">404</span>
                        </div>
                    </div>
                </div>

                <!-- Error Content -->
                <div class="error-content max-w-2xl mx-auto">
                    <h1 class="page-title text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        <?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?>
                    </h1>
                    
                    <p class="error-message text-xl text-gray-600 mb-8">
                        <?php esc_html_e('It looks like this page has swum away! The page you\'re looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe'); ?>
                    </p>

                    <!-- Search Form -->
                    <div class="error-search mb-12">
                        <p class="text-lg text-gray-700 mb-6">
                            <?php esc_html_e('Try searching for what you need:', 'aqualuxe'); ?>
                        </p>
                        
                        <form role="search" method="get" class="search-form max-w-md mx-auto" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="flex">
                                <input type="search" class="search-field flex-1 px-6 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" required>
                                <button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-r-lg transition-colors">
                                    <span class="sr-only"><?php echo _x('Search', 'submit button', 'aqualuxe'); ?></span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Action Buttons -->
                    <div class="error-actions flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary inline-block bg-primary hover:bg-primary-dark text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                            <?php esc_html_e('Go Home', 'aqualuxe'); ?>
                        </a>
                        
                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-secondary inline-block border-2 border-primary text-primary hover:bg-primary hover:text-white font-semibold px-8 py-3 rounded-lg transition-colors">
                                <?php esc_html_e('Shop Products', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn-tertiary inline-block text-gray-600 hover:text-primary font-semibold px-8 py-3 transition-colors">
                            <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Helpful Links Section -->
    <section class="helpful-links py-16 bg-white">
        <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    <?php esc_html_e('You Might Be Looking For...', 'aqualuxe'); ?>
                </h2>
                <p class="text-lg text-gray-600">
                    <?php esc_html_e('Here are some popular pages that might help you find what you need', 'aqualuxe'); ?>
                </p>
            </div>

            <div class="helpful-links-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Popular Categories -->
                <?php if (aqualuxe_is_woocommerce_active()) : ?>
                    <?php
                    $product_categories = get_terms([
                        'taxonomy' => 'product_cat',
                        'hide_empty' => true,
                        'number' => 4,
                        'parent' => 0,
                    ]);

                    if ($product_categories && !is_wp_error($product_categories)) :
                        foreach ($product_categories as $category) :
                            $category_link = get_term_link($category);
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            $image = '';
                            if ($thumbnail_id) {
                                $image = wp_get_attachment_image($thumbnail_id, 'medium', false, ['class' => 'w-full h-32 object-cover rounded-lg mb-4']);
                            }
                            ?>
                            <div class="helpful-link-item text-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                                <?php if ($image) : ?>
                                    <div class="link-image mb-4">
                                        <a href="<?php echo esc_url($category_link); ?>">
                                            <?php echo $image; ?>
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div class="link-icon w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V7l-7-5z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="link-title text-lg font-semibold text-gray-900 mb-2">
                                    <a href="<?php echo esc_url($category_link); ?>" class="hover:text-primary transition-colors">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                </h3>
                                
                                <?php if ($category->description) : ?>
                                    <p class="link-description text-sm text-gray-600 mb-4">
                                        <?php echo esc_html(wp_trim_words($category->description, 15)); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <span class="link-count text-xs text-gray-500">
                                    <?php printf(_n('%s product', '%s products', $category->count, 'aqualuxe'), $category->count); ?>
                                </span>
                            </div>
                            <?php
                        endforeach;
                    endif;
                else :
                    // Default helpful links if WooCommerce is not active
                    $default_links = [
                        [
                            'title' => __('About Us', 'aqualuxe'),
                            'url' => home_url('/about'),
                            'description' => __('Learn about our mission and expertise', 'aqualuxe'),
                            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                        ],
                        [
                            'title' => __('Services', 'aqualuxe'),
                            'url' => home_url('/services'),
                            'description' => __('Explore our aquarium services', 'aqualuxe'),
                            'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'
                        ],
                        [
                            'title' => __('Blog', 'aqualuxe'),
                            'url' => home_url('/blog'),
                            'description' => __('Tips and guides for aquarium enthusiasts', 'aqualuxe'),
                            'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
                        ],
                        [
                            'title' => __('Contact', 'aqualuxe'),
                            'url' => home_url('/contact'),
                            'description' => __('Get in touch with our team', 'aqualuxe'),
                            'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'
                        ]
                    ];

                    foreach ($default_links as $link) :
                        ?>
                        <div class="helpful-link-item text-center p-6 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="link-icon w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr($link['icon']); ?>"></path>
                                </svg>
                            </div>
                            
                            <h3 class="link-title text-lg font-semibold text-gray-900 mb-2">
                                <a href="<?php echo esc_url($link['url']); ?>" class="hover:text-primary transition-colors">
                                    <?php echo esc_html($link['title']); ?>
                                </a>
                            </h3>
                            
                            <p class="link-description text-sm text-gray-600">
                                <?php echo esc_html($link['description']); ?>
                            </p>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>

            </div>
        </div>
    </section>

    <!-- Recent Posts -->
    <?php
    $recent_posts = get_posts([
        'numberposts' => 3,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    
    if ($recent_posts) :
        ?>
        <section class="recent-posts py-16 bg-gray-50">
            <div class="<?php echo esc_attr(aqualuxe_get_container_classes()); ?>">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">
                        <?php esc_html_e('Recent Posts', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php esc_html_e('Check out our latest articles and updates', 'aqualuxe'); ?>
                    </p>
                </div>

                <div class="posts-grid grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php foreach ($recent_posts as $post) : setup_postdata($post); ?>
                        <article class="post-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-content p-6">
                                <div class="post-meta text-sm text-gray-500 mb-2">
                                    <?php echo get_the_date(); ?>
                                </div>
                                
                                <h3 class="post-title text-lg font-semibold text-gray-900 mb-3 line-clamp-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <div class="post-excerpt text-gray-600 mb-4 line-clamp-3">
                                    <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="read-more text-primary hover:text-primary-dark font-medium transition-colors">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?> →
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
        <?php
    endif;
    ?>

</main><!-- #main -->

<?php get_footer(); ?>

<style>
/* Custom animations for 404 page */
@keyframes swim {
    0%, 100% { transform: translateX(0) rotate(0deg); }
    50% { transform: translateX(20px) rotate(5deg); }
}

@keyframes swim-reverse {
    0%, 100% { transform: translateX(0) rotate(0deg); }
    50% { transform: translateX(-20px) rotate(-5deg); }
}

@keyframes bubble {
    0% { transform: translateY(0) scale(1); opacity: 0.7; }
    50% { transform: translateY(-20px) scale(1.2); opacity: 0.5; }
    100% { transform: translateY(-40px) scale(0.8); opacity: 0; }
}

.animate-swim {
    animation: swim 3s ease-in-out infinite;
}

.animate-swim-reverse {
    animation: swim-reverse 3s ease-in-out infinite;
}

.animate-bubble {
    animation: bubble 2s ease-in-out infinite;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
