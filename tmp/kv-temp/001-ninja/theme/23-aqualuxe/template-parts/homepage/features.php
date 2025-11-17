<?php
/**
 * Template part for displaying the features section on the homepage
 *
 * @package AquaLuxe
 */

// Get customizer options
$features_title = get_theme_mod( 'aqualuxe_features_title', __( 'Key Features', 'aqualuxe' ) );
$features_subtitle = get_theme_mod( 'aqualuxe_features_subtitle', __( 'Discover what makes our theme stand out from the crowd', 'aqualuxe' ) );
$features_layout = get_theme_mod( 'aqualuxe_features_layout', 'grid' );
$features_columns = get_theme_mod( 'aqualuxe_features_columns', '3' );
$features_background = get_theme_mod( 'aqualuxe_features_background', 'light' );

// Set background class based on setting
$bg_class = 'bg-white dark:bg-gray-900';
if ( $features_background === 'light-gray' ) {
    $bg_class = 'bg-gray-50 dark:bg-gray-800';
} elseif ( $features_background === 'dark' ) {
    $bg_class = 'bg-gray-900 text-white';
} elseif ( $features_background === 'primary' ) {
    $bg_class = 'bg-blue-600 text-white';
}

// Set columns class based on setting
$columns_class = 'grid-cols-1 md:grid-cols-3';
if ( $features_columns === '2' ) {
    $columns_class = 'grid-cols-1 md:grid-cols-2';
} elseif ( $features_columns === '4' ) {
    $columns_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
}

// Default features if not set in customizer
$default_features = array(
    array(
        'icon' => 'desktop',
        'title' => __( 'Responsive Design', 'aqualuxe' ),
        'description' => __( 'Looks great on all devices, from mobile phones to widescreen monitors.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'paint-brush',
        'title' => __( 'Customizable', 'aqualuxe' ),
        'description' => __( 'Easily customize colors, layouts, and more to match your brand.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'shopping-cart',
        'title' => __( 'WooCommerce Ready', 'aqualuxe' ),
        'description' => __( 'Fully compatible with WooCommerce for your online store needs.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'bolt',
        'title' => __( 'Performance Optimized', 'aqualuxe' ),
        'description' => __( 'Built with performance in mind for fast loading times.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'mobile',
        'title' => __( 'Mobile First', 'aqualuxe' ),
        'description' => __( 'Designed with a mobile-first approach for the best experience.', 'aqualuxe' ),
    ),
    array(
        'icon' => 'code',
        'title' => __( 'Clean Code', 'aqualuxe' ),
        'description' => __( 'Written with best practices for easy customization.', 'aqualuxe' ),
    ),
);

// Get features from customizer or use defaults
$features = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $feature_enabled = get_theme_mod( 'aqualuxe_feature_' . $i . '_enabled', true );
    
    if ( $feature_enabled ) {
        $features[] = array(
            'icon' => get_theme_mod( 'aqualuxe_feature_' . $i . '_icon', $default_features[$i-1]['icon'] ),
            'title' => get_theme_mod( 'aqualuxe_feature_' . $i . '_title', $default_features[$i-1]['title'] ),
            'description' => get_theme_mod( 'aqualuxe_feature_' . $i . '_description', $default_features[$i-1]['description'] ),
        );
    }
}

?>

<section id="features" class="features-section py-16 md:py-24 <?php echo esc_attr( $bg_class ); ?>">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12 md:mb-16">
            <?php if ( $features_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $features_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $features_subtitle ) : ?>
                <p class="text-xl opacity-80 max-w-3xl mx-auto"><?php echo esc_html( $features_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $features ) ) : ?>
            <?php if ( $features_layout === 'grid' ) : ?>
                <div class="grid <?php echo esc_attr( $columns_class ); ?> gap-8">
                    <?php foreach ( $features as $feature ) : ?>
                        <div class="feature-item text-center p-6 rounded-lg transition-all hover:shadow-lg <?php echo $features_background === 'dark' || $features_background === 'primary' ? 'bg-white/10 hover:bg-white/20' : 'bg-white dark:bg-gray-800 shadow-sm'; ?>">
                            <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                <div class="feature-icon mx-auto mb-4 w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                    <i class="fas fa-<?php echo esc_attr( $feature['icon'] ); ?> text-2xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $feature['title'] ) ) : ?>
                                <h3 class="text-xl font-bold mb-3"><?php echo esc_html( $feature['title'] ); ?></h3>
                            <?php endif; ?>
                            
                            <?php if ( ! empty( $feature['description'] ) ) : ?>
                                <p class="opacity-80"><?php echo esc_html( $feature['description'] ); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="space-y-8">
                    <?php foreach ( $features as $index => $feature ) : ?>
                        <div class="feature-item flex flex-col md:flex-row items-center md:items-start gap-6 p-6 rounded-lg transition-all hover:shadow-lg <?php echo $features_background === 'dark' || $features_background === 'primary' ? 'bg-white/10 hover:bg-white/20' : 'bg-white dark:bg-gray-800 shadow-sm'; ?> <?php echo $index % 2 !== 0 ? 'md:flex-row-reverse' : ''; ?>">
                            <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                <div class="feature-icon shrink-0 mb-4 md:mb-0 w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                                    <i class="fas fa-<?php echo esc_attr( $feature['icon'] ); ?> text-2xl"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="feature-content text-center md:text-left <?php echo $index % 2 !== 0 ? 'md:text-right' : ''; ?>">
                                <?php if ( ! empty( $feature['title'] ) ) : ?>
                                    <h3 class="text-xl font-bold mb-3"><?php echo esc_html( $feature['title'] ); ?></h3>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $feature['description'] ) ) : ?>
                                    <p class="opacity-80"><?php echo esc_html( $feature['description'] ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>