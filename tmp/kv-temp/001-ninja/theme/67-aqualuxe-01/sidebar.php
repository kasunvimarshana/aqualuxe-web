<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! aqualuxe_has_sidebar() ) {
    return;
}

$sidebar_position = aqualuxe_get_sidebar_position();
$sidebar_classes = array(
    'widget-area',
    'sidebar',
    'sidebar-position-' . $sidebar_position,
);

// Determine which sidebar to display
$sidebar = 'sidebar-1'; // Default sidebar

if ( class_exists( 'WooCommerce' ) ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        $sidebar = 'shop-sidebar';
    } elseif ( is_product() ) {
        $sidebar = 'product-sidebar';
    }
}

if ( is_home() && ! is_front_page() ) {
    $sidebar = 'home-sidebar';
}

do_action( 'aqualuxe_sidebar_before' );
?>

<aside id="secondary" class="<?php echo esc_attr( implode( ' ', $sidebar_classes ) ); ?>" role="complementary">
    <?php if ( is_active_sidebar( $sidebar ) ) : ?>
        <?php dynamic_sidebar( $sidebar ); ?>
    <?php else : ?>
        <div class="widget no-widgets">
            <h2 class="widget-title"><?php esc_html_e( 'Sidebar', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ); ?></p>
        </div>
    <?php endif; ?>
</aside><!-- #secondary -->

<?php
do_action( 'aqualuxe_sidebar_after' );