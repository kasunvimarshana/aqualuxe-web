<?php
/**
 * Template part for displaying the about section on the homepage
 *
 * @package AquaLuxe
 */

// Get about section settings from customizer
$about_title = get_theme_mod('aqualuxe_about_title', __('About AquaLuxe', 'aqualuxe'));
$about_description = get_theme_mod('aqualuxe_about_description', __('AquaLuxe is a premium ornamental fish farming business dedicated to providing high-quality aquatic life and supplies for hobbyists and professionals alike. With over 20 years of experience, we specialize in breeding rare and exotic fish species while maintaining the highest standards of care and sustainability.', 'aqualuxe'));
$about_image = get_theme_mod('aqualuxe_about_image', '');
$about_button_text = get_theme_mod('aqualuxe_about_button_text', __('Learn More About Us', 'aqualuxe'));
$about_button_url = get_theme_mod('aqualuxe_about_button_url', '#');
$about_enable = get_theme_mod('aqualuxe_about_enable', true);

// Exit if about section is disabled
if (!$about_enable) {
    return;
}

// Get the about page URL if not specified
if (empty($about_button_url) || $about_button_url === '#') {
    $about_page = get_page_by_title('About Us');
    if ($about_page) {
        $about_button_url = get_permalink($about_page->ID);
    }
}
?>

<section class="about">
    <div class="container">
        <div class="about-wrapper">
            <?php if ($about_image) : ?>
                <div class="about-image">
                    <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>">
                </div>
            <?php endif; ?>
            
            <div class="about-content">
                <?php if ($about_title) : ?>
                    <h2 class="section-title"><?php echo esc_html($about_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($about_description) : ?>
                    <div class="about-description">
                        <?php echo wpautop(esc_html($about_description)); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($about_button_text && $about_button_url) : ?>
                    <a href="<?php echo esc_url($about_button_url); ?>" class="btn btn-primary"><?php echo esc_html($about_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>