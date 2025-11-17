<?php get_header(); ?>

<main class="error-404-page">
    
    <!-- 404 Header -->
    <header class="error-header py-16 lg:py-24 bg-gradient-to-br from-primary-600 via-secondary-500 to-aqua-400 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                
                <div class="error-code text-8xl lg:text-9xl font-bold font-secondary opacity-50 mb-4" data-aos="fade-up">
                    404
                </div>
                
                <h1 class="error-title text-4xl lg:text-6xl font-bold font-secondary mb-6" data-aos="fade-up" data-aos-delay="200">
                    <?php esc_html_e('Page Not Found', 'aqualuxe'); ?>
                </h1>
                
                <p class="error-subtitle text-xl lg:text-2xl text-gray-100 font-light leading-relaxed max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="400">
                    <?php esc_html_e('Oops! The page you\'re looking for seems to have swum away. Don\'t worry, let\'s help you find what you need.', 'aqualuxe'); ?>
                </p>
                
            </div>
        </div>
    </header>

    <!-- 404 Content -->
    <section class="error-content py-16 lg:py-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                
                <!-- Search Form -->
                <div class="search-section mb-16" data-aos="fade-up">
                    <div class="search-wrapper bg-white p-8 lg:p-12 rounded-2xl shadow-lg text-center">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">
                            <?php esc_html_e('Try Searching', 'aqualuxe'); ?>
                        </h2>
                        <p class="text-gray-600 mb-8">
                            <?php esc_html_e('Maybe we can help you find what you\'re looking for with a search.', 'aqualuxe'); ?>
                        </p>
                        
                        <form role="search" method="get" class="search-form max-w-2xl mx-auto" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="search-input-wrapper relative">
                                <input type="search" 
                                       class="search-field w-full px-6 py-4 pr-16 text-lg text-gray-900 bg-gray-50 rounded-full border border-gray-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors" 
                                       placeholder="<?php esc_attr_e('Search for anything...', 'aqualuxe'); ?>" 
                                       name="s">
                                <button type="submit" 
                                        class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-primary-600 hover:bg-primary-700 text-white rounded-full flex items-center justify-center transition-colors focus:outline-none focus:ring-4 focus:ring-primary-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    <span class="sr-only"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="quick-links-section mb-16" data-aos="fade-up">
                    <div class="quick-links-wrapper bg-white p-8 lg:p-12 rounded-2xl shadow-lg">
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-8 text-center">
                            <?php esc_html_e('Quick Links', 'aqualuxe'); ?>
                        </h2>
                        
                        <div class="links-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            
                            <!-- Home -->
                            <div class="link-card group">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('Home', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Return to our homepage', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            
                            <!-- Shop -->
                            <?php if (class_exists('WooCommerce')) : ?>
                            <div class="link-card group">
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5zM6 9a1 1 0 112 0 1 1 0 01-2 0zm6 0a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('Shop', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Browse our products', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Blog -->
                            <div class="link-card group">
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('Blog', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Read our latest articles', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            
                            <!-- Services -->
                            <div class="link-card group">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('Services', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Explore our services', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            
                            <!-- About -->
                            <div class="link-card group">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('About Us', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Learn more about AquaLuxe', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            
                            <!-- Contact -->
                            <div class="link-card group">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" 
                                   class="block p-6 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors">
                                    <div class="link-icon w-12 h-12 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary-200 transition-colors">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                        <?php esc_html_e('Contact', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        <?php esc_html_e('Get in touch with us', 'aqualuxe'); ?>
                                    </p>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="help-section" data-aos="fade-up">
                    <div class="help-wrapper bg-gradient-to-r from-primary-50 to-secondary-50 p-8 lg:p-12 rounded-2xl">
                        <div class="help-content text-center">
                            <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">
                                <?php esc_html_e('Need More Help?', 'aqualuxe'); ?>
                            </h2>
                            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                                <?php esc_html_e('If you\'re still having trouble finding what you need, our team is here to help. Contact us and we\'ll get you sorted out quickly.', 'aqualuxe'); ?>
                            </p>
                            
                            <div class="help-actions flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" 
                                   class="btn btn-primary bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-full transition-colors">
                                    <?php esc_html_e('Contact Support', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('faq'))); ?>" 
                                   class="btn btn-outline border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white px-8 py-4 rounded-full transition-all">
                                    <?php esc_html_e('View FAQ', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Recent Posts -->
    <section class="recent-posts py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <h2 class="text-3xl lg:text-4xl font-bold font-secondary text-gray-900 mb-12 text-center" data-aos="fade-up">
                    <?php esc_html_e('Recent Articles', 'aqualuxe'); ?>
                </h2>
                
                <?php
                $recent_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($recent_posts->have_posts()) :
                    ?>
                    <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        $delay = 0;
                        while ($recent_posts->have_posts()) : $recent_posts->the_post();
                            ?>
                            <article class="post-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                <a href="<?php the_permalink(); ?>" class="block group">
                                    <div class="card bg-white rounded-2xl overflow-hidden shadow-soft hover:shadow-lg transition-all duration-300 transform group-hover:-translate-y-1">
                                        
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="post-image relative h-48 overflow-hidden">
                                                <?php the_post_thumbnail('medium', array(
                                                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
                                                )); ?>
                                                <div class="image-overlay absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="post-content p-6">
                                            <h3 class="post-title text-lg font-semibold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-2">
                                                <?php the_title(); ?>
                                            </h3>
                                            
                                            <p class="post-excerpt text-gray-600 text-sm line-clamp-3 mb-4">
                                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                                            </p>
                                            
                                            <div class="post-meta text-xs text-gray-500">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </time>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </a>
                            </article>
                            <?php
                            $delay += 100;
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                    <?php
                endif;
                ?>
                
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
