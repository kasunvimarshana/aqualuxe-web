<?php
/**
 * Template part for displaying header bottom content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get header layout
$header_layout = aqualuxe_get_header_layout();

// Only display bottom header for certain layouts
if ( in_array( $header_layout, array( 'default', 'transparent' ) ) ) :
?>

<div class="header-bottom">
    <div class="<?php echo esc_attr( aqualuxe_get_container_class() ); ?>">
        <div class="header-bottom-inner">
            <?php
            // Display category menu for WooCommerce
            if ( class_exists( 'WooCommerce' ) && get_theme_mod( 'aqualuxe_header_category_menu', true ) ) :
                ?>
                <div class="header-category-menu">
                    <button class="category-menu-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                        <span><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></span>
                    </button>
                    
                    <div class="category-menu-dropdown">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'category',
                                'menu_id'        => 'category-menu',
                                'container'      => false,
                                'menu_class'     => 'category-menu',
                                'fallback_cb'    => function() {
                                    // Display product categories if no menu is assigned
                                    $args = array(
                                        'taxonomy'     => 'product_cat',
                                        'orderby'      => 'name',
                                        'show_count'   => true,
                                        'pad_counts'   => true,
                                        'hierarchical' => true,
                                        'title_li'     => '',
                                        'hide_empty'   => true,
                                    );
                                    echo '<ul class="category-menu">';
                                    wp_list_categories( $args );
                                    echo '</ul>';
                                },
                            )
                        );
                        ?>
                    </div>
                </div>
                <?php
            endif;
            
            // Display search form
            if ( get_theme_mod( 'aqualuxe_header_search', true ) ) :
                ?>
                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
                <?php
            endif;
            
            // Display contact information
            if ( get_theme_mod( 'aqualuxe_header_contact', true ) ) :
                $phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
                $email = get_theme_mod( 'aqualuxe_contact_email', '' );
                
                if ( ! empty( $phone ) || ! empty( $email ) ) :
                    ?>
                    <div class="header-contact">
                        <?php if ( ! empty( $phone ) ) : ?>
                            <div class="contact-phone">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $email ) ) : ?>
                            <div class="contact-email">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                endif;
            endif;
            ?>
        </div>
    </div>
</div>

<?php
endif;