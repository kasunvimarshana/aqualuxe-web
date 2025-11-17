<?php
/**
 * Homepage About Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get about section settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_title', __( 'About Our Brand', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_about_content', __( 'AquaLuxe is a premium WordPress theme designed for modern e-commerce businesses. Our mission is to provide a seamless shopping experience with cutting-edge features and elegant design.', 'aqualuxe' ) );
$section_image = get_theme_mod( 'aqualuxe_about_image', get_template_directory_uri() . '/assets/images/about-image.jpg' );
$section_button_text = get_theme_mod( 'aqualuxe_about_button_text', __( 'Learn More', 'aqualuxe' ) );
$section_button_url = get_theme_mod( 'aqualuxe_about_button_url', '#' );
$section_layout = get_theme_mod( 'aqualuxe_about_layout', 'image-left' );

// Section classes
$section_classes = array( 'about-section', 'section' );
if ( $section_layout === 'image-right' ) {
    $section_classes[] = 'image-right';
} else {
    $section_classes[] = 'image-left';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="about-wrapper">
            <?php if ( $section_image ) : ?>
                <div class="about-image">
                    <img src="<?php echo esc_url( $section_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>">
                </div>
            <?php endif; ?>
            
            <div class="about-content">
                <?php if ( $section_title ) : ?>
                    <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( $section_content ) : ?>
                    <div class="section-text">
                        <?php echo wp_kses_post( wpautop( $section_content ) ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $section_button_text && $section_button_url ) : ?>
                    <div class="section-button">
                        <a href="<?php echo esc_url( $section_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $section_button_text ); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>