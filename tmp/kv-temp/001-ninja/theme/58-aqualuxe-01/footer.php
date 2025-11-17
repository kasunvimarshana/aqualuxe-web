<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Footer options
$footer_layout = isset($options['footer_layout']) ? $options['footer_layout'] : 'standard';
$footer_columns = isset($options['footer_columns']) ? $options['footer_columns'] : 4;
$enable_footer_widgets = isset($options['enable_footer_widgets']) ? $options['enable_footer_widgets'] : true;
$enable_footer_menu = isset($options['enable_footer_menu']) ? $options['enable_footer_menu'] : true;
$enable_newsletter = isset($options['enable_newsletter']) ? $options['enable_newsletter'] : true;
$newsletter_shortcode = isset($options['newsletter_shortcode']) ? $options['newsletter_shortcode'] : '';
$copyright_text = isset($options['copyright_text']) ? $options['copyright_text'] : '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe');
$enable_back_to_top = isset($options['enable_back_to_top']) ? $options['enable_back_to_top'] : true;
$social_show_footer = isset($options['social_show_footer']) ? $options['social_show_footer'] : true;
$enable_dark_mode = isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
$dark_mode_toggle_position = isset($options['dark_mode_toggle_position']) ? $options['dark_mode_toggle_position'] : 'header';
$dark_mode_toggle_style = isset($options['dark_mode_toggle_style']) ? $options['dark_mode_toggle_style'] : 'icon';

// Get footer class
$footer_class = 'site-footer';
$footer_class .= ' footer-' . $footer_layout;
$footer_class .= ' footer-cols-' . $footer_columns;

