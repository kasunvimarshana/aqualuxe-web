<?php
/**
 * About Page Our Story Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get our story settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_story_title', __( 'Our Story', 'aqualuxe' ) );
$section_content = get_theme_mod( 'aqualuxe_about_story_content', __( 'AquaLuxe was founded in 2020 with a vision to create a premium WordPress theme that combines elegant design with powerful e-commerce capabilities. Our journey began when a team of passionate developers and designers came together with a shared goal: to simplify online selling while maintaining the highest standards of performance and user experience.

Over the years, we\'ve grown from a small startup to a recognized name in the WordPress theme industry, serving thousands of businesses worldwide. Our commitment to quality, innovation, and customer satisfaction has remained unwavering throughout our journey.

Today, AquaLuxe continues to evolve, incorporating the latest web technologies and e-commerce trends to provide our customers with a cutting-edge platform for their online businesses.', 'aqualuxe' ) );
$section_image = get_theme_mod( 'aqualuxe_about_story_image', get_template_directory_uri() . '/assets/images/about/our-story.jpg' );
$section_video = get_theme_mod( 'aqualuxe_about_story_video', '' );
$section_layout = get_theme_mod( 'aqualuxe_about_story_layout', 'image-right' );

// Section classes
$section_classes = array( 'our-story-section', 'section' );
if ( $section_layout === 'image-right' ) {
    $section_classes[] = 'image-right';
} else {
    $section_classes[] = 'image-left';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 <?php echo $section_layout === 'image-right' ? 'order-lg-1' : ''; ?>">
                <div class="story-content">
                    <?php if ( $section_title ) : ?>
                        <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $section_content ) : ?>
                        <div class="section-text">
                            <?php echo wp_kses_post( wpautop( $section_content ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-lg-6 <?php echo $section_layout === 'image-right' ? '' : 'order-lg-1'; ?>">
                <div class="story-media">
                    <?php if ( $section_video ) : ?>
                        <div class="story-video">
                            <?php echo wp_oembed_get( $section_video ); ?>
                        </div>
                    <?php elseif ( $section_image ) : ?>
                        <div class="story-image">
                            <img src="<?php echo esc_url( $section_image ); ?>" alt="<?php echo esc_attr( $section_title ); ?>">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>