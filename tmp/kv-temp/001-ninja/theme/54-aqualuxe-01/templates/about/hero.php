<?php
/**
 * About Page Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$hero_title = get_theme_mod('aqualuxe_about_hero_title', __('About AquaLuxe', 'aqualuxe'));
$hero_subtitle = get_theme_mod('aqualuxe_about_hero_subtitle', __('Bringing elegance to aquatic life since 2010', 'aqualuxe'));
$hero_image = get_theme_mod('aqualuxe_about_hero_image', get_template_directory_uri() . '/assets/dist/images/about-hero.jpg');
$hero_overlay = get_theme_mod('aqualuxe_about_hero_overlay', true);
$hero_overlay_opacity = get_theme_mod('aqualuxe_about_hero_overlay_opacity', 0.5);
$hero_text_color = get_theme_mod('aqualuxe_about_hero_text_color', '#ffffff');
$hero_height = get_theme_mod('aqualuxe_about_hero_height', 'medium'); // small, medium, large

// Set height class
$height_class = '';
switch ($hero_height) {
    case 'small':
        $height_class = 'min-h-[300px] md:min-h-[400px]';
        break;
    case 'medium':
        $height_class = 'min-h-[400px] md:min-h-[500px]';
        break;
    case 'large':
        $height_class = 'min-h-[500px] md:min-h-[600px]';
        break;
}

// Set overlay style
$overlay_style = '';
if ($hero_overlay) {
    $overlay_style = 'background-color: rgba(0, 0, 0, ' . esc_attr($hero_overlay_opacity) . ');';
}

// Set text style
$text_style = '';
if ($hero_text_color) {
    $text_style = 'color: ' . esc_attr($hero_text_color) . ';';
}
?>

<section class="aqualuxe-about-hero relative <?php echo esc_attr($height_class); ?> bg-cover bg-center flex items-center" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <?php if ($hero_overlay) : ?>
        <div class="absolute inset-0" style="<?php echo esc_attr($overlay_style); ?>"></div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl" style="<?php echo esc_attr($text_style); ?>">
            <?php if ($hero_title) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
            <?php endif; ?>
            
            <?php if ($hero_subtitle) : ?>
                <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>