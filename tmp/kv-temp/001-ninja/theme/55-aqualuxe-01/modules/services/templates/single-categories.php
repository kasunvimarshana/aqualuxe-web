<?php
/**
 * Single service categories template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service categories
$categories = isset($args['categories']) ? $args['categories'] : [];

// Check if we have categories
if (!empty($categories)) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-categories">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Categories', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <?php
        $category_links = [];
        foreach ($categories as $category) {
            $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
        }
        echo implode(', ', $category_links);
        ?>
    </div>
</div>
<?php endif; ?>