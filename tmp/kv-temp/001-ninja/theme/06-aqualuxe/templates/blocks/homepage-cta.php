<?php
/**
 * Homepage CTA Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'cta_title' => __( 'Ready to Transform Your Aquarium?', 'aqualuxe' ),
    'cta_text' => __( 'Discover our premium selection of ornamental fish and aquarium supplies. Elevate your aquatic experience today.', 'aqualuxe' ),
    'cta_button_text' => __( 'Shop Now', 'aqualuxe' ),
    'cta_button_url' => home_url( '/shop/' ),
    'cta_background' => get_template_directory_uri() . '/assets/images/cta-background.jpg',
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['cta_title'];
$text = $args['cta_text'];
$button_text = $args['cta_button_text'];
$button_url = $args['cta_button_url'];
$background = $args['cta_background'];

// Set default background if empty
if ( empty( $background ) ) {
    $background = get_template_directory_uri() . '/assets/images/cta-background.jpg';
}
?>

<section class="aqualuxe-cta" style="background-image: url('<?php echo esc_url( $background ); ?>');">
    <div class="aqualuxe-container">
        <div class="aqualuxe-cta-content">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-cta-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $text ) ) : ?>
                <div class="aqualuxe-cta-text"><?php echo wp_kses_post( $text ); ?></div>
            <?php endif; ?>
            
            <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
                <div class="aqualuxe-cta-action">
                    <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-primary aqualuxe-button-large"><?php echo esc_html( $button_text ); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>