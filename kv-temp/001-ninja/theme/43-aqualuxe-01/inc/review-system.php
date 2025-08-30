<?php
/**
 * AquaLuxe Review System with Photos
 *
 * Enhanced review system with photo upload capabilities
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize the enhanced review system
 */
function aqualuxe_init_review_system() {
    // Only initialize if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Add photo upload field to review form
    add_action('comment_form_logged_in_after', 'aqualuxe_review_photo_upload_field');
    add_action('comment_form_after_fields', 'aqualuxe_review_photo_upload_field');
    
    // Save uploaded photos
    add_action('comment_post', 'aqualuxe_save_review_photos', 10, 3);
    
    // Display photos in reviews
    add_action('woocommerce_review_after_comment_text', 'aqualuxe_display_review_photos', 10, 1);
    
    // Add review photos to review list schema
    add_filter('woocommerce_structured_data_review', 'aqualuxe_add_photos_to_review_schema', 10, 2);
    
    // Add admin column for reviews with photos
    add_filter('manage_edit-comments_columns', 'aqualuxe_add_review_photo_column');
    add_action('manage_comments_custom_column', 'aqualuxe_review_photo_column_content', 10, 2);
    
    // Add filter for reviews with photos
    add_action('restrict_manage_comments', 'aqualuxe_filter_reviews_with_photos');
    add_filter('comments_clauses', 'aqualuxe_filter_reviews_with_photos_query', 10, 2);
    
    // Add AJAX handlers for photo management
    add_action('wp_ajax_aqualuxe_delete_review_photo', 'aqualuxe_ajax_delete_review_photo');
    
    // Add review photo gallery lightbox
    add_action('wp_footer', 'aqualuxe_review_photo_lightbox');
    
    // Add review photo moderation tools
    add_action('add_meta_boxes_comment', 'aqualuxe_add_review_photo_meta_box');
    add_action('edit_comment', 'aqualuxe_save_review_photo_meta');
    
    // Enqueue scripts and styles
    add_action('wp_enqueue_scripts', 'aqualuxe_review_system_scripts');
    add_action('admin_enqueue_scripts', 'aqualuxe_review_admin_scripts');
}
add_action('init', 'aqualuxe_init_review_system');

/**
 * Add photo upload field to review form
 */
function aqualuxe_review_photo_upload_field() {
    // Only show on product pages
    if (!is_product()) {
        return;
    }
    
    // Get maximum number of photos from settings
    $max_photos = get_option('aqualuxe_max_review_photos', 3);
    
    // Add the photo upload field
    ?>
    <p class="comment-form-review-photos">
        <label for="review_photos"><?php esc_html_e('Upload Photos (Optional)', 'aqualuxe'); ?></label>
        <input type="file" name="review_photos[]" id="review_photos" multiple accept="image/*" data-max-files="<?php echo esc_attr($max_photos); ?>" />
        <span class="description"><?php printf(esc_html__('You can upload up to %d photos. Maximum size per photo: 2MB.', 'aqualuxe'), $max_photos); ?></span>
        <div class="review-photo-previews"></div>
    </p>
    <?php
}

/**
 * Save uploaded review photos
 */
