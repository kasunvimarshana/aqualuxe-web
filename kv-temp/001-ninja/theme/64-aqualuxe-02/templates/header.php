<?php
/**
 * Template part for displaying the header
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Check if top bar is enabled
$enable_top_bar = aqualuxe_get_option( 'enable_header_top_bar', true );

// Check if sticky header is enabled
$enable_sticky_header = aqualuxe_get_option( 'enable_sticky_header', true );

// Get header layout
$header_layout = aqualuxe_get_option( 'header_layout', 'default' );

// Header classes
$header_classes = [
    'site-header',
    'header-layout-' . $header_layout,
];

if ( $enable_sticky_header ) {
    $header_classes[] = 'sticky-header';
}

?>
<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>" <?php aqualuxe_attr( 'header' ); ?>>
    <?php if ( $enable_top_bar ) : ?>
        <div class="header-top-bar">
            <div class="container">
                <div class="header-top-bar-inner">
                    <div class="header-top-bar-left">
                        <?php aqualuxe_contact_info(); ?>
                    </div>
                    <div class="header-top-bar-right">
                        <?php if ( aqualuxe_is_module_active( 'multilingual' ) ) : ?>
                            <?php aqualuxe_language_switcher(); ?>
                        <?php endif; ?>
                        
                        <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                            <?php aqualuxe_currency_switcher(); ?>
                        <?php endif; ?>
                        
                        <?php if ( aqualuxe_is_module_active( 'dark-mode' ) ) : ?>
                            <?php aqualuxe_dark_mode_toggle(); ?>
                        <?php endif; ?>
                        
                        <?php aqualuxe_social_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="header-main">
        <div class="container">
            <div class="header-main-inner">
                <div class="site-branding">
                    <?php aqualuxe_logo(); ?>
                </div>
                
                <nav id="site-navigation" class="main-navigation" <?php aqualuxe_attr( 'navigation' ); ?>>
                    <?php
                    wp_nav_menu(
                        [
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                        ]
                    );
                    ?>
                </nav>
                
                <div class="header-actions">
                    <?php if ( aqualuxe_get_option( 'enable_header_search', true ) ) : ?>
                        <?php aqualuxe_search_toggle(); ?>
                    <?php endif; ?>
                    
                    <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                        <?php aqualuxe_header_account(); ?>
                        
                        <?php if ( aqualuxe_is_module_active( 'wishlist' ) ) : ?>
                            <?php aqualuxe_header_wishlist(); ?>
                        <?php endif; ?>
                        
                        <?php aqualuxe_header_cart(); ?>
                    <?php endif; ?>
                </div>
                
                <button class="mobile-menu-toggle" aria-controls="mobile-menu" aria-expanded="false">
                    <?php echo aqualuxe_get_icon( 'menu' ); ?>
                    <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
                </button>
            </div>
        </div>
    </div>
</header>