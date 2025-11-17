<?php
/**
 * Shop Fallback Template (when WooCommerce is not active)
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <div class="shop-fallback max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Hero Section -->
            <div class="hero-section text-center py-16 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-lg mb-12">
                <h1 class="text-4xl font-bold mb-4">
                    <?php esc_html_e('AquaLuxe Collections', 'aqualuxe'); ?>
                </h1>
                <p class="text-xl mb-8">
                    <?php esc_html_e('Premium aquatic species and equipment from around the world', 'aqualuxe'); ?>
                </p>
                <div class="hero-stats grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div class="stat-item">
                        <div class="stat-number text-3xl font-bold">500+</div>
                        <div class="stat-label"><?php esc_html_e('Rare Species', 'aqualuxe'); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-3xl font-bold">50+</div>
                        <div class="stat-label"><?php esc_html_e('Countries Shipped', 'aqualuxe'); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number text-3xl font-bold">25+</div>
                        <div class="stat-label"><?php esc_html_e('Years Experience', 'aqualuxe'); ?></div>
                    </div>
                </div>
            </div>
            
            <!-- Product Categories -->
            <div class="product-categories mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                    <?php esc_html_e('Our Collections', 'aqualuxe'); ?>
                </h2>
                
                <div class="categories-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Tropical Fish -->
                    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="category-image h-48 bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                            </svg>
                        </div>
                        <div class="category-info p-6">
                            <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Tropical Fish', 'aqualuxe'); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php esc_html_e('Rare and exotic tropical fish species from pristine waters worldwide.', 'aqualuxe'); ?>
                            </p>
                            <div class="category-features text-sm text-gray-500 dark:text-gray-400">
                                <div>• <?php esc_html_e('Discus & Angelfish', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Rare Plecos', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Cichlids & Tetras', 'aqualuxe'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Aquatic Plants -->
                    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="category-image h-48 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A3,3 0 0,1 15,5V11A3,3 0 0,1 12,14A3,3 0 0,1 9,11V5A3,3 0 0,1 12,2M12,4A1,1 0 0,0 11,5V11A1,1 0 0,0 12,12A1,1 0 0,0 13,11V5A1,1 0 0,0 12,4M12,15L15.5,22H8.5L12,15Z"/>
                            </svg>
                        </div>
                        <div class="category-info p-6">
                            <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Aquatic Plants', 'aqualuxe'); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php esc_html_e('Premium aquatic plants for stunning aquascapes and natural habitats.', 'aqualuxe'); ?>
                            </p>
                            <div class="category-features text-sm text-gray-500 dark:text-gray-400">
                                <div>• <?php esc_html_e('Carpeting Plants', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Stem Plants', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Tissue Culture', 'aqualuxe'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Equipment -->
                    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="category-image h-48 bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4M12,6A6,6 0 0,0 6,12A6,6 0 0,0 12,18A6,6 0 0,0 18,12A6,6 0 0,0 12,6M12,8A4,4 0 0,1 16,12A4,4 0 0,1 12,16A4,4 0 0,1 8,12A4,4 0 0,1 12,8Z"/>
                            </svg>
                        </div>
                        <div class="category-info p-6">
                            <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Premium Equipment', 'aqualuxe'); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php esc_html_e('Professional-grade aquarium equipment for optimal aquatic environments.', 'aqualuxe'); ?>
                            </p>
                            <div class="category-features text-sm text-gray-500 dark:text-gray-400">
                                <div>• <?php esc_html_e('Filtration Systems', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('LED Lighting', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('CO2 Systems', 'aqualuxe'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Services -->
                    <div class="category-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="category-image h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,16.5L6.5,12L7.91,10.59L11,13.67L16.59,8.09L18,9.5L11,16.5Z"/>
                            </svg>
                        </div>
                        <div class="category-info p-6">
                            <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Expert Services', 'aqualuxe'); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php esc_html_e('Professional aquarium design, maintenance, and consultation services.', 'aqualuxe'); ?>
                            </p>
                            <div class="category-features text-sm text-gray-500 dark:text-gray-400">
                                <div>• <?php esc_html_e('Custom Design', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Maintenance Plans', 'aqualuxe'); ?></div>
                                <div>• <?php esc_html_e('Expert Consultation', 'aqualuxe'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="features-section bg-gray-50 dark:bg-gray-800 rounded-lg p-8 mb-16">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                    <?php esc_html_e('Why Choose AquaLuxe?', 'aqualuxe'); ?>
                </h2>
                
                <div class="features-grid grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="feature-item text-center">
                        <div class="feature-icon w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                            <?php esc_html_e('Quality Guarantee', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <?php esc_html_e('Every specimen undergoes rigorous health checks and comes with our satisfaction guarantee.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <div class="feature-item text-center">
                        <div class="feature-icon w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                            <?php esc_html_e('Global Shipping', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <?php esc_html_e('Safe and secure shipping worldwide with temperature-controlled transportation.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <div class="feature-item text-center">
                        <div class="feature-icon w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">
                            <?php esc_html_e('Expert Support', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            <?php esc_html_e('24/7 support from our team of aquatic experts and marine biologists.', 'aqualuxe'); ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section text-center bg-gradient-to-r from-primary-600 to-cyan-600 text-white rounded-lg p-12">
                <h2 class="text-3xl font-bold mb-4">
                    <?php esc_html_e('Ready to Create Your Dream Aquarium?', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8">
                    <?php esc_html_e('Get started with a consultation from our aquatic experts', 'aqualuxe'); ?>
                </p>
                <div class="cta-buttons flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo esc_url(home_url('/contact')); ?>" 
                       class="btn btn-white btn-lg">
                        <?php esc_html_e('Contact Our Experts', 'aqualuxe'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/services')); ?>" 
                       class="btn btn-outline-white btn-lg">
                        <?php esc_html_e('View Our Services', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
            
            <!-- Notice about WooCommerce -->
            <?php if (current_user_can('manage_options')) : ?>
                <div class="woocommerce-notice mt-8 p-4 bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-1">
                                <?php esc_html_e('Administrator Notice', 'aqualuxe'); ?>
                            </h4>
                            <p class="text-blue-700 dark:text-blue-300 text-sm">
                                <?php esc_html_e('WooCommerce is not currently active. Install and activate WooCommerce to enable the full e-commerce functionality with shopping cart, checkout, and product management.', 'aqualuxe'); ?>
                                <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" 
                                   class="underline ml-2">
                                    <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
        
    </main>
</div>

<?php get_footer(); ?>