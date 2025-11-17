<?php
/** Classifieds module: CPT, taxonomies, submission, templates (lightweight) */
namespace AquaLuxe\Modules\Classifieds;
if (!defined('ABSPATH')) { exit; }

// Register CPT and taxonomies
\add_action('init', function(){
    $esc_html__ = \function_exists('esc_html__') ? 'esc_html__' : null;
    $labels = [
        'name' => $esc_html__ ? (string) \call_user_func($esc_html__, 'Classifieds','aqualuxe') : 'Classifieds',
        'singular_name' => $esc_html__ ? (string) \call_user_func($esc_html__, 'Classified','aqualuxe') : 'Classified',
    ];
    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'classifieds'],
        'supports' => ['title','editor','thumbnail','author','custom-fields','excerpt'],
        'map_meta_cap' => true,
        'show_in_rest' => true,
    ];
    // Allow external filters to adjust CPT args
    if (\function_exists('apply_filters')) { $args = (array) \call_user_func('apply_filters','aqualuxe_classifieds_cpt_args', $args ); }
    if (\function_exists('register_post_type')) { \call_user_func('register_post_type','alx_classified', $args); }

    $tax_cat = [
        'label' => $esc_html__ ? (string) \call_user_func($esc_html__, 'Categories','aqualuxe') : 'Categories',
        'public' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'classified-category'],
        'show_in_rest' => true,
    ];
    if (\function_exists('apply_filters')) { $tax_cat = (array) \call_user_func('apply_filters','aqualuxe_classifieds_category_tax_args', $tax_cat ); }
    if (\function_exists('register_taxonomy')) { \call_user_func('register_taxonomy','alx_classified_category','alx_classified',$tax_cat); }

    $tax_loc = [
        'label' => $esc_html__ ? (string) \call_user_func($esc_html__, 'Locations','aqualuxe') : 'Locations',
        'public' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'classified-location'],
        'show_in_rest' => true,
    ];
    if (\function_exists('apply_filters')) { $tax_loc = (array) \call_user_func('apply_filters','aqualuxe_classifieds_location_tax_args', $tax_loc ); }
    if (\function_exists('register_taxonomy')) { \call_user_func('register_taxonomy','alx_classified_location','alx_classified',$tax_loc); }
});

// Front-end submit form (shortcode) with progressive enhancement
\add_shortcode('alx_classified_submit', function(){
    if (!\function_exists('is_user_logged_in') || !\call_user_func('is_user_logged_in')) {
        $esc = \function_exists('esc_html__') ? 'esc_html__' : null;
        $msg = $esc ? (string) \call_user_func($esc, 'Please log in to submit a listing.','aqualuxe') : 'Please log in to submit a listing.';
        return '<p>'.\esc_html($msg).'</p>';
    }
    $nonce = \function_exists('wp_create_nonce') ? (string) \call_user_func('wp_create_nonce','alx_classified_submit') : '';
    $out  = '<form method="post" enctype="multipart/form-data" class="alx-form">';
    $out .= '<input type="hidden" name="alx_cls_nonce" value="'.( \function_exists('esc_attr') ? \esc_attr($nonce) : $nonce ).'" />';
    // Simple honeypot
    $out .= '<div style="position:absolute;left:-999em;" aria-hidden="true"><label>Leave blank<input type="text" name="alx_hp" tabindex="-1" autocomplete="off" /></label></div>';
    $out .= '<label>'.( \function_exists('esc_html__') ? \esc_html__('Title','aqualuxe') : 'Title' ).'<input name="post_title" required class="alx-input" /></label>';
    $out .= '<label>'.( \function_exists('esc_html__') ? \esc_html__('Description','aqualuxe') : 'Description' ).'<textarea name="post_content" required class="alx-textarea"></textarea></label>';
    $cats = \function_exists('wp_dropdown_categories') ? (string) \call_user_func('wp_dropdown_categories',[ 'taxonomy'=>'alx_classified_category','hide_empty'=>false,'echo'=>false,'name'=>'alx_classified_category' ]) : '';
    $out .= '<label>'.( \function_exists('esc_html__') ? \esc_html__('Category','aqualuxe') : 'Category' ).$cats.'</label>';
    $out .= '<label>'.( \function_exists('esc_html__') ? \esc_html__('Location','aqualuxe') : 'Location' ).'<input name="alx_classified_location" class="alx-input" /></label>';
    $out .= '<label>'.( \function_exists('esc_html__') ? \esc_html__('Price','aqualuxe') : 'Price' ).'<input name="alx_price" type="number" step="0.01" min="0" class="alx-input" /></label>';
    $out .= '<button class="button" type="submit" name="alx_classified_submit">'.( \function_exists('esc_html__') ? \esc_html__('Submit','aqualuxe') : 'Submit' ).'</button>';
    $out .= '</form>';
    return $out;
});

