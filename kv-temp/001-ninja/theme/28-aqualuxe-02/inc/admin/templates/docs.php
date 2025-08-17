<?php
/**
 * AquaLuxe API Documentation Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Documentation', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-docs">
        <div class="aqualuxe-api-docs-nav">
            <div class="aqualuxe-api-docs-nav-item active" data-section="overview"><?php _e('Overview', 'aqualuxe'); ?></div>
            <div class="aqualuxe-api-docs-nav-item" data-section="authentication"><?php _e('Authentication', 'aqualuxe'); ?></div>
            <div class="aqualuxe-api-docs-nav-item" data-section="endpoints"><?php _e('Endpoints', 'aqualuxe'); ?></div>
            <div class="aqualuxe-api-docs-nav-item" data-section="sync"><?php _e('Sync', 'aqualuxe'); ?></div>
            <div class="aqualuxe-api-docs-nav-item" data-section="notifications"><?php _e('Notifications', 'aqualuxe'); ?></div>
            <div class="aqualuxe-api-docs-nav-item" data-section="mobile"><?php _e('Mobile App', 'aqualuxe'); ?></div>
        </div>
        
        <div class="aqualuxe-api-docs-content">
            <div id="overview" class="aqualuxe-api-docs-section active">
                <h3><?php _e('API Overview', 'aqualuxe'); ?></h3>
                
                <p><?php _e('The AquaLuxe API provides a RESTful interface for mobile applications to interact with your AquaLuxe WordPress site. It allows for retrieving and managing products, orders, users, subscriptions, care guides, water calculator data, compatibility checker data, auctions, and trade-ins.', 'aqualuxe'); ?></p>
                
                <h4><?php _e('API Namespace', 'aqualuxe'); ?></h4>
                <p><?php _e('All API endpoints are prefixed with:', 'aqualuxe'); ?></p>
                <pre>/wp-json/aqualuxe/v1/</pre>
                
                <h4><?php _e('Response Format', 'aqualuxe'); ?></h4>
                <p><?php _e('All API responses are returned in JSON format with the following structure:', 'aqualuxe'); ?></p>
                <pre>{
  "data": {
    // Response data
  },
  "success": true,
  "status": 200
}</pre>
                
                <h4><?php _e('Error Handling', 'aqualuxe'); ?></h4>
                <p><?php _e('Errors are returned with appropriate HTTP status codes and the following structure:', 'aqualuxe'); ?></p>
                <pre>{
  "code": "error_code",
  "message": "Error message",
  "data": {
    "status": 400
  }
}</pre>
                
                <h4><?php _e('Rate Limiting', 'aqualuxe'); ?></h4>
                <p><?php _e('API requests are rate limited to prevent abuse. The current rate limit is:', 'aqualuxe'); ?></p>
                <pre><?php echo get_option('aqualuxe_api_rate_limit', 60); ?> requests per minute per user</pre>
                <p><?php _e('Rate limit information is included in the response headers:', 'aqualuxe'); ?></p>
                <pre>X-Rate-Limit-Limit: 60
X-Rate-Limit-Remaining: 59
X-Rate-Limit-Reset: 1598456789</pre>
            </div>
            
            <div id="authentication" class="aqualuxe-api-docs-section">
                <h3><?php _e('Authentication', 'aqualuxe'); ?></h3>
                
                <p><?php _e('The AquaLuxe API uses JWT (JSON Web Tokens) for authentication. To authenticate, you need to obtain a token by sending your credentials to the login endpoint.', 'aqualuxe'); ?></p>
                
                <h4><?php _e('Login', 'aqualuxe'); ?></h4>
                <p><?php _e('To obtain a token, send a POST request to:', 'aqualuxe'); ?></p>
                <pre>/wp-json/aqualuxe/v1/auth/login</pre>
                <p><?php _e('With the following parameters:', 'aqualuxe'); ?></p>
                <pre>{
  "username": "your_username",
  "password": "your_password"
}</pre>
                <p><?php _e('The response will include an access token and a refresh token:', 'aqualuxe'); ?></p>
                <pre>{
  "success": true,
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "user_id": 1,
    "user_email": "user@example.com",
    "user_display_name": "User Name",
    "expires_in": 604800
  }
}</pre>
                
                <h4><?php _e('Using the Token', 'aqualuxe'); ?></h4>
                <p><?php _e('Include the token in the Authorization header of all API requests:', 'aqualuxe'); ?></p>
                <pre>Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...</pre>
                
                <h4><?php _e('Refreshing the Token', 'aqualuxe'); ?></h4>
                <p><?php _e('To refresh an expired token, send a POST request to:', 'aqualuxe'); ?></p>
                <pre>/wp-json/aqualuxe/v1/auth/refresh</pre>
                <p><?php _e('With the following parameters:', 'aqualuxe'); ?></p>
                <pre>{
  "refresh_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}</pre>
                <p><?php _e('The response will include a new access token and refresh token.', 'aqualuxe'); ?></p>
                
                <h4><?php _e('Token Expiration', 'aqualuxe'); ?></h4>
                <p><?php _e('Access tokens expire after:', 'aqualuxe'); ?></p>
                <pre><?php echo get_option('aqualuxe_api_token_expiration', 7); ?> days</pre>
                <p><?php _e('Refresh tokens expire after 30 days.', 'aqualuxe'); ?></p>
            </div>
            
            <div id="endpoints" class="aqualuxe-api-docs-section">
                <h3><?php _e('API Endpoints', 'aqualuxe'); ?></h3>
                
                <h4><?php _e('Products', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /products</code> - <?php _e('Get all products', 'aqualuxe'); ?></li>
                    <li><code>GET /products/{id}</code> - <?php _e('Get a specific product', 'aqualuxe'); ?></li>
                    <li><code>GET /products/category/{id}</code> - <?php _e('Get products by category', 'aqualuxe'); ?></li>
                    <li><code>GET /products/featured</code> - <?php _e('Get featured products', 'aqualuxe'); ?></li>
                    <li><code>GET /products/on-sale</code> - <?php _e('Get on sale products', 'aqualuxe'); ?></li>
                    <li><code>GET /products/categories</code> - <?php _e('Get product categories', 'aqualuxe'); ?></li>
                    <li><code>GET /products/search</code> - <?php _e('Search products', 'aqualuxe'); ?></li>
                    <li><code>GET /products/{id}/related</code> - <?php _e('Get related products', 'aqualuxe'); ?></li>
                    <li><code>GET /products/{id}/reviews</code> - <?php _e('Get product reviews', 'aqualuxe'); ?></li>
                    <li><code>POST /products/{id}/reviews</code> - <?php _e('Create product review', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Orders', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /orders</code> - <?php _e('Get all orders', 'aqualuxe'); ?></li>
                    <li><code>GET /orders/{id}</code> - <?php _e('Get a specific order', 'aqualuxe'); ?></li>
                    <li><code>POST /orders</code> - <?php _e('Create an order', 'aqualuxe'); ?></li>
                    <li><code>PUT /orders/{id}</code> - <?php _e('Update an order', 'aqualuxe'); ?></li>
                    <li><code>DELETE /orders/{id}</code> - <?php _e('Delete an order', 'aqualuxe'); ?></li>
                    <li><code>GET /orders/{id}/notes</code> - <?php _e('Get order notes', 'aqualuxe'); ?></li>
                    <li><code>POST /orders/{id}/notes</code> - <?php _e('Add order note', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Users', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /users/me</code> - <?php _e('Get current user', 'aqualuxe'); ?></li>
                    <li><code>PUT /users/me</code> - <?php _e('Update current user', 'aqualuxe'); ?></li>
                    <li><code>GET /users/me/orders</code> - <?php _e('Get current user orders', 'aqualuxe'); ?></li>
                    <li><code>GET /users/me/addresses</code> - <?php _e('Get user addresses', 'aqualuxe'); ?></li>
                    <li><code>PUT /users/me/addresses</code> - <?php _e('Update user addresses', 'aqualuxe'); ?></li>
                    <li><code>GET /users/me/payment-methods</code> - <?php _e('Get user payment methods', 'aqualuxe'); ?></li>
                    <li><code>POST /users/me/payment-methods</code> - <?php _e('Add payment method', 'aqualuxe'); ?></li>
                    <li><code>DELETE /users/me/payment-methods/{id}</code> - <?php _e('Delete payment method', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Subscriptions', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /subscriptions</code> - <?php _e('Get all subscriptions', 'aqualuxe'); ?></li>
                    <li><code>GET /subscriptions/{id}</code> - <?php _e('Get a specific subscription', 'aqualuxe'); ?></li>
                    <li><code>PUT /subscriptions/{id}</code> - <?php _e('Update a subscription', 'aqualuxe'); ?></li>
                    <li><code>PUT /subscriptions/{id}/status</code> - <?php _e('Update subscription status', 'aqualuxe'); ?></li>
                    <li><code>GET /subscriptions/{id}/orders</code> - <?php _e('Get subscription orders', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Care Guides', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /care-guides</code> - <?php _e('Get all care guides', 'aqualuxe'); ?></li>
                    <li><code>GET /care-guides/{id}</code> - <?php _e('Get a specific care guide', 'aqualuxe'); ?></li>
                    <li><code>GET /care-guides/categories</code> - <?php _e('Get care guide categories', 'aqualuxe'); ?></li>
                    <li><code>GET /care-guides/search</code> - <?php _e('Search care guides', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Water Calculator', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>POST /water-calculator/calculate</code> - <?php _e('Calculate water parameters', 'aqualuxe'); ?></li>
                    <li><code>GET /water-calculator/presets</code> - <?php _e('Get calculator presets', 'aqualuxe'); ?></li>
                    <li><code>POST /water-calculator/presets</code> - <?php _e('Save calculator preset', 'aqualuxe'); ?></li>
                    <li><code>DELETE /water-calculator/presets/{id}</code> - <?php _e('Delete calculator preset', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Compatibility Checker', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>POST /compatibility-checker/check</code> - <?php _e('Check fish compatibility', 'aqualuxe'); ?></li>
                    <li><code>GET /compatibility-checker/species</code> - <?php _e('Get all fish species', 'aqualuxe'); ?></li>
                    <li><code>GET /compatibility-checker/species/{id}</code> - <?php _e('Get specific fish species', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Auctions', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /auctions</code> - <?php _e('Get all auctions', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/{id}</code> - <?php _e('Get a specific auction', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/category/{id}</code> - <?php _e('Get auctions by category', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/featured</code> - <?php _e('Get featured auctions', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/categories</code> - <?php _e('Get auction categories', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/search</code> - <?php _e('Search auctions', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/{id}/bids</code> - <?php _e('Get auction bids', 'aqualuxe'); ?></li>
                    <li><code>POST /auctions/{id}/bid</code> - <?php _e('Place bid on auction', 'aqualuxe'); ?></li>
                    <li><code>GET /auctions/{id}/status</code> - <?php _e('Get auction status', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Trade-Ins', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /trade-ins</code> - <?php _e('Get all trade-in requests', 'aqualuxe'); ?></li>
                    <li><code>GET /trade-ins/{id}</code> - <?php _e('Get a specific trade-in request', 'aqualuxe'); ?></li>
                    <li><code>POST /trade-ins</code> - <?php _e('Submit trade-in request', 'aqualuxe'); ?></li>
                    <li><code>GET /trade-ins/{id}/status</code> - <?php _e('Get trade-in status', 'aqualuxe'); ?></li>
                    <li><code>GET /trade-ins/history</code> - <?php _e('Get user trade-in history', 'aqualuxe'); ?></li>
                    <li><code>GET /trade-ins/categories</code> - <?php _e('Get trade-in categories', 'aqualuxe'); ?></li>
                    <li><code>GET /trade-ins/{id}/evaluation</code> - <?php _e('Get trade-in evaluation', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div id="sync" class="aqualuxe-api-docs-section">
                <h3><?php _e('Data Synchronization', 'aqualuxe'); ?></h3>
                
                <p><?php _e('The AquaLuxe API provides endpoints for synchronizing data between the server and mobile applications. This allows for offline functionality and efficient data transfer.', 'aqualuxe'); ?></p>
                
                <h4><?php _e('Sync Endpoints', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>GET /sync/status</code> - <?php _e('Get sync status', 'aqualuxe'); ?></li>
                    <li><code>GET /sync/data</code> - <?php _e('Get sync data for multiple entity types', 'aqualuxe'); ?></li>
                    <li><code>GET /sync/delta</code> - <?php _e('Get delta sync for a specific entity type', 'aqualuxe'); ?></li>
                    <li><code>POST /sync/conflicts</code> - <?php _e('Submit sync conflicts', 'aqualuxe'); ?></li>
                    <li><code>GET /sync/schema/{entity_type}</code> - <?php _e('Get entity schema', 'aqualuxe'); ?></li>
                    <li><code>GET /sync/settings</code> - <?php _e('Get sync settings', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Sync Process', 'aqualuxe'); ?></h4>
                <ol>
                    <li><?php _e('Initial Sync: The app requests all data for each entity type.', 'aqualuxe'); ?></li>
                    <li><?php _e('Delta Sync: Subsequent syncs only request data that has changed since the last sync.', 'aqualuxe'); ?></li>
                    <li><?php _e('Conflict Resolution: If both server and client data have changed, conflicts are resolved based on the configured strategy.', 'aqualuxe'); ?></li>
                </ol>
                
                <h4><?php _e('Sync Settings', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php _e('Sync Interval:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_sync_interval', 15); ?> <?php _e('minutes', 'aqualuxe'); ?></li>
                    <li><?php _e('Conflict Resolution:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_conflict_resolution', 'server') === 'server' ? __('Server Wins', 'aqualuxe') : __('Client Wins', 'aqualuxe'); ?></li>
                    <li><?php _e('Background Sync:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_background_sync', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Wi-Fi Only:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_sync_wifi_only', false) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Minimum Battery Level:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_sync_battery_level', 20); ?>%</li>
                </ul>
            </div>
            
            <div id="notifications" class="aqualuxe-api-docs-section">
                <h3><?php _e('Push Notifications', 'aqualuxe'); ?></h3>
                
                <p><?php _e('The AquaLuxe API supports push notifications to mobile devices using Firebase Cloud Messaging (FCM).', 'aqualuxe'); ?></p>
                
                <h4><?php _e('Device Registration', 'aqualuxe'); ?></h4>
                <p><?php _e('To receive push notifications, devices must register their FCM token with the API:', 'aqualuxe'); ?></p>
                <pre>POST /notifications/register-device</pre>
                <p><?php _e('With the following parameters:', 'aqualuxe'); ?></p>
                <pre>{
  "device_token": "fcm_token",
  "device_type": "android|ios",
  "device_name": "Device Name",
  "app_version": "1.0.0",
  "os_version": "10.0"
}</pre>
                
                <h4><?php _e('Notification Types', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php _e('New Order Notifications:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_push_new_order', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Order Status Updates:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_push_order_status', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Auction Bid Notifications:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_push_auction_bid', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Auction End Notifications:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_push_auction_end', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                    <li><?php _e('Trade-In Status Updates:', 'aqualuxe'); ?> <?php echo get_option('aqualuxe_push_trade_in_status', true) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?></li>
                </ul>
                
                <h4><?php _e('Notification Endpoints', 'aqualuxe'); ?></h4>
                <ul>
                    <li><code>POST /notifications/register-device</code> - <?php _e('Register device for push notifications', 'aqualuxe'); ?></li>
                    <li><code>DELETE /notifications/unregister-device</code> - <?php _e('Unregister device', 'aqualuxe'); ?></li>
                    <li><code>GET /notifications</code> - <?php _e('Get user notifications', 'aqualuxe'); ?></li>
                    <li><code>PUT /notifications/{id}/read</code> - <?php _e('Mark notification as read', 'aqualuxe'); ?></li>
                    <li><code>PUT /notifications/read-all</code> - <?php _e('Mark all notifications as read', 'aqualuxe'); ?></li>
                </ul>
            </div>
            
            <div id="mobile" class="aqualuxe-api-docs-section">
                <h3><?php _e('Mobile App Integration', 'aqualuxe'); ?></h3>
                
                <p><?php _e('The AquaLuxe API is designed to be used with mobile applications. Here are some guidelines for integrating with the API.', 'aqualuxe'); ?></p>
                
                <h4><?php _e('Authentication Flow', 'aqualuxe'); ?></h4>
                <ol>
                    <li><?php _e('User enters credentials in the app.', 'aqualuxe'); ?></li>
                    <li><?php _e('App sends credentials to the login endpoint.', 'aqualuxe'); ?></li>
                    <li><?php _e('API returns access token and refresh token.', 'aqualuxe'); ?></li>
                    <li><?php _e('App stores tokens securely.', 'aqualuxe'); ?></li>
                    <li><?php _e('App includes access token in all API requests.', 'aqualuxe'); ?></li>
                    <li><?php _e('When access token expires, app uses refresh token to get a new one.', 'aqualuxe'); ?></li>
                </ol>
                
                <h4><?php _e('Offline Support', 'aqualuxe'); ?></h4>
                <ol>
                    <li><?php _e('App performs initial sync to download all required data.', 'aqualuxe'); ?></li>
                    <li><?php _e('App stores data locally using a database or storage mechanism.', 'aqualuxe'); ?></li>
                    <li><?php _e('App periodically syncs with the server to get updates.', 'aqualuxe'); ?></li>
                    <li><?php _e('When offline, app uses local data for read operations.', 'aqualuxe'); ?></li>
                    <li><?php _e('Write operations are queued and synced when online.', 'aqualuxe'); ?></li>
                </ol>
                
                <h4><?php _e('Push Notifications', 'aqualuxe'); ?></h4>
                <ol>
                    <li><?php _e('App registers device token with the API.', 'aqualuxe'); ?></li>
                    <li><?php _e('Server sends push notifications via Firebase Cloud Messaging.', 'aqualuxe'); ?></li>
                    <li><?php _e('App handles notifications and updates UI accordingly.', 'aqualuxe'); ?></li>
                </ol>
                
                <h4><?php _e('Performance Considerations', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php _e('Use pagination for large data sets.', 'aqualuxe'); ?></li>
                    <li><?php _e('Implement caching to reduce API calls.', 'aqualuxe'); ?></li>
                    <li><?php _e('Use delta sync to minimize data transfer.', 'aqualuxe'); ?></li>
                    <li><?php _e('Compress request and response data when possible.', 'aqualuxe'); ?></li>
                    <li><?php _e('Implement retry logic for failed requests.', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>