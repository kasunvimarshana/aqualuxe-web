<?php
/**
 * Contact Page Map Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get map settings from customizer or use defaults
$map_title = get_theme_mod( 'aqualuxe_map_title', 'Find Us' );
$map_subtitle = get_theme_mod( 'aqualuxe_map_subtitle', 'Visit our facility in Coral Springs, Florida' );
$map_embed = get_theme_mod( 'aqualuxe_map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114496.84064482826!2d-80.32005226854492!3d26.27125590000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d906f3403c3d79%3A0x1e5e6c1f5a0c8d3a!2sCoral%20Springs%2C%20FL%2C%20USA!5e0!3m2!1sen!2s!4v1628000000000!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>' );
$map_directions_text = get_theme_mod( 'aqualuxe_map_directions_text', 'Our facility is located 5 miles west of I-95, just off Coral Ridge Drive. Look for the blue AquaLuxe sign at the entrance. Visitor parking is available in front of the main building.' );
?>

<section class="contact-map-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php echo esc_html( $map_title ); ?></h2>
            <div class="section-subtitle"><?php echo wp_kses_post( $map_subtitle ); ?></div>
        </div>
        
        <div class="map-container">
            <?php echo wp_kses( $map_embed, array(
                'iframe' => array(
                    'src'             => array(),
                    'width'           => array(),
                    'height'          => array(),
                    'frameborder'     => array(),
                    'style'           => array(),
                    'allowfullscreen' => array(),
                    'loading'         => array(),
                ),
            ) ); ?>
        </div>
        
        <div class="map-directions">
            <div class="directions-icon">
                <span class="icon-directions"></span>
            </div>
            <div class="directions-content">
                <h3><?php esc_html_e( 'Directions', 'aqualuxe' ); ?></h3>
                <p><?php echo esc_html( $map_directions_text ); ?></p>
                <a href="https://www.google.com/maps/dir//Coral+Springs,+FL,+USA/" class="btn btn-secondary" target="_blank" rel="noopener"><?php esc_html_e( 'Get Directions', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </div>
</section>