function aqualuxe_save_review_photos($comment_id, $comment_approved, $commentdata) {
    // Check if this is a product review
    $post_type = get_post_type($commentdata['comment_post_ID']);
    if ($post_type !== 'product') {
        return;
    }
    
    // Check if files were uploaded
    if (empty($_FILES['review_photos']['name']) || !is_array($_FILES['review_photos']['name'])) {
        return;
    }
    
    // Get maximum number of photos from settings
    $max_photos = get_option('aqualuxe_max_review_photos', 3);
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Initialize WordPress media handling
    if (!function_exists('wp_handle_upload')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    
    // Store photo IDs
    $photo_ids = array();
    
    // Process each uploaded file
    $files = $_FILES['review_photos'];
    $file_count = count($files['name']);
    $file_count = min($file_count, $max_photos);
    
    for ($i = 0; $i < $file_count; $i++) {
        // Skip empty files
        if (empty($files['name'][$i])) {
            continue;
        }
        
        // Check file size
        if ($files['size'][$i] > $max_size) {
            continue;
        }
        
        // Create file array for this specific file
        $file = array(
            'name'     => sanitize_file_name($files['name'][$i]),
            'type'     => $files['type'][$i],
            'tmp_name' => $files['tmp_name'][$i],
            'error'    => $files['error'][$i],
            'size'     => $files['size'][$i],
        );
        
        // Check file type
        $filetype = wp_check_filetype($file['name']);
        if (!in_array($filetype['ext'], array('jpg', 'jpeg', 'png', 'gif'))) {
            continue;
        }
        
        // Upload the file
        $upload = wp_handle_upload($file, array('test_form' => false));
        
        if (!isset($upload['error']) && isset($upload['file'])) {
            // Prepare attachment data
            $attachment = array(
                'post_mime_type' => $upload['type'],
                'post_title'     => sprintf(__('Review Photo %d for Comment %d', 'aqualuxe'), $i + 1, $comment_id),
                'post_content'   => '',
                'post_status'    => 'inherit',
                'guid'           => $upload['url'],
            );
            
            // Insert attachment
            $attachment_id = wp_insert_attachment($attachment, $upload['file'], $commentdata['comment_post_ID']);
            
            if (!is_wp_error($attachment_id)) {
                // Generate metadata and thumbnails
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                
                // Add attachment ID to array
                $photo_ids[] = $attachment_id;
                
                // Link attachment to comment
                add_comment_meta($comment_id, 'review_photo_id', $attachment_id);
            }
        }
    }
    
    // If photos were uploaded, mark the review as having photos
    if (!empty($photo_ids)) {
        add_comment_meta($comment_id, 'has_review_photos', '1');
        
        // Set review status to pending if moderation is enabled
        if (get_option('aqualuxe_moderate_photo_reviews', 'yes') === 'yes' && $comment_approved === 1) {
            wp_set_comment_status($comment_id, 'hold');
        }
    }
}

/**
 * Display review photos
 */
function aqualuxe_display_review_photos($comment) {
    // Get photo IDs
    $photo_ids = get_comment_meta($comment->comment_ID, 'review_photo_id');
    
    if (empty($photo_ids)) {
        return;
    }
    
    echo '<div class="review-photos">';
    echo '<h4>' . esc_html__('Customer Photos', 'aqualuxe') . '</h4>';
    echo '<div class="review-photo-gallery">';
    
    foreach ($photo_ids as $photo_id) {
        $thumbnail = wp_get_attachment_image_url($photo_id, 'thumbnail');
        $full_size = wp_get_attachment_image_url($photo_id, 'full');
        
        if ($thumbnail && $full_size) {
            echo '<a href="' . esc_url($full_size) . '" class="review-photo" data-review-id="' . esc_attr($comment->comment_ID) . '" data-photo-id="' . esc_attr($photo_id) . '">';
            echo '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr__('Review Photo', 'aqualuxe') . '" />';
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Add review photos to review schema
 */
function aqualuxe_add_photos_to_review_schema($markup, $comment) {
    // Get photo IDs
    $photo_ids = get_comment_meta($comment->comment_ID, 'review_photo_id');
    
    if (empty($photo_ids)) {
        return $markup;
    }
    
    // Add image URLs to schema
    $image_urls = array();
    
    foreach ($photo_ids as $photo_id) {
        $image_url = wp_get_attachment_image_url($photo_id, 'full');
        if ($image_url) {
            $image_urls[] = $image_url;
        }
    }
    
    if (!empty($image_urls)) {
        $markup['image'] = $image_urls;
    }
    
    return $markup;
}

/**
 * Add admin column for reviews with photos
 */
function aqualuxe_add_review_photo_column($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        
        // Add photos column after comment column
        if ($key === 'comment') {
            $new_columns['review_photos'] = __('Photos', 'aqualuxe');
        }
    }
    
    return $new_columns;
}

/**
 * Display review photo count in admin column
 */
function aqualuxe_review_photo_column_content($column, $comment_id) {
    if ($column !== 'review_photos') {
        return;
    }
    
    // Get photo IDs
    $photo_ids = get_comment_meta($comment_id, 'review_photo_id');
    
    if (empty($photo_ids)) {
        echo '—';
        return;
    }
    
    $count = count($photo_ids);
    
    echo '<a href="' . esc_url(admin_url('comment.php?action=editcomment&c=' . $comment_id)) . '">';
    echo '<span class="review-photo-count">' . esc_html($count) . '</span>';
    echo '</a>';
}

/**
 * Add filter for reviews with photos
 */
function aqualuxe_filter_reviews_with_photos() {
    $screen = get_current_screen();
    
    if (!$screen || $screen->id !== 'edit-comments') {
        return;
    }
    
    $selected = isset($_GET['has_photos']) ? $_GET['has_photos'] : '';
    
    echo '<select name="has_photos">';
    echo '<option value="">' . esc_html__('All reviews', 'aqualuxe') . '</option>';
    echo '<option value="1" ' . selected($selected, '1', false) . '>' . esc_html__('Reviews with photos', 'aqualuxe') . '</option>';
    echo '</select>';
}

/**
 * Filter reviews with photos in query
 */
function aqualuxe_filter_reviews_with_photos_query($clauses, $query) {
    global $wpdb;
    
    if (!isset($_GET['has_photos']) || $_GET['has_photos'] !== '1') {
        return $clauses;
    }
    
    $clauses['join'] .= " LEFT JOIN $wpdb->commentmeta AS cm ON $wpdb->comments.comment_ID = cm.comment_id AND cm.meta_key = 'has_review_photos'";
    $clauses['where'] .= " AND cm.meta_value = '1'";
    
    return $clauses;
}

/**
 * AJAX handler for deleting review photos
 */
function aqualuxe_ajax_delete_review_photo() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_review_photo')) {
        wp_send_json_error(__('Security check failed', 'aqualuxe'));
    }
    
    // Check permissions
    if (!current_user_can('moderate_comments')) {
        wp_send_json_error(__('You do not have permission to do this', 'aqualuxe'));
    }
    
    // Check required data
    if (!isset($_POST['comment_id']) || !isset($_POST['photo_id'])) {
        wp_send_json_error(__('Missing required data', 'aqualuxe'));
    }
    
    $comment_id = intval($_POST['comment_id']);
    $photo_id = intval($_POST['photo_id']);
    
    // Verify photo belongs to this comment
    $photo_ids = get_comment_meta($comment_id, 'review_photo_id');
    
    if (!in_array($photo_id, $photo_ids)) {
        wp_send_json_error(__('Photo does not belong to this review', 'aqualuxe'));
    }
    
    // Delete the photo
    delete_comment_meta($comment_id, 'review_photo_id', $photo_id);
    wp_delete_attachment($photo_id, true);
    
    // Check if this was the last photo
    $remaining_photos = get_comment_meta($comment_id, 'review_photo_id');
    
    if (empty($remaining_photos)) {
        delete_comment_meta($comment_id, 'has_review_photos');
    }
    
    wp_send_json_success(__('Photo deleted successfully', 'aqualuxe'));
}

/**
 * Add review photo lightbox to footer
 */
function aqualuxe_review_photo_lightbox() {
    // Only add on product pages
    if (!is_product()) {
        return;
    }
    
    ?>
    <div id="review-photo-lightbox" class="review-photo-lightbox">
        <div class="lightbox-content">
            <span class="close-lightbox">&times;</span>
            <img class="lightbox-image" src="" alt="">
            <div class="lightbox-nav">
                <button class="prev-photo"><?php esc_html_e('Previous', 'aqualuxe'); ?></button>
                <span class="photo-counter"></span>
                <button class="next-photo"><?php esc_html_e('Next', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Add meta box for review photos in admin
 */
function aqualuxe_add_review_photo_meta_box($comment) {
    // Check if this comment has photos
    $photo_ids = get_comment_meta($comment->comment_ID, 'review_photo_id');
    
    if (empty($photo_ids)) {
        return;
    }
    
    add_meta_box(
        'aqualuxe_review_photos',
        __('Review Photos', 'aqualuxe'),
        'aqualuxe_review_photo_meta_box_callback',
        'comment',
        'normal',
        'high'
    );
}

/**
 * Review photo meta box callback
 */
function aqualuxe_review_photo_meta_box_callback($comment) {
    // Get photo IDs
    $photo_ids = get_comment_meta($comment->comment_ID, 'review_photo_id');
    
    if (empty($photo_ids)) {
        return;
    }
    
    wp_nonce_field('aqualuxe_review_photo_meta', 'aqualuxe_review_photo_nonce');
    
    echo '<div class="review-photos-admin">';
    
    foreach ($photo_ids as $photo_id) {
        $thumbnail = wp_get_attachment_image_url($photo_id, 'thumbnail');
        $full_size = wp_get_attachment_image_url($photo_id, 'full');
        
        if ($thumbnail && $full_size) {
            echo '<div class="review-photo-item" data-photo-id="' . esc_attr($photo_id) . '">';
            echo '<div class="photo-preview">';
            echo '<a href="' . esc_url($full_size) . '" target="_blank">';
            echo '<img src="' . esc_url($thumbnail) . '" alt="' . esc_attr__('Review Photo', 'aqualuxe') . '" />';
            echo '</a>';
            echo '</div>';
            echo '<div class="photo-actions">';
            echo '<label><input type="checkbox" name="review_photo_approved[]" value="' . esc_attr($photo_id) . '" ' . checked(get_post_meta($photo_id, '_approved', true), '1', false) . ' /> ' . esc_html__('Approved', 'aqualuxe') . '</label>';
            echo '<button type="button" class="button delete-review-photo" data-comment-id="' . esc_attr($comment->comment_ID) . '" data-photo-id="' . esc_attr($photo_id) . '">' . esc_html__('Delete', 'aqualuxe') . '</button>';
            echo '</div>';
            echo '</div>';
        }
    }
    
    echo '</div>';
}

/**
 * Save review photo meta
 */
function aqualuxe_save_review_photo_meta($comment_id) {
    // Check nonce
    if (!isset($_POST['aqualuxe_review_photo_nonce']) || !wp_verify_nonce($_POST['aqualuxe_review_photo_nonce'], 'aqualuxe_review_photo_meta')) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('moderate_comments')) {
        return;
    }
    
    // Get photo IDs
    $photo_ids = get_comment_meta($comment_id, 'review_photo_id');
    
    if (empty($photo_ids)) {
        return;
    }
    
    // Get approved photos
    $approved_photos = isset($_POST['review_photo_approved']) ? $_POST['review_photo_approved'] : array();
    
    // Update approval status for each photo
    foreach ($photo_ids as $photo_id) {
        if (in_array($photo_id, $approved_photos)) {
            update_post_meta($photo_id, '_approved', '1');
        } else {
            update_post_meta($photo_id, '_approved', '0');
        }
    }
}

/**
 * Enqueue scripts and styles for review system
 */
function aqualuxe_review_system_scripts() {
    // Only enqueue on product pages
    if (!is_product()) {
        return;
    }
    
    wp_enqueue_style(
        'aqualuxe-review-system',
        AQUALUXE_URI . 'dist/css/review-system.css',
        array(),
        AQUALUXE_VERSION
    );
    
    wp_enqueue_script(
        'aqualuxe-review-system',
        AQUALUXE_URI . 'dist/js/review-system.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    wp_localize_script('aqualuxe-review-system', 'AqualuxeReviews', array(
        'max_file_size' => 2 * 1024 * 1024, // 2MB
        'max_files' => get_option('aqualuxe_max_review_photos', 3),
        'i18n' => array(
            'file_too_large' => __('File is too large. Maximum size is 2MB.', 'aqualuxe'),
            'invalid_file_type' => __('Invalid file type. Only images are allowed.', 'aqualuxe'),
            'too_many_files' => __('Too many files. Maximum is %d.', 'aqualuxe'),
        ),
    ));
}

/**
 * Enqueue admin scripts and styles for review system
 */
function aqualuxe_review_admin_scripts($hook) {
    // Only enqueue on comment edit page
    if ($hook !== 'comment.php') {
        return;
    }
    
    wp_enqueue_style(
        'aqualuxe-review-admin',
        AQUALUXE_URI . 'dist/css/review-admin.css',
        array(),
        AQUALUXE_VERSION
    );
    
    wp_enqueue_script(
        'aqualuxe-review-admin',
        AQUALUXE_URI . 'dist/js/review-admin.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    wp_localize_script('aqualuxe-review-admin', 'AqualuxeReviewAdmin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_review_photo'),
        'i18n' => array(
            'confirm_delete' => __('Are you sure you want to delete this photo?', 'aqualuxe'),
            'deleting' => __('Deleting...', 'aqualuxe'),
            'deleted' => __('Deleted', 'aqualuxe'),
            'error' => __('Error', 'aqualuxe'),
        ),
    ));
}

