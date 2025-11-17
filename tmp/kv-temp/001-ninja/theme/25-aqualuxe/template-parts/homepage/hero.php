<?php
/**
 * Template part for displaying the homepage hero section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get hero settings from theme options
$hero_title = get_theme_mod('aqualuxe_hero_title', __('Bringing Elegance to Aquatic Life – Globally', 'aqualuxe'));
$hero_subtitle = get_theme_mod('aqualuxe_hero_subtitle', __('Premium ornamental fish for collectors and enthusiasts', 'aqualuxe'));
$hero_text = get_theme_mod('aqualuxe_hero_text', __('Discover our exclusive collection of rare and exotic ornamental fish, sourced from sustainable farms around the world.', 'aqualuxe'));
$hero_button_text = get_theme_mod('aqualuxe_hero_button_text', __('Explore Collection', 'aqualuxe'));
$hero_button_url = get_theme_mod('aqualuxe_hero_button_url', '#');
$hero_button2_text = get_theme_mod('aqualuxe_hero_button2_text', __('Learn More', 'aqualuxe'));
$hero_button2_url = get_theme_mod('aqualuxe_hero_button2_url', '#');
$hero_image = get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/images/hero-default.jpg');
$hero_overlay = get_theme_mod('aqualuxe_hero_overlay', true);
$hero_height = get_theme_mod('aqualuxe_hero_height', 'medium');

// Set height class based on setting
switch ($hero_height) {
    case 'small':
        $height_class = 'min-h-[50vh]';
        break;
    case 'large':
        $height_class = 'min-h-[90vh]';
        break;
    case 'medium':
    default:
        $height_class = 'min-h-[70vh]';
        break;
}

// Set overlay class if enabled
$overlay_class = $hero_overlay ? 'after:absolute after:inset-0 after:bg-black after:bg-opacity-40 after:z-10' : '';
?>

<section class="hero-section relative <?php echo esc_attr($height_class); ?> <?php echo esc_attr($overlay_class); ?> flex items-center">
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
            
            <div class="hero-buttons flex flex-wrap gap-4">
                <?php if ($hero_button_text && $hero_button_url) : ?>
                    <a href="<?php echo esc_url($hero_button_url); ?>" class="button button-primary">
                        <?php echo esc_html($hero_button_text); ?>
                    </a>
                <?php endif; ?>
                
                <?php if ($hero_button2_text && $hero_button2_url) : ?>
                    <a href="<?php echo esc_url($hero_button2_url); ?>" class="button button-outline-white">
                        <?php echo esc_html($hero_button2_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>