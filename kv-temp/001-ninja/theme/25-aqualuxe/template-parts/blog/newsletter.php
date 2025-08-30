<?php
/**
 * Template part for displaying the blog page newsletter section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_blog_newsletter_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_blog_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
}

$section_text = get_post_meta($page_id, '_aqualuxe_blog_newsletter_text', true);
if (!$section_text) {
    $section_text = get_theme_mod('aqualuxe_blog_newsletter_text', __('Stay updated with our latest articles, tips, and special offers. Subscribe to our newsletter for exclusive content delivered straight to your inbox.', 'aqualuxe'));
}

$newsletter_form = get_post_meta($page_id, '_aqualuxe_blog_newsletter_form', true);
if (!$newsletter_form) {
    $newsletter_form = get_theme_mod('aqualuxe_blog_newsletter_form', '');
}

$section_background = get_post_meta($page_id, '_aqualuxe_blog_newsletter_background', true);
if (!$section_background) {
    $section_background = get_theme_mod('aqualuxe_blog_newsletter_background', 'primary');
}

$section_image = get_post_meta($page_id, '_aqualuxe_blog_newsletter_image', true);
if (!$section_image) {
    $section_image = get_theme_mod('aqualuxe_blog_newsletter_image', '');
}

// Set background class based on setting
$bg_class = '';
$text_class = '';

if ($section_background === 'primary') {
    $bg_class = 'bg-primary';
    $text_class = 'text-white';
} elseif ($section_background === 'accent') {
    $bg_class = 'bg-accent';
    $text_class = 'text-white';
} elseif ($section_background === 'image' && $section_image) {
    $bg_class = 'bg-cover bg-center relative after:absolute after:inset-0 after:bg-black after:bg-opacity-60 after:z-0';
    $text_class = 'text-white';
} else {
    $bg_class = 'bg-gray-100';
    $text_class = 'text-gray-800';
}
?>

<section class="blog-newsletter-section py-16 <?php echo esc_attr($bg_class); ?>" <?php echo ($section_background === 'image' && $section_image) ? 'style="background-image: url(' . esc_url($section_image) . ');"' : ''; ?>>
    <div class="container mx-auto px-4 relative z-10">
        <div class="newsletter-content max-w-3xl mx-auto text-center <?php echo esc_attr($text_class); ?>">
            <?php if ($section_title) : ?>
                <h2 class="newsletter-title text-3xl md:text-4xl font-bold mb-6">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_text) : ?>
                <div class="newsletter-text text-lg mb-8 <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'text-white text-opacity-90' : ''; ?>">
                    <?php echo wp_kses_post(wpautop($section_text)); ?>
                </div>
            <?php endif; ?>
            
            <div class="newsletter-form">
                <?php if ($newsletter_form && shortcode_exists('contact-form-7')) : ?>
                    <?php echo do_shortcode($newsletter_form); ?>
                <?php else : ?>
                    <form action="#" method="post" class="flex flex-col md:flex-row gap-3 justify-center">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" class="px-4 py-3 rounded-md w-full md:w-auto md:flex-1" required>
                        <button type="submit" class="button <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'button-white' : 'button-primary'; ?> px-6 py-3 rounded-md"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                    </form>
                    <p class="text-sm <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'text-white text-opacity-70' : 'text-gray-600'; ?> mt-3"><?php esc_html_e('* By subscribing, you agree to our Privacy Policy.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>