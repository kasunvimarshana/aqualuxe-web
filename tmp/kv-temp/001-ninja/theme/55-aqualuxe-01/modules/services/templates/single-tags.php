<?php
/**
 * Single service tags template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get service tags
$tags = isset($args['tags']) ? $args['tags'] : [];

// Check if we have tags
if (!empty($tags)) :
?>
<div class="aqualuxe-single-service-meta-item aqualuxe-single-service-meta-tags">
    <div class="aqualuxe-single-service-meta-label"><?php esc_html_e('Tags', 'aqualuxe'); ?></div>
    <div class="aqualuxe-single-service-meta-value">
        <?php
        $tag_links = [];
        foreach ($tags as $tag) {
            $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
        }
        echo implode(', ', $tag_links);
        ?>
    </div>
</div>
<?php endif; ?>