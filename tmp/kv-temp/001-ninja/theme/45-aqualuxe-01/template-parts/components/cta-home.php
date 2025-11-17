<?php
/**
 * Template part for displaying the homepage call-to-action section
 *
 * @package AquaLuxe
 */

// Get CTA options from theme customizer
$cta_style = get_theme_mod('aqualuxe_cta_style', 'default');
$cta_bg_color = get_theme_mod('aqualuxe_cta_bg_color', '#f8f9fa');
$cta_text_color = get_theme_mod('aqualuxe_cta_text_color', '#212529');
$cta_bg_image = get_theme_mod('aqualuxe_cta_bg_image', '');
$cta_overlay = get_theme_mod('aqualuxe_cta_overlay', 'rgba(0,0,0,0.5)');
$cta_title = get_theme_mod('aqualuxe_cta_title', __('Ready to Transform Your Aquatic Experience?', 'aqualuxe'));
$cta_text = get_theme_mod('aqualuxe_cta_text', __('Contact us today to schedule a consultation with our aquatic experts.', 'aqualuxe'));
$cta_button_text = get_theme_mod('aqualuxe_cta_button_text', __('Contact Us', 'aqualuxe'));
$cta_button_url = get_theme_mod('aqualuxe_cta_button_url', '#');
$cta_button2_text = get_theme_mod('aqualuxe_cta_button2_text', __('View Services', 'aqualuxe'));
$cta_button2_url = get_theme_mod('aqualuxe_cta_button2_url', '#');

// CTA classes
$cta_classes = array('cta-section');
$cta_classes[] = 'cta-style-' . $cta_style;

if (!empty($cta_bg_image)) {
    $cta_classes[] = 'has-background';
}

// Check if CTA should be displayed
if (empty($cta_title) && empty($cta_text) && empty($cta_button_text)) {
    return;
}
?>

<div class="<?php echo esc_attr(implode(' ', $cta_classes)); ?>" id="cta-section">
    <?php if (!empty($cta_bg_image)) : ?>
        <div class="cta-background" style="background-image: url('<?php echo esc_url($cta_bg_image); ?>');">
            <div class="cta-overlay" style="background-color: <?php echo esc_attr($cta_overlay); ?>;"></div>
        </div>
    <?php else : ?>
        <div class="cta-background" style="background-color: <?php echo esc_attr($cta_bg_color); ?>;"></div>
    <?php endif; ?>
    
    <div class="container">
        <div class="cta-content" style="color: <?php echo esc_attr($cta_text_color); ?>;">
            <?php if (!empty($cta_title)) : ?>
                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($cta_text)) : ?>
                <div class="cta-text"><?php echo wp_kses_post($cta_text); ?></div>
            <?php endif; ?>
            
            <div class="cta-buttons">
                <?php if (!empty($cta_button_text) && !empty($cta_button_url)) : ?>
                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-primary">
                        <?php echo esc_html($cta_button_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($cta_button2_text) && !empty($cta_button2_url)) : ?>
                    <a href="<?php echo esc_url($cta_button2_url); ?>" class="btn btn-outline-primary">
                        <?php echo esc_html($cta_button2_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>