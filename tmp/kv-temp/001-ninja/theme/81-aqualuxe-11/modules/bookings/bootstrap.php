<?php
/** Bookings module: lightweight booking request form (non-Woo core), schedules CPT */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_post_type('booking', [
        'label' => __('Bookings','aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor','custom-fields'],
        'show_in_rest' => true,
        'capability_type' => 'post'
    ]);
});

add_shortcode('ax_booking_form', function(){
    $nonce = wp_create_nonce('ax_booking_submit');
    return '<form method="post" class="grid gap-2">
        <input type="hidden" name="ax_nonce" value="'.esc_attr($nonce).'" />
        <label class="grid"><span>'.esc_html__('Name','aqualuxe').'</span><input class="border p-2" required name="ax_name"></label>
        <label class="grid"><span>'.esc_html__('Email','aqualuxe').'</span><input class="border p-2" type="email" required name="ax_email"></label>
        <label class="grid"><span>'.esc_html__('Service','aqualuxe').'</span><input class="border p-2" name="ax_service"></label>
        <label class="grid"><span>'.esc_html__('Preferred Date','aqualuxe').'</span><input class="border p-2" type="date" name="ax_date"></label>
        <button class="ax-btn">'.esc_html__('Request Booking','aqualuxe').'</button>
    </form>';
});

add_action('init', function(){
    if (!isset($_POST['ax_nonce']) || !isset($_POST['ax_name']) || !wp_verify_nonce(sanitize_text_field($_POST['ax_nonce']), 'ax_booking_submit')) return;
    $name = sanitize_text_field($_POST['ax_name']);
    $email = sanitize_email($_POST['ax_email'] ?? '');
    $svc = sanitize_text_field($_POST['ax_service'] ?? '');
    $date = sanitize_text_field($_POST['ax_date'] ?? '');
    $id = wp_insert_post(['post_type'=>'booking','post_title'=>$name.' - '.$date,'post_content'=>'Service: '.$svc.'\nEmail: '.$email,'post_status'=>'publish']);
    if ($id && !is_wp_error($id)) { wp_safe_redirect(add_query_arg('booking','requested', wp_get_referer() ?: home_url('/'))); exit; }
});