// Handle submission (non-AJAX fallback)
\add_action('template_redirect', function(){
    if (!isset($_POST['alx_classified_submit'])) { return; }
    // Honeypot check
    if (!empty($_POST['alx_hp'])) { return; }
    if (!\function_exists('is_user_logged_in') || !\call_user_func('is_user_logged_in')) { return; }
    if (!isset($_POST['alx_cls_nonce']) || !\function_exists('wp_verify_nonce') || !\call_user_func('wp_verify_nonce', $_POST['alx_cls_nonce'], 'alx_classified_submit')) { return; }

    $title_raw = isset($_POST['post_title']) ? $_POST['post_title'] : '';
    if (\function_exists('wp_unslash')) { $title_raw = (string) \call_user_func('wp_unslash', $title_raw); }
    $title = \function_exists('sanitize_text_field') ? (string) \call_user_func('sanitize_text_field', $title_raw) : (string) $title_raw;

    $content_raw = isset($_POST['post_content']) ? $_POST['post_content'] : '';
    if (\function_exists('wp_unslash')) { $content_raw = (string) \call_user_func('wp_unslash', $content_raw); }
    $content = \function_exists('wp_kses_post') ? (string) \call_user_func('wp_kses_post', $content_raw) : (string) $content_raw;
    $cat = isset($_POST['alx_classified_category']) ? (int) $_POST['alx_classified_category'] : 0;
    $loc_raw = isset($_POST['alx_classified_location']) ? $_POST['alx_classified_location'] : '';
    if (\function_exists('wp_unslash')) { $loc_raw = (string) \call_user_func('wp_unslash', $loc_raw); }
    $loc = \function_exists('sanitize_text_field') ? (string) \call_user_func('sanitize_text_field', $loc_raw) : (string) $loc_raw;
    $price = isset($_POST['alx_price']) ? (float) $_POST['alx_price'] : 0;

    if (!$title || !$content) { return; }

    $default_status = 'pending';
    if (\function_exists('apply_filters')) { $default_status = (string) \call_user_func('apply_filters','aqualuxe_classifieds_default_status', 'pending' ); }
    $post_id = ( \function_exists('wp_insert_post') ? \call_user_func('wp_insert_post', [
        'post_type' => 'alx_classified',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => $default_status ?: 'pending',
        'post_author' => ( \function_exists('get_current_user_id') ? \call_user_func('get_current_user_id') : 0 ),
    ], true) : 0 );

    if ( \function_exists('is_wp_error') && \call_user_func('is_wp_error', $post_id) ) { return; }

    if ($cat && \function_exists('wp_set_post_terms')) { \call_user_func('wp_set_post_terms', $post_id, [$cat], 'alx_classified_category', false ); }
    if ($loc && \function_exists('wp_set_post_terms')) { \call_user_func('wp_set_post_terms', $post_id, [$loc], 'alx_classified_location', true ); }
    if ($price >= 0 && \function_exists('update_post_meta')) { \call_user_func('update_post_meta', $post_id, '_alx_price', $price ); }

    // Action hook after successful submission
    if (\function_exists('do_action')) { \call_user_func('do_action','aqualuxe_classifieds_submitted', $post_id ); }

    // Notify admin (best-effort)
    if (\function_exists('get_option') && \function_exists('wp_mail')) {
        $admin_email = (string) \call_user_func('get_option','admin_email');
        if ($admin_email) {
            $subject = ( \function_exists('sprintf') ? \sprintf('[%s] New classified submitted', ( \function_exists('get_bloginfo') ? \call_user_func('get_bloginfo','name') : 'Site' ) ) : 'New classified submitted' );
            $link = \function_exists('get_edit_post_link') ? (string) \call_user_func('get_edit_post_link', $post_id ) : '';
            $body = 'A new classified has been submitted and is pending review.' . ( $link ? "\n\nEdit: $link" : '' );
            \call_user_func('wp_mail', $admin_email, $subject, $body);
        }
    }

    if (\function_exists('wp_safe_redirect') && \function_exists('add_query_arg') && \function_exists('get_permalink') ) {
        \call_user_func('wp_safe_redirect', \call_user_func('add_query_arg','submitted','1', \call_user_func('get_permalink',$post_id)) );
        exit;
    }
});

