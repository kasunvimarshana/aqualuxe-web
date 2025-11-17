<?php
/**
 * Homepage Features Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get features settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_features_title', __( 'Why Choose Us', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_features_subtitle', __( 'Discover the benefits of shopping with us', 'aqualuxe' ) );
$columns = get_theme_mod( 'aqualuxe_features_columns', 3 );

// Default features if not set in customizer
$default_features = array(
    array(
        'icon'  => 'truck',
        'title' => __( 'Free Shipping', 'aqualuxe' ),
        'text'  => __( 'Free shipping on all orders over $50', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'shield',
        'title' => __( 'Secure Payments', 'aqualuxe' ),
        'text'  => __( 'All payments are processed securely', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'refresh',
        'title' => __( 'Easy Returns', 'aqualuxe' ),
        'text'  => __( '30-day money back guarantee', 'aqualuxe' ),
    ),
    array(
        'icon'  => 'headset',
        'title' => __( '24/7 Support', 'aqualuxe' ),
        'text'  => __( 'Our support team is always available', 'aqualuxe' ),
    ),
);

// Get features from customizer or use defaults
$features = array();
for ( $i = 1; $i <= 4; $i++ ) {
    $feature_icon = get_theme_mod( 'aqualuxe_feature_' . $i . '_icon', $default_features[$i-1]['icon'] );
    $feature_title = get_theme_mod( 'aqualuxe_feature_' . $i . '_title', $default_features[$i-1]['title'] );
    $feature_text = get_theme_mod( 'aqualuxe_feature_' . $i . '_text', $default_features[$i-1]['text'] );
    
    if ( $feature_title ) {
        $features[] = array(
            'icon'  => $feature_icon,
            'title' => $feature_title,
            'text'  => $feature_text,
        );
    }
}

// Skip if no features
if ( empty( $features ) ) {
    return;
}
?>

<section class="features-section section">
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
        
        <div class="features-grid columns-<?php echo esc_attr( $columns ); ?>">
            <?php foreach ( $features as $feature ) : ?>
                <div class="feature-item">
                    <?php if ( $feature['icon'] ) : ?>
                        <div class="feature-icon">
                            <i class="icon-<?php echo esc_attr( $feature['icon'] ); ?>"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="feature-content">
                        <?php if ( $feature['title'] ) : ?>
                            <h3 class="feature-title"><?php echo esc_html( $feature['title'] ); ?></h3>
                        <?php endif; ?>
                        
                        <?php if ( $feature['text'] ) : ?>
                            <div class="feature-text">
                                <p><?php echo wp_kses_post( $feature['text'] ); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>