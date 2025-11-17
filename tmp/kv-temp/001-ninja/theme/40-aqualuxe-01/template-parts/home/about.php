<?php
/**
 * Template part for displaying the about section on the front page
 *
 * @package AquaLuxe
 */

// Get section settings from customizer or default values
$section_title = get_theme_mod( 'aqualuxe_about_title', __( 'About AquaLuxe', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_about_content', __( 'AquaLuxe is a premium aquatic retailer specializing in rare and exotic fish species, high-quality aquarium equipment, and professional aquascaping services. With a commitment to quality, sustainability, and customer satisfaction, we bring elegance to aquatic life globally.', 'aqualuxe' ) );
$image_id = get_theme_mod( 'aqualuxe_about_image', '' );
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : get_template_directory_uri() . '/assets/dist/images/about-default.jpg';
$button_text = get_theme_mod( 'aqualuxe_about_button_text', __( 'Learn More', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_about_button_url', home_url( '/about' ) );
$layout = get_theme_mod( 'aqualuxe_about_layout', 'image-left' );

// Section classes
$section_classes = array(
    'section',
    'about-section',
    'about-layout-' . $layout,
);

// Check if we should show the section
$show_section = get_theme_mod( 'aqualuxe_show_about', true );

if ( ! $show_section ) {
    return;
}

// Get features
$features = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $feature_title = get_theme_mod( 'aqualuxe_about_feature_' . $i . '_title', '' );
    $feature_description = get_theme_mod( 'aqualuxe_about_feature_' . $i . '_description', '' );
    $feature_icon = get_theme_mod( 'aqualuxe_about_feature_' . $i . '_icon', '' );
    
    if ( $feature_title ) {
        $features[] = array(
            'title' => $feature_title,
            'description' => $feature_description,
            'icon' => $feature_icon,
        );
    }
}

// Default features if none are set
if ( empty( $features ) ) {
    $features = array(
        array(
            'title' => __( 'Premium Quality', 'aqualuxe' ),
            'description' => __( 'We source only the highest quality aquatic specimens and products.', 'aqualuxe' ),
            'icon' => 'award',
        ),
        array(
            'title' => __( 'Expert Advice', 'aqualuxe' ),
            'description' => __( 'Our team of aquatic specialists provides personalized guidance.', 'aqualuxe' ),
            'icon' => 'user',
        ),
        array(
            'title' => __( 'Global Shipping', 'aqualuxe' ),
            'description' => __( 'We deliver our premium products to collectors worldwide.', 'aqualuxe' ),
            'icon' => 'globe',
        ),
        array(
            'title' => __( 'Sustainability', 'aqualuxe' ),
            'description' => __( 'Committed to ethical sourcing and environmental responsibility.', 'aqualuxe' ),
            'icon' => 'leaf',
        ),
    );
}
?>

<section id="about" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="about-inner">
            <div class="about-image">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $section_title ); ?>" class="about-img">
            </div>
            
            <div class="about-content">
                <?php if ( $section_title ) : ?>
                    <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( $section_content ) : ?>
                    <div class="about-description">
                        <?php echo wp_kses_post( $section_content ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $features ) ) : ?>
                    <div class="about-features">
                        <?php foreach ( $features as $feature ) : ?>
                            <div class="about-feature">
                                <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                    <div class="feature-icon">
                                        <i class="icon-<?php echo esc_attr( $feature['icon'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="feature-content">
                                    <h3 class="feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
                                    
                                    <?php if ( ! empty( $feature['description'] ) ) : ?>
                                        <div class="feature-description">
                                            <?php echo wp_kses_post( $feature['description'] ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $button_text && $button_url ) : ?>
                    <div class="about-button">
                        <a href="<?php echo esc_url( $button_url ); ?>" class="button"><?php echo esc_html( $button_text ); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>