<?php
/**
 * AquaLuxe API Settings Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Settings', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-settings">
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_api_general'); ?>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('General Settings', 'aqualuxe'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_enabled"><?php _e('API Status', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="aqualuxe_api_enabled" id="aqualuxe_api_enabled" value="1" <?php checked($api_enabled); ?>>
                                <?php _e('Enable AquaLuxe API', 'aqualuxe'); ?>
                            </label>
                            <p class="description"><?php _e('Enable or disable the AquaLuxe API functionality.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_allowed_origins"><?php _e('Allowed Origins', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="aqualuxe_api_allowed_origins" id="aqualuxe_api_allowed_origins" value="<?php echo esc_attr(is_array($allowed_origins) ? implode(', ', $allowed_origins) : $allowed_origins); ?>" class="regular-text">
                            <p class="description"><?php _e('Comma-separated list of allowed origins for CORS. Use * to allow all origins.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_rate_limit"><?php _e('Rate Limit', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="aqualuxe_api_rate_limit" id="aqualuxe_api_rate_limit" value="<?php echo esc_attr($rate_limit); ?>" class="small-text" min="1" max="1000">
                            <p class="description"><?php _e('Maximum number of API requests per minute per user.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_token_expiration"><?php _e('Token Expiration', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="aqualuxe_api_token_expiration" id="aqualuxe_api_token_expiration" value="<?php echo esc_attr($token_expiration); ?>" class="small-text" min="1" max="30">
                            <p class="description"><?php _e('Number of days before API tokens expire.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="aqualuxe-api-card">
                <h2><?php _e('API Keys', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-api-key-container">
                    <h3><?php _e('Default API Key', 'aqualuxe'); ?></h3>
                    
                    <div class="aqualuxe-api-key-field">
                        <label for="aqualuxe-api-key"><?php _e('Consumer Key:', 'aqualuxe'); ?></label>
                        <input type="text" id="aqualuxe-api-key" value="<?php echo esc_attr($api_keys['default_key']); ?>" readonly>
                        <button type="button" class="button" onclick="copyToClipboard('aqualuxe-api-key')"><?php _e('Copy', 'aqualuxe'); ?></button>
                    </div>
                    
                    <div class="aqualuxe-api-key-field">
                        <label for="aqualuxe-api-secret"><?php _e('Consumer Secret:', 'aqualuxe'); ?></label>
                        <input type="text" id="aqualuxe-api-secret" value="<?php echo esc_attr($api_keys['default_secret']); ?>" readonly>
                        <button type="button" class="button" onclick="copyToClipboard('aqualuxe-api-secret')"><?php _e('Copy', 'aqualuxe'); ?></button>
                    </div>
                    
                    <p style="margin-top: 15px;">
                        <button type="button" id="aqualuxe-api-regenerate-keys" class="aqualuxe-api-button danger"><?php _e('Regenerate API Keys', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-api-loading" style="display: none;"></span>
                    </p>
                    
                    <div id="aqualuxe-api-regenerate-keys-result" style="margin-top: 15px;"></div>
                    
                    <p class="description"><?php _e('Warning: Regenerating API keys will invalidate any existing keys.', 'aqualuxe'); ?></p>
                </div>
                
                <?php if (!empty($api_keys['keys'])) : ?>
                    <h3><?php _e('All API Keys', 'aqualuxe'); ?></h3>
                    
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php _e('Description', 'aqualuxe'); ?></th>
                                <th><?php _e('User', 'aqualuxe'); ?></th>
                                <th><?php _e('Permissions', 'aqualuxe'); ?></th>
                                <th><?php _e('Truncated Key', 'aqualuxe'); ?></th>
                                <th><?php _e('Last Used', 'aqualuxe'); ?></th>
                                <th><?php _e('Created', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($api_keys['keys'] as $key) : ?>
                                <tr>
                                    <td><?php echo esc_html($key->description); ?></td>
                                    <td>
                                        <?php
                                        $user = get_user_by('id', $key->user_id);
                                        echo $user ? esc_html($user->display_name) : __('Unknown', 'aqualuxe');
                                        ?>
                                    </td>
                                    <td><?php echo esc_html($key->permissions); ?></td>
                                    <td><?php echo esc_html($key->truncated_key); ?></td>
                                    <td>
                                        <?php
                                        if ($key->last_access) {
                                            echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($key->last_access));
                                        } else {
                                            echo __('Never', 'aqualuxe');
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo date_i18n(get_option('date_format'), strtotime($key->date_created)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
        </form>
    </div>
</div>