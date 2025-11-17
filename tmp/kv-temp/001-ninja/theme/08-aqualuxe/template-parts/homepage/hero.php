<?php
/**
 * Template part for displaying the hero section on the homepage
 *
 * @package AquaLuxe
 */

// Get hero section settings from customizer
$hero_title = get_theme_mod('aqualuxe_hero_title', __('Premium Aquatic Life for Your Aquarium', 'aqualuxe'));
$hero_description = get_theme_mod('aqualuxe_hero_description', __('Discover our wide selection of ornamental fish, aquatic plants, and aquarium supplies for both hobbyists and professionals.', 'aqualuxe'));
$primary_button_text = get_theme_mod('aqualuxe_hero_primary_button_text', __('Shop Now', 'aqualuxe'));
$primary_button_url = get_theme_mod('aqualuxe_hero_primary_button_url', '#');
$secondary_button_text = get_theme_mod('aqualuxe_hero_secondary_button_text', __('Learn More', 'aqualuxe'));
$secondary_button_url = get_theme_mod('aqualuxe_hero_secondary_button_url', '#');
$hero_image = get_theme_mod('aqualuxe_hero_image', '');
$hero_enable = get_theme_mod('aqualuxe_hero_enable', true);

// Exit if hero section is disabled
if (!$hero_enable) {
    return;
}
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <?php if ($hero_title) : ?>
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
            <?php endif; ?>
            
            <?php if ($hero_description) : ?>
                <p class="hero-description"><?php echo esc_html($hero_description); ?></p>
            <?php endif; ?>
            
            <div class="hero-buttons">
                <?php if ($primary_button_text && $primary_button_url) : ?>
                    <a href="<?php echo esc_url($primary_button_url); ?>" class="btn btn-accent"><?php echo esc_html($primary_button_text); ?></a>
                <?php endif; ?>
                
                <?php if ($secondary_button_text && $secondary_button_url) : ?>
                    <a href="<?php echo esc_url($secondary_button_url); ?>" class="btn btn-outline"><?php echo esc_html($secondary_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if ($hero_image) : ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="hero-image">
    <?php endif; ?>
</section>