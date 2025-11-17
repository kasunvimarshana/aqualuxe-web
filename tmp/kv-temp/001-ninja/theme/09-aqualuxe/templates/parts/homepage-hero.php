<?php
/**
 * Homepage Hero Section
 *
 * @package AquaLuxe
 */

// Get hero content from theme options or use default
$hero_title = get_theme_mod('aqualuxe_hero_title', __('Bringing elegance to aquatic life – globally.', 'aqualuxe'));
$hero_description = get_theme_mod('aqualuxe_hero_description', __('Premium ornamental fish, aquatic plants, and custom aquarium solutions for hobbyists and professionals.', 'aqualuxe'));
$hero_button_text = get_theme_mod('aqualuxe_hero_button_text', __('Shop Now', 'aqualuxe'));
$hero_button_url = get_theme_mod('aqualuxe_hero_button_url', class_exists('WooCommerce') ? wc_get_page_permalink('shop') : '#');
$hero_secondary_button_text = get_theme_mod('aqualuxe_hero_secondary_button_text', __('Learn More', 'aqualuxe'));
$hero_secondary_button_url = get_theme_mod('aqualuxe_hero_secondary_button_url', '#');
$hero_image = get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg');
$hero_overlay = get_theme_mod('aqualuxe_hero_overlay', true);
$hero_height = get_theme_mod('aqualuxe_hero_height', 'medium');

// Set height class based on setting
$height_class = '';
switch ($hero_height) {
    case 'small':
        $height_class = 'min-h-[400px]';
        break;
    case 'medium':
        $height_class = 'min-h-[600px]';
        break;
    case 'large':
        $height_class = 'min-h-[800px]';
        break;
    case 'full':
        $height_class = 'min-h-screen';
        break;
    default:
        $height_class = 'min-h-[600px]';
}

// Overlay class
$overlay_class = $hero_overlay ? 'before:absolute before:inset-0 before:bg-black before:bg-opacity-40 before:z-10' : '';
?>

<section class="hero-section relative <?php echo esc_attr($height_class); ?> <?php echo esc_attr($overlay_class); ?> flex items-center">
    <?php if ($hero_image) : ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="w-full h-full object-cover">
        </div>
    <?php endif; ?>
    
    <div class="container relative z-20">
        <div class="max-w-2xl text-white">
            <?php if ($hero_title) : ?>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6"><?php echo esc_html($hero_title); ?></h1>
            <?php endif; ?>
            
            <?php if ($hero_description) : ?>
                <p class="text-lg md:text-xl mb-8"><?php echo esc_html($hero_description); ?></p>
            <?php endif; ?>
            
            <div class="flex flex-wrap gap-4">
                <?php if ($hero_button_text && $hero_button_url) : ?>
                    <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-primary"><?php echo esc_html($hero_button_text); ?></a>
                <?php endif; ?>
                
                <?php if ($hero_secondary_button_text && $hero_secondary_button_url) : ?>
                    <a href="<?php echo esc_url($hero_secondary_button_url); ?>" class="btn bg-white text-primary hover:bg-gray-100"><?php echo esc_html($hero_secondary_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>