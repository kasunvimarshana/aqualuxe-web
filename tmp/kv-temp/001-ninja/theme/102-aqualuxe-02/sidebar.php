<?php
/**
 * The sidebar containing the main widget area
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar lg:w-1/3 lg:pl-8" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'aqualuxe' ); ?>">
    <div class="sidebar-content space-y-8">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </div>
</aside><!-- #secondary -->