// Price display helper
\add_filter('the_content', function($content){
    if ( !\function_exists('get_post_type') || \call_user_func('get_post_type') !== 'alx_classified') { return $content; }
    $price = \function_exists('get_post_meta') ? \call_user_func('get_post_meta', ( \function_exists('get_the_ID') ? \call_user_func('get_the_ID') : 0 ), '_alx_price', true ) : '';
    if ($price === '' || $price === null) { return $content; }
    $formatted = (string) $price;
    if (\function_exists('AquaLuxe\\Modules\\Multicurrency\\format_current_amount')) {
        $formatted = \call_user_func('AquaLuxe\\Modules\\Multicurrency\\format_current_amount', (float) $price );
    }
    $label = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Price:','aqualuxe') : 'Price:';
    $fmt = \function_exists('wp_kses_post') ? \call_user_func('wp_kses_post', $formatted ) : $formatted;
    return $content . '<p class="alx-price"><strong>'.\esc_html($label).'</strong> '.$fmt.'</p>';
});

// Admin columns: show Price in list table
\add_filter('manage_alx_classified_posts_columns', function($cols){
    if (!\is_array($cols)) { $cols = []; }
    $cols['alx_price'] = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Price','aqualuxe') : 'Price';
    return $cols;
});

\add_action('manage_alx_classified_posts_custom_column', function($column, $post_id){
    if ($column !== 'alx_price') { return; }
    $price = \function_exists('get_post_meta') ? \call_user_func('get_post_meta', (int) $post_id, '_alx_price', true ) : '';
    if ($price === '' || $price === null) { echo '&mdash;'; return; }
    $out = (string) $price;
    if (\function_exists('AquaLuxe\\Modules\\Multicurrency\\format_current_amount')) {
        $out = \call_user_func('AquaLuxe\\Modules\\Multicurrency\\format_current_amount', (float) $price );
    } else if (\function_exists('esc_html')) { $out = \esc_html($out); }
    echo \function_exists('wp_kses_post') ? \wp_kses_post($out) : $out;
}, 10, 2);

\add_filter('manage_edit-alx_classified_sortable_columns', function($cols){
    $cols['alx_price'] = 'alx_price';
    return $cols;
});

\add_action('pre_get_posts', function($q){
    if (!\function_exists('is_admin') || !\call_user_func('is_admin') || !\is_object($q)) { return; }
    $orderby = \method_exists($q,'get') ? $q->get('orderby') : '';
    $post_type = \method_exists($q,'get') ? $q->get('post_type') : '';
    if ($post_type !== 'alx_classified' || $orderby !== 'alx_price') { return; }
    if (\method_exists($q,'set')) {
        $q->set('meta_key','_alx_price');
        $q->set('orderby','meta_value_num');
    }
});

