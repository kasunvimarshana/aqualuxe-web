        <footer id="colophon" class="site-footer mt-auto" role="contentinfo">
            
            <!-- Footer widgets -->
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets py-12 lg:py-16">
                    <div class="container mx-auto px-4">
                        <div class="footer-widget-area">
                            <?php for ($i = 1; $i <= 4; $i++) : ?>
                                <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                    <div class="footer-widget">
                                        <?php dynamic_sidebar('footer-' . $i); ?>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Footer bottom -->
            <div class="footer-bottom border-t border-secondary-800 py-6">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
                        
                        <!-- Copyright -->
                        <div class="footer-copyright text-sm text-secondary-400">
                            <?php
                            $copyright_text = get_theme_mod('footer_copyright_text');
                            if ($copyright_text) :
                                echo wp_kses_post($copyright_text);
                            else :
                            ?>
                                <p>
                                    <?php
                                    printf(
                                        /* translators: 1: Copyright symbol, 2: Current year, 3: Site name */
                                        esc_html__('%1$s %2$s %3$s. All rights reserved.', 'aqualuxe'),
                                        '&copy;',
                                        date('Y'),
                                        get_bloginfo('name')
                                    );
                                    ?>
                                </p>
                                <p class="text-xs mt-1">
                                    <?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Footer menu -->
                        <?php if (has_nav_menu('footer')) : ?>
                            <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer menu', 'aqualuxe'); ?>">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_id'        => 'footer-menu',
                                    'menu_class'     => 'flex flex-wrap items-center space-x-6 text-sm',
                                    'container'      => false,
                                    'depth'          => 1,
                                    'fallback_cb'    => false,
                                ));
                                ?>
                            </nav>
                        <?php endif; ?>
                        
                        <!-- Social links -->
                        <?php if (has_nav_menu('social')) : ?>
                            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e('Social links', 'aqualuxe'); ?>">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'social',
                                    'menu_id'        => 'social-menu',
                                    'menu_class'     => 'flex items-center space-x-4',
                                    'container'      => false,
                                    'depth'          => 1,
                                    'link_before'    => '<span class="screen-reader-text">',
                                    'link_after'     => '</span>',
                                    'fallback_cb'    => false,
                                ));
                                ?>
                            </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </footer>
        
        <!-- Scroll to top button -->
        <button type="button" 
                class="scroll-to-top fixed bottom-6 right-6 z-40 btn-primary rounded-full p-3 shadow-lg opacity-0 invisible transition-all duration-300"
                aria-label="<?php esc_attr_e('Scroll to top', 'aqualuxe'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
            </svg>
        </button>
    </div><!-- #page -->
    
    <!-- Search modal -->
    <div id="search-modal" class="search-modal fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="search-modal-title">
        <div class="search-modal-overlay absolute inset-0 bg-black bg-opacity-75"></div>
        <div class="search-modal-content relative flex items-start justify-center min-h-screen px-4 pt-16">
            <div class="search-modal-inner w-full max-w-2xl bg-white dark:bg-secondary-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 id="search-modal-title" class="text-xl font-semibold">
                        <?php esc_html_e('Search', 'aqualuxe'); ?>
                    </h2>
                    <button type="button" 
                            class="search-modal-close btn-ghost btn-sm"
                            data-dismiss="modal"
                            aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <?php get_search_form(); ?>
                
                <div class="search-suggestions mt-6">
                    <?php
                    // Popular searches or recent posts
                    $popular_posts = get_posts(array(
                        'numberposts' => 5,
                        'meta_key'    => 'popular_posts',
                        'orderby'     => 'meta_value_num',
                        'order'       => 'DESC'
                    ));
                    
                    if ($popular_posts) :
                    ?>
                        <h3 class="text-sm font-semibold text-secondary-600 dark:text-secondary-400 mb-3">
                            <?php esc_html_e('Popular searches', 'aqualuxe'); ?>
                        </h3>
                        <ul class="space-y-2">
                            <?php foreach ($popular_posts as $post) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" 
                                       class="text-sm text-secondary-700 dark:text-secondary-300 hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html($post->post_title); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cookie consent (if needed) -->
    <?php if (get_theme_mod('show_cookie_consent', false)) : ?>
        <div id="cookie-consent" class="cookie-consent fixed bottom-0 left-0 right-0 z-40 bg-secondary-900 text-white p-4 transform translate-y-full transition-transform duration-300">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
                    <div class="cookie-message text-sm">
                        <?php
                        $cookie_message = get_theme_mod('cookie_consent_message', 
                            __('This website uses cookies to ensure you get the best experience on our website.', 'aqualuxe')
                        );
                        echo wp_kses_post($cookie_message);
                        ?>
                        <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" class="text-primary-400 hover:text-primary-300 ml-1">
                            <?php esc_html_e('Learn more', 'aqualuxe'); ?>
                        </a>
                    </div>
                    <div class="cookie-actions flex space-x-3">
                        <button type="button" class="cookie-accept btn-primary btn-sm">
                            <?php esc_html_e('Accept', 'aqualuxe'); ?>
                        </button>
                        <button type="button" class="cookie-decline btn-outline btn-sm">
                            <?php esc_html_e('Decline', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Reading progress bar -->
    <?php if (is_single() && get_theme_mod('show_reading_progress', true)) : ?>
        <div class="reading-progress fixed top-0 left-0 h-1 bg-primary-600 z-50 transition-all duration-100" style="width: 0%;"></div>
    <?php endif; ?>
    
    <?php wp_footer(); ?>
    
    <!-- Schema.org structured data -->
    <?php get_template_part('template-parts/schema/organization'); ?>
    
</body>
</html>