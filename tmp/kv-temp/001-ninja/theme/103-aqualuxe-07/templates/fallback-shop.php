<?php
/**
 * Fallback Shop Template
 * 
 * Displayed when WooCommerce is not active but shop functionality is still needed
 * Provides graceful degradation with call-to-action to install WooCommerce
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="fallback-shop-wrapper">
    <div class="container mx-auto px-4 py-16">
        
        <!-- Shop Coming Soon Section -->
        <div class="shop-coming-soon text-center mb-16">
            <div class="shop-icon mb-8">
                <svg class="w-24 h-24 mx-auto text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            
            <h1 class="text-4xl font-bold mb-6 text-neutral-900 dark:text-neutral-100">
                <?php esc_html_e('Shop Coming Soon', 'aqualuxe'); ?>
            </h1>
            
            <p class="text-xl text-neutral-600 dark:text-neutral-300 mb-8 max-w-2xl mx-auto">
                <?php esc_html_e('Our premium aquatic marketplace is currently being set up. Soon you\'ll be able to browse and purchase our exclusive collection of ornamental fish, aquarium equipment, and aquatic services.', 'aqualuxe'); ?>
            </p>
            
            <?php if (current_user_can('manage_options')) : ?>
                <div class="admin-notice bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-8 max-w-lg mx-auto">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-2">
                        <?php esc_html_e('For Administrators', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-blue-800 dark:text-blue-200 mb-4">
                        <?php esc_html_e('To enable the full shop functionality, please install and activate WooCommerce.', 'aqualuxe'); ?>
                    </p>
                    <a 
                        href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- What We Offer Section -->
        <div class="what-we-offer mb-16">
            <h2 class="text-3xl font-bold text-center mb-12 text-neutral-900 dark:text-neutral-100">
                <?php esc_html_e('What We Offer', 'aqualuxe'); ?>
            </h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <div class="offer-item text-center">
                    <div class="icon mb-4">
                        <svg class="w-12 h-12 mx-auto text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-900 dark:text-neutral-100">
                        <?php esc_html_e('Premium Fish', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-neutral-600 dark:text-neutral-300">
                        <?php esc_html_e('Rare and exotic ornamental fish from trusted breeders worldwide, quarantined and health-guaranteed.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="offer-item text-center">
                    <div class="icon mb-4">
                        <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-900 dark:text-neutral-100">
                        <?php esc_html_e('Quality Equipment', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-neutral-600 dark:text-neutral-300">
                        <?php esc_html_e('Professional-grade aquarium equipment, filtration systems, lighting, and accessories for optimal aquatic environments.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="offer-item text-center">
                    <div class="icon mb-4">
                        <svg class="w-12 h-12 mx-auto text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-900 dark:text-neutral-100">
                        <?php esc_html_e('Expert Services', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-neutral-600 dark:text-neutral-300">
                        <?php esc_html_e('Professional aquarium design, installation, maintenance, and consultation services from certified aquatic specialists.', 'aqualuxe'); ?>
                    </p>
                </div>
                
                <div class="offer-item text-center">
                    <div class="icon mb-4">
                        <svg class="w-12 h-12 mx-auto text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9a9 9 0 01-9-9m9 9c0 1.657-4.03 3-9 3s-9-1.343-9-3m9-9c0-1.657-4.03-3-9-3s-9-1.343-9-3m0 3v9"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-900 dark:text-neutral-100">
                        <?php esc_html_e('Global Shipping', 'aqualuxe'); ?>
                    </h3>
                    <p class="text-neutral-600 dark:text-neutral-300">
                        <?php esc_html_e('Secure international shipping with live arrival guarantee and specialized aquatic transport solutions.', 'aqualuxe'); ?>
                    </p>
                </div>
                
            </div>
        </div>
        
        <!-- Featured Categories Preview -->
        <div class="featured-categories mb-16">
            <h2 class="text-3xl font-bold text-center mb-12 text-neutral-900 dark:text-neutral-100">
                <?php esc_html_e('Featured Categories', 'aqualuxe'); ?>
            </h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="category-card bg-white dark:bg-neutral-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                    <div class="category-image h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="category-content p-6">
                        <h3 class="text-xl font-semibold mb-2 text-neutral-900 dark:text-neutral-100">
                            <?php esc_html_e('Freshwater Fish', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-neutral-600 dark:text-neutral-300 mb-4">
                            <?php esc_html_e('Tropical and coldwater species including discus, angelfish, tetras, and more.', 'aqualuxe'); ?>
                        </p>
                        <span class="text-primary-600 font-medium">
                            <?php esc_html_e('Coming Soon', 'aqualuxe'); ?>
                        </span>
                    </div>
                </div>
                
                <div class="category-card bg-white dark:bg-neutral-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                    <div class="category-image h-48 bg-gradient-to-br from-cyan-400 to-cyan-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </div>
                    <div class="category-content p-6">
                        <h3 class="text-xl font-semibold mb-2 text-neutral-900 dark:text-neutral-100">
                            <?php esc_html_e('Marine Fish', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-neutral-600 dark:text-neutral-300 mb-4">
                            <?php esc_html_e('Saltwater species including clownfish, tangs, wrasses, and reef-safe varieties.', 'aqualuxe'); ?>
                        </p>
                        <span class="text-primary-600 font-medium">
                            <?php esc_html_e('Coming Soon', 'aqualuxe'); ?>
                        </span>
                    </div>
                </div>
                
                <div class="category-card bg-white dark:bg-neutral-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                    <div class="category-image h-48 bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div class="category-content p-6">
                        <h3 class="text-xl font-semibold mb-2 text-neutral-900 dark:text-neutral-100">
                            <?php esc_html_e('Equipment & Supplies', 'aqualuxe'); ?>
                        </h3>
                        <p class="text-neutral-600 dark:text-neutral-300 mb-4">
                            <?php esc_html_e('Professional equipment, substrates, decorations, and maintenance supplies.', 'aqualuxe'); ?>
                        </p>
                        <span class="text-primary-600 font-medium">
                            <?php esc_html_e('Coming Soon', 'aqualuxe'); ?>
                        </span>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Newsletter Signup -->
        <div class="newsletter-signup bg-primary-50 dark:bg-primary-900 rounded-lg p-8 text-center">
            <h2 class="text-2xl font-bold mb-4 text-primary-900 dark:text-primary-100">
                <?php esc_html_e('Stay Updated', 'aqualuxe'); ?>
            </h2>
            <p class="text-primary-800 dark:text-primary-200 mb-6 max-w-md mx-auto">
                <?php esc_html_e('Be the first to know when our shop launches. Get exclusive early access and special promotions.', 'aqualuxe'); ?>
            </p>
            
            <form class="newsletter-form max-w-md mx-auto flex gap-2" method="post" action="">
                <input 
                    type="email" 
                    name="newsletter_email" 
                    placeholder="<?php esc_attr_e('Enter your email', 'aqualuxe'); ?>"
                    class="flex-1 px-4 py-2 border border-primary-200 dark:border-primary-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-primary-800 dark:text-primary-100"
                    required
                >
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors"
                >
                    <?php esc_html_e('Notify Me', 'aqualuxe'); ?>
                </button>
                <?php wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce'); ?>
            </form>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>