// Admin row action: Approve (publish) pending classifieds
\add_filter('post_row_actions', function($actions, $post){
    if (!\is_array($actions) || !\is_object($post)) { return $actions; }
    $ptype = isset($post->post_type) ? (string) $post->post_type : '';
    $pstatus = isset($post->post_status) ? (string) $post->post_status : '';
    if ($ptype !== 'alx_classified' || $pstatus !== 'pending') { return $actions; }
    if (!\function_exists('current_user_can') || !\call_user_func('current_user_can','publish_post', $post->ID)) { return $actions; }
    $nonce = \function_exists('wp_create_nonce') ? (string) \call_user_func('wp_create_nonce','alx_cls_approve_'.$post->ID) : '';
    $base = \function_exists('admin_url') ? (string) \call_user_func('admin_url','edit.php') : 'edit.php';
    $url = $base;
    if (\function_exists('add_query_arg')) {
        $url = \call_user_func('add_query_arg', [
            'post_type' => 'alx_classified',
            'action' => 'alx_cls_approve',
            'post' => (int) $post->ID,
            '_wpnonce' => $nonce,
        ], $base);
    }
    $label = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Approve','aqualuxe') : 'Approve';
    $actions['alx_cls_approve'] = '<a href="'.( \function_exists('esc_url') ? \esc_url($url) : $url ).'">'.( \function_exists('esc_html') ? \esc_html($label) : $label ).'</a>';
    return $actions;
}, 10, 2);

// Handle approve action
\add_action('admin_init', function(){
    if (!isset($_GET['action']) || $_GET['action'] !== 'alx_cls_approve') { return; }
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id) { return; }
    // Capability check
    if (!\function_exists('current_user_can') || !\call_user_func('current_user_can','publish_post', $post_id)) { return; }
    // Nonce check
    if (!isset($_GET['_wpnonce']) || !\function_exists('wp_verify_nonce') || !\call_user_func('wp_verify_nonce', $_GET['_wpnonce'], 'alx_cls_approve_'.$post_id)) { return; }
    // Publish
    if (\function_exists('wp_update_post')) { \call_user_func('wp_update_post', [ 'ID' => $post_id, 'post_status' => 'publish' ] ); }
    // Email post author on approval (best-effort)
    if (\function_exists('get_post_field') && \function_exists('get_userdata') && \function_exists('wp_mail')) {
        $author_id = (int) \call_user_func('get_post_field','post_author', $post_id );
        $ud = $author_id ? \call_user_func('get_userdata', $author_id ) : null;
        $author_email = ($ud && isset($ud->user_email)) ? (string) $ud->user_email : '';
        if ($author_email) {
            $site = \function_exists('get_bloginfo') ? (string) \call_user_func('get_bloginfo','name') : 'Site';
            $subject = 'Your classified has been approved';
            if (\function_exists('apply_filters')) { $subject = (string) \call_user_func('apply_filters','aqualuxe_classifieds_approved_subject', '['.$site.'] '.$subject, $post_id ); }
            $link = \function_exists('get_permalink') ? (string) \call_user_func('get_permalink', $post_id ) : '';
            $body = 'Hi, your classified is now live.' . ( $link ? "\n\nView: $link" : '' );
            if (\function_exists('apply_filters')) { $body = (string) \call_user_func('apply_filters','aqualuxe_classifieds_approved_body', $body, $post_id ); }
            \call_user_func('wp_mail', $author_email, $subject, $body);
        }
    }
    // Redirect back with message
    $dest = \function_exists('admin_url') ? (string) \call_user_func('admin_url', 'edit.php?post_type=alx_classified&alx_cls_msg=approved') : 'edit.php?post_type=alx_classified&alx_cls_msg=approved';
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $dest ); exit; }
});

