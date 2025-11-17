<?php
/**
 * Template part for displaying footer newsletter signup
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Check if newsletter is enabled in theme options
if (!get_theme_mod('aqualuxe_enable_footer_newsletter', true)) {
    return;
}

$newsletter_title = get_theme_mod('aqualuxe_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
$newsletter_text = get_theme_mod('aqualuxe_newsletter_text', __('Stay updated with our latest news and special offers.', 'aqualuxe'));
$newsletter_form = get_theme_mod('aqualuxe_newsletter_form', '');
?>

<div class="footer-newsletter py-12 bg-primary-dark">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <?php if ($newsletter_title) : ?>
                <h3 class="text-2xl font-bold text-white mb-3"><?php echo esc_html($newsletter_title); ?></h3>
            <?php endif; ?>
            
            <?php if ($newsletter_text) : ?>
                <p class="text-white text-opacity-90 mb-6"><?php echo esc_html($newsletter_text); ?></p>
            <?php endif; ?>
            
            <div class="newsletter-form">
                <?php if ($newsletter_form && shortcode_exists('contact-form-7')) : ?>
                    <?php echo do_shortcode($newsletter_form); ?>
                <?php else : ?>
                    <form action="#" method="post" class="flex flex-col md:flex-row gap-3 justify-center">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="px-4 py-3 rounded-md w-full md:w-auto md:flex-1" required>
                        <button type="submit" class="button button-accent px-6 py-3 rounded-md"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                    </form>
                    <p class="text-sm text-white text-opacity-70 mt-3"><?php esc_html_e('* By subscribing, you agree to our Privacy Policy.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>