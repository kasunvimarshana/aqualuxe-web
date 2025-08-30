<?php
/**
 * Ticket Form Template
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$has_available_tickets = false;

foreach ($tickets as $ticket) {
    if ($ticket->is_available()) {
        $has_available_tickets = true;
        break;
    }
}
?>

<div id="tickets" class="aqualuxe-ticket-form">
    <h3 class="aqualuxe-ticket-form-title"><?php echo esc_html__('Purchase Tickets', 'aqualuxe'); ?></h3>
    
    <?php if ($has_available_tickets) : ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="aqualuxe-ticket-purchase-form">
            <input type="hidden" name="action" value="aqualuxe_purchase_tickets">
            <input type="hidden" name="event_id" value="<?php echo esc_attr($event->id); ?>">
            <?php wp_nonce_field('aqualuxe_purchase_tickets', 'aqualuxe_ticket_nonce'); ?>
            
            <div class="aqualuxe-ticket-form-section aqualuxe-ticket-selection">
                <h4><?php echo esc_html__('Select Tickets', 'aqualuxe'); ?></h4>
                
                <div class="aqualuxe-tickets-list">
                    <?php foreach ($tickets as $ticket) : ?>
                        <?php aqualuxe_get_event_template_part('content', 'ticket', array('ticket' => $ticket)); ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="aqualuxe-ticket-form-section aqualuxe-ticket-attendee-info">
                <h4><?php echo esc_html__('Attendee Information', 'aqualuxe'); ?></h4>
                
                <div class="aqualuxe-form-row">
                    <div class="aqualuxe-form-field">
                        <label for="attendee_name"><?php echo esc_html__('Full Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="attendee_name" name="attendee_name" required>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="attendee_email"><?php echo esc_html__('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="email" id="attendee_email" name="attendee_email" required>
                    </div>
                </div>
                
                <div class="aqualuxe-form-row">
                    <div class="aqualuxe-form-field">
                        <label for="attendee_phone"><?php echo esc_html__('Phone', 'aqualuxe'); ?></label>
                        <input type="tel" id="attendee_phone" name="attendee_phone">
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="attendee_company"><?php echo esc_html__('Company', 'aqualuxe'); ?></label>
                        <input type="text" id="attendee_company" name="attendee_company">
                    </div>
                </div>
                
                <div class="aqualuxe-form-field">
                    <label for="attendee_notes"><?php echo esc_html__('Special Requirements', 'aqualuxe'); ?></label>
                    <textarea id="attendee_notes" name="attendee_notes" rows="3"></textarea>
                </div>
            </div>
            
            <div class="aqualuxe-ticket-form-section aqualuxe-ticket-payment">
                <h4><?php echo esc_html__('Payment Information', 'aqualuxe'); ?></h4>
                
                <div class="aqualuxe-form-row">
                    <div class="aqualuxe-form-field">
                        <label for="card_name"><?php echo esc_html__('Name on Card', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="card_name" name="card_name" required>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="card_number"><?php echo esc_html__('Card Number', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="card_number" name="card_number" placeholder="•••• •••• •••• ••••" required>
                    </div>
                </div>
                
                <div class="aqualuxe-form-row">
                    <div class="aqualuxe-form-field">
                        <label for="card_expiry"><?php echo esc_html__('Expiry Date', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="card_expiry" name="card_expiry" placeholder="MM/YY" required>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="card_cvc"><?php echo esc_html__('CVC', 'aqualuxe'); ?> <span class="required">*</span></label>
                        <input type="text" id="card_cvc" name="card_cvc" placeholder="•••" required>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-ticket-form-section aqualuxe-ticket-summary">
                <h4><?php echo esc_html__('Order Summary', 'aqualuxe'); ?></h4>
                
                <div class="aqualuxe-ticket-summary-table">
                    <div class="aqualuxe-ticket-summary-header">
                        <div class="aqualuxe-ticket-summary-ticket"><?php echo esc_html__('Ticket', 'aqualuxe'); ?></div>
                        <div class="aqualuxe-ticket-summary-price"><?php echo esc_html__('Price', 'aqualuxe'); ?></div>
                        <div class="aqualuxe-ticket-summary-quantity"><?php echo esc_html__('Quantity', 'aqualuxe'); ?></div>
                        <div class="aqualuxe-ticket-summary-total"><?php echo esc_html__('Total', 'aqualuxe'); ?></div>
                    </div>
                    
                    <div class="aqualuxe-ticket-summary-body">
                        <!-- This will be populated by JavaScript -->
                    </div>
                    
                    <div class="aqualuxe-ticket-summary-footer">
                        <div class="aqualuxe-ticket-summary-subtotal">
                            <span class="aqualuxe-ticket-summary-label"><?php echo esc_html__('Subtotal', 'aqualuxe'); ?></span>
                            <span class="aqualuxe-ticket-summary-value">0.00</span>
                        </div>
                        
                        <div class="aqualuxe-ticket-summary-tax">
                            <span class="aqualuxe-ticket-summary-label"><?php echo esc_html__('Tax', 'aqualuxe'); ?></span>
                            <span class="aqualuxe-ticket-summary-value">0.00</span>
                        </div>
                        
                        <div class="aqualuxe-ticket-summary-grand-total">
                            <span class="aqualuxe-ticket-summary-label"><?php echo esc_html__('Total', 'aqualuxe'); ?></span>
                            <span class="aqualuxe-ticket-summary-value">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-ticket-form-actions">
                <button type="submit" class="aqualuxe-button aqualuxe-button-primary aqualuxe-button-large aqualuxe-ticket-purchase-button">
                    <?php echo esc_html__('Complete Purchase', 'aqualuxe'); ?>
                </button>
            </div>
        </form>
        
        <script>
            jQuery(document).ready(function($) {
                // Get currency symbol
                var currencySymbol = '<?php echo esc_js(get_woocommerce_currency_symbol()); ?>';
                
                // Ticket data
                var tickets = <?php
                    $ticket_data = array();
                    foreach ($tickets as $ticket) {
                        $ticket_data[$ticket->id] = array(
                            'id' => $ticket->id,
                            'title' => $ticket->get_title(),
                            'price' => $ticket->is_on_sale() ? $ticket->get_sale_price(false) : $ticket->get_price(false),
                            'available' => $ticket->is_available(),
                        );
                    }
                    echo json_encode($ticket_data);
                ?>;
                
                // Update order summary when quantity changes
                $('.aqualuxe-ticket-quantity-select').on('change', function() {
                    updateOrderSummary();
                });
                
                // Format currency
                function formatCurrency(amount) {
                    return currencySymbol + parseFloat(amount).toFixed(2);
                }
                
                // Update order summary
                function updateOrderSummary() {
                    var summaryBody = $('.aqualuxe-ticket-summary-body');
                    var subtotal = 0;
                    
                    // Clear summary body
                    summaryBody.empty();
                    
                    // Add selected tickets to summary
                    $('.aqualuxe-ticket-quantity-select').each(function() {
                        var ticketId = $(this).attr('name').match(/\[(\d+)\]/)[1];
                        var quantity = parseInt($(this).val());
                        
                        if (quantity > 0 && tickets[ticketId]) {
                            var ticket = tickets[ticketId];
                            var price = parseFloat(ticket.price);
                            var total = price * quantity;
                            
                            // Add to subtotal
                            subtotal += total;
                            
                            // Add row to summary
                            summaryBody.append(
                                '<div class="aqualuxe-ticket-summary-row">' +
                                    '<div class="aqualuxe-ticket-summary-ticket">' + ticket.title + '</div>' +
                                    '<div class="aqualuxe-ticket-summary-price">' + formatCurrency(price) + '</div>' +
                                    '<div class="aqualuxe-ticket-summary-quantity">' + quantity + '</div>' +
                                    '<div class="aqualuxe-ticket-summary-total">' + formatCurrency(total) + '</div>' +
                                '</div>'
                            );
                        }
                    });
                    
                    // Calculate tax (10%)
                    var tax = subtotal * 0.1;
                    var grandTotal = subtotal + tax;
                    
                    // Update summary footer
                    $('.aqualuxe-ticket-summary-subtotal .aqualuxe-ticket-summary-value').text(formatCurrency(subtotal));
                    $('.aqualuxe-ticket-summary-tax .aqualuxe-ticket-summary-value').text(formatCurrency(tax));
                    $('.aqualuxe-ticket-summary-grand-total .aqualuxe-ticket-summary-value').text(formatCurrency(grandTotal));
                    
                    // Disable submit button if no tickets selected
                    if (subtotal === 0) {
                        $('.aqualuxe-ticket-purchase-button').prop('disabled', true);
                    } else {
                        $('.aqualuxe-ticket-purchase-button').prop('disabled', false);
                    }
                }
                
                // Initialize order summary
                updateOrderSummary();
            });
        </script>
    <?php else : ?>
        <div class="aqualuxe-ticket-form-no-tickets">
            <p><?php echo esc_html__('Sorry, tickets are not available for this event.', 'aqualuxe'); ?></p>
        </div>
    <?php endif; ?>
</div>