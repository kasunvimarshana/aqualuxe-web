<?php
/**
 * Template part for displaying the services page call-to-action section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$cta_title = get_post_meta($page_id, '_aqualuxe_services_cta_title', true);
if (!$cta_title) {
    $cta_title = get_theme_mod('aqualuxe_services_cta_title', __('Ready to Get Started?', 'aqualuxe'));
}

$cta_text = get_post_meta($page_id, '_aqualuxe_services_cta_text', true);
if (!$cta_text) {
    $cta_text = get_theme_mod('aqualuxe_services_cta_text', __('Contact our team today to discuss your specific needs and discover how our services can help you create and maintain a stunning aquatic environment.', 'aqualuxe'));
}

$primary_button_text = get_post_meta($page_id, '_aqualuxe_services_cta_primary_button_text', true);
if (!$primary_button_text) {
    $primary_button_text = get_theme_mod('aqualuxe_services_cta_primary_button_text', __('Request a Consultation', 'aqualuxe'));
}

$primary_button_url = get_post_meta($page_id, '_aqualuxe_services_cta_primary_button_url', true);
if (!$primary_button_url) {
    $primary_button_url = get_theme_mod('aqualuxe_services_cta_primary_button_url', '#');
}

$secondary_button_text = get_post_meta($page_id, '_aqualuxe_services_cta_secondary_button_text', true);
if (!$secondary_button_text) {
    $secondary_button_text = get_theme_mod('aqualuxe_services_cta_secondary_button_text', __('Contact Us', 'aqualuxe'));
}

$secondary_button_url = get_post_meta($page_id, '_aqualuxe_services_cta_secondary_button_url', true);
if (!$secondary_button_url) {
    $secondary_button_url = get_theme_mod('aqualuxe_services_cta_secondary_button_url', '#');
}

$cta_background = get_post_meta($page_id, '_aqualuxe_services_cta_background', true);
if (!$cta_background) {
    $cta_background = get_theme_mod('aqualuxe_services_cta_background', 'primary');
}

$cta_image = get_post_meta($page_id, '_aqualuxe_services_cta_image', true);
if (!$cta_image) {
    $cta_image = get_theme_mod('aqualuxe_services_cta_image', '');
}

// Set background class based on setting
$bg_class = '';
$text_class = '';

if ($cta_background === 'primary') {
    $bg_class = 'bg-primary';
    $text_class = 'text-white';
} elseif ($cta_background === 'accent') {
    $bg_class = 'bg-accent';
    $text_class = 'text-white';
} elseif ($cta_background === 'image' && $cta_image) {
    $bg_class = 'bg-cover bg-center relative after:absolute after:inset-0 after:bg-black after:bg-opacity-60 after:z-0';
    $text_class = 'text-white';
} else {
    $bg_class = 'bg-gray-100';
    $text_class = 'text-gray-800';
}
?>

<section class="services-cta-section py-16 <?php echo esc_attr($bg_class); ?>" <?php echo ($cta_background === 'image' && $cta_image) ? 'style="background-image: url(' . esc_url($cta_image) . ');"' : ''; ?>>
    <div class="container mx-auto px-4 relative z-10">
        <div class="cta-content max-w-4xl mx-auto text-center <?php echo esc_attr($text_class); ?>">
            <?php if ($cta_title) : ?>
                <h2 class="cta-title text-3xl md:text-4xl font-bold mb-6">
                    <?php echo esc_html($cta_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($cta_text) : ?>
                <div class="cta-text text-lg mb-8 <?php echo $cta_background === 'primary' || $cta_background === 'accent' || $cta_background === 'image' ? 'text-white text-opacity-90' : ''; ?>">
                    <?php echo wp_kses_post(wpautop($cta_text)); ?>
                </div>
            <?php endif; ?>
            
            <div class="cta-buttons flex flex-wrap justify-center gap-4">
                <?php if ($primary_button_text && $primary_button_url) : ?>
                    <a href="<?php echo esc_url($primary_button_url); ?>" class="button <?php echo $cta_background === 'primary' ? 'button-white' : 'button-primary'; ?>">
                        <?php echo esc_html($primary_button_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($secondary_button_text && $secondary_button_url) : ?>
                    <a href="<?php echo esc_url($secondary_button_url); ?>" class="button <?php echo $cta_background === 'primary' || $cta_background === 'accent' || $cta_background === 'image' ? 'button-outline-white' : 'button-outline-primary'; ?>">
                        <?php echo esc_html($secondary_button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>