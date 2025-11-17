<?php
/**
 * Template part for displaying the homepage hero section
 *
 * @package AquaLuxe
 */

// Get hero options from theme customizer
$hero_style = get_theme_mod('aqualuxe_hero_style', 'slider');
$hero_height = get_theme_mod('aqualuxe_hero_height', 'full');
$hero_content_alignment = get_theme_mod('aqualuxe_hero_content_alignment', 'center');
$hero_overlay = get_theme_mod('aqualuxe_hero_overlay', 'rgba(0,0,0,0.3)');

// Hero classes
$hero_classes = array('hero-section');
$hero_classes[] = 'hero-style-' . $hero_style;
$hero_classes[] = 'hero-height-' . $hero_height;
$hero_classes[] = 'hero-align-' . $hero_content_alignment;

// Get hero slides
$hero_slides = array();

if ($hero_style === 'slider') {
    // Get slides from theme options
    $hero_slides = get_theme_mod('aqualuxe_hero_slides', array());
    
    // If no slides are defined in theme options, create a default slide
    if (empty($hero_slides)) {
        $hero_slides = array(
            array(
                'image' => get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/dist/images/hero-default.jpg'),
                'title' => get_theme_mod('aqualuxe_hero_title', __('Welcome to AquaLuxe', 'aqualuxe')),
                'subtitle' => get_theme_mod('aqualuxe_hero_subtitle', __('Luxury Aquatic Products & Services', 'aqualuxe')),
                'text' => get_theme_mod('aqualuxe_hero_text', __('Discover our premium selection of aquariums, accessories, and professional services.', 'aqualuxe')),
                'button_text' => get_theme_mod('aqualuxe_hero_button_text', __('Explore Our Products', 'aqualuxe')),
                'button_url' => get_theme_mod('aqualuxe_hero_button_url', '#'),
                'button2_text' => get_theme_mod('aqualuxe_hero_button2_text', __('Our Services', 'aqualuxe')),
                'button2_url' => get_theme_mod('aqualuxe_hero_button2_url', '#'),
            ),
        );
    }
} elseif ($hero_style === 'video') {
    // Get video background
    $hero_video_url = get_theme_mod('aqualuxe_hero_video_url', '');
    $hero_video_poster = get_theme_mod('aqualuxe_hero_video_poster', '');
    
    // Create a single slide with video background
    $hero_slides = array(
        array(
            'video' => $hero_video_url,
            'poster' => $hero_video_poster,
            'title' => get_theme_mod('aqualuxe_hero_title', __('Welcome to AquaLuxe', 'aqualuxe')),
            'subtitle' => get_theme_mod('aqualuxe_hero_subtitle', __('Luxury Aquatic Products & Services', 'aqualuxe')),
            'text' => get_theme_mod('aqualuxe_hero_text', __('Discover our premium selection of aquariums, accessories, and professional services.', 'aqualuxe')),
            'button_text' => get_theme_mod('aqualuxe_hero_button_text', __('Explore Our Products', 'aqualuxe')),
            'button_url' => get_theme_mod('aqualuxe_hero_button_url', '#'),
            'button2_text' => get_theme_mod('aqualuxe_hero_button2_text', __('Our Services', 'aqualuxe')),
            'button2_url' => get_theme_mod('aqualuxe_hero_button2_url', '#'),
        ),
    );
} else {
    // Static hero
    $hero_slides = array(
        array(
            'image' => get_theme_mod('aqualuxe_hero_image', get_template_directory_uri() . '/assets/dist/images/hero-default.jpg'),
            'title' => get_theme_mod('aqualuxe_hero_title', __('Welcome to AquaLuxe', 'aqualuxe')),
            'subtitle' => get_theme_mod('aqualuxe_hero_subtitle', __('Luxury Aquatic Products & Services', 'aqualuxe')),
            'text' => get_theme_mod('aqualuxe_hero_text', __('Discover our premium selection of aquariums, accessories, and professional services.', 'aqualuxe')),
            'button_text' => get_theme_mod('aqualuxe_hero_button_text', __('Explore Our Products', 'aqualuxe')),
            'button_url' => get_theme_mod('aqualuxe_hero_button_url', '#'),
            'button2_text' => get_theme_mod('aqualuxe_hero_button2_text', __('Our Services', 'aqualuxe')),
            'button2_url' => get_theme_mod('aqualuxe_hero_button2_url', '#'),
        ),
    );
}

// Check if we have any slides
if (empty($hero_slides)) {
    return;
}
?>

<div class="<?php echo esc_attr(implode(' ', $hero_classes)); ?>">
    <?php if ($hero_style === 'slider' && count($hero_slides) > 1) : ?>
        <div class="hero-slider">
            <?php foreach ($hero_slides as $slide) : ?>
                <div class="hero-slide">
                    <?php if (!empty($slide['image'])) : ?>
                        <div class="hero-background" style="background-image: url('<?php echo esc_url($slide['image']); ?>');">
                            <div class="hero-overlay" style="background-color: <?php echo esc_attr($hero_overlay); ?>;"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="container">
                        <div class="hero-content">
                            <?php if (!empty($slide['subtitle'])) : ?>
                                <div class="hero-subtitle"><?php echo esc_html($slide['subtitle']); ?></div>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['title'])) : ?>
                                <h1 class="hero-title"><?php echo esc_html($slide['title']); ?></h1>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['text'])) : ?>
                                <div class="hero-text"><?php echo wp_kses_post($slide['text']); ?></div>
                            <?php endif; ?>
                            
                            <div class="hero-buttons">
                                <?php if (!empty($slide['button_text']) && !empty($slide['button_url'])) : ?>
                                    <a href="<?php echo esc_url($slide['button_url']); ?>" class="btn btn-primary">
                                        <?php echo esc_html($slide['button_text']); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($slide['button2_text']) && !empty($slide['button2_url'])) : ?>
                                    <a href="<?php echo esc_url($slide['button2_url']); ?>" class="btn btn-outline-light">
                                        <?php echo esc_html($slide['button2_text']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($hero_style === 'video' && !empty($hero_slides[0]['video'])) : ?>
        <div class="hero-video-container">
            <video class="hero-video" autoplay muted loop playsinline<?php echo !empty($hero_slides[0]['poster']) ? ' poster="' . esc_url($hero_slides[0]['poster']) . '"' : ''; ?>>
                <source src="<?php echo esc_url($hero_slides[0]['video']); ?>" type="video/mp4">
            </video>
            
            <div class="hero-overlay" style="background-color: <?php echo esc_attr($hero_overlay); ?>;"></div>
            
            <div class="container">
                <div class="hero-content">
                    <?php if (!empty($hero_slides[0]['subtitle'])) : ?>
                        <div class="hero-subtitle"><?php echo esc_html($hero_slides[0]['subtitle']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($hero_slides[0]['title'])) : ?>
                        <h1 class="hero-title"><?php echo esc_html($hero_slides[0]['title']); ?></h1>
                    <?php endif; ?>
                    
                    <?php if (!empty($hero_slides[0]['text'])) : ?>
                        <div class="hero-text"><?php echo wp_kses_post($hero_slides[0]['text']); ?></div>
                    <?php endif; ?>
                    
                    <div class="hero-buttons">
                        <?php if (!empty($hero_slides[0]['button_text']) && !empty($hero_slides[0]['button_url'])) : ?>
                            <a href="<?php echo esc_url($hero_slides[0]['button_url']); ?>" class="btn btn-primary">
                                <?php echo esc_html($hero_slides[0]['button_text']); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($hero_slides[0]['button2_text']) && !empty($hero_slides[0]['button2_url'])) : ?>
                            <a href="<?php echo esc_url($hero_slides[0]['button2_url']); ?>" class="btn btn-outline-light">
                                <?php echo esc_html($hero_slides[0]['button2_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="hero-static">
            <?php if (!empty($hero_slides[0]['image'])) : ?>
                <div class="hero-background" style="background-image: url('<?php echo esc_url($hero_slides[0]['image']); ?>');">
                    <div class="hero-overlay" style="background-color: <?php echo esc_attr($hero_overlay); ?>;"></div>
                </div>
            <?php endif; ?>
            
            <div class="container">
                <div class="hero-content">
                    <?php if (!empty($hero_slides[0]['subtitle'])) : ?>
                        <div class="hero-subtitle"><?php echo esc_html($hero_slides[0]['subtitle']); ?></div>
                    <?php endif; ?>
                    
                    <?php if (!empty($hero_slides[0]['title'])) : ?>
                        <h1 class="hero-title"><?php echo esc_html($hero_slides[0]['title']); ?></h1>
                    <?php endif; ?>
                    
                    <?php if (!empty($hero_slides[0]['text'])) : ?>
                        <div class="hero-text"><?php echo wp_kses_post($hero_slides[0]['text']); ?></div>
                    <?php endif; ?>
                    
                    <div class="hero-buttons">
                        <?php if (!empty($hero_slides[0]['button_text']) && !empty($hero_slides[0]['button_url'])) : ?>
                            <a href="<?php echo esc_url($hero_slides[0]['button_url']); ?>" class="btn btn-primary">
                                <?php echo esc_html($hero_slides[0]['button_text']); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (!empty($hero_slides[0]['button2_text']) && !empty($hero_slides[0]['button2_url'])) : ?>
                            <a href="<?php echo esc_url($hero_slides[0]['button2_url']); ?>" class="btn btn-outline-light">
                                <?php echo esc_html($hero_slides[0]['button2_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($hero_height === 'full' && get_theme_mod('aqualuxe_show_hero_scroll_down', true)) : ?>
        <div class="hero-scroll-down">
            <a href="#content" class="scroll-down-link">
                <span class="scroll-down-text"><?php echo esc_html__('Scroll Down', 'aqualuxe'); ?></span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    <?php endif; ?>
</div>