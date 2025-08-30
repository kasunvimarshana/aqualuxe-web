<?php
/**
 * Template part for displaying the services page hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get hero settings from page meta or theme options
$hero_title = get_post_meta($page_id, '_aqualuxe_services_hero_title', true);
if (!$hero_title) {
    $hero_title = get_theme_mod('aqualuxe_services_hero_title', __('Our Services', 'aqualuxe'));
}

$hero_subtitle = get_post_meta($page_id, '_aqualuxe_services_hero_subtitle', true);
if (!$hero_subtitle) {
    $hero_subtitle = get_theme_mod('aqualuxe_services_hero_subtitle', __('Comprehensive Aquatic Solutions', 'aqualuxe'));
}

$hero_text = get_post_meta($page_id, '_aqualuxe_services_hero_text', true);
if (!$hero_text) {
    $hero_text = get_theme_mod('aqualuxe_services_hero_text', __('Discover our range of professional services designed to meet all your ornamental fish and aquarium needs.', 'aqualuxe'));
}

$hero_image = get_post_meta($page_id, '_aqualuxe_services_hero_image', true);
if (!$hero_image) {
    $hero_image = get_theme_mod('aqualuxe_services_hero_image', get_template_directory_uri() . '/assets/images/services-hero.jpg');
}

$hero_overlay = get_post_meta($page_id, '_aqualuxe_services_hero_overlay', true);
if ($hero_overlay === '') {
    $hero_overlay = get_theme_mod('aqualuxe_services_hero_overlay', true);
}

// Set overlay class if enabled
$overlay_class = $hero_overlay ? 'after:absolute after:inset-0 after:bg-black after:bg-opacity-40 after:z-10' : '';
?>

<section class="services-hero-section relative min-h-[50vh] <?php echo esc_attr($overlay_class); ?> flex items-center">
    <?php if ($hero_image) : ?>
        <div class="hero-background absolute inset-0 z-0">
            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="w-full h-full object-cover">
        </div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-20">
        <div class="hero-content max-w-3xl text-white">
            <?php if ($hero_subtitle) : ?>
                <div class="hero-subtitle mb-2 text-lg md:text-xl font-light tracking-wider">
                    <?php echo esc_html($hero_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($hero_title) : ?>
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                    <?php echo esc_html($hero_title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ($hero_text) : ?>
                <div class="hero-text text-lg mb-8 text-white text-opacity-90">
                    <?php echo wp_kses_post($hero_text); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>