/**
 * Add review system settings to WooCommerce
 */
function aqualuxe_review_system_settings($settings) {
    $review_settings = array(
        array(
            'title' => __('Review Photos', 'aqualuxe'),
            'type'  => 'title',
            'desc'  => __('Settings for customer review photos', 'aqualuxe'),
            'id'    => 'aqualuxe_review_photos',
        ),
        array(
            'title'   => __('Maximum Photos', 'aqualuxe'),
            'desc'    => __('Maximum number of photos a customer can upload with a review', 'aqualuxe'),
            'id'      => 'aqualuxe_max_review_photos',
            'default' => '3',
            'type'    => 'number',
            'custom_attributes' => array(
                'min'  => '1',
                'max'  => '10',
                'step' => '1',
            ),
        ),
        array(
            'title'   => __('Moderate Photo Reviews', 'aqualuxe'),
            'desc'    => __('Hold reviews with photos for moderation', 'aqualuxe'),
            'id'      => 'aqualuxe_moderate_photo_reviews',
            'default' => 'yes',
            'type'    => 'checkbox',
        ),
        array(
            'type' => 'sectionend',
            'id'   => 'aqualuxe_review_photos',
        ),
    );
    
    return array_merge($settings, $review_settings);
}
add_filter('woocommerce_product_review_settings', 'aqualuxe_review_system_settings');

