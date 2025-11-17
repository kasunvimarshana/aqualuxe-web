<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// Sidebar options
$sticky_sidebar = isset($options['enable_sticky_sidebar']) ? $options['enable_sticky_sidebar'] : true;

// Set sidebar class
$sidebar_class = 'sidebar-area';
if ($sticky_sidebar) {
    $sidebar_class .= ' sticky-sidebar';
}

// Check if the sidebar is active
if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="<?php echo esc_attr($sidebar_class); ?>">
    <div class="sidebar-inner">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </div>
</aside><!-- #secondary -->