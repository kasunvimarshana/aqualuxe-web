<?php
/**
 * Event Tickets Meta Box Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get event and module from template args
$event = $args['event'] ?? null;
$module = $args['module'] ?? null;

if ( ! $event || ! $module ) {
	return;
}

// Get tickets
$tickets = $event->get_tickets();
?>

<div class="aqualuxe-event-admin">
    <div class="aqualuxe-event-admin__tickets">
        <div class="aqualuxe-event-admin__tickets-header">
            <h3 class="aqualuxe-event-admin__tickets-title"><?php esc_html_e( 'Event Tickets', 'aqualuxe' ); ?></h3>
            <button type="button" class="button button-primary aqualuxe-event-admin__add-ticket-button">
                <span class="dashicons dashicons-plus"></span>
                <?php esc_html_e( 'Add Ticket', 'aqualuxe' ); ?>
            </button>
        </div>
        
        <div class="aqualuxe-event-admin__tickets-container">
            <div class="aqualuxe-event-admin__tickets-list" id="aqualuxe-tickets-list">
                <?php if ( ! empty( $tickets ) ) : ?>
                    <?php foreach ( $tickets as $index => $ticket ) : ?>
                        <div class="aqualuxe-event-admin__ticket" data-ticket-id="<?php echo esc_attr( $ticket->get_id() ); ?>">
                            <div class="aqualuxe-event-admin__ticket-header">
                                <div class="aqualuxe-event-admin__ticket-title">
                                    <span class="aqualuxe-event-admin__ticket-drag-handle dashicons dashicons-menu"></span>
                                    <span class="aqualuxe-event-admin__ticket-name"><?php echo esc_html( $ticket->get_name() ); ?></span>
                                </div>
                                <div class="aqualuxe-event-admin__ticket-actions">
                                    <button type="button" class="button aqualuxe-event-admin__ticket-toggle-button">
                                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                                    </button>
                                    <button type="button" class="button aqualuxe-event-admin__ticket-remove-button">
                                        <span class="dashicons dashicons-trash"></span>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="aqualuxe-event-admin__ticket-content">
                                <input type="hidden" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][id]" value="<?php echo esc_attr( $ticket->get_id() ); ?>">
                                
                                <div class="aqualuxe-event-admin__field">
                                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Ticket Name', 'aqualuxe' ); ?></label>
                                    <input type="text" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][name]" value="<?php echo esc_attr( $ticket->get_name() ); ?>" class="aqualuxe-event-admin__input" required>
                                </div>
                                
                                <div class="aqualuxe-event-admin__field">
                                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
                                    <textarea name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][description]" class="aqualuxe-event-admin__textarea"><?php echo esc_textarea( $ticket->get_description() ); ?></textarea>
                                </div>
                                
                                <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
                                    <div class="aqualuxe-event-admin__field-col">
                                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
                                        <input type="number" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][price]" value="<?php echo esc_attr( $ticket->get_price() ); ?>" min="0" step="0.01" class="aqualuxe-event-admin__input">
                                    </div>
                                    
                                    <div class="aqualuxe-event-admin__field-col">
                                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
                                        <input type="number" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][capacity]" value="<?php echo esc_attr( $ticket->get_capacity() ); ?>" min="0" step="1" class="aqualuxe-event-admin__input">
                                        <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Set to 0 for unlimited capacity', 'aqualuxe' ); ?></p>
                                    </div>
                                </div>
                                
                                <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
                                    <div class="aqualuxe-event-admin__field-col">
                                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Sale Start Date', 'aqualuxe' ); ?></label>
                                        <input type="date" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][start_date]" value="<?php echo esc_attr( $ticket->get_start_date() ); ?>" class="aqualuxe-event-admin__input">
                                    </div>
                                    
                                    <div class="aqualuxe-event-admin__field-col">
                                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Sale End Date', 'aqualuxe' ); ?></label>
                                        <input type="date" name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][end_date]" value="<?php echo esc_attr( $ticket->get_end_date() ); ?>" class="aqualuxe-event-admin__input">
                                    </div>
                                </div>
                                
                                <div class="aqualuxe-event-admin__field">
                                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
                                    <select name="aqualuxe_tickets[<?php echo esc_attr( $index ); ?>][status]" class="aqualuxe-event-admin__select">
                                        <option value="available" <?php selected( $ticket->get_status(), 'available' ); ?>><?php esc_html_e( 'Available', 'aqualuxe' ); ?></option>
                                        <option value="sold_out" <?php selected( $ticket->get_status(), 'sold_out' ); ?>><?php esc_html_e( 'Sold Out', 'aqualuxe' ); ?></option>
                                        <option value="unavailable" <?php selected( $ticket->get_status(), 'unavailable' ); ?>><?php esc_html_e( 'Unavailable', 'aqualuxe' ); ?></option>
                                    </select>
                                </div>
                                
                                <?php if ( $ticket->exists() ) : ?>
                                    <div class="aqualuxe-event-admin__ticket-stats">
                                        <div class="aqualuxe-event-admin__ticket-stat">
                                            <span class="aqualuxe-event-admin__ticket-stat-label"><?php esc_html_e( 'Sold:', 'aqualuxe' ); ?></span>
                                            <span class="aqualuxe-event-admin__ticket-stat-value"><?php echo esc_html( $ticket->get_sold_count() ); ?></span>
                                        </div>
                                        
                                        <div class="aqualuxe-event-admin__ticket-stat">
                                            <span class="aqualuxe-event-admin__ticket-stat-label"><?php esc_html_e( 'Available:', 'aqualuxe' ); ?></span>
                                            <span class="aqualuxe-event-admin__ticket-stat-value">
                                                <?php
                                                $available = $ticket->get_available_count();
                                                if ( $available === -1 ) {
                                                    esc_html_e( 'Unlimited', 'aqualuxe' );
                                                } else {
                                                    echo esc_html( $available );
                                                }
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="aqualuxe-event-admin__no-tickets">
                        <p><?php esc_html_e( 'No tickets have been created for this event yet.', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script type="text/template" id="aqualuxe-ticket-template">
        <div class="aqualuxe-event-admin__ticket" data-ticket-id="">
            <div class="aqualuxe-event-admin__ticket-header">
                <div class="aqualuxe-event-admin__ticket-title">
                    <span class="aqualuxe-event-admin__ticket-drag-handle dashicons dashicons-menu"></span>
                    <span class="aqualuxe-event-admin__ticket-name"><?php esc_html_e( 'New Ticket', 'aqualuxe' ); ?></span>
                </div>
                <div class="aqualuxe-event-admin__ticket-actions">
                    <button type="button" class="button aqualuxe-event-admin__ticket-toggle-button">
                        <span class="dashicons dashicons-arrow-down-alt2"></span>
                    </button>
                    <button type="button" class="button aqualuxe-event-admin__ticket-remove-button">
                        <span class="dashicons dashicons-trash"></span>
                    </button>
                </div>
            </div>
            
            <div class="aqualuxe-event-admin__ticket-content">
                <input type="hidden" name="aqualuxe_tickets[{index}][id]" value="0">
                
                <div class="aqualuxe-event-admin__field">
                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Ticket Name', 'aqualuxe' ); ?></label>
                    <input type="text" name="aqualuxe_tickets[{index}][name]" value="" class="aqualuxe-event-admin__input" required>
                </div>
                
                <div class="aqualuxe-event-admin__field">
                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
                    <textarea name="aqualuxe_tickets[{index}][description]" class="aqualuxe-event-admin__textarea"></textarea>
                </div>
                
                <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
                    <div class="aqualuxe-event-admin__field-col">
                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></label>
                        <input type="number" name="aqualuxe_tickets[{index}][price]" value="0" min="0" step="0.01" class="aqualuxe-event-admin__input">
                    </div>
                    
                    <div class="aqualuxe-event-admin__field-col">
                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Capacity', 'aqualuxe' ); ?></label>
                        <input type="number" name="aqualuxe_tickets[{index}][capacity]" value="0" min="0" step="1" class="aqualuxe-event-admin__input">
                        <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Set to 0 for unlimited capacity', 'aqualuxe' ); ?></p>
                    </div>
                </div>
                
                <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
                    <div class="aqualuxe-event-admin__field-col">
                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Sale Start Date', 'aqualuxe' ); ?></label>
                        <input type="date" name="aqualuxe_tickets[{index}][start_date]" value="" class="aqualuxe-event-admin__input">
                    </div>
                    
                    <div class="aqualuxe-event-admin__field-col">
                        <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Sale End Date', 'aqualuxe' ); ?></label>
                        <input type="date" name="aqualuxe_tickets[{index}][end_date]" value="" class="aqualuxe-event-admin__input">
                    </div>
                </div>
                
                <div class="aqualuxe-event-admin__field">
                    <label class="aqualuxe-event-admin__label"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></label>
                    <select name="aqualuxe_tickets[{index}][status]" class="aqualuxe-event-admin__select">
                        <option value="available"><?php esc_html_e( 'Available', 'aqualuxe' ); ?></option>
                        <option value="sold_out"><?php esc_html_e( 'Sold Out', 'aqualuxe' ); ?></option>
                        <option value="unavailable"><?php esc_html_e( 'Unavailable', 'aqualuxe' ); ?></option>
                    </select>
                </div>
            </div>
        </div>
    </script>
    
    <script>
        jQuery(document).ready(function($) {
            var ticketTemplate = $('#aqualuxe-ticket-template').html();
            var ticketIndex = <?php echo count( $tickets ); ?>;
            
            // Add ticket
            $('.aqualuxe-event-admin__add-ticket-button').on('click', function(e) {
                e.preventDefault();
                
                var newTicket = ticketTemplate.replace(/{index}/g, ticketIndex);
                $('#aqualuxe-tickets-list').append(newTicket);
                
                ticketIndex++;
                
                // Remove no tickets message if it exists
                $('.aqualuxe-event-admin__no-tickets').remove();
            });
            
            // Remove ticket
            $(document).on('click', '.aqualuxe-event-admin__ticket-remove-button', function(e) {
                e.preventDefault();
                
                if (confirm('<?php esc_html_e( 'Are you sure you want to remove this ticket?', 'aqualuxe' ); ?>')) {
                    $(this).closest('.aqualuxe-event-admin__ticket').remove();
                    
                    // Show no tickets message if no tickets left
                    if ($('.aqualuxe-event-admin__ticket').length === 0) {
                        $('#aqualuxe-tickets-list').append('<div class="aqualuxe-event-admin__no-tickets"><p><?php esc_html_e( 'No tickets have been created for this event yet.', 'aqualuxe' ); ?></p></div>');
                    }
                }
            });
            
            // Toggle ticket content
            $(document).on('click', '.aqualuxe-event-admin__ticket-toggle-button', function(e) {
                e.preventDefault();
                
                var $ticket = $(this).closest('.aqualuxe-event-admin__ticket');
                var $content = $ticket.find('.aqualuxe-event-admin__ticket-content');
                var $icon = $(this).find('.dashicons');
                
                $content.slideToggle(200);
                
                if ($icon.hasClass('dashicons-arrow-down-alt2')) {
                    $icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
                } else {
                    $icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
                }
            });
            
            // Make tickets sortable
            if ($.fn.sortable) {
                $('#aqualuxe-tickets-list').sortable({
                    handle: '.aqualuxe-event-admin__ticket-drag-handle',
                    axis: 'y',
                    opacity: 0.7,
                    update: function(event, ui) {
                        // Update ticket indices after sorting
                        $('.aqualuxe-event-admin__ticket').each(function(index) {
                            var $ticket = $(this);
                            var ticketId = $ticket.data('ticket-id');
                            
                            $ticket.find('input[name^="aqualuxe_tickets["]').each(function() {
                                var name = $(this).attr('name');
                                var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                                $(this).attr('name', newName);
                            });
                            
                            $ticket.find('textarea[name^="aqualuxe_tickets["]').each(function() {
                                var name = $(this).attr('name');
                                var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                                $(this).attr('name', newName);
                            });
                            
                            $ticket.find('select[name^="aqualuxe_tickets["]').each(function() {
                                var name = $(this).attr('name');
                                var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                                $(this).attr('name', newName);
                            });
                        });
                    }
                });
            }
        });
    </script>
</div>