<?php
/**
 * Template part for displaying the legal page contact section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_legal_contact_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_legal_contact_title', __('Questions About Our Policies?', 'aqualuxe'));
}

$section_text = get_post_meta($page_id, '_aqualuxe_legal_contact_text', true);
if (!$section_text) {
    $section_text = get_theme_mod('aqualuxe_legal_contact_text', __('If you have any questions or concerns about our legal policies, please don\'t hesitate to contact us. Our team is here to help.', 'aqualuxe'));
}

$primary_button_text = get_post_meta($page_id, '_aqualuxe_legal_contact_primary_button_text', true);
if (!$primary_button_text) {
    $primary_button_text = get_theme_mod('aqualuxe_legal_contact_primary_button_text', __('Contact Us', 'aqualuxe'));
}

$primary_button_url = get_post_meta($page_id, '_aqualuxe_legal_contact_primary_button_url', true);
if (!$primary_button_url) {
    $primary_button_url = get_theme_mod('aqualuxe_legal_contact_primary_button_url', '#');
}

$section_background = get_post_meta($page_id, '_aqualuxe_legal_contact_background', true);
if (!$section_background) {
    $section_background = get_theme_mod('aqualuxe_legal_contact_background', 'primary');
}

$section_image = get_post_meta($page_id, '_aqualuxe_legal_contact_image', true);
if (!$section_image) {
    $section_image = get_theme_mod('aqualuxe_legal_contact_image', '');
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

<section class="legal-contact-section py-12 <?php echo esc_attr($bg_class); ?>" <?php echo ($section_background === 'image' && $section_image) ? 'style="background-image: url(' . esc_url($section_image) . ');"' : ''; ?>>
    <div class="container mx-auto px-4 relative z-10">
        <div class="contact-content max-w-3xl mx-auto text-center <?php echo esc_attr($text_class); ?>">
            <?php if ($section_title) : ?>
                <h2 class="contact-title text-2xl md:text-3xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_text) : ?>
                <div class="contact-text mb-6 <?php echo $section_background === 'primary' || $section_background === 'accent' || $section_background === 'image' ? 'text-white text-opacity-90' : ''; ?>">
                    <?php echo wp_kses_post(wpautop($section_text)); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($primary_button_text && $primary_button_url) : ?>
                <a href="<?php echo esc_url($primary_button_url); ?>" class="button <?php echo $section_background === 'primary' ? 'button-white' : 'button-primary'; ?>">
                    <?php echo esc_html($primary_button_text); ?>
                </a>
            <?php endif; ?>
            
            <div class="contact-info mt-8 flex flex-wrap justify-center gap-8">
                <?php if (get_theme_mod('aqualuxe_contact_email')) : ?>
                    <div class="contact-email flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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