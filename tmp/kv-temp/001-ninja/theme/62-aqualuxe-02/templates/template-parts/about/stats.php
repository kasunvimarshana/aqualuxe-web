<?php
/**
 * About Page Stats Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get stats settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_about_stats_title', __( 'Our Impact', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_about_stats_subtitle', __( 'Numbers that speak for themselves', 'aqualuxe' ) );
$background_color = get_theme_mod( 'aqualuxe_about_stats_background', 'dark' );
$background_image = get_theme_mod( 'aqualuxe_about_stats_background_image', '' );
$columns = get_theme_mod( 'aqualuxe_about_stats_columns', 4 );

// Default stats if not set in customizer
$default_stats = array(
    array(
        'number' => '10,000+',
        'label'  => __( 'Happy Customers', 'aqualuxe' ),
        'icon'   => 'users',
    ),
    array(
        'number' => '50+',
        'label'  => __( 'Countries Served', 'aqualuxe' ),
        'icon'   => 'globe',
    ),
    array(
        'number' => '5+',
        'label'  => __( 'Years of Excellence', 'aqualuxe' ),
        'icon'   => 'calendar',
    ),
    array(
        'number' => '24/7',
        'label'  => __( 'Customer Support', 'aqualuxe' ),
        'icon'   => 'headset',
    ),
);

// Get stats from customizer or use defaults
$stats = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $stat_number = get_theme_mod( 'aqualuxe_about_stat_' . $i . '_number', $default_stats[$i-1]['number'] );
    $stat_label = get_theme_mod( 'aqualuxe_about_stat_' . $i . '_label', $default_stats[$i-1]['label'] );
    $stat_icon = get_theme_mod( 'aqualuxe_about_stat_' . $i . '_icon', $default_stats[$i-1]['icon'] );
    
    if ( $stat_number && $stat_label ) {
        $stats[] = array(
            'number' => $stat_number,
            'label'  => $stat_label,
            'icon'   => $stat_icon,
        );
    }
}

// Skip if no stats
if ( empty( $stats ) ) {
    return;
}

// Section classes
$section_classes = array( 'stats-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}

if ( $background_image ) {
    $section_classes[] = 'has-background-image';
}

// Section style
$section_style = '';
if ( $background_image ) {
    $section_style = 'background-image: url(' . esc_url( $background_image ) . ');';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php if ( $section_style ) echo 'style="' . esc_attr( $section_style ) . '"'; ?>>
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
        
        <div class="stats-grid columns-<?php echo esc_attr( $columns ); ?>">
            <?php foreach ( $stats as $stat ) : ?>
                <div class="stat-item">
                    <?php if ( $stat['icon'] ) : ?>
                        <div class="stat-icon">
                            <i class="icon-<?php echo esc_attr( $stat['icon'] ); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="stat-number" data-count="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $stat['number'] ) ); ?>">
                        <?php echo esc_html( $stat['number'] ); ?>
                    </div>
                    
                    <div class="stat-label">
                        <?php echo esc_html( $stat['label'] ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>