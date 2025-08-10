<?php
/**
 * About Hero Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'hero_title' => __( 'About AquaLuxe', 'aqualuxe' ),
    'hero_subtitle' => __( 'Learn about our company, mission, and team', 'aqualuxe' ),
    'hero_image' => get_template_directory_uri() . '/assets/images/about-hero-background.jpg',
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['hero_title'];
$subtitle = $args['hero_subtitle'];
$image = $args['hero_image'];

// Set default image if empty
if ( empty( $image ) ) {
    $image = get_template_directory_uri() . '/assets/images/about-hero-background.jpg';
}
?>

<section class="aqualuxe-hero aqualuxe-about-hero" style="background-image: url('<?php echo esc_url( $image ); ?>');">
    <div class="aqualuxe-container">
        <div class="aqualuxe-hero-content">
            <?php if ( ! empty( $title ) ) : ?>
                <h1 class="aqualuxe-hero-title"><?php echo esc_html( $title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-hero-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>