<?php
/**
 * Template part for displaying content after the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Back to top button
$back_to_top = get_theme_mod( 'aqualuxe_back_to_top', true );
if ( $back_to_top ) :
    ?>
    <a href="#" id="back-to-top" class="back-to-top">
        <i class="icon-arrow-up"></i>
        <span class="screen-reader-text"><?php esc_html_e( 'Back to top', 'aqualuxe' ); ?></span>
    </a>
    <?php
endif;

// Cookie notice
$cookie_notice = get_theme_mod( 'aqualuxe_cookie_notice', true );
if ( $cookie_notice && ! isset( $_COOKIE['aqualuxe_cookie_notice'] ) ) :
    ?>
    <div id="cookie-notice" class="cookie-notice">
        <div class="container">
            <div class="cookie-notice-inner">
                <div class="cookie-notice-content">
                    <p>
                        <?php
                        echo wp_kses_post(
                            sprintf(
                                /* translators: %1$s: privacy policy link */
                                __( 'We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies. Read our <a href="%1$s">Privacy Policy</a> for more information.', 'aqualuxe' ),
                                esc_url( get_privacy_policy_url() )
                            )
                        );
                        ?>
                    </p>
                </div>
                <div class="cookie-notice-actions">
                    <button id="cookie-notice-accept" class="button"><?php esc_html_e( 'Accept', 'aqualuxe' ); ?></button>
                    <button id="cookie-notice-decline" class="button button-outline"><?php esc_html_e( 'Decline', 'aqualuxe' ); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php
endif;

// Google Analytics
$google_analytics = \AquaLuxe\Helpers\Utils::get_theme_google_analytics();
if ( ! empty( $google_analytics ) ) :
    ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $google_analytics ); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo esc_attr( $google_analytics ); ?>');
    </script>
    <?php
endif;

// Facebook Pixel
$facebook_pixel = \AquaLuxe\Helpers\Utils::get_theme_facebook_pixel();
if ( ! empty( $facebook_pixel ) ) :
    ?>
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?php echo esc_attr( $facebook_pixel ); ?>');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" 
            src="https://www.facebook.com/tr?id=<?php echo esc_attr( $facebook_pixel ); ?>&ev=PageView&noscript=1"/>
    </noscript>
    <?php
endif;

// Custom JavaScript
$custom_js = \AquaLuxe\Helpers\Utils::get_theme_custom_js();
if ( ! empty( $custom_js ) ) :
    ?>
    <script>
        <?php echo $custom_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
    </script>
    <?php
endif;