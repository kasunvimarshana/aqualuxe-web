<?php
/**
 * About CTA Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'cta_title' => __( 'Want to Learn More?', 'aqualuxe' ),
    'cta_text' => __( 'Contact us today to learn more about our products and services. Our team is ready to help you create the perfect aquatic environment.', 'aqualuxe' ),
    'cta_button_text' => __( 'Contact Us', 'aqualuxe' ),
    'cta_button_url' => home_url( '/contact/' ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['cta_title'];
$text = $args['cta_text'];
$button_text = $args['cta_button_text'];
$button_url = $args['cta_button_url'];
?>

<section class="aqualuxe-about-cta">
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
                    <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-primary"><?php echo esc_html( $button_text ); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>