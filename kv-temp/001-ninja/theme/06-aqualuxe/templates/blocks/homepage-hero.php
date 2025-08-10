<?php
/**
 * Homepage Hero Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'hero_title' => __( 'Welcome to AquaLuxe', 'aqualuxe' ),
    'hero_subtitle' => __( 'Premium Ornamental Fish for Your Aquarium', 'aqualuxe' ),
    'hero_image' => get_template_directory_uri() . '/assets/images/hero-background.jpg',
    'hero_button_text' => __( 'Shop Now', 'aqualuxe' ),
    'hero_button_url' => home_url( '/shop/' ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['hero_title'];
$subtitle = $args['hero_subtitle'];
$image = $args['hero_image'];
$button_text = $args['hero_button_text'];
$button_url = $args['hero_button_url'];

// Set default image if empty
if ( empty( $image ) ) {
    $image = get_template_directory_uri() . '/assets/images/hero-background.jpg';
}
?>

<section class="aqualuxe-hero" style="background-image: url('<?php echo esc_url( $image ); ?>');">
    <div class="aqualuxe-container">
        <div class="aqualuxe-hero-content">
            <?php if ( ! empty( $title ) ) : ?>
                <h1 class="aqualuxe-hero-title"><?php echo esc_html( $title ); ?></h1>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-hero-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
            
            <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
                <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-primary"><?php echo esc_html( $button_text ); ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>