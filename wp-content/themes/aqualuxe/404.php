<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="content-wrapper">
    <div class="container">
        <div class="max-w-4xl mx-auto text-center py-16">
            <!-- 404 Animation -->
            <div class="error-animation mb-12">
                <div class="relative inline-block">
                    <!-- Animated waves -->
                    <svg width="300" height="150" viewBox="0 0 300 150" class="error-waves">
                        <defs>
                            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#0ea5e9;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#06b6d4;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#0ea5e9;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        
                        <!-- Animated wave paths -->
                        <path d="M0,75 Q75,25 150,75 T300,75 L300,150 L0,150 Z" fill="url(#waveGradient)" opacity="0.3" class="wave wave-1">
                            <animateTransform
                                attributeName="transform"
                                type="translate"
                                values="0,0; -150,0; 0,0"
                                dur="4s"
                                repeatCount="indefinite"/>
                        </path>
                        
                        <path d="M0,90 Q75,40 150,90 T300,90 L300,150 L0,150 Z" fill="url(#waveGradient)" opacity="0.5" class="wave wave-2">
                            <animateTransform
                                attributeName="transform"
                                type="translate"
                                values="0,0; 150,0; 0,0"
                                dur="3s"
                                repeatCount="indefinite"/>
                        </path>
                        
                        <path d="M0,105 Q75,55 150,105 T300,105 L300,150 L0,150 Z" fill="url(#waveGradient)" opacity="0.7" class="wave wave-3">
                            <animateTransform
                                attributeName="transform"
                                type="translate"
                                values="0,0; -75,0; 0,0"
                                dur="5s"
                                repeatCount="indefinite"/>
                        </path>
                    </svg>
                    
                    <!-- 404 Text -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <h1 class="text-8xl lg:text-9xl font-bold text-white drop-shadow-lg">404</h1>
                    </div>
                </div>
            </div>
            
            <!-- Error Message -->
            <div class="error-content">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                    <?php esc_html_e( 'Oops! Page Not Found', 'aqualuxe' ); ?>
                </h2>
                
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto">
                    <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Let\'s get you back on track!', 'aqualuxe' ); ?>
                </p>
                
                <!-- Search Form -->
                <div class="search-section mb-12">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e( 'Try searching for what you need:', 'aqualuxe' ); ?>
                    </h3>
                    <div class="max-w-md mx-auto">
                        <?php get_search_form(); ?>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="quick-actions mb-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
                        <!-- Home -->
                        <div class="action-card">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-700">
                                <div class="text-primary-500 mb-3">
                                    <i class="fas fa-home text-3xl" aria-hidden="true"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2"><?php esc_html_e( 'Go Home', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Return to our homepage', 'aqualuxe' ); ?></p>
                            </a>
                        </div>
                        
                        <!-- Blog -->
                        <div class="action-card">
                            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-700">
                                <div class="text-primary-500 mb-3">
                                    <i class="fas fa-blog text-3xl" aria-hidden="true"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2"><?php esc_html_e( 'Browse Blog', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Check out our latest posts', 'aqualuxe' ); ?></p>
                            </a>
                        </div>
                        
                        <!-- Contact -->
                        <?php if ( $contact_page = get_option( 'aqualuxe_contact_page' ) ) : ?>
                        <div class="action-card">
                            <a href="<?php echo esc_url( get_permalink( $contact_page ) ); ?>" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-700">
                                <div class="text-primary-500 mb-3">
                                    <i class="fas fa-envelope text-3xl" aria-hidden="true"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Get in touch with our team', 'aqualuxe' ); ?></p>
                            </a>
                        </div>
                        <?php else : ?>
                        <!-- Shop (if WooCommerce is active) -->
                        <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                        <div class="action-card">
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow border border-gray-200 dark:border-gray-700">
                                <div class="text-primary-500 mb-3">
                                    <i class="fas fa-shopping-bag text-3xl" aria-hidden="true"></i>
                                </div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Explore our products', 'aqualuxe' ); ?></p>
                            </a>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Popular Content -->
                <?php
                $popular_posts = new WP_Query( array(
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                    'meta_key'       => 'post_views_count',
                    'orderby'        => 'meta_value_num',
                    'order'          => 'DESC',
                    'meta_query'     => array(
                        array(
                            'key'     => '_thumbnail_id',
                            'compare' => 'EXISTS'
                        )
                    )
                ) );
                
                if ( $popular_posts->have_posts() ) :
                ?>
                <div class="popular-content">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e( 'Popular Content', 'aqualuxe' ); ?>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php while ( $popular_posts->have_posts() ) : $popular_posts->the_post(); ?>
                            <article class="popular-post card">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="post-thumbnail aspect-ratio-16-9">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h4 class="post-title text-lg font-semibold mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-primary-500 transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                    
                                    <div class="post-meta text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                            <?php echo esc_html( get_the_date() ); ?>
                                        </time>
                                    </div>
                                    
                                    <div class="post-excerpt text-gray-600 dark:text-gray-300 text-sm">
                                        <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php 
                wp_reset_postdata();
                endif;
                ?>
                
                <!-- Help Text -->
                <div class="help-text mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-gray-600 dark:text-gray-300">
                        <?php
                        printf(
                            /* translators: %s: Site contact email */
                            esc_html__( 'Still can\'t find what you\'re looking for? %s and we\'ll help you out.', 'aqualuxe' ),
                            '<a href="mailto:' . esc_attr( get_bloginfo( 'admin_email' ) ) . '" class="text-primary-500 hover:text-primary-600 transition-colors">' . esc_html__( 'Contact us', 'aqualuxe' ) . '</a>'
                        );
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-waves {
    max-width: 100%;
    height: auto;
}

@media (prefers-reduced-motion: reduce) {
    .error-waves animateTransform {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
    }
}
</style>

<?php
get_footer();
