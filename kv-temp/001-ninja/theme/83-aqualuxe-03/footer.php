<?php
/**
 * The template for displaying the footer
 * 
 * Contains the closing of the #content div and all content after.
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <?php if (is_active_sidebar('footer-1')) : ?>
                                <div class="footer-widget-area">
                                    <?php dynamic_sidebar('footer-1'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <?php if (is_active_sidebar('footer-2')) : ?>
                                <div class="footer-widget-area">
                                    <?php dynamic_sidebar('footer-2'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <?php if (is_active_sidebar('footer-3')) : ?>
                                <div class="footer-widget-area">
                                    <?php dynamic_sidebar('footer-3'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <?php if (is_active_sidebar('footer-4')) : ?>
                                <div class="footer-widget-area">
                                    <?php dynamic_sidebar('footer-4'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="footer-info">
                            <div class="site-info">
                                <?php
                                $footer_text = kv_get_theme_option('footer_text', '');
                                if ($footer_text) {
                                    echo wp_kses_post($footer_text);
                                } else {
                                    printf(
                                        esc_html__('© %1$s %2$s. All rights reserved.', KV_THEME_TEXTDOMAIN),
                                        date('Y'),
                                        get_bloginfo('name')
                                    );
                                }
                                ?>
                            </div>
                            
                            <?php
                            $privacy_policy_url = get_privacy_policy_url();
                            if ($privacy_policy_url) : ?>
                                <div class="privacy-policy">
                                    <a href="<?php echo esc_url($privacy_policy_url); ?>">
                                        <?php esc_html_e('Privacy Policy', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="footer-utilities">
                            <?php
                            // Footer navigation menu
                            if (has_nav_menu('footer')) :
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_class'     => 'footer-menu',
                                    'container'      => 'nav',
                                    'container_class'=> 'footer-navigation',
                                    'container_aria_label' => esc_attr__('Footer Navigation', KV_THEME_TEXTDOMAIN),
                                    'depth'          => 1,
                                ));
                            endif;
                            ?>
                            
                            <?php
                            // Social media links
                            $social_networks = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'github'];
                            $has_social = false;
                            
                            foreach ($social_networks as $network) {
                                if (kv_get_theme_option("social_{$network}", '')) {
                                    $has_social = true;
                                    break;
                                }
                            }
                            
                            if ($has_social) : ?>
                                <div class="footer-social">
                                    <span class="social-label"><?php esc_html_e('Follow Us:', KV_THEME_TEXTDOMAIN); ?></span>
                                    <div class="social-links">
                                        <?php foreach ($social_networks as $network) :
                                            $url = kv_get_theme_option("social_{$network}", '');
                                            if ($url) : ?>
                                                <a href="<?php echo esc_url($url); ?>" 
                                                   class="social-link social-<?php echo esc_attr($network); ?>" 
                                                   target="_blank" 
                                                   rel="noopener noreferrer"
                                                   aria-label="<?php printf(esc_attr__('Follow us on %s', KV_THEME_TEXTDOMAIN), ucfirst($network)); ?>">
                                                    <i class="fab fa-<?php echo esc_attr($network === 'github' ? 'github' : $network); ?>" aria-hidden="true"></i>
                                                </a>
                                            <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php
                // Additional footer content
                $footer_additional = kv_get_theme_option('footer_additional_content', '');
                if ($footer_additional) : ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="footer-additional">
                                <?php echo wp_kses_post($footer_additional); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php
        // Developer credits (can be disabled in theme options)
        if (kv_get_theme_option('show_developer_credits', true)) : ?>
            <div class="developer-credits">
                <div class="container">
                    <div class="credits-text">
                        <?php
                        printf(
                            esc_html__('Powered by %1$s | Theme: %2$s', KV_THEME_TEXTDOMAIN),
                            '<a href="' . esc_url('https://wordpress.org/') . '" target="_blank" rel="noopener">WordPress</a>',
                            '<a href="' . esc_url('https://github.com/kv-enterprise/theme') . '" target="_blank" rel="noopener">KV Enterprise</a>'
                        );
                        ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </footer><!-- #colophon -->
    
    <!-- Back to Top Button -->
    <button class="back-to-top" aria-label="<?php esc_attr_e('Back to top', KV_THEME_TEXTDOMAIN); ?>" style="display: none;">
        <i class="fas fa-chevron-up" aria-hidden="true"></i>
    </button>
    
    <!-- Notifications Container -->
    <div class="notifications" aria-live="polite" aria-atomic="true"></div>
    
    <?php
    // Cookie consent notice (GDPR compliance)
    if (kv_get_theme_option('show_cookie_notice', true) && !isset($_COOKIE['kv_cookie_consent'])) : ?>
        <div id="cookie-notice" class="cookie-notice" role="dialog" aria-labelledby="cookie-notice-title" aria-describedby="cookie-notice-description">
            <div class="cookie-notice-content">
                <h3 id="cookie-notice-title" class="cookie-notice-title">
                    <?php esc_html_e('Cookie Notice', KV_THEME_TEXTDOMAIN); ?>
                </h3>
                <p id="cookie-notice-description" class="cookie-notice-description">
                    <?php
                    $cookie_notice_text = kv_get_theme_option('cookie_notice_text', 
                        __('We use cookies to ensure you get the best experience on our website. By continuing to use this site, you consent to our use of cookies.', KV_THEME_TEXTDOMAIN)
                    );
                    echo esc_html($cookie_notice_text);
                    ?>
                </p>
                <div class="cookie-notice-actions">
                    <button type="button" class="cookie-accept btn btn-primary" onclick="kvAcceptCookies()">
                        <?php esc_html_e('Accept', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                    <button type="button" class="cookie-decline btn btn-secondary" onclick="kvDeclineCookies()">
                        <?php esc_html_e('Decline', KV_THEME_TEXTDOMAIN); ?>
                    </button>
                    <?php
                    $privacy_policy_url = get_privacy_policy_url();
                    if ($privacy_policy_url) : ?>
                        <a href="<?php echo esc_url($privacy_policy_url); ?>" class="cookie-policy-link">
                            <?php esc_html_e('Learn More', KV_THEME_TEXTDOMAIN); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p class="loading-text"><?php esc_html_e('Loading...', KV_THEME_TEXTDOMAIN); ?></p>
        </div>
    </div>
    
</div><!-- #page -->

<?php wp_footer(); ?>

<script>
// Cookie consent functionality
function kvAcceptCookies() {
    // Set consent cookie for 365 days
    const expiryDate = new Date();
    expiryDate.setTime(expiryDate.getTime() + (365 * 24 * 60 * 60 * 1000));
    document.cookie = `kv_cookie_consent=accepted; expires=${expiryDate.toUTCString()}; path=/`;
    
    // Hide notice
    document.getElementById('cookie-notice').style.display = 'none';
    
    // Enable analytics or other tracking scripts here
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'granted'
        });
    }
}

function kvDeclineCookies() {
    // Set decline cookie for 365 days
    const expiryDate = new Date();
    expiryDate.setTime(expiryDate.getTime() + (365 * 24 * 60 * 60 * 1000));
    document.cookie = `kv_cookie_consent=declined; expires=${expiryDate.toUTCString()}; path=/`;
    
    // Hide notice
    document.getElementById('cookie-notice').style.display = 'none';
    
    // Disable analytics or other tracking scripts here
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'denied'
        });
    }
}

// Loading overlay functions
function kvShowLoading() {
    document.getElementById('loading-overlay').style.display = 'flex';
}

function kvHideLoading() {
    document.getElementById('loading-overlay').style.display = 'none';
}

// Progressive enhancement: remove no-js class
document.documentElement.classList.remove('no-js');
document.documentElement.classList.add('js');

// Service Worker registration for PWA support
if ('serviceWorker' in navigator && typeof kvEnterprise !== 'undefined' && kvEnterprise.enablePWA) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful: ', registration.scope);
            })
            .catch(function(error) {
                console.log('ServiceWorker registration failed: ', error);
            });
    });
}

// Handle online/offline status
window.addEventListener('online', function() {
    document.body.classList.remove('is-offline');
    if (typeof KVEnterprise !== 'undefined') {
        KVEnterprise.showNotification('<?php esc_html_e('You are back online!', KV_THEME_TEXTDOMAIN); ?>', 'success');
    }
});

window.addEventListener('offline', function() {
    document.body.classList.add('is-offline');
    if (typeof KVEnterprise !== 'undefined') {
        KVEnterprise.showNotification('<?php esc_html_e('You are currently offline. Some features may not be available.', KV_THEME_TEXTDOMAIN); ?>', 'warning');
    }
});

// Print styles optimization
window.addEventListener('beforeprint', function() {
    // Hide unnecessary elements when printing
    const elementsToHide = document.querySelectorAll('.no-print, .social-links, .back-to-top, .cookie-notice');
    elementsToHide.forEach(function(element) {
        element.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    // Restore elements after printing
    const elementsToShow = document.querySelectorAll('.no-print, .social-links, .back-to-top, .cookie-notice');
    elementsToShow.forEach(function(element) {
        element.style.display = '';
    });
});

// Performance monitoring
if (typeof performance !== 'undefined' && performance.timing) {
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            if (loadTime > 3000) { // If page takes more than 3 seconds to load
                console.warn('Page load time:', loadTime + 'ms');
                // You could send this data to analytics
            }
        }, 0);
    });
}
</script>

</body>
</html>