// Output JSON-LD in head on single classified
\add_action('wp_head', function(){
    if (!\function_exists('is_singular') || !\call_user_func('is_singular','alx_classified')) { return; }
    $pid = \function_exists('get_the_ID') ? (int) \call_user_func('get_the_ID') : 0;
    $title = \function_exists('get_the_title') ? (string) \call_user_func('get_the_title', $pid ) : '';
    $perma = \function_exists('get_permalink') ? (string) \call_user_func('get_permalink', $pid ) : '';
    $price = \function_exists('get_post_meta') ? \call_user_func('get_post_meta', $pid, '_alx_price', true ) : '';
    $currency = \function_exists('AquaLuxe\\Modules\\Multicurrency\\current_currency') ? (string) \call_user_func('AquaLuxe\\Modules\\Multicurrency\\current_currency') : 'USD';
    $data = [ '@context' => 'https://schema.org', '@type' => 'Product', 'name' => $title, 'url' => $perma ];
    if ($price !== '' && is_numeric($price)) {
        $data['offers'] = [ '@type' => 'Offer', 'price' => (string) $price, 'priceCurrency' => $currency ?: 'USD', 'url' => $perma, 'availability' => 'https://schema.org/InStock' ];
    }
    $json = \function_exists('wp_json_encode') ? \call_user_func('wp_json_encode', $data, 0 ) : json_encode($data);
    echo "\n<script type=\"application/ld+json\">" . $json . "</script>\n";
});

// Admin notice for approve
\add_action('admin_notices', function(){
    if (!isset($_GET['alx_cls_msg']) || $_GET['alx_cls_msg'] !== 'approved') { return; }
    $msg = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Classified approved.','aqualuxe') : 'Classified approved.';
    echo '<div class="notice notice-success is-dismissible"><p>'.( \function_exists('esc_html') ? \esc_html($msg) : $msg ).'</p></div>';
});

// Admin row action: Reject (trash) pending classifieds
\add_filter('post_row_actions', function($actions, $post){
    if (!\is_array($actions) || !\is_object($post)) { return $actions; }
    $ptype = isset($post->post_type) ? (string) $post->post_type : '';
    $pstatus = isset($post->post_status) ? (string) $post->post_status : '';
    if ($ptype !== 'alx_classified' || $pstatus !== 'pending') { return $actions; }
    if (!\function_exists('current_user_can') || !\call_user_func('current_user_can','delete_post', $post->ID)) { return $actions; }
    $nonce = \function_exists('wp_create_nonce') ? (string) \call_user_func('wp_create_nonce','alx_cls_reject_'.$post->ID) : '';
    $base = \function_exists('admin_url') ? (string) \call_user_func('admin_url','edit.php') : 'edit.php';
    $url = $base;
    if (\function_exists('add_query_arg')) {
        $url = \call_user_func('add_query_arg', [
            'post_type' => 'alx_classified',
            'action' => 'alx_cls_reject',
            'post' => (int) $post->ID,
            '_wpnonce' => $nonce,
        ], $base);
    }
    $label = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Reject','aqualuxe') : 'Reject';
    $actions['alx_cls_reject'] = '<a href="'.( \function_exists('esc_url') ? \esc_url($url) : $url ).'" class="submitdelete">'.( \function_exists('esc_html') ? \esc_html($label) : $label ).'</a>';
    return $actions;
}, 10, 2);

// Handle reject action
\add_action('admin_init', function(){
    if (!isset($_GET['action']) || $_GET['action'] !== 'alx_cls_reject') { return; }
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : 0;
    if (!$post_id) { return; }
    if (!\function_exists('current_user_can') || !\call_user_func('current_user_can','delete_post', $post_id)) { return; }
    if (!isset($_GET['_wpnonce']) || !\function_exists('wp_verify_nonce') || !\call_user_func('wp_verify_nonce', $_GET['_wpnonce'], 'alx_cls_reject_'.$post_id)) { return; }
    if (\function_exists('wp_trash_post')) { \call_user_func('wp_trash_post', $post_id ); }
    $dest = \function_exists('admin_url') ? (string) \call_user_func('admin_url', 'edit.php?post_type=alx_classified&alx_cls_msg=rejected') : 'edit.php?post_type=alx_classified&alx_cls_msg=rejected';
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $dest ); exit; }
});

// Admin notice for reject
\add_action('admin_notices', function(){
    if (!isset($_GET['alx_cls_msg']) || $_GET['alx_cls_msg'] !== 'rejected') { return; }
    $msg = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Classified moved to trash.','aqualuxe') : 'Classified moved to trash.';
    echo '<div class="notice notice-warning is-dismissible"><p>'.( \function_exists('esc_html') ? \esc_html($msg) : $msg ).'</p></div>';
});

