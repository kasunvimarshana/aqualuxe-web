<?php
/**
 * 404 Error Page Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-3xl mx-auto text-center">
            <div class="error-404 not-found">
                <!-- Large 404 Number -->
                <div class="error-number mb-8">
                    <h1 class="text-9xl font-bold text-primary-600 opacity-20">404</h1>
                </div>

                <!-- Error Message -->
                <header class="page-header mb-8">
                    <h2 class="page-title text-4xl font-bold text-gray-900 mb-4">
                        <?php _e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600">
                        <?php _e('It looks like nothing was found at this location. The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe'); ?>
                    </p>
                </header>

                <!-- Search Section -->
                <section class="error-search mb-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">
                        <?php _e('Try searching for what you need:', 'aqualuxe'); ?>
                    </h3>
                    <div class="max-w-md mx-auto">
                        <?php get_search_form(); ?>
                    </div>
                </section>

                <!-- Navigation Links -->
                <section class="error-navigation mb-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">
                        <?php _e('Or try one of these popular pages:', 'aqualuxe'); ?>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <?php aqualuxe_svg_icon('home', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                <span class="font-medium text-gray-900"><?php _e('Home Page', 'aqualuxe'); ?></span>
                            </div>
                        </a>

                        <?php if (aqualuxe_is_woocommerce_activated()) : ?>
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <?php aqualuxe_svg_icon('cart', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                    <span class="font-medium text-gray-900"><?php _e('Shop', 'aqualuxe'); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Get services page if it exists
                        $services_page = get_page_by_path('services');
                        if ($services_page) :
                        ?>
                            <a href="<?php echo esc_url(get_permalink($services_page)); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <?php aqualuxe_svg_icon('wrench', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                    <span class="font-medium text-gray-900"><?php _e('Services', 'aqualuxe'); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Get about page if it exists
                        $about_page = get_page_by_path('about');
                        if ($about_page) :
                        ?>
                            <a href="<?php echo esc_url(get_permalink($about_page)); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <?php aqualuxe_svg_icon('info', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                    <span class="font-medium text-gray-900"><?php _e('About Us', 'aqualuxe'); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Get contact page if it exists
                        $contact_page = get_page_by_path('contact');
                        if ($contact_page) :
                        ?>
                            <a href="<?php echo esc_url(get_permalink($contact_page)); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center">
                                    <?php aqualuxe_svg_icon('mail', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                    <span class="font-medium text-gray-900"><?php _e('Contact', 'aqualuxe'); ?></span>
                                </div>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="error-link-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <?php aqualuxe_svg_icon('document', array('class' => 'w-6 h-6 text-primary-600 mr-3')); ?>
                                <span class="font-medium text-gray-900"><?php _e('Blog', 'aqualuxe'); ?></span>
                            </div>
                        </a>
                    </div>
                </section>

                <!-- Recent Posts -->
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 3,
                    'post_status' => 'publish',
                ));

                if (!empty($recent_posts)) :
                ?>
                    <section class="error-recent-posts">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">
                            <?php _e('Recent Blog Posts:', 'aqualuxe'); ?>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <?php foreach ($recent_posts as $post_item) : ?>
                                <article class="recent-post-card bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <?php if (has_post_thumbnail($post_item['ID'])) : ?>
                                        <div class="recent-post-thumbnail">
                                            <a href="<?php echo esc_url(get_permalink($post_item['ID'])); ?>">
                                                <?php echo get_the_post_thumbnail($post_item['ID'], 'medium', array('class' => 'w-full h-32 object-cover')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="recent-post-content p-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">
                                            <a href="<?php echo esc_url(get_permalink($post_item['ID'])); ?>" class="hover:text-primary-600">
                                                <?php echo esc_html($post_item['post_title']); ?>
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            <?php echo esc_html(get_the_date('', $post_item['ID'])); ?>
                                        </p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Go Back Button -->
                <div class="error-actions mt-12">
                    <button onclick="history.back()" class="btn btn-outline-primary mr-4">
                        <?php aqualuxe_svg_icon('arrow-left', array('class' => 'w-4 h-4 mr-2 inline')); ?>
                        <?php _e('Go Back', 'aqualuxe'); ?>
                    </button>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php aqualuxe_svg_icon('home', array('class' => 'w-4 h-4 mr-2 inline')); ?>
                        <?php _e('Go Home', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>