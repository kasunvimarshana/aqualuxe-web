<?php
/**
 * AquaLuxe API Notifications Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Push Notifications', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-notifications">
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_api_notifications'); ?>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Push Notification Settings', 'aqualuxe'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_push_enabled"><?php _e('Push Notifications', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_push_enabled" id="aqualuxe_push_enabled" value="1" <?php checked($push_enabled); ?>>
                                <?php _e('Enable Push Notifications', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable or disable push notifications to mobile devices.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_firebase_server_key"><?php _e('Firebase Server Key', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="aqualuxe_firebase_server_key" id="aqualuxe_firebase_server_key" value="<?php echo esc_attr($firebase_server_key); ?>" class="regular-text">
                            <p class="description"><?php _e('Firebase Cloud Messaging server key for sending push notifications.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_firebase_sender_id"><?php _e('Firebase Sender ID', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="aqualuxe_firebase_sender_id" id="aqualuxe_firebase_sender_id" value="<?php echo esc_attr($firebase_sender_id); ?>" class="regular-text">
                            <p class="description"><?php _e('Firebase Cloud Messaging sender ID for push notifications.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Notification Types', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-api-notification-types">
                    <div class="aqualuxe-api-notification-type">
                        <label>
                            <input type="checkbox" name="aqualuxe_push_new_order" value="1" <?php checked($push_new_order); ?>>
                            <?php _e('New Order Notifications', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php _e('Send push notifications when new orders are placed.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-api-notification-type">
                        <label>
                            <input type="checkbox" name="aqualuxe_push_order_status" value="1" <?php checked($push_order_status); ?>>
                            <?php _e('Order Status Updates', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php _e('Send push notifications when order statuses change.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-api-notification-type">
                        <label>
                            <input type="checkbox" name="aqualuxe_push_auction_bid" value="1" <?php checked($push_auction_bid); ?>>
                            <?php _e('Auction Bid Notifications', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php _e('Send push notifications when new bids are placed on auctions.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-api-notification-type">
                        <label>
                            <input type="checkbox" name="aqualuxe_push_auction_end" value="1" <?php checked($push_auction_end); ?>>
                            <?php _e('Auction End Notifications', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php _e('Send push notifications when auctions end.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="aqualuxe-api-notification-type">
                        <label>
                            <input type="checkbox" name="aqualuxe_push_trade_in_status" value="1" <?php checked($push_trade_in_status); ?>>
                            <?php _e('Trade-In Status Updates', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php _e('Send push notifications when trade-in request statuses change.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Device Statistics', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-api-device-stats">
                    <div class="aqualuxe-api-device-stat">
                        <h3><?php _e('Total Devices', 'aqualuxe'); ?></h3>
                        <div class="stat-value"><?php echo number_format($device_count); ?></div>
                    </div>
                    
                    <?php foreach ($device_platforms as $platform => $count) : ?>
                        <div class="aqualuxe-api-device-stat">
                            <h3><?php echo esc_html(ucfirst($platform)); ?></h3>
                            <div class="stat-value"><?php echo number_format($count); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($device_count > 0) : ?>
                    <p>
                        <a href="#" class="aqualuxe-api-button secondary"><?php _e('View Registered Devices', 'aqualuxe'); ?></a>
                    </p>
                <?php endif; ?>
            </div>
            
            <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
        </form>
    </div>
</div>