/**
 * Create CSS file for review system
 */
function aqualuxe_create_review_system_css() {
    // Create directory if it doesn't exist
    $css_dir = AQUALUXE_DIR . 'dist/css';
    if (!file_exists($css_dir)) {
        wp_mkdir_p($css_dir);
    }
    
    // CSS content
    $css = <<<CSS
/**
 * AquaLuxe Review System Styles
 */

/* Review photo upload field */
.comment-form-review-photos {
    margin-bottom: 1.5em;
}

.comment-form-review-photos label {
    display: block;
    margin-bottom: 0.5em;
}

.comment-form-review-photos .description {
    display: block;
    font-size: 0.85em;
    margin-top: 0.3em;
    color: #767676;
}

.review-photo-previews {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.review-photo-preview {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid #ddd;
}

.review-photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.review-photo-preview .remove-photo {
    position: absolute;
    top: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.5);
    color: white;
    width: 20px;
    height: 20px;
    line-height: 20px;
    text-align: center;
    cursor: pointer;
    font-size: 12px;
}

/* Review photos display */
.review-photos {
    margin-top: 1.5em;
}

.review-photos h4 {
    margin-bottom: 0.5em;
    font-size: 1em;
}

.review-photo-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.review-photo {
    display: block;
    width: 80px;
    height: 80px;
    border-radius: 4px;
    overflow: hidden;
    border: 1px solid #ddd;
    cursor: pointer;
}

.review-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.review-photo:hover img {
    transform: scale(1.05);
}

/* Lightbox */
.review-photo-lightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
}

