    <?php
    /**
     * Hook: aqualuxe_after_content
     */
    do_action( 'aqualuxe_after_content' );
    ?>
</main>

<!-- Footer -->
<footer class="site-footer" role="contentinfo">
    <div class="footer-content">
        <!-- Footer Widgets -->
        <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
                        <?php if ( is_active_sidebar( "footer-{$i}" ) ) : ?>
                        <div class="footer-widget footer-widget-<?php echo $i; ?>">
                            <?php dynamic_sidebar( "footer-{$i}" ); ?>
                        </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Newsletter Signup -->
        <?php if ( get_theme_mod( 'aqualuxe_newsletter_enabled', true ) ) : ?>
        <div class="newsletter-section bg-primary-500 text-white py-16">
            <div class="container">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="newsletter-content mb-8">
                        <h2 class="text-3xl lg:text-4xl font-bold mb-4">
                            <?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_title', __( 'Stay in the Current', 'aqualuxe' ) ) ); ?>
                        </h2>
                        <p class="text-xl opacity-90">
                            <?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_description', __( 'Dive into our newsletter for the latest aquatic trends, luxury lifestyle tips, and exclusive offers.', 'aqualuxe' ) ) ); ?>
                        </p>
                    </div>
                    
                    <form class="newsletter-form max-w-md mx-auto" method="post" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <label for="newsletter-email" class="sr-only"><?php esc_html_e( 'Email address', 'aqualuxe' ); ?></label>
                                <input type="email" 
                                       id="newsletter-email" 
                                       name="email" 
                                       class="w-full px-4 py-3 rounded-lg border-0 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-white focus:ring-opacity-50" 
                                       placeholder="<?php esc_attr_e( 'Enter your email', 'aqualuxe' ); ?>" 
                                       required>
                            </div>
                            <button type="submit" class="btn btn-white px-8 py-3 font-semibold">
                                <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                            </button>
                        </div>
                        <input type="hidden" name="action" value="aqualuxe_newsletter_signup">
                        <?php wp_nonce_field( 'aqualuxe_newsletter', 'newsletter_nonce' ); ?>
                        
                        <p class="mt-4 text-sm opacity-75">
                            <?php esc_html_e( 'By subscribing, you agree to our Privacy Policy and Terms of Service.', 'aqualuxe' ); ?>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom bg-gray-900 text-gray-300 py-8">
            <div class="container">
                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="footer-info">
                        <?php if ( has_custom_logo() ) : ?>
                            <div class="footer-logo mb-4">
                                <?php the_custom_logo(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <p class="copyright text-sm">
                            <?php
                            printf(
                                /* translators: 1: Copyright year, 2: Site name, 3: Theme name */
                                esc_html__( '© %1$s %2$s. Powered by %3$s.', 'aqualuxe' ),
                                date( 'Y' ),
                                get_bloginfo( 'name' ),
                                '<a href="https://aqualuxetheme.com" class="hover:text-white transition-colors">AquaLuxe</a>'
                            );
                            ?>
                        </p>
                        
                        <?php if ( $tagline = get_theme_mod( 'aqualuxe_footer_tagline', __( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ) ) ) : ?>
                        <p class="tagline text-sm mt-2 opacity-75">
                            <?php echo esc_html( $tagline ); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="footer-actions">
                        <!-- Footer Navigation -->
                        <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <nav class="footer-navigation mb-4" role="navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-nav-menu flex flex-wrap justify-center lg:justify-end gap-6 text-sm',
                                'container'      => false,
                                'depth'          => 1,
                            ) );
                            ?>
                        </nav>
                        <?php endif; ?>
                        
                        <!-- Social Links -->
                        <?php aqualuxe_social_links( 'footer' ); ?>
                        
                        <!-- Payment Methods -->
                        <?php if ( aqualuxe_is_woocommerce_active() && get_theme_mod( 'aqualuxe_payment_icons_enabled', true ) ) : ?>
                        <div class="payment-methods mt-4">
                            <p class="text-xs mb-2 opacity-75"><?php esc_html_e( 'We accept:', 'aqualuxe' ); ?></p>
                            <div class="payment-icons flex justify-center lg:justify-end space-x-2">
                                <?php aqualuxe_payment_icons(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Additional Footer Info -->
                <div class="footer-additional-info mt-8 pt-8 border-t border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
                        <!-- Security Badges -->
                        <?php if ( get_theme_mod( 'aqualuxe_security_badges_enabled', true ) ) : ?>
                        <div class="security-badges">
                            <h4 class="font-semibold mb-2"><?php esc_html_e( 'Security & Trust', 'aqualuxe' ); ?></h4>
                            <div class="flex space-x-2 opacity-75">
                                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                                <span><?php esc_html_e( 'SSL Secured', 'aqualuxe' ); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Customer Service -->
                        <?php if ( get_theme_mod( 'aqualuxe_customer_service_enabled', true ) ) : ?>
                        <div class="customer-service">
                            <h4 class="font-semibold mb-2"><?php esc_html_e( 'Customer Service', 'aqualuxe' ); ?></h4>
                            <div class="space-y-1 opacity-75">
                                <?php if ( $support_hours = get_theme_mod( 'aqualuxe_support_hours' ) ) : ?>
                                <p><?php echo esc_html( $support_hours ); ?></p>
                                <?php endif; ?>
                                
                                <?php if ( $support_phone = get_theme_mod( 'aqualuxe_support_phone' ) ) : ?>
                                <p>
                                    <a href="tel:<?php echo esc_attr( $support_phone ); ?>" class="hover:text-white transition-colors">
                                        <?php echo esc_html( $support_phone ); ?>
                                    </a>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Shipping Info -->
                        <?php if ( aqualuxe_is_woocommerce_active() && get_theme_mod( 'aqualuxe_shipping_info_enabled', true ) ) : ?>
                        <div class="shipping-info">
                            <h4 class="font-semibold mb-2"><?php esc_html_e( 'Shipping', 'aqualuxe' ); ?></h4>
                            <div class="space-y-1 opacity-75">
                                <p><?php esc_html_e( 'Free shipping worldwide', 'aqualuxe' ); ?></p>
                                <p><?php esc_html_e( 'Express delivery available', 'aqualuxe' ); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button class="back-to-top fixed bottom-8 right-8 w-12 h-12 bg-primary-500 text-white rounded-full shadow-lg hover:bg-primary-600 transform scale-0 transition-all duration-300 z-40" aria-label="<?php esc_attr_e( 'Back to top', 'aqualuxe' ); ?>">
    <i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<!-- Cookie Consent Notice -->
<?php if ( get_theme_mod( 'aqualuxe_cookie_notice_enabled', true ) && ! isset( $_COOKIE['aqualuxe_cookie_consent'] ) ) : ?>
<div class="cookie-notice fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-50 transform translate-y-full transition-transform duration-300">
    <div class="container">
        <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0">
            <div class="cookie-message flex-1">
                <p class="text-sm">
                    <?php echo wp_kses_post( get_theme_mod( 'aqualuxe_cookie_message', __( 'We use cookies to enhance your browsing experience and analyze our traffic. By continuing to use our site, you consent to our use of cookies.', 'aqualuxe' ) ) ); ?>
                    <?php if ( $privacy_url = get_theme_mod( 'aqualuxe_privacy_policy_url' ) ) : ?>
                    <a href="<?php echo esc_url( $privacy_url ); ?>" class="underline hover:no-underline">
                        <?php esc_html_e( 'Learn more', 'aqualuxe' ); ?>
                    </a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="cookie-actions flex space-x-4">
                <button class="cookie-decline btn btn-outline btn-sm">
                    <?php esc_html_e( 'Decline', 'aqualuxe' ); ?>
                </button>
                <button class="cookie-accept btn btn-primary btn-sm">
                    <?php esc_html_e( 'Accept', 'aqualuxe' ); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- GDPR Compliance Modal -->
<?php if ( get_theme_mod( 'aqualuxe_gdpr_enabled', true ) ) : ?>
<div id="gdpr-modal" class="modal" style="display: none;">
    <div class="modal-overlay">
        <div class="modal-content max-w-2xl">
            <div class="modal-header">
                <h2 class="text-2xl font-bold"><?php esc_html_e( 'Privacy Preferences', 'aqualuxe' ); ?></h2>
                <button class="modal-close" aria-label="<?php esc_attr_e( 'Close', 'aqualuxe' ); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <p class="mb-6">
                    <?php esc_html_e( 'We use cookies and similar technologies to provide, protect and improve our products and services. Choose which cookies you want to allow.', 'aqualuxe' ); ?>
                </p>
                
                <div class="space-y-4">
                    <div class="cookie-category">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold"><?php esc_html_e( 'Essential Cookies', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Required for basic site functionality', 'aqualuxe' ); ?></p>
                            </div>
                            <input type="checkbox" checked disabled class="cookie-toggle">
                        </div>
                    </div>
                    
                    <div class="cookie-category">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold"><?php esc_html_e( 'Analytics Cookies', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Help us understand how visitors use our site', 'aqualuxe' ); ?></p>
                            </div>
                            <input type="checkbox" class="cookie-toggle" name="analytics" value="1">
                        </div>
                    </div>
                    
                    <div class="cookie-category">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold"><?php esc_html_e( 'Marketing Cookies', 'aqualuxe' ); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Used to deliver personalized ads', 'aqualuxe' ); ?></p>
                            </div>
                            <input type="checkbox" class="cookie-toggle" name="marketing" value="1">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <div class="flex space-x-4">
                    <button class="btn btn-outline flex-1" id="reject-all-cookies">
                        <?php esc_html_e( 'Reject All', 'aqualuxe' ); ?>
                    </button>
                    <button class="btn btn-primary flex-1" id="accept-selected-cookies">
                        <?php esc_html_e( 'Accept Selected', 'aqualuxe' ); ?>
                    </button>
                    <button class="btn btn-primary flex-1" id="accept-all-cookies">
                        <?php esc_html_e( 'Accept All', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Notification Container -->
<div class="notification-container fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Loading States -->
<div class="loading-overlay fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="loading-content bg-white dark:bg-gray-800 rounded-lg p-8 text-center">
        <div class="loading-spinner w-8 h-8 mx-auto mb-4"></div>
        <p class="text-gray-600 dark:text-gray-300"><?php esc_html_e( 'Loading...', 'aqualuxe' ); ?></p>
    </div>
</div>

<?php wp_footer(); ?>

<!-- Theme Inline Scripts -->
<script>
// Remove no-js class
document.documentElement.classList.remove('no-js');

// Add theme configuration
window.aqualuxe_config = {
    ajax_url: '<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>',
    nonce: '<?php echo wp_create_nonce( "aqualuxe_nonce" ); ?>',
    theme_url: '<?php echo esc_url( get_template_directory_uri() ); ?>',
    is_rtl: <?php echo is_rtl() ? 'true' : 'false'; ?>,
    breakpoints: {
        sm: 640,
        md: 768,
        lg: 1024,
        xl: 1280,
        '2xl': 1536
    },
    animations: {
        duration: 300,
        easing: 'cubic-bezier(0.4, 0, 0.2, 1)'
    },
    i18n: {
        loading: '<?php esc_html_e( "Loading...", "aqualuxe" ); ?>',
        error: '<?php esc_html_e( "An error occurred", "aqualuxe" ); ?>',
        success: '<?php esc_html_e( "Success!", "aqualuxe" ); ?>',
        confirm: '<?php esc_html_e( "Are you sure?", "aqualuxe" ); ?>',
        cancel: '<?php esc_html_e( "Cancel", "aqualuxe" ); ?>',
        close: '<?php esc_html_e( "Close", "aqualuxe" ); ?>'
    }
};

// Initialize critical functionality immediately
(function() {
    // Show back to top button
    function updateBackToTop() {
        const btn = document.querySelector('.back-to-top');
        if (btn) {
            if (window.scrollY > 300) {
                btn.style.transform = 'scale(1)';
            } else {
                btn.style.transform = 'scale(0)';
            }
        }
    }
    
    window.addEventListener('scroll', updateBackToTop);
    updateBackToTop();
    
    // Show cookie notice
    setTimeout(function() {
        const notice = document.querySelector('.cookie-notice');
        if (notice) {
            notice.style.transform = 'translateY(0)';
        }
    }, 2000);
    
    // Header dropdown interactions
    document.addEventListener('mouseenter', function(e) {
        if (e.target.closest('.cart-toggle')) {
            const dropdown = e.target.closest('.cart-toggle').querySelector('.mini-cart-dropdown');
            if (dropdown) {
                dropdown.style.opacity = '1';
                dropdown.style.visibility = 'visible';
                dropdown.style.transform = 'translateY(0)';
            }
        }
        
        if (e.target.closest('.account-toggle')) {
            const dropdown = e.target.closest('.account-toggle').querySelector('.account-dropdown');
            if (dropdown) {
                dropdown.style.opacity = '1';
                dropdown.style.visibility = 'visible';
                dropdown.style.transform = 'translateY(0)';
            }
        }
    }, true);
    
    document.addEventListener('mouseleave', function(e) {
        if (e.target.closest('.cart-toggle')) {
            const dropdown = e.target.closest('.cart-toggle').querySelector('.mini-cart-dropdown');
            if (dropdown) {
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
                dropdown.style.transform = 'translateY(8px)';
            }
        }
        
        if (e.target.closest('.account-toggle')) {
            const dropdown = e.target.closest('.account-toggle').querySelector('.account-dropdown');
            if (dropdown) {
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
                dropdown.style.transform = 'translateY(8px)';
            }
        }
    }, true);
    
    // Search overlay toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.search-toggle')) {
            e.preventDefault();
            const overlay = document.querySelector('.search-overlay');
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.visibility = 'visible';
                setTimeout(() => {
                    const searchField = overlay.querySelector('.search-field');
                    if (searchField) searchField.focus();
                }, 100);
            }
        }
        
        if (e.target.closest('.search-overlay-close')) {
            const overlay = document.querySelector('.search-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.visibility = 'hidden';
            }
        }
    });
    
    // Mobile menu toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.mobile-menu-toggle')) {
            const menu = document.querySelector('.mobile-menu');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const panel = document.querySelector('.mobile-menu-panel');
            
            if (menu && toggle && panel) {
                const isOpen = menu.style.opacity === '1';
                
                if (!isOpen) {
                    menu.style.opacity = '1';
                    menu.style.visibility = 'visible';
                    panel.style.transform = 'translateX(0)';
                    toggle.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                    panel.style.transform = 'translateX(100%)';
                    toggle.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        }
        
        if (e.target.closest('.mobile-menu-close') || e.target.classList.contains('mobile-menu-overlay')) {
            const menu = document.querySelector('.mobile-menu');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const panel = document.querySelector('.mobile-menu-panel');
            
            if (menu && toggle && panel) {
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                panel.style.transform = 'translateX(100%)';
                toggle.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });
})();
</script>

</body>
</html>
