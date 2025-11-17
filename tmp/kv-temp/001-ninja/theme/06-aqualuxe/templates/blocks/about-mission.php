<?php
/**
 * About Mission Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'mission_title' => __( 'Our Mission & Values', 'aqualuxe' ),
    'mission_statement' => __( 'Our mission is to provide the highest quality ornamental fish and aquarium supplies while promoting sustainable aquaculture practices and educating our customers on proper fish care.', 'aqualuxe' ),
    'values' => array(
        array(
            'title' => __( 'Quality', 'aqualuxe' ),
            'description' => __( 'We are committed to providing the highest quality fish and products to our customers.', 'aqualuxe' ),
            'icon' => 'award',
        ),
        array(
            'title' => __( 'Sustainability', 'aqualuxe' ),
            'description' => __( 'We practice and promote sustainable aquaculture to protect our natural resources.', 'aqualuxe' ),
            'icon' => 'leaf',
        ),
        array(
            'title' => __( 'Education', 'aqualuxe' ),
            'description' => __( 'We believe in educating our customers on proper fish care and aquarium maintenance.', 'aqualuxe' ),
            'icon' => 'book',
        ),
        array(
            'title' => __( 'Innovation', 'aqualuxe' ),
            'description' => __( 'We continuously seek new and better ways to breed, raise, and care for ornamental fish.', 'aqualuxe' ),
            'icon' => 'lightbulb',
        ),
    ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['mission_title'];
$mission_statement = $args['mission_statement'];
$values = $args['values'];

// Ensure we have values
if ( empty( $values ) ) {
    $values = $defaults['values'];
}

// Get icon HTML
function aqualuxe_get_icon_html( $icon ) {
    switch ( $icon ) {
        case 'award':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>';
        case 'leaf':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 4 13c0-5 7-11 9-11s9 6 9 11a7 7 0 0 1-7 7z"></path><path d="M12 20v-9"></path><path d="M8 15h8"></path></svg>';
        case 'book':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>';
        case 'lightbulb':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M12 2v4"></path><path d="M12 14v4"></path><path d="M12 6a6 6 0 0 0-6 6c0 2 1 3 2 4h8c1-1 2-2 2-4a6 6 0 0 0-6-6z"></path></svg>';
        case 'heart':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
        case 'globe':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>';
        case 'users':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>';
        case 'shield':
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>';
        default:
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg>';
    }
}
?>

<section class="aqualuxe-about-mission">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $mission_statement ) ) : ?>
            <div class="aqualuxe-mission-statement">
                <p><?php echo esc_html( $mission_statement ); ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $values ) ) : ?>
            <div class="aqualuxe-values-grid">
                <?php foreach ( $values as $value ) : ?>
                    <div class="aqualuxe-value-card">
                        <div class="aqualuxe-value-icon">
                            <?php echo aqualuxe_get_icon_html( $value['icon'] ); ?>
                        </div>
                        <h3 class="aqualuxe-value-title"><?php echo esc_html( $value['title'] ); ?></h3>
                        <p class="aqualuxe-value-description"><?php echo esc_html( $value['description'] ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>