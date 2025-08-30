<?php
/**
 * Template part for displaying the call-to-action section on the homepage
 *
 * @package AquaLuxe
 */

// Get customizer options
$cta_title = get_theme_mod( 'aqualuxe_cta_title', __( 'Ready to Get Started?', 'aqualuxe' ) );
$cta_subtitle = get_theme_mod( 'aqualuxe_cta_subtitle', __( 'Join thousands of satisfied customers using AquaLuxe for their websites.', 'aqualuxe' ) );
$cta_button_text = get_theme_mod( 'aqualuxe_cta_button_text', __( 'Get AquaLuxe Now', 'aqualuxe' ) );
$cta_button_url = get_theme_mod( 'aqualuxe_cta_button_url', '#' );
$cta_secondary_button_text = get_theme_mod( 'aqualuxe_cta_secondary_button_text', __( 'Learn More', 'aqualuxe' ) );
$cta_secondary_button_url = get_theme_mod( 'aqualuxe_cta_secondary_button_url', '#' );
$cta_background = get_theme_mod( 'aqualuxe_cta_background', 'primary' );
$cta_image = get_theme_mod( 'aqualuxe_cta_image', '' );
$cta_style = get_theme_mod( 'aqualuxe_cta_style', 'centered' );

// Set background class based on setting
$bg_class = 'bg-white dark:bg-gray-900';
if ( $cta_background === 'light-gray' ) {
    $bg_class = 'bg-gray-50 dark:bg-gray-800';
} elseif ( $cta_background === 'dark' ) {
    $bg_class = 'bg-gray-900 text-white';
} elseif ( $cta_background === 'primary' ) {
    $bg_class = 'bg-blue-600 text-white';
} elseif ( $cta_background === 'gradient' ) {
    $bg_class = 'bg-gradient-to-r from-blue-600 to-purple-600 text-white';
}

// Set layout class based on style
$layout_class = 'text-center';
if ( $cta_style === 'split' && $cta_image ) {
    $layout_class = 'md:flex md:items-center md:justify-between';
}

?>

<section id="cta" class="cta-section py-16 md:py-20 <?php echo esc_attr( $bg_class ); ?>">
    <div class="container mx-auto px-4">
        <div class="<?php echo esc_attr( $layout_class ); ?>">
            <?php if ( $cta_style === 'split' && $cta_image ) : ?>
                <div class="cta-content md:w-1/2 mb-8 md:mb-0 md:pr-8">
                    <?php if ( $cta_title ) : ?>
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $cta_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $cta_subtitle ) : ?>
                        <p class="text-xl opacity-90 mb-8"><?php echo esc_html( $cta_subtitle ); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-4">
                        <?php if ( $cta_button_text && $cta_button_url ) : ?>
                            <a href="<?php echo esc_url( $cta_button_url ); ?>" class="px-6 py-3 bg-white text-blue-600 hover:bg-gray-100 font-medium rounded-md transition-colors">
                                <?php echo esc_html( $cta_button_text ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $cta_secondary_button_text && $cta_secondary_button_url ) : ?>
                            <a href="<?php echo esc_url( $cta_secondary_button_url ); ?>" class="px-6 py-3 bg-transparent hover:bg-white/10 text-white border border-white font-medium rounded-md transition-colors">
                                <?php echo esc_html( $cta_secondary_button_text ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="cta-image md:w-1/2">
                    <img src="<?php echo esc_url( $cta_image ); ?>" alt="<?php echo esc_attr( $cta_title ); ?>" class="w-full h-auto rounded-lg shadow-lg" />
                </div>
            <?php else : ?>
                <div class="cta-content max-w-3xl mx-auto">
                    <?php if ( $cta_title ) : ?>
                        <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $cta_title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( $cta_subtitle ) : ?>
                        <p class="text-xl opacity-90 mb-8"><?php echo esc_html( $cta_subtitle ); ?></p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-4 justify-center">
                        <?php if ( $cta_button_text && $cta_button_url ) : ?>
                            <a href="<?php echo esc_url( $cta_button_url ); ?>" class="px-6 py-3 bg-white text-blue-600 hover:bg-gray-100 font-medium rounded-md transition-colors">
                                <?php echo esc_html( $cta_button_text ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $cta_secondary_button_text && $cta_secondary_button_url ) : ?>
                            <a href="<?php echo esc_url( $cta_secondary_button_url ); ?>" class="px-6 py-3 bg-transparent hover:bg-white/10 text-white border border-white font-medium rounded-md transition-colors">
                                <?php echo esc_html( $cta_secondary_button_text ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>