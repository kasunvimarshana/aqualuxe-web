<?php
/**
 * Template part for displaying the footer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get footer layout
$footer_layout = aqualuxe_get_option( 'footer_layout', 'default' );

// Get footer widgets columns
$footer_widgets_columns = aqualuxe_get_option( 'footer_widgets_columns', '4' );

// Get footer copyright text
$footer_copyright = aqualuxe_get_option( 'footer_copyright', sprintf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ), date( 'Y' ), get_bloginfo( 'name' ) ) );

// Footer classes
$footer_classes = [
    'site-footer',
    'footer-layout-' . $footer_layout,
    'footer-widgets-columns-' . $footer_widgets_columns,
];

?>
<footer id="colophon" class="<?php echo esc_attr( implode( ' ', $footer_classes ) ); ?>" <?php aqualuxe_attr( 'footer' ); ?>>
    <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) : ?>
        <div class="footer-widgets">
            <div class="container">
                <div class="footer-widgets-inner">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-widget footer-widget-1">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-2' ) && $footer_widgets_columns >= 2 ) : ?>
                        <div class="footer-widget footer-widget-2">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-3' ) && $footer_widgets_columns >= 3 ) : ?>
                        <div class="footer-widget footer-widget-3">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-4' ) && $footer_widgets_columns >= 4 ) : ?>
                        <div class="footer-widget footer-widget-4">
                            <?php dynamic_sidebar( 'footer-4' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="footer-copyright">
                    <?php echo wp_kses_post( $footer_copyright ); ?>
                </div>
                
                <?php if ( has_nav_menu( 'footer' ) ) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(
                            [
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ]
                        );
                        ?>
                    </nav>
                <?php endif; ?>
                
                <div class="footer-social">
                    <?php aqualuxe_social_links(); ?>
                </div>
            </div>
        </div>
    </div>
</footer>