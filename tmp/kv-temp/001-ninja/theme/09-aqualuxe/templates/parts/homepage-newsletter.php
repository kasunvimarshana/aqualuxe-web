<?php
/**
 * Homepage Newsletter Section
 *
 * @package AquaLuxe
 */

// Get section content from theme options or use default
$section_title = get_theme_mod('aqualuxe_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_newsletter_description', __('Stay updated with our latest products, events, and aquatic care tips.', 'aqualuxe'));
$background_color = get_theme_mod('aqualuxe_newsletter_background', 'primary'); // primary, light, dark
$form_shortcode = get_theme_mod('aqualuxe_newsletter_form_shortcode', '');
$show_social = get_theme_mod('aqualuxe_newsletter_show_social', true);

// Set background class based on setting
$bg_class = '';
switch ($background_color) {
    case 'primary':
        $bg_class = 'bg-primary text-white';
        break;
    case 'light':
        $bg_class = 'bg-gray-100';
        break;
    case 'dark':
        $bg_class = 'bg-gray-900 text-white';
        break;
    default:
        $bg_class = 'bg-primary text-white';
}

// Get social media links
$facebook = get_theme_mod('aqualuxe_facebook', '');
$instagram = get_theme_mod('aqualuxe_instagram', '');
$twitter = get_theme_mod('aqualuxe_twitter', '');
$youtube = get_theme_mod('aqualuxe_youtube', '');
$pinterest = get_theme_mod('aqualuxe_pinterest', '');
?>

<section class="newsletter-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container">
        <div class="max-w-3xl mx-auto text-center">
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($section_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <p class="section-description text-lg opacity-80 mb-8"><?php echo esc_html($section_description); ?></p>
            <?php endif; ?>
            
            <div class="newsletter-form mb-8">
                <?php if ($form_shortcode) : ?>
                    <?php echo do_shortcode($form_shortcode); ?>
                <?php else : ?>
                    <!-- Default newsletter form -->
                    <form class="flex flex-col md:flex-row gap-4 max-w-lg mx-auto" action="#" method="post">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required class="flex-grow px-4 py-3 rounded-md text-gray-800">
                        <button type="submit" class="bg-secondary hover:bg-opacity-80 text-white px-6 py-3 rounded-md font-medium transition duration-300">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </form>
                    <p class="text-sm mt-3 opacity-70"><?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($show_social && ($facebook || $instagram || $twitter || $youtube || $pinterest)) : ?>
                <div class="social-links">
                    <p class="mb-4"><?php esc_html_e('Follow us on social media', 'aqualuxe'); ?></p>
                    <div class="flex justify-center gap-4">
                        <?php if ($facebook) : ?>
                            <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-300">
                                <i class="fab fa-facebook-f"></i>
                                <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($instagram) : ?>
                            <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-300">
                                <i class="fab fa-instagram"></i>
                                <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($twitter) : ?>
                            <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-300">
                                <i class="fab fa-twitter"></i>
                                <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($youtube) : ?>
                            <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-300">
                                <i class="fab fa-youtube"></i>
                                <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($pinterest) : ?>
                            <a href="<?php echo esc_url($pinterest); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition duration-300">
                                <i class="fab fa-pinterest-p"></i>
                                <span class="sr-only"><?php esc_html_e('Pinterest', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>