// Shortcode: My Classifieds (list current user's entries)
\add_shortcode('alx_my_classifieds', function(){
    if (!\function_exists('is_user_logged_in') || !\call_user_func('is_user_logged_in')) {
        $m = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','Please log in to view your classifieds.','aqualuxe') : 'Please log in to view your classifieds.';
        return '<p>'.( \function_exists('esc_html') ? \esc_html($m) : $m ).'</p>';
    }
    $uid = \function_exists('get_current_user_id') ? (int) \call_user_func('get_current_user_id') : 0;
    if (!$uid) { return ''; }
    $posts = \function_exists('get_posts') ? (array) \call_user_func('get_posts', [
        'post_type' => 'alx_classified',
        'author' => $uid,
        'posts_per_page' => 20,
        'post_status' => [ 'pending', 'publish', 'draft' ],
        'orderby' => 'date',
        'order' => 'DESC',
    ]) : [];
    if (!$posts) {
        $m = \function_exists('esc_html__') ? (string) \call_user_func('esc_html__','You have no classifieds yet.','aqualuxe') : 'You have no classifieds yet.';
        return '<p>'.( \function_exists('esc_html') ? \esc_html($m) : $m ).'</p>';
    }
    $out = '<div class="alx-container"><table class="alx-table"><thead><tr>'
        .'<th>'.( \function_exists('esc_html__') ? \esc_html__('Title','aqualuxe') : 'Title' ).'</th>'
        .'<th>'.( \function_exists('esc_html__') ? \esc_html__('Status','aqualuxe') : 'Status' ).'</th>'
        .'<th>'.( \function_exists('esc_html__') ? \esc_html__('Price','aqualuxe') : 'Price' ).'</th>'
        .'<th>'.( \function_exists('esc_html__') ? \esc_html__('Actions','aqualuxe') : 'Actions' ).'</th>'
        .'</tr></thead><tbody>';
    foreach ($posts as $p) {
        $pid = isset($p->ID) ? (int) $p->ID : 0;
        $title = isset($p->post_title) ? (string) $p->post_title : '';
        $status = isset($p->post_status) ? (string) $p->post_status : '';
        $price = \function_exists('get_post_meta') ? \call_user_func('get_post_meta', $pid, '_alx_price', true ) : '';
        $price_out = (string) $price;
        if ($price_out !== '' && \function_exists('AquaLuxe\\Modules\\Multicurrency\\format_current_amount')) {
            $price_out = \call_user_func('AquaLuxe\\Modules\\Multicurrency\\format_current_amount', (float) $price );
        } else if (\function_exists('esc_html')) { $price_out = \esc_html($price_out); }
        $view = '#';
        if (\function_exists('get_permalink')) { $view = (string) \call_user_func('get_permalink', $pid); }
        $view = \function_exists('esc_url') ? \esc_url($view) : $view;
        $edit_link = '';
        if (\function_exists('current_user_can') && \call_user_func('current_user_can','edit_post', $pid) && \function_exists('get_edit_post_link')) {
            $el = (string) \call_user_func('get_edit_post_link', $pid );
            $edit_link = $el ? ' | <a href="'.( \function_exists('esc_url') ? \esc_url($el) : $el ).'">'.( \function_exists('esc_html__') ? \esc_html__('Edit','aqualuxe') : 'Edit' ).'</a>' : '';
        }
        $out .= '<tr>'
            .'<td>'.( \function_exists('esc_html') ? \esc_html($title) : $title ).'</td>'
            .'<td>'.( \function_exists('esc_html') ? \esc_html($status) : $status ).'</td>'
            .'<td>'.( \function_exists('wp_kses_post') ? \wp_kses_post($price_out) : $price_out ).'</td>'
            .'<td><a href="'.$view.'">'.( \function_exists('esc_html__') ? \esc_html__('View','aqualuxe') : 'View' ).'</a>'.$edit_link.'</td>'
            .'</tr>';
    }
    $out .= '</tbody></table></div>';
    return $out;
});
