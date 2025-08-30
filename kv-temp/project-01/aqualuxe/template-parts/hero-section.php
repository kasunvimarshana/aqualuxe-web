<?php
/**
 * Hero Section Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$hero_title = aqualuxe_get_option('aqualuxe_hero_title', __('Premium Ornamental Fish Collection', 'aqualuxe'));
$hero_subtitle = aqualuxe_get_option('aqualuxe_hero_subtitle', __('Discover the beauty and elegance of premium ornamental fish for your aquarium', 'aqualuxe'));
$hero_button_text = aqualuxe_get_option('aqualuxe_hero_button_text', __('Shop Now', 'aqualuxe'));
$hero_button_url = aqualuxe_get_option('aqualuxe_hero_button_url', wc_get_page_permalink('shop'));
?>

<section class="aqualuxe-hero" id="hero">
    <div class="aqualuxe-hero-content">
        <h1><?php echo esc_html($hero_title); ?></h1>
        <p><?php echo esc_html($hero_subtitle); ?></p>
        <a href="<?php echo esc_url($hero_button_url); ?>" class="aqualuxe-btn aqualuxe-ripple">
            <?php echo esc_html($hero_button_text); ?>
        </a>
    </div>
</section>