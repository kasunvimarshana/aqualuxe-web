<?php
/**
 * Template part for displaying the FAQ page contact section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_faq_contact_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_faq_contact_title', __('Still Have Questions?', 'aqualuxe'));
}

$section_text = get_post_meta($page_id, '_aqualuxe_faq_contact_text', true);
if (!$section_text) {
    $section_text = get_theme_mod('aqualuxe_faq_contact_text', __('If you couldn\'t find the answer to your question, our support team is here to help. Contact us and we\'ll get back to you as soon as possible.', 'aqualuxe'));
}

$primary_button_text = get_post_meta($page_id, '_aqualuxe_faq_contact_primary_button_text', true);
if (!$primary_button_text) {
    $primary_button_text = get_theme_mod('aqualuxe_faq_contact_primary_button_text', __('Contact Us', 'aqualuxe'));
}

$primary_button_url = get_post_meta($page_id, '_aqualuxe_faq_contact_primary_button_url', true);
if (!$primary_button_url) {
    $primary_button_url = get_theme_mod('aqualuxe_faq_contact_primary_button_url', '#');
}

$secondary_button_text = get_post_meta($page_id, '_aqualuxe_faq_contact_secondary_button_text', true);
if (!$secondary_button_text) {
    $secondary_button_text = get_theme_mod('aqualuxe_faq_contact_secondary_button_text', __('Submit a Ticket', 'aqualuxe'));
}

$secondary_button_url = get_post_meta($page_id, '_aqualuxe_faq_contact_secondary_button_url', true);
if (!$secondary_button_url) {
    $secondary_button_url = get_theme_mod('aqualuxe_faq_contact_secondary_button_url', '#');
}

$section_background = get_post_meta($page_id, '_aqualuxe_faq_contact_background', true);
if (!$section_background) {
    $section_background = get_theme_mod('aqualuxe_faq_contact_background', 'primary');
}

$section_image = get_post_meta($page_id, '_aqualuxe_faq_contact_image', true);
if (!$section_image) {
    $section_image = get_theme_mod('aqualuxe_faq_contact_image', '');
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

<section class="faq-contact-section py-16 <?php echo esc_attr($bg_class); ?>" <?php echo ($section_background === 'image' && $section_image) ? 'style="background-image: url(' . esc_url($section_image) . ');"' : ''; ?>>
    <div class="container mx-auto px-4 relative z-10">
        <div class="contact-content max-w-3xl mx-auto text-center <?php echo esc_attr($text_class); ?>">
            <?php if ($section_title) : ?>
                <h2 class="contact-title text-3xl md:text-4xl font-bold mb-6">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_text) : ?>
                <div class="contact-text text-lg mb-8 <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'text-white text-opacity-90' : ''; ?>">
                    <?php echo wp_kses_post(wpautop($section_text)); ?>
                </div>
            <?php endif; ?>
            
            <div class="contact-buttons flex flex-wrap justify-center gap-4">
                <?php if ($primary_button_text && $primary_button_url) : ?>
                    <a href="<?php echo esc_url($primary_button_url); ?>" class="button <?php echo $section_background === 'primary' ? 'button-white' : 'button-primary'; ?>">
                        <?php echo esc_html($primary_button_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($secondary_button_text && $secondary_button_url) : ?>
                    <a href="<?php echo esc_url($secondary_button_url); ?>" class="button <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'button-outline-white' : 'button-outline-primary'; ?>">
                        <?php echo esc_html($secondary_button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="contact-info mt-12 flex flex-wrap justify-center gap-8">
                <?php if (get_theme_mod('aqualuxe_contact_phone')) : ?>
                    <div class="contact-phone flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', get_theme_mod('aqualuxe_contact_phone'))); ?>" class="hover:underline">
                            <?php echo esc_html(get_theme_mod('aqualuxe_contact_phone')); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (get_theme_mod('aqualuxe_contact_email')) : ?>
                    <div class="contact-email flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_contact_email')); ?>" class="hover:underline">
                            <?php echo esc_html(get_theme_mod('aqualuxe_contact_email')); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>