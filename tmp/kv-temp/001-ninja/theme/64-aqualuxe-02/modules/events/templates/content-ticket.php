<?php
/**
 * Ticket Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$is_available = $ticket->is_available();
$is_on_sale = $ticket->is_on_sale();
$remaining = $ticket->get_remaining();
$features = $ticket->get_features();
?>

<div class="aqualuxe-ticket <?php echo !$is_available ? 'aqualuxe-ticket-unavailable' : ''; ?>">
    <div class="aqualuxe-ticket-header">
        <h4 class="aqualuxe-ticket-title"><?php echo esc_html($ticket->get_title()); ?></h4>
        
        <div class="aqualuxe-ticket-price">
            <?php if ($is_on_sale) : ?>
                <span class="aqualuxe-ticket-sale-price"><?php echo esc_html($ticket->get_sale_price()); ?></span>
                <span class="aqualuxe-ticket-regular-price"><?php echo esc_html($ticket->get_regular_price()); ?></span>
            <?php else : ?>
                <span class="aqualuxe-ticket-regular-price"><?php echo esc_html($ticket->get_price()); ?></span>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($ticket->get_description()) : ?>
        <div class="aqualuxe-ticket-description">
            <?php echo wp_kses_post($ticket->get_description()); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($features)) : ?>
        <div class="aqualuxe-ticket-features">
            <ul class="aqualuxe-ticket-features-list">
                <?php foreach ($features as $feature) : ?>
                    <li class="aqualuxe-ticket-feature">
                        <i class="aqualuxe-icon aqualuxe-icon-check"></i>
                        <?php echo esc_html($feature); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="aqualuxe-ticket-footer">
        <?php if ($is_available) : ?>
            <?php if ($remaining > 0) : ?>
                <div class="aqualuxe-ticket-availability">
                    <?php echo esc_html(sprintf(
                        __('%d tickets remaining', 'aqualuxe'),
                        $remaining
                    )); ?>
                </div>
            <?php elseif ($remaining === -1) : ?>
                <div class="aqualuxe-ticket-availability">
                    <?php echo esc_html__('Unlimited tickets available', 'aqualuxe'); ?>
                </div>
            <?php endif; ?>
            
            <div class="aqualuxe-ticket-quantity">
                <label for="ticket-quantity-<?php echo esc_attr($ticket->id); ?>"><?php echo esc_html__('Quantity', 'aqualuxe'); ?></label>
                <select id="ticket-quantity-<?php echo esc_attr($ticket->id); ?>" name="ticket_quantity[<?php echo esc_attr($ticket->id); ?>]" class="aqualuxe-ticket-quantity-select">
                    <option value="0"><?php echo esc_html__('0', 'aqualuxe'); ?></option>
                    <?php
                    $min = $ticket->get_min_purchase();
                    $max = $ticket->get_max_purchase();
                    
                    if ($max === 0 || $remaining === -1) {
                        $max = 10; // Default max for unlimited tickets
                    } else {
                        $max = min($max, $remaining);
                    }
                    
                    for ($i = $min; $i <= $max; $i++) :
                    ?>
                        <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        <?php else : ?>
            <div class="aqualuxe-ticket-unavailable-message">
                <?php echo esc_html__('Tickets not available', 'aqualuxe'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>