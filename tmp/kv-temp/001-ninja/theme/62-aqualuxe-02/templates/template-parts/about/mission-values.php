<?php
/**
 * About Page Mission & Values Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get mission & values settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_mission_title', __( 'Our Mission & Values', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_about_mission_subtitle', __( 'The principles that guide everything we do', 'aqualuxe' ) );
$mission_title = get_theme_mod( 'aqualuxe_about_mission_statement_title', __( 'Our Mission', 'aqualuxe' ) );
$mission_content = get_theme_mod( 'aqualuxe_about_mission_statement_content', __( 'To empower businesses with a powerful, flexible, and user-friendly e-commerce platform that helps them succeed in the digital marketplace.', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_about_mission_background', 'light' );

// Default values if not set in customizer
$default_values = array(
    array(
        'icon'  => 'star',
        'title' => __( 'Excellence', 'aqualuxe' ),
        'text'  => __( 'We strive for excellence in everything we do, from code quality to customer support.', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'heart',
        'title' => __( 'Customer Focus', 'aqualuxe' ),
        'text'  => __( 'Our customers are at the heart of our business. We listen, learn, and evolve based on their needs.', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'lightbulb',
        'title' => __( 'Innovation', 'aqualuxe' ),
        'text'  => __( 'We embrace new technologies and ideas to continuously improve our products.', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'users',
        'title' => __( 'Collaboration', 'aqualuxe' ),
        'text'  => __( 'We believe in the power of teamwork and collaboration to achieve great results.', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'shield',
        'title' => __( 'Integrity', 'aqualuxe' ),
        'text'  => __( 'We operate with honesty, transparency, and ethical business practices.', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'globe',
        'title' => __( 'Sustainability', 'aqualuxe' ),
        'text'  => __( 'We are committed to environmentally sustainable practices in our operations.', 'aqualuxe' ),
    ),
);

// Get values from customizer or use defaults
$values = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $value_icon = get_theme_mod( 'aqualuxe_about_value_' . $i . '_icon', $default_values[$i-1]['icon'] );
    $value_title = get_theme_mod( 'aqualuxe_about_value_' . $i . '_title', $default_values[$i-1]['title'] );
    $value_text = get_theme_mod( 'aqualuxe_about_value_' . $i . '_text', $default_values[$i-1]['text'] );
    
    if ( $value_title ) {
        $values[] = array(
            'icon'  => $value_icon,
            'title' => $value_title,
            'text'  => $value_text,
        );
    }
}

// Section classes
$section_classes = array( 'mission-values-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ( $mission_title && $mission_content ) : ?>
            <div class="mission-statement text-center">
                <h3 class="mission-title"><?php echo esc_html( $mission_title ); ?></h3>
                <div class="mission-content">
                    <p><?php echo wp_kses_post( $mission_content ); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $values ) ) : ?>
            <div class="values-grid">
                <div class="row">
                    <?php foreach ( $values as $value ) : ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="value-item">
                                <?php if ( $value['icon'] ) : ?>
                                    <div class="value-icon">
                                        <i class="icon-<?php echo esc_attr( $value['icon'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="value-content">
                                    <?php if ( $value['title'] ) : ?>
                                        <h4 class="value-title"><?php echo esc_html( $value['title'] ); ?></h4>
                                    <?php endif; ?>
                                    
                                    <?php if ( $value['text'] ) : ?>
                                        <div class="value-text">
                                            <p><?php echo wp_kses_post( $value['text'] ); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>