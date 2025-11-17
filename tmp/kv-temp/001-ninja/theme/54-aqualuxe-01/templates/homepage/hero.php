<?php
/**
 * Homepage Hero Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer settings
$hero_title = get_theme_mod('aqualuxe_homepage_hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'));
$hero_subtitle = get_theme_mod('aqualuxe_homepage_hero_subtitle', __('Premium aquariums, accessories, and services for enthusiasts and professionals', 'aqualuxe'));
$hero_button_text = get_theme_mod('aqualuxe_homepage_hero_button_text', __('Shop Now', 'aqualuxe'));
$hero_button_url = get_theme_mod('aqualuxe_homepage_hero_button_url', '#');
$hero_secondary_button_text = get_theme_mod('aqualuxe_homepage_hero_secondary_button_text', __('Learn More', 'aqualuxe'));
$hero_secondary_button_url = get_theme_mod('aqualuxe_homepage_hero_secondary_button_url', '#');
$hero_image = get_theme_mod('aqualuxe_homepage_hero_image', get_template_directory_uri() . '/assets/dist/images/hero-default.jpg');
$hero_overlay = get_theme_mod('aqualuxe_homepage_hero_overlay', true);
$hero_overlay_opacity = get_theme_mod('aqualuxe_homepage_hero_overlay_opacity', 0.5);
$hero_text_color = get_theme_mod('aqualuxe_homepage_hero_text_color', '#ffffff');
$hero_height = get_theme_mod('aqualuxe_homepage_hero_height', 'medium'); // small, medium, large, full

// Set height class
$height_class = '';
switch ($hero_height) {
    case 'small':
        $height_class = 'min-h-[400px] md:min-h-[500px]';
        break;
    case 'medium':
        $height_class = 'min-h-[500px] md:min-h-[600px]';
        break;
    case 'large':
        $height_class = 'min-h-[600px] md:min-h-[700px]';
        break;
    case 'full':
        $height_class = 'min-h-screen';
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

<section class="aqualuxe-hero relative <?php echo esc_attr($height_class); ?> bg-cover bg-center flex items-center" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <?php if ($hero_overlay) : ?>
        <div class="absolute inset-0" style="<?php echo esc_attr($overlay_style); ?>"></div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl" style="<?php echo esc_attr($text_style); ?>">
            <?php if ($hero_title) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
            <?php endif; ?>
            
            <?php if ($hero_subtitle) : ?>
                <p class="text-xl md:text-2xl mb-8"><?php echo esc_html($hero_subtitle); ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap gap-4">
                <?php if ($hero_button_text && $hero_button_url) : ?>
                    <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-primary"><?php echo esc_html($hero_button_text); ?></a>
                <?php endif; ?>
                
                <?php if ($hero_secondary_button_text && $hero_secondary_button_url) : ?>
                    <a href="<?php echo esc_url($hero_secondary_button_url); ?>" class="btn btn-secondary"><?php echo esc_html($hero_secondary_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>