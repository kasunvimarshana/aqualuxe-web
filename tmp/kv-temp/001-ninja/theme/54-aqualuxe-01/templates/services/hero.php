<?php
/**
 * Services Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_hero_title', __('Our Services', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_hero_subtitle', __('Discover our premium aquatic solutions', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_hero_description', __('We offer a wide range of professional services to meet all your aquatic needs.', 'aqualuxe'));
$image = get_theme_mod('aqualuxe_services_hero_image', get_template_directory_uri() . '/assets/images/services-hero.jpg');
$overlay = get_theme_mod('aqualuxe_services_hero_overlay', true);
$overlay_opacity = get_theme_mod('aqualuxe_services_hero_overlay_opacity', 0.7);
$text_color = get_theme_mod('aqualuxe_services_hero_text_color', 'text-white');
$button_text = get_theme_mod('aqualuxe_services_hero_button_text', __('Contact Us', 'aqualuxe'));
$button_url = get_theme_mod('aqualuxe_services_hero_button_url', '#contact');
$button_style = get_theme_mod('aqualuxe_services_hero_button_style', 'primary');
?>

<section class="services-hero relative bg-cover bg-center py-24 md:py-32" style="background-image: url('<?php echo esc_url($image); ?>');">
    <?php if ($overlay) : ?>
        <div class="absolute inset-0 bg-blue-900 opacity-<?php echo esc_attr($overlay_opacity * 100); ?>"></div>
    <?php endif; ?>
    
    <div class="container relative z-10 mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <?php if ($subtitle) : ?>
                <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full <?php echo esc_attr($text_color); ?> bg-blue-600 bg-opacity-30">
                    <?php echo esc_html($subtitle); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($title) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 <?php echo esc_attr($text_color); ?>">
                    <?php echo esc_html($title); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ($description) : ?>
                <p class="text-lg md:text-xl mb-8 <?php echo esc_attr($text_color); ?>">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
            
            <?php if ($button_text && $button_url) : ?>
                <a href="<?php echo esc_url($button_url); ?>" class="btn btn-<?php echo esc_attr($button_style); ?> text-lg px-8 py-3">
                    <?php echo esc_html($button_text); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>