?>

    </div><!-- #content -->

    <?php if ($enable_newsletter) : ?>
        <div class="newsletter-section">
            <div class="container">
                <div class="newsletter-inner">
                    <div class="newsletter-content">
                        <h3><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Stay updated with our latest news and special offers.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="newsletter-form">
                        <?php if (!empty($newsletter_shortcode)) : ?>
                            <?php echo do_shortcode($newsletter_shortcode); ?>
                        <?php else : ?>
                            <form class="newsletter-default-form">
                                <input type="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required>
                                <button type="submit"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <footer id="colophon" class="<?php echo esc_attr($footer_class); ?>">
        <?php if ($enable_footer_widgets) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widgets-inner">
                        <?php for ($i = 1; $i <= $footer_columns; $i++) : ?>
                            <div class="footer-column footer-column-<?php echo esc_attr($i); ?>">
                                <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                    <?php dynamic_sidebar('footer-' . $i); ?>
                                <?php else : ?>
                                    <?php if ($i === 1) : ?>
                                        <div class="widget widget_text">
                                            <h3 class="widget-title"><?php esc_html_e('About Us', 'aqualuxe'); ?></h3>
                                            <div class="textwidget">
                                                <?php if (has_custom_logo()) : ?>
                                                    <div class="footer-logo">
                                                        <?php the_custom_logo(); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <p><?php esc_html_e('AquaLuxe offers premium aquatic products and services for both residential and commercial clients.', 'aqualuxe'); ?></p>
                                            </div>
                                        </div>
                                    <?php elseif ($i === 2) : ?>
                                        <div class="widget widget_nav_menu">
                                            <h3 class="widget-title"><?php esc_html_e('Quick Links', 'aqualuxe'); ?></h3>
                                            <ul class="menu">
                                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aqualuxe'); ?></a></li>
                                                <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>"><?php esc_html_e('About Us', 'aqualuxe'); ?></a></li>
                                                <li><a href="<?php echo esc_url(home_url('/services/')); ?>"><?php esc_html_e('Services', 'aqualuxe'); ?></a></li>
                                                <li><a href="<?php echo esc_url(home_url('/products/')); ?>"><?php esc_html_e('Products', 'aqualuxe'); ?></a></li>
                                                <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php esc_html_e('Contact', 'aqualuxe'); ?></a></li>
                                            </ul>
                                        </div>
                                    <?php elseif ($i === 3) : ?>
                                        <div class="widget widget_text">
                                            <h3 class="widget-title"><?php esc_html_e('Contact Info', 'aqualuxe'); ?></h3>
                                            <div class="textwidget">
                                                <ul class="contact-info">
                                                    <li class="address"><?php esc_html_e('123 Aquatic Avenue, Ocean City, CA 90210', 'aqualuxe'); ?></li>
                                                    <li class="phone"><?php esc_html_e('Phone: +1 (555) 123-4567', 'aqualuxe'); ?></li>
                                                    <li class="email"><?php esc_html_e('Email: info@aqualuxe.com', 'aqualuxe'); ?></li>
                                                    <li class="hours"><?php esc_html_e('Hours: Mon-Fri: 9AM-6PM, Sat: 10AM-4PM', 'aqualuxe'); ?></li>
                                                </ul>
                                            </div>
                                        </div>
                                    <?php elseif ($i === 4) : ?>
                                        <div class="widget widget_gallery">
                                            <h3 class="widget-title"><?php esc_html_e('Gallery', 'aqualuxe'); ?></h3>
                                            <div class="footer-gallery">
                                                <div class="gallery-grid">
                                                    <?php for ($j = 1; $j <= 6; $j++) : ?>
                                                        <div class="gallery-item">
                                                            <a href="#" class="gallery-image placeholder-image"></a>
                                                        </div>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <div class="copyright">
                            <?php echo wp_kses_post($copyright_text); ?>
                        </div>
                    </div>
                    <div class="footer-bottom-right">
                        <?php if ($enable_footer_menu) : ?>
                            <nav class="footer-navigation">
                                <?php
                                wp_nav_menu(array(
                                    'theme_location' => 'footer',
                                    'menu_id'        => 'footer-menu',
                                    'container'      => false,
                                    'depth'          => 1,
                                    'fallback_cb'    => false,
                                ));
                                ?>
                            </nav>
                        <?php endif; ?>

                        <?php if ($social_show_footer && function_exists('aqualuxe_social_icons')) : ?>
                            <div class="social-icons">
                                <?php aqualuxe_social_icons('footer'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($enable_dark_mode && ($dark_mode_toggle_position === 'footer' || $dark_mode_toggle_position === 'both')) : ?>
                            <div class="dark-mode-toggle dark-mode-toggle-<?php echo esc_attr($dark_mode_toggle_style); ?>">
                                <button class="dark-mode-toggle-button" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
                                    <span class="icon-light"></span>
                                    <span class="icon-dark"></span>
                                    <?php if ($dark_mode_toggle_style === 'text' || $dark_mode_toggle_style === 'button') : ?>
                                        <span class="toggle-text toggle-text-light"><?php esc_html_e('Light', 'aqualuxe'); ?></span>
                                        <span class="toggle-text toggle-text-dark"><?php esc_html_e('Dark', 'aqualuxe'); ?></span>
                                    <?php endif; ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php if ($enable_back_to_top) : ?>
    <a href="#" id="back-to-top" class="back-to-top">
        <span class="icon-arrow-up"></span>
        <span class="screen-reader-text"><?php esc_html_e('Back to top', 'aqualuxe'); ?></span>
    </a>
<?php endif; ?>

<?php if (isset($options['enable_cookie_notice']) && $options['enable_cookie_notice']) : ?>
    <div id="cookie-notice" class="cookie-notice">
        <div class="container">
            <div class="cookie-notice-inner">
                <div class="cookie-notice-content">
                    <?php 
                    $cookie_text = isset($options['cookie_notice_text']) ? $options['cookie_notice_text'] : __('This website uses cookies to ensure you get the best experience on our website.', 'aqualuxe');
                    echo wp_kses_post($cookie_text); 
                    ?>
                </div>
                <div class="cookie-notice-actions">
                    <button id="cookie-accept" class="button cookie-accept"><?php esc_html_e('Accept', 'aqualuxe'); ?></button>
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" class="cookie-learn-more"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($enable_dark_mode && $dark_mode_toggle_position === 'floating') : ?>
    <div class="dark-mode-toggle dark-mode-toggle-floating dark-mode-toggle-<?php echo esc_attr($dark_mode_toggle_style); ?>">
        <button class="dark-mode-toggle-button" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
            <span class="icon-light"></span>
            <span class="icon-dark"></span>
            <?php if ($dark_mode_toggle_style === 'text' || $dark_mode_toggle_style === 'button') : ?>
                <span class="toggle-text toggle-text-light"><?php esc_html_e('Light', 'aqualuxe'); ?></span>
                <span class="toggle-text toggle-text-dark"><?php esc_html_e('Dark', 'aqualuxe'); ?></span>
            <?php endif; ?>
        </button>
    </div>
<?php endif; ?>

<?php if (isset($options['footer_code']) && !empty($options['footer_code'])) : ?>
    <?php echo wp_kses_post($options['footer_code']); ?>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>