.lightbox-content {
    position: relative;
    margin: auto;
    padding: 20px;
    width: 80%;
    max-width: 800px;
    height: 80%;
    display: flex;
    flex-direction: column;
}

.close-lightbox {
    position: absolute;
    top: 10px;
    right: 25px;
    color: white;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
}

.lightbox-image {
    max-width: 100%;
    max-height: calc(100% - 60px);
    margin: auto;
    display: block;
    object-fit: contain;
}

.lightbox-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    color: white;
}

.prev-photo,
.next-photo {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 4px;
}

.prev-photo:hover,
.next-photo:hover {
    background: rgba(255, 255, 255, 0.3);
}

.photo-counter {
    color: white;
    font-size: 14px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .lightbox-content {
        width: 95%;
        height: 90%;
        padding: 10px;
    }
    
    .close-lightbox {
        top: 5px;
        right: 15px;
    }
}
CSS;
    
    // Write CSS file
    file_put_contents($css_dir . '/review-system.css', $css);
    
    // Admin CSS content
    $admin_css = <<<CSS
/**
 * AquaLuxe Review Admin Styles
 */

.review-photos-admin {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 10px;
}

.review-photo-item {
    width: 150px;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    background: #f9f9f9;
}

.photo-preview {
    width: 100%;
    height: 100px;
    margin-bottom: 10px;
    overflow: hidden;
    border-radius: 3px;
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.review-photo-count {
    display: inline-block;
    background: #e7e7e7;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    font-size: 12px;
}
CSS;
    
    // Write admin CSS file
    file_put_contents($css_dir . '/review-admin.css', $admin_css);
}

/**
 * Create JavaScript file for review system
 */
function aqualuxe_create_review_system_js() {
    // Create directory if it doesn't exist
    $js_dir = AQUALUXE_DIR . 'dist/js';
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
    
    // JS content
    $js = <<<JS
/**
 * AquaLuxe Review System Scripts
 */
(function($) {
    'use strict';
    
    // File upload preview
    function handleFileUpload() {
        const fileInput = $('#review_photos');
        const previewContainer = $('.review-photo-previews');
        const maxFiles = parseInt(fileInput.data('max-files'), 10) || 3;
        const maxSize = AqualuxeReviews.max_file_size || 2 * 1024 * 1024; // 2MB
        
        fileInput.on('change', function() {
            previewContainer.empty();
            
            const files = this.files;
            
            // Check if too many files
            if (files.length > maxFiles) {
                alert(AqualuxeReviews.i18n.too_many_files.replace('%d', maxFiles));
                this.value = '';
                return;
            }
            
            // Process each file
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Check file size
                if (file.size > maxSize) {
                    alert(AqualuxeReviews.i18n.file_too_large);
                    this.value = '';
                    previewContainer.empty();
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    alert(AqualuxeReviews.i18n.invalid_file_type);
                    this.value = '';
                    previewContainer.empty();
                    return;
                }
                
                // Create preview
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const preview = $('<div class="review-photo-preview"></div>');
                    const img = $('<img>').attr('src', e.target.result);
                    const removeBtn = $('<span class="remove-photo">&times;</span>');
                    
                    preview.append(img);
                    preview.append(removeBtn);
                    previewContainer.append(preview);
                    
                    // Handle remove button
                    removeBtn.on('click', function() {
                        preview.remove();
                        
                        // Reset file input if all previews are removed
                        if (previewContainer.children().length === 0) {
                            fileInput.val('');
                        }
                    });
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Photo lightbox
    function initPhotoLightbox() {
        const lightbox = $('#review-photo-lightbox');
        const lightboxImage = lightbox.find('.lightbox-image');
        const prevButton = lightbox.find('.prev-photo');
        const nextButton = lightbox.find('.next-photo');
        const closeButton = lightbox.find('.close-lightbox');
        const counter = lightbox.find('.photo-counter');
        
        let currentReviewId = null;
        let currentPhotoIndex = 0;
        let photoGallery = [];
        
        // Open lightbox when clicking on a photo
        $(document).on('click', '.review-photo', function(e) {
            e.preventDefault();
            
            const reviewId = $(this).data('review-id');
            const photoId = $(this).data('photo-id');
            
            // Get all photos in this review
            const reviewPhotos = $(`.review-photo[data-review-id="${reviewId}"]`);
            
            // Reset gallery
            photoGallery = [];
            currentReviewId = reviewId;
            
            // Build gallery array
            reviewPhotos.each(function() {
                photoGallery.push({
                    id: $(this).data('photo-id'),
                    url: $(this).attr('href')
                });
            });
            
            // Find current photo index
            currentPhotoIndex = photoGallery.findIndex(photo => photo.id === photoId);
            
            // Show the photo
            showCurrentPhoto();
            
            // Show lightbox
            lightbox.fadeIn(300);
        });
        
        // Close lightbox
        closeButton.on('click', function() {
            lightbox.fadeOut(300);
        });
        
        // Close on ESC key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && lightbox.is(':visible')) {
                lightbox.fadeOut(300);
            }
        });
        
        // Previous photo
        prevButton.on('click', function() {
            currentPhotoIndex = (currentPhotoIndex - 1 + photoGallery.length) % photoGallery.length;
            showCurrentPhoto();
        });
        
        // Next photo
        nextButton.on('click', function() {
            currentPhotoIndex = (currentPhotoIndex + 1) % photoGallery.length;
            showCurrentPhoto();
        });
        
        // Keyboard navigation
        $(document).on('keydown', function(e) {
            if (!lightbox.is(':visible')) {
                return;
            }
            
            if (e.key === 'ArrowLeft') {
                currentPhotoIndex = (currentPhotoIndex - 1 + photoGallery.length) % photoGallery.length;
                showCurrentPhoto();
            } else if (e.key === 'ArrowRight') {
                currentPhotoIndex = (currentPhotoIndex + 1) % photoGallery.length;
                showCurrentPhoto();
            }
        });
        
        // Show current photo
        function showCurrentPhoto() {
            if (photoGallery.length === 0) {
                return;
            }
            
            const photo = photoGallery[currentPhotoIndex];
            lightboxImage.attr('src', photo.url);
            counter.text(`${currentPhotoIndex + 1} / ${photoGallery.length}`);
            
            // Enable/disable navigation buttons
            if (photoGallery.length <= 1) {
                prevButton.prop('disabled', true);
                nextButton.prop('disabled', true);
            } else {
                prevButton.prop('disabled', false);
                nextButton.prop('disabled', false);
            }
        }
    }
    
    // Initialize
    $(document).ready(function() {
        handleFileUpload();
        initPhotoLightbox();
    });
    
})(jQuery);
JS;
    
    // Write JS file
    file_put_contents($js_dir . '/review-system.js', $js);
    
    // Admin JS content
    $admin_js = <<<JS
/**
 * AquaLuxe Review Admin Scripts
 */
(function($) {
    'use strict';
    
    // Handle photo deletion
    function handlePhotoDelete() {
        $('.delete-review-photo').on('click', function() {
            const button = $(this);
            const commentId = button.data('comment-id');
            const photoId = button.data('photo-id');
            const photoItem = button.closest('.review-photo-item');
            
            // Confirm deletion
            if (!confirm(AqualuxeReviewAdmin.i18n.confirm_delete)) {
                return;
            }
            
            // Disable button and show loading state
            button.prop('disabled', true).text(AqualuxeReviewAdmin.i18n.deleting);
            
            // Send AJAX request
            $.ajax({
                url: AqualuxeReviewAdmin.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_delete_review_photo',
                    nonce: AqualuxeReviewAdmin.nonce,
                    comment_id: commentId,
                    photo_id: photoId
                },
                success: function(response) {
                    if (response.success) {
                        // Remove photo item
                        photoItem.fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no photos left, hide the meta box
                            if ($('.review-photo-item').length === 0) {
                                $('#aqualuxe_review_photos').hide();
                            }
                        });
                    } else {
                        // Show error
                        button.prop('disabled', false).text(AqualuxeReviewAdmin.i18n.error);
                        alert(response.data || AqualuxeReviewAdmin.i18n.error);
                    }
                },
                error: function() {
                    // Show error
                    button.prop('disabled', false).text(AqualuxeReviewAdmin.i18n.error);
                    alert(AqualuxeReviewAdmin.i18n.error);
                }
            });
        });
    }
    
    // Initialize
    $(document).ready(function() {
        handlePhotoDelete();
    });
    
})(jQuery);
JS;
    
    // Write admin JS file
    file_put_contents($js_dir . '/review-admin.js', $admin_js);
}

// Create CSS and JS files
add_action('after_setup_theme', 'aqualuxe_create_review_system_css');
add_action('after_setup_theme', 'aqualuxe_create_review_system_js');