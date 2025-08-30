<?php
/**
 * Single service navigation template part
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get previous and next services
$prev_service = get_previous_post();
$next_service = get_next_post();

// Check if we have previous or next services
if ($prev_service || $next_service) :
?>
<nav class="aqualuxe-single-service-navigation">
    <?php if ($prev_service) : ?>
        <div class="aqualuxe-single-service-navigation-prev">
            <a href="<?php echo esc_url(get_permalink($prev_service)); ?>" rel="prev">
                <span class="aqualuxe-single-service-navigation-label"><?php esc_html_e('Previous Service', 'aqualuxe'); ?></span>
                <span class="aqualuxe-single-service-navigation-title"><?php echo esc_html(get_the_title($prev_service)); ?></span>
            </a>
        </div>
    <?php endif; ?>

    <?php if ($next_service) : ?>
        <div class="aqualuxe-single-service-navigation-next">
            <a href="<?php echo esc_url(get_permalink($next_service)); ?>" rel="next">
                <span class="aqualuxe-single-service-navigation-label"><?php esc_html_e('Next Service', 'aqualuxe'); ?></span>
                <span class="aqualuxe-single-service-navigation-title"><?php echo esc_html(get_the_title($next_service)); ?></span>
            </a>
        </div>
    <?php endif; ?>
</nav>
<?php endif; ?>