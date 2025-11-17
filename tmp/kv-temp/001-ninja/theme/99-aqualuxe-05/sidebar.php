<?php
/**
 * Sidebar template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="sidebar lg:w-1/4 mt-8 lg:mt-0 lg:ml-8" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'aqualuxe' ); ?>">
    <div class="sidebar-content space-y-6">
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    </div>
</aside>