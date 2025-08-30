<?php
/**
 * Template part for displaying the call-to-action section on the homepage
 *
 * @package AquaLuxe
 */

// Get CTA section settings from customizer
$cta_title = get_theme_mod('aqualuxe_cta_title', __('Ready to Transform Your Aquatic Experience?', 'aqualuxe'));
$cta_description = get_theme_mod('aqualuxe_cta_description', __('Join thousands of satisfied customers who have chosen AquaLuxe for their ornamental fish and aquarium needs.', 'aqualuxe'));
$cta_button_text = get_theme_mod('aqualuxe_cta_button_text', __('Shop Now', 'aqualuxe'));
$cta_button_url = get_theme_mod('aqualuxe_cta_button_url', '#');
$cta_background_image = get_theme_mod('aqualuxe_cta_background_image', '');
$cta_enable = get_theme_mod('aqualuxe_cta_enable', true);

// Exit if CTA section is disabled
if (!$cta_enable) {
    return;
}

// Set default shop URL if WooCommerce is active
if (empty($cta_button_url) || $cta_button_url === '#') {
    if (class_exists('WooCommerce')) {
        $cta_button_url = get_permalink(wc_get_page_id('shop'));
    }
}

// Set background style if image is provided
$background_style = '';
if ($cta_background_image) {
    $background_style = 'style="background-image: url(' . esc_url($cta_background_image) . ');"';
}
?>

<section class="cta" <?php echo $background_style; ?>>
    <div class="container">
        <div class="cta-content">
            <?php if ($cta_title) : ?>
                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($cta_description) : ?>
                <p class="cta-description"><?php echo esc_html($cta_description); ?></p>
            <?php endif; ?>
            
            <?php if ($cta_button_text && $cta_button_url) : ?>
                <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-accent btn-large"><?php echo esc_html($cta_button_text); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>