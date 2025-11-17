<?php
/**
 * Template part for displaying the about section on the homepage
 *
 * @package AquaLuxe
 */

// Get about section options from theme customizer
$show_about = get_theme_mod('aqualuxe_show_home_about', true);
$about_layout = get_theme_mod('aqualuxe_home_about_layout', 'image-left');
$about_title = get_theme_mod('aqualuxe_home_about_title', __('About AquaLuxe', 'aqualuxe'));
$about_subtitle = get_theme_mod('aqualuxe_home_about_subtitle', __('Premium Aquatic Solutions', 'aqualuxe'));
$about_content = get_theme_mod('aqualuxe_home_about_content', __('AquaLuxe is a premier provider of luxury aquatic products and services. With years of experience and a passion for aquatic life, we offer the highest quality aquariums, accessories, and maintenance services to enhance your aquatic experience.', 'aqualuxe'));
$about_image = get_theme_mod('aqualuxe_home_about_image', '');
$about_video_url = get_theme_mod('aqualuxe_home_about_video_url', '');
$about_button_text = get_theme_mod('aqualuxe_home_about_button_text', __('Learn More', 'aqualuxe'));
$about_button_url = get_theme_mod('aqualuxe_home_about_button_url', '#');
$about_features = get_theme_mod('aqualuxe_home_about_features', array(
    array(
        'icon' => 'fas fa-award',
        'title' => __('Premium Quality', 'aqualuxe'),
        'text' => __('We offer only the highest quality products and services.', 'aqualuxe'),
    ),
    array(
        'icon' => 'fas fa-users',
        'title' => __('Expert Team', 'aqualuxe'),
        'text' => __('Our team of experts is passionate about aquatic life.', 'aqualuxe'),
    ),
    array(
        'icon' => 'fas fa-headset',
        'title' => __('24/7 Support', 'aqualuxe'),
        'text' => __('We provide round-the-clock support for all your needs.', 'aqualuxe'),
    ),
));

// Check if about section should be displayed
if (!$show_about) {
    return;
}

// About section classes
$about_classes = array('about-section', 'section-padding');
$about_classes[] = 'about-layout-' . $about_layout;

// Content column classes
$content_class = 'col-lg-6';
$image_class = 'col-lg-6';

// Check if we have an image or video
$has_media = !empty($about_image) || !empty($about_video_url);

// If no media, make content full width
if (!$has_media) {
    $content_class = 'col-lg-12';
    $about_layout = 'content-only';
    $about_classes[] = 'about-layout-content-only';
}
?>

<div class="<?php echo esc_attr(implode(' ', $about_classes)); ?>">
    <div class="container">
        <div class="row align-items-center">
            <?php if ($about_layout === 'image-left' && $has_media) : ?>
                <div class="<?php echo esc_attr($image_class); ?>">
                    <div class="about-media">
                        <?php if (!empty($about_video_url)) : ?>
                            <div class="about-video">
                                <div class="video-wrapper">
                                    <?php if (!empty($about_image)) : ?>
                                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="img-fluid">
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo esc_url($about_video_url); ?>" class="video-play-button" data-fancybox>
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div>
                        <?php elseif (!empty($about_image)) : ?>
                            <div class="about-image">
                                <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="<?php echo esc_attr($content_class); ?>">
                <div class="about-content">
                    <div class="section-header">
                        <?php if (!empty($about_subtitle)) : ?>
                            <div class="section-subtitle"><?php echo esc_html($about_subtitle); ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($about_title)) : ?>
                            <h2 class="section-title"><?php echo esc_html($about_title); ?></h2>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($about_content)) : ?>
                        <div class="about-text">
                            <?php echo wp_kses_post($about_content); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($about_features)) : ?>
                        <div class="about-features">
                            <div class="row">
                                <?php foreach ($about_features as $feature) : ?>
                                    <?php if (!empty($feature['title'])) : ?>
                                        <div class="col-md-6">
                                            <div class="feature-item">
                                                <?php if (!empty($feature['icon'])) : ?>
                                                    <div class="feature-icon">
                                                        <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="feature-content">
                                                    <h3 class="feature-title"><?php echo esc_html($feature['title']); ?></h3>
                                                    
                                                    <?php if (!empty($feature['text'])) : ?>
                                                        <div class="feature-text">
                                                            <?php echo esc_html($feature['text']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($about_button_text) && !empty($about_button_url)) : ?>
                        <div class="about-button">
                            <a href="<?php echo esc_url($about_button_url); ?>" class="btn btn-primary">
                                <?php echo esc_html($about_button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($about_layout === 'image-right' && $has_media) : ?>
                <div class="<?php echo esc_attr($image_class); ?>">
                    <div class="about-media">
                        <?php if (!empty($about_video_url)) : ?>
                            <div class="about-video">
                                <div class="video-wrapper">
                                    <?php if (!empty($about_image)) : ?>
                                        <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="img-fluid">
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo esc_url($about_video_url); ?>" class="video-play-button" data-fancybox>
                                        <i class="fas fa-play"></i>
                                    </a>
                                </div>
                            </div>
                        <?php elseif (!empty($about_image)) : ?>
                            <div class="about-image">
                                <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>" class="img-fluid">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        // Counter section
        $show_counters = get_theme_mod('aqualuxe_show_home_counters', true);
        $counters = get_theme_mod('aqualuxe_home_counters', array(
            array(
                'number' => '15',
                'suffix' => '+',
                'title' => __('Years of Experience', 'aqualuxe'),
            ),
            array(
                'number' => '1000',
                'suffix' => '+',
                'title' => __('Happy Clients', 'aqualuxe'),
            ),
            array(
                'number' => '500',
                'suffix' => '+',
                'title' => __('Projects Completed', 'aqualuxe'),
            ),
            array(
                'number' => '25',
                'suffix' => '+',
                'title' => __('Team Members', 'aqualuxe'),
            ),
        ));
        
        if ($show_counters && !empty($counters)) :
        ?>
        <div class="about-counters">
            <div class="row">
                <?php foreach ($counters as $counter) : ?>
                    <?php if (!empty($counter['number']) && !empty($counter['title'])) : ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="counter-item">
                                <div class="counter-number">
                                    <span class="counter"><?php echo esc_html($counter['number']); ?></span>
                                    <?php if (!empty($counter['suffix'])) : ?>
                                        <span class="counter-suffix"><?php echo esc_html($counter['suffix']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="counter-title"><?php echo esc_html($counter['title']); ?></h3>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>