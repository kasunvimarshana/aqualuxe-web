<?php
/**
 * AquaLuxe API Sync Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Sync Management', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-sync">
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_api_sync'); ?>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Sync Settings', 'aqualuxe'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_interval"><?php _e('Sync Interval', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="aqualuxe_sync_interval" id="aqualuxe_sync_interval" value="<?php echo esc_attr($sync_interval); ?>" class="small-text" min="1" max="1440">
                            <p class="description"><?php _e('How often the app should sync with the server (in minutes).', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_conflict_resolution"><?php _e('Conflict Resolution', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <select name="aqualuxe_conflict_resolution" id="aqualuxe_conflict_resolution">
                                <option value="server" <?php selected($conflict_resolution, 'server'); ?>><?php _e('Server Wins', 'aqualuxe'); ?></option>
                                <option value="client" <?php selected($conflict_resolution, 'client'); ?>><?php _e('Client Wins', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php _e('How to resolve conflicts when both server and client data have changed.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_background_sync"><?php _e('Background Sync', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_background_sync" id="aqualuxe_background_sync" value="1" <?php checked($background_sync); ?>>
                                <?php _e('Enable Background Sync', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Allow the app to sync data in the background.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Entity Sync Settings', 'aqualuxe'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_products"><?php _e('Products', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_products" id="aqualuxe_sync_products" value="1" <?php checked($sync_products); ?>>
                                <?php _e('Sync Products', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of product data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_products_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_products_batch" id="aqualuxe_sync_products_batch" value="<?php echo esc_attr($sync_products_batch); ?>" class="small-text" min="1" max="100">
                                <p class="description"><?php _e('Number of products to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_categories"><?php _e('Categories', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_categories" id="aqualuxe_sync_categories" value="1" <?php checked($sync_categories); ?>>
                                <?php _e('Sync Categories', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of category data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_categories_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_categories_batch" id="aqualuxe_sync_categories_batch" value="<?php echo esc_attr($sync_categories_batch); ?>" class="small-text" min="1" max="200">
                                <p class="description"><?php _e('Number of categories to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_orders"><?php _e('Orders', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_orders" id="aqualuxe_sync_orders" value="1" <?php checked($sync_orders); ?>>
                                <?php _e('Sync Orders', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of order data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_orders_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_orders_batch" id="aqualuxe_sync_orders_batch" value="<?php echo esc_attr($sync_orders_batch); ?>" class="small-text" min="1" max="50">
                                <p class="description"><?php _e('Number of orders to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_customers"><?php _e('Customers', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_customers" id="aqualuxe_sync_customers" value="1" <?php checked($sync_customers); ?>>
                                <?php _e('Sync Customers', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of customer data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_customers_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_customers_batch" id="aqualuxe_sync_customers_batch" value="<?php echo esc_attr($sync_customers_batch); ?>" class="small-text" min="1" max="100">
                                <p class="description"><?php _e('Number of customers to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_auctions"><?php _e('Auctions', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_auctions" id="aqualuxe_sync_auctions" value="1" <?php checked($sync_auctions); ?>>
                                <?php _e('Sync Auctions', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of auction data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_auctions_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_auctions_batch" id="aqualuxe_sync_auctions_batch" value="<?php echo esc_attr($sync_auctions_batch); ?>" class="small-text" min="1" max="50">
                                <p class="description"><?php _e('Number of auctions to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_trade_ins"><?php _e('Trade-Ins', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_trade_ins" id="aqualuxe_sync_trade_ins" value="1" <?php checked($sync_trade_ins); ?>>
                                <?php _e('Sync Trade-Ins', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable syncing of trade-in data.', 'aqualuxe'); ?></p>
                            
                            <div style="margin-top: 10px;">
                                <label for="aqualuxe_sync_trade_ins_batch"><?php _e('Batch Size:', 'aqualuxe'); ?></label>
                                <input type="number" name="aqualuxe_sync_trade_ins_batch" id="aqualuxe_sync_trade_ins_batch" value="<?php echo esc_attr($sync_trade_ins_batch); ?>" class="small-text" min="1" max="50">
                                <p class="description"><?php _e('Number of trade-ins to sync in each batch.', 'aqualuxe'); ?></p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Mobile App Sync Settings', 'aqualuxe'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_wifi_only"><?php _e('Wi-Fi Only', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_sync_wifi_only" id="aqualuxe_sync_wifi_only" value="1" <?php checked($sync_wifi_only); ?>>
                                <?php _e('Sync only when connected to Wi-Fi', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Only sync data when the device is connected to Wi-Fi to save mobile data.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_sync_battery_level"><?php _e('Minimum Battery Level', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="aqualuxe_sync_battery_level" id="aqualuxe_sync_battery_level" value="<?php echo esc_attr($sync_battery_level); ?>" class="small-text" min="0" max="100">%
                            <p class="description"><?php _e('Minimum battery level required for background sync.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('Sync Status', 'aqualuxe'); ?></h2>
                
                <p>
                    <?php if (!empty($sync_stats['last_sync'])) : ?>
                        <?php _e('Last full sync:', 'aqualuxe'); ?> <strong><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($sync_stats['last_sync'])); ?></strong>
                    <?php else : ?>
                        <?php _e('No full sync has been performed yet.', 'aqualuxe'); ?>
                    <?php endif; ?>
                </p>
                
                <div class="aqualuxe-api-sync-entities">
                    <?php foreach ($sync_stats['entities'] as $entity_type => $entity) : ?>
                        <div class="aqualuxe-api-sync-entity">
                            <h3><?php echo esc_html(ucfirst($entity_type)); ?></h3>
                            
                            <div class="sync-progress">
                                <div class="sync-progress-bar" style="width: <?php echo esc_attr($entity['percent']); ?>%;">
                                    <?php echo esc_html($entity['percent']); ?>%
                                </div>
                            </div>
                            
                            <div class="sync-stats">
                                <span><?php echo sprintf(__('Synced: %s', 'aqualuxe'), number_format($entity['synced'])); ?></span>
                                <span><?php echo sprintf(__('Total: %s', 'aqualuxe'), number_format($entity['total'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <p style="margin-top: 15px;">
                    <a href="#" class="aqualuxe-api-button"><?php _e('Force Full Sync', 'aqualuxe'); ?></a>
                </p>
            </div>
            
            <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
        </form>
    </div>
</div>