<?php
/**
 * Event Registrations Meta Box Template
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

// Get registrations
$registrations = $event->get_registrations();

// Get registration statuses
$statuses = $module->get_registration_statuses();

// Get registered count
$registered_count = $event->get_registered_count();

// Get available capacity
$available_capacity = $event->get_available_capacity();

// Get capacity
$capacity = $event->get_capacity();
?>

<div class="aqualuxe-event-admin">
    <div class="aqualuxe-event-admin__registrations">
        <div class="aqualuxe-event-admin__registrations-header">
            <h3 class="aqualuxe-event-admin__registrations-title"><?php esc_html_e( 'Event Registrations', 'aqualuxe' ); ?></h3>
            
            <div class="aqualuxe-event-admin__registrations-stats">
                <div class="aqualuxe-event-admin__registrations-stat">
                    <span class="aqualuxe-event-admin__registrations-stat-label"><?php esc_html_e( 'Registered:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-event-admin__registrations-stat-value"><?php echo esc_html( $registered_count ); ?></span>
                </div>
                
                <div class="aqualuxe-event-admin__registrations-stat">
                    <span class="aqualuxe-event-admin__registrations-stat-label"><?php esc_html_e( 'Available:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-event-admin__registrations-stat-value">
                        <?php
                        if ( $available_capacity === -1 ) {
                            esc_html_e( 'Unlimited', 'aqualuxe' );
                        } else {
                            echo esc_html( $available_capacity );
                        }
                        ?>
                    </span>
                </div>
                
                <div class="aqualuxe-event-admin__registrations-stat">
                    <span class="aqualuxe-event-admin__registrations-stat-label"><?php esc_html_e( 'Capacity:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-event-admin__registrations-stat-value">
                        <?php
                        if ( $capacity === 0 ) {
                            esc_html_e( 'Unlimited', 'aqualuxe' );
                        } else {
                            echo esc_html( $capacity );
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
        
        <?php if ( ! empty( $registrations ) ) : ?>
            <div class="aqualuxe-event-admin__registrations-filters">
                <div class="aqualuxe-event-admin__registrations-filter">
                    <label for="aqualuxe-registrations-filter-status" class="aqualuxe-event-admin__registrations-filter-label"><?php esc_html_e( 'Filter by Status:', 'aqualuxe' ); ?></label>
                    <select id="aqualuxe-registrations-filter-status" class="aqualuxe-event-admin__registrations-filter-select">
                        <option value=""><?php esc_html_e( 'All Statuses', 'aqualuxe' ); ?></option>
                        <?php foreach ( $statuses as $status => $label ) : ?>
                            <option value="<?php echo esc_attr( $status ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="aqualuxe-event-admin__registrations-filter">
                    <label for="aqualuxe-registrations-filter-search" class="aqualuxe-event-admin__registrations-filter-label"><?php esc_html_e( 'Search:', 'aqualuxe' ); ?></label>
                    <input type="text" id="aqualuxe-registrations-filter-search" class="aqualuxe-event-admin__registrations-filter-input" placeholder="<?php esc_attr_e( 'Search by name or email...', 'aqualuxe' ); ?>">
                </div>
                
                <div class="aqualuxe-event-admin__registrations-actions">
                    <button type="button" id="aqualuxe-registrations-export-csv" class="button">
                        <span class="dashicons dashicons-download"></span>
                        <?php esc_html_e( 'Export CSV', 'aqualuxe' ); ?>
                    </button>
                    
                    <button type="button" id="aqualuxe-registrations-print" class="button">
                        <span class="dashicons dashicons-printer"></span>
                        <?php esc_html_e( 'Print', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
            
            <div class="aqualuxe-event-admin__registrations-table-container">
                <table class="aqualuxe-event-admin__registrations-table widefat">
                    <thead>
                        <tr>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'ID', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Attendee', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Ticket', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Payment', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>
                            <th class="aqualuxe-event-admin__registrations-table-header"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $registrations as $registration ) : ?>
                            <?php
                            $attendee = $registration->get_attendee_data();
                            $ticket = $registration->get_ticket();
                            $payment = $registration->get_payment_data();
                            ?>
                            <tr class="aqualuxe-event-admin__registrations-table-row" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-registration-status="<?php echo esc_attr( $registration->get_status() ); ?>">
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <a href="<?php echo esc_url( get_edit_post_link( $registration->get_id() ) ); ?>" target="_blank">
                                        #<?php echo esc_html( $registration->get_id() ); ?>
                                    </a>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <div class="aqualuxe-event-admin__registrations-attendee">
                                        <div class="aqualuxe-event-admin__registrations-attendee-name">
                                            <?php echo esc_html( $attendee['name'] ?? '' ); ?>
                                        </div>
                                        <div class="aqualuxe-event-admin__registrations-attendee-email">
                                            <a href="mailto:<?php echo esc_attr( $attendee['email'] ?? '' ); ?>">
                                                <?php echo esc_html( $attendee['email'] ?? '' ); ?>
                                            </a>
                                        </div>
                                        <?php if ( ! empty( $attendee['phone'] ) ) : ?>
                                            <div class="aqualuxe-event-admin__registrations-attendee-phone">
                                                <?php echo esc_html( $attendee['phone'] ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <?php echo esc_html( $ticket->get_name() ); ?>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <?php echo esc_html( $registration->get_quantity() ); ?>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <?php echo esc_html( $registration->get_formatted_price() ); ?>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <span class="aqualuxe-event-admin__registrations-status aqualuxe-event-admin__registrations-status--<?php echo esc_attr( $registration->get_status() ); ?>">
                                        <?php echo esc_html( $statuses[ $registration->get_status() ] ?? $registration->get_status() ); ?>
                                    </span>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <?php if ( ! empty( $payment ) ) : ?>
                                        <span class="aqualuxe-event-admin__registrations-payment aqualuxe-event-admin__registrations-payment--<?php echo esc_attr( $payment['status'] ?? 'none' ); ?>">
                                            <?php
                                            switch ( $payment['status'] ?? '' ) {
                                                case 'completed':
                                                    esc_html_e( 'Completed', 'aqualuxe' );
                                                    break;
                                                case 'pending':
                                                    esc_html_e( 'Pending', 'aqualuxe' );
                                                    break;
                                                case 'failed':
                                                    esc_html_e( 'Failed', 'aqualuxe' );
                                                    break;
                                                case 'refunded':
                                                    esc_html_e( 'Refunded', 'aqualuxe' );
                                                    break;
                                                case 'cancelled':
                                                    esc_html_e( 'Cancelled', 'aqualuxe' );
                                                    break;
                                                default:
                                                    esc_html_e( 'None', 'aqualuxe' );
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    <?php else : ?>
                                        <span class="aqualuxe-event-admin__registrations-payment aqualuxe-event-admin__registrations-payment--none">
                                            <?php esc_html_e( 'None', 'aqualuxe' ); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <?php echo esc_html( $registration->get_formatted_date() ); ?>
                                </td>
                                <td class="aqualuxe-event-admin__registrations-table-cell">
                                    <div class="aqualuxe-event-admin__registrations-actions">
                                        <a href="<?php echo esc_url( get_edit_post_link( $registration->get_id() ) ); ?>" class="button" target="_blank">
                                            <span class="dashicons dashicons-edit"></span>
                                        </a>
                                        
                                        <div class="aqualuxe-event-admin__registrations-dropdown">
                                            <button type="button" class="button aqualuxe-event-admin__registrations-dropdown-toggle">
                                                <span class="dashicons dashicons-arrow-down-alt2"></span>
                                            </button>
                                            
                                            <div class="aqualuxe-event-admin__registrations-dropdown-content">
                                                <?php if ( $registration->get_status() !== 'confirmed' ) : ?>
                                                    <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--confirm" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                        <span class="dashicons dashicons-yes"></span>
                                                        <?php esc_html_e( 'Confirm', 'aqualuxe' ); ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( $registration->get_status() !== 'cancelled' ) : ?>
                                                    <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--cancel" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                        <span class="dashicons dashicons-no"></span>
                                                        <?php esc_html_e( 'Cancel', 'aqualuxe' ); ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( $registration->get_status() !== 'completed' ) : ?>
                                                    <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--complete" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                        <span class="dashicons dashicons-yes-alt"></span>
                                                        <?php esc_html_e( 'Complete', 'aqualuxe' ); ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if ( ! empty( $payment ) && $payment['status'] === 'completed' && $registration->get_status() !== 'refunded' ) : ?>
                                                    <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--refund" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                        <span class="dashicons dashicons-money-alt"></span>
                                                        <?php esc_html_e( 'Refund', 'aqualuxe' ); ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--email" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                    <span class="dashicons dashicons-email"></span>
                                                    <?php esc_html_e( 'Send Email', 'aqualuxe' ); ?>
                                                </a>
                                                
                                                <a href="#" class="aqualuxe-event-admin__registrations-action aqualuxe-event-admin__registrations-action--delete" data-registration-id="<?php echo esc_attr( $registration->get_id() ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                                                    <span class="dashicons dashicons-trash"></span>
                                                    <?php esc_html_e( 'Delete', 'aqualuxe' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div id="aqualuxe-event-admin__email-modal" class="aqualuxe-event-admin__modal" style="display: none;">
                <div class="aqualuxe-event-admin__modal-content">
                    <div class="aqualuxe-event-admin__modal-header">
                        <h3 class="aqualuxe-event-admin__modal-title"><?php esc_html_e( 'Send Email to Attendee', 'aqualuxe' ); ?></h3>
                        <button type="button" class="aqualuxe-event-admin__modal-close">
                            <span class="dashicons dashicons-no-alt"></span>
                        </button>
                    </div>
                    
                    <div class="aqualuxe-event-admin__modal-body">
                        <form id="aqualuxe-event-admin__email-form">
                            <input type="hidden" id="aqualuxe-event-admin__email-registration-id" name="registration_id" value="">
                            <input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-events-admin-nonce' ) ); ?>">
                            
                            <div class="aqualuxe-event-admin__field">
                                <label for="aqualuxe-event-admin__email-to" class="aqualuxe-event-admin__label"><?php esc_html_e( 'To', 'aqualuxe' ); ?></label>
                                <input type="email" id="aqualuxe-event-admin__email-to" name="to" class="aqualuxe-event-admin__input" readonly>
                            </div>
                            
                            <div class="aqualuxe-event-admin__field">
                                <label for="aqualuxe-event-admin__email-subject" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></label>
                                <input type="text" id="aqualuxe-event-admin__email-subject" name="subject" class="aqualuxe-event-admin__input" required>
                            </div>
                            
                            <div class="aqualuxe-event-admin__field">
                                <label for="aqualuxe-event-admin__email-message" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Message', 'aqualuxe' ); ?></label>
                                <textarea id="aqualuxe-event-admin__email-message" name="message" class="aqualuxe-event-admin__textarea" rows="10" required></textarea>
                            </div>
                            
                            <div class="aqualuxe-event-admin__field">
                                <label class="aqualuxe-event-admin__label aqualuxe-event-admin__label--checkbox">
                                    <input type="checkbox" id="aqualuxe-event-admin__email-include-ticket" name="include_ticket" value="1" checked>
                                    <?php esc_html_e( 'Include ticket information', 'aqualuxe' ); ?>
                                </label>
                            </div>
                            
                            <div class="aqualuxe-event-admin__modal-actions">
                                <button type="button" class="button aqualuxe-event-admin__modal-cancel"><?php esc_html_e( 'Cancel', 'aqualuxe' ); ?></button>
                                <button type="submit" class="button button-primary aqualuxe-event-admin__modal-submit"><?php esc_html_e( 'Send Email', 'aqualuxe' ); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <script>
                jQuery(document).ready(function($) {
                    // Filter registrations by status
                    $('#aqualuxe-registrations-filter-status').on('change', function() {
                        var status = $(this).val();
                        
                        if (status) {
                            $('.aqualuxe-event-admin__registrations-table-row').hide();
                            $('.aqualuxe-event-admin__registrations-table-row[data-registration-status="' + status + '"]').show();
                        } else {
                            $('.aqualuxe-event-admin__registrations-table-row').show();
                        }
                    });
                    
                    // Filter registrations by search
                    $('#aqualuxe-registrations-filter-search').on('keyup', function() {
                        var search = $(this).val().toLowerCase();
                        
                        $('.aqualuxe-event-admin__registrations-table-row').each(function() {
                            var text = $(this).text().toLowerCase();
                            
                            if (text.indexOf(search) > -1) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        });
                    });
                    
                    // Export CSV
                    $('#aqualuxe-registrations-export-csv').on('click', function(e) {
                        e.preventDefault();
                        
                        var csv = [];
                        var rows = $('.aqualuxe-event-admin__registrations-table tr:visible');
                        
                        rows.each(function(index, row) {
                            var rowData = [];
                            
                            $(row).find('th, td').each(function(cellIndex, cell) {
                                if (cellIndex < 8) { // Skip actions column
                                    rowData.push('"' + $(cell).text().trim().replace(/"/g, '""') + '"');
                                }
                            });
                            
                            csv.push(rowData.join(','));
                        });
                        
                        var csvContent = csv.join('\n');
                        var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                        var url = URL.createObjectURL(blob);
                        
                        var link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'event_registrations.csv');
                        link.click();
                    });
                    
                    // Print registrations
                    $('#aqualuxe-registrations-print').on('click', function(e) {
                        e.preventDefault();
                        
                        var printContent = '<html><head><title><?php echo esc_js( $event->get_title() ); ?> - <?php esc_html_e( 'Registrations', 'aqualuxe' ); ?></title>';
                        printContent += '<style>';
                        printContent += 'body { font-family: Arial, sans-serif; }';
                        printContent += 'table { width: 100%; border-collapse: collapse; }';
                        printContent += 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
                        printContent += 'th { background-color: #f2f2f2; }';
                        printContent += '.header { margin-bottom: 20px; }';
                        printContent += '.header h1 { margin: 0; }';
                        printContent += '.header p { margin: 5px 0; }';
                        printContent += '</style>';
                        printContent += '</head><body>';
                        
                        printContent += '<div class="header">';
                        printContent += '<h1><?php echo esc_js( $event->get_title() ); ?></h1>';
                        printContent += '<p><?php echo esc_js( $event->get_formatted_start_date() ); ?> - <?php echo esc_js( $event->get_formatted_start_time() ); ?></p>';
                        printContent += '<p><?php esc_html_e( 'Registrations:', 'aqualuxe' ); ?> <?php echo esc_js( $registered_count ); ?></p>';
                        printContent += '</div>';
                        
                        printContent += '<table>';
                        printContent += '<thead><tr>';
                        printContent += '<th><?php esc_html_e( 'ID', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Attendee', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Ticket', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Quantity', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Payment', 'aqualuxe' ); ?></th>';
                        printContent += '<th><?php esc_html_e( 'Date', 'aqualuxe' ); ?></th>';
                        printContent += '</tr></thead>';
                        printContent += '<tbody>';
                        
                        $('.aqualuxe-event-admin__registrations-table-row:visible').each(function() {
                            printContent += '<tr>';
                            
                            $(this).find('td').each(function(index) {
                                if (index < 8) { // Skip actions column
                                    printContent += '<td>' + $(this).text().trim() + '</td>';
                                }
                            });
                            
                            printContent += '</tr>';
                        });
                        
                        printContent += '</tbody></table>';
                        printContent += '</body></html>';
                        
                        var printWindow = window.open('', '_blank');
                        printWindow.document.write(printContent);
                        printWindow.document.close();
                        printWindow.focus();
                        printWindow.print();
                        printWindow.close();
                    });
                    
                    // Registration actions
                    $('.aqualuxe-event-admin__registrations-action--confirm').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var nonce = $(this).data('nonce');
                        
                        if (confirm('<?php esc_html_e( 'Are you sure you want to confirm this registration?', 'aqualuxe' ); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_update_registration_status',
                                    registration_id: registrationId,
                                    status: 'confirmed',
                                    nonce: nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                },
                                error: function() {
                                    alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                }
                            });
                        }
                    });
                    
                    $('.aqualuxe-event-admin__registrations-action--cancel').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var nonce = $(this).data('nonce');
                        
                        if (confirm('<?php esc_html_e( 'Are you sure you want to cancel this registration?', 'aqualuxe' ); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_update_registration_status',
                                    registration_id: registrationId,
                                    status: 'cancelled',
                                    nonce: nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                },
                                error: function() {
                                    alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                }
                            });
                        }
                    });
                    
                    $('.aqualuxe-event-admin__registrations-action--complete').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var nonce = $(this).data('nonce');
                        
                        if (confirm('<?php esc_html_e( 'Are you sure you want to mark this registration as completed?', 'aqualuxe' ); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_update_registration_status',
                                    registration_id: registrationId,
                                    status: 'completed',
                                    nonce: nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                },
                                error: function() {
                                    alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                }
                            });
                        }
                    });
                    
                    $('.aqualuxe-event-admin__registrations-action--refund').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var nonce = $(this).data('nonce');
                        
                        if (confirm('<?php esc_html_e( 'Are you sure you want to refund this registration?', 'aqualuxe' ); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_refund_registration',
                                    registration_id: registrationId,
                                    nonce: nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                },
                                error: function() {
                                    alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                }
                            });
                        }
                    });
                    
                    $('.aqualuxe-event-admin__registrations-action--delete').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var nonce = $(this).data('nonce');
                        
                        if (confirm('<?php esc_html_e( 'Are you sure you want to delete this registration? This action cannot be undone.', 'aqualuxe' ); ?>')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'aqualuxe_delete_registration',
                                    registration_id: registrationId,
                                    nonce: nonce
                                },
                                success: function(response) {
                                    if (response.success) {
                                        location.reload();
                                    } else {
                                        alert(response.data.message);
                                    }
                                },
                                error: function() {
                                    alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                }
                            });
                        }
                    });
                    
                    // Email modal
                    $('.aqualuxe-event-admin__registrations-action--email').on('click', function(e) {
                        e.preventDefault();
                        
                        var registrationId = $(this).data('registration-id');
                        var row = $(this).closest('tr');
                        var email = row.find('.aqualuxe-event-admin__registrations-attendee-email a').text().trim();
                        var name = row.find('.aqualuxe-event-admin__registrations-attendee-name').text().trim();
                        
                        $('#aqualuxe-event-admin__email-registration-id').val(registrationId);
                        $('#aqualuxe-event-admin__email-to').val(email);
                        $('#aqualuxe-event-admin__email-subject').val('<?php echo esc_js( sprintf( __( 'Information about your registration for %s', 'aqualuxe' ), $event->get_title() ) ); ?>');
                        
                        var message = '<?php echo esc_js( __( 'Dear', 'aqualuxe' ) ); ?> ' + name + ',\n\n';
                        message += '<?php echo esc_js( __( 'Thank you for registering for our event.', 'aqualuxe' ) ); ?>\n\n';
                        message += '<?php echo esc_js( __( 'Event details:', 'aqualuxe' ) ); ?>\n';
                        message += '<?php echo esc_js( $event->get_title() ); ?>\n';
                        message += '<?php echo esc_js( sprintf( __( 'Date: %s', 'aqualuxe' ), $event->get_formatted_start_date() ) ); ?>\n';
                        message += '<?php echo esc_js( sprintf( __( 'Time: %s - %s', 'aqualuxe' ), $event->get_formatted_start_time(), $event->get_formatted_end_time() ) ); ?>\n\n';
                        message += '<?php echo esc_js( __( 'If you have any questions, please don\'t hesitate to contact us.', 'aqualuxe' ) ); ?>\n\n';
                        message += '<?php echo esc_js( __( 'Best regards,', 'aqualuxe' ) ); ?>\n';
                        message += '<?php echo esc_js( get_bloginfo( 'name' ) ); ?>';
                        
                        $('#aqualuxe-event-admin__email-message').val(message);
                        
                        $('#aqualuxe-event-admin__email-modal').show();
                    });
                    
                    // Close modal
                    $('.aqualuxe-event-admin__modal-close, .aqualuxe-event-admin__modal-cancel').on('click', function() {
                        $('#aqualuxe-event-admin__email-modal').hide();
                    });
                    
                    // Send email
                    $('#aqualuxe-event-admin__email-form').on('submit', function(e) {
                        e.preventDefault();
                        
                        var formData = $(this).serialize();
                        
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_send_registration_email',
                                ...formData
                            },
                            beforeSend: function() {
                                $('.aqualuxe-event-admin__modal-submit').prop('disabled', true).text('<?php esc_html_e( 'Sending...', 'aqualuxe' ); ?>');
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert('<?php esc_html_e( 'Email sent successfully!', 'aqualuxe' ); ?>');
                                    $('#aqualuxe-event-admin__email-modal').hide();
                                } else {
                                    alert(response.data.message);
                                }
                                
                                $('.aqualuxe-event-admin__modal-submit').prop('disabled', false).text('<?php esc_html_e( 'Send Email', 'aqualuxe' ); ?>');
                            },
                            error: function() {
                                alert('<?php esc_html_e( 'An error occurred. Please try again.', 'aqualuxe' ); ?>');
                                $('.aqualuxe-event-admin__modal-submit').prop('disabled', false).text('<?php esc_html_e( 'Send Email', 'aqualuxe' ); ?>');
                            }
                        });
                    });
                    
                    // Toggle dropdown
                    $(document).on('click', '.aqualuxe-event-admin__registrations-dropdown-toggle', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var dropdown = $(this).next('.aqualuxe-event-admin__registrations-dropdown-content');
                        $('.aqualuxe-event-admin__registrations-dropdown-content').not(dropdown).hide();
                        dropdown.toggle();
                    });
                    
                    // Close dropdown when clicking outside
                    $(document).on('click', function(e) {
                        if (!$(e.target).closest('.aqualuxe-event-admin__registrations-dropdown').length) {
                            $('.aqualuxe-event-admin__registrations-dropdown-content').hide();
                        }
                    });
                });
            </script>
        <?php else : ?>
            <div class="aqualuxe-event-admin__no-registrations">
                <p><?php esc_html_e( 'No registrations have been made for this event yet.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>