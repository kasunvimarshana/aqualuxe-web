<?php
/**
 * Event Organizer Meta Box Template
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

// Get organizer data
$organizer = $event->get_organizer_data();
$organizer_name = $organizer['name'] ?? '';
$organizer_description = $organizer['description'] ?? '';
$organizer_website = $organizer['website'] ?? '';
$organizer_phone = $organizer['phone'] ?? '';
$organizer_email = $organizer['email'] ?? '';
?>

<div class="aqualuxe-event-admin">
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_organizer_name" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Organizer Name', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_organizer_name" name="aqualuxe_organizer_data[name]" value="<?php echo esc_attr( $organizer_name ); ?>" class="aqualuxe-event-admin__input">
        </div>
        
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_organizer_description" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe_organizer_description" name="aqualuxe_organizer_data[description]" class="aqualuxe-event-admin__textarea"><?php echo esc_textarea( $organizer_description ); ?></textarea>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_organizer_website" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></label>
            <input type="url" id="aqualuxe_organizer_website" name="aqualuxe_organizer_data[website]" value="<?php echo esc_attr( $organizer_website ); ?>" class="aqualuxe-event-admin__input">
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_organizer_phone" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                <input type="tel" id="aqualuxe_organizer_phone" name="aqualuxe_organizer_data[phone]" value="<?php echo esc_attr( $organizer_phone ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_organizer_email" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                <input type="email" id="aqualuxe_organizer_email" name="aqualuxe_organizer_data[email]" value="<?php echo esc_attr( $organizer_email ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field">
            <button type="button" id="aqualuxe-organizer-copy-from-user" class="button"><?php esc_html_e( 'Copy from Current User', 'aqualuxe' ); ?></button>
            <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Copy organizer information from your user profile.', 'aqualuxe' ); ?></p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                $('#aqualuxe-organizer-copy-from-user').on('click', function(e) {
                    e.preventDefault();
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_current_user_data',
                            nonce: '<?php echo wp_create_nonce( 'aqualuxe-events-admin-nonce' ); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                var userData = response.data;
                                
                                $('#aqualuxe_organizer_name').val(userData.display_name);
                                $('#aqualuxe_organizer_email').val(userData.user_email);
                                
                                if (userData.user_url) {
                                    $('#aqualuxe_organizer_website').val(userData.user_url);
                                }
                                
                                if (userData.description) {
                                    $('#aqualuxe_organizer_description').val(userData.description);
                                }
                                
                                if (userData.phone) {
                                    $('#aqualuxe_organizer_phone').val(userData.phone);
                                }
                            } else {
                                alert('<?php esc_html_e( 'Failed to get user data.', 'aqualuxe' ); ?>');
                            }
                        },
                        error: function() {
                            alert('<?php esc_html_e( 'An error occurred while getting user data.', 'aqualuxe' ); ?>');
                        }
                    });
                });
            });
        </script>
    </div>
</div>