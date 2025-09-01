<?php
/** Franchise module: inquiry form + CPT */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_post_type('franchise_inquiry', [
        'label' => __('Franchise Inquiries','aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor','custom-fields'],
    ]);
});

add_shortcode('ax_franchise_form', function(){
    $nonce = wp_create_nonce('ax_franchise_submit');
    ob_start(); ?>
    <form method="post" class="grid gap-2">
      <input type="hidden" name="ax_nonce" value="<?php echo esc_attr($nonce); ?>" />
      <label><span><?php esc_html_e('Name','aqualuxe'); ?></span><input class="border p-2" name="ax_name" required></label>
      <label><span><?php esc_html_e('Email','aqualuxe'); ?></span><input class="border p-2" type="email" name="ax_email" required></label>
      <label><span><?php esc_html_e('Region','aqualuxe'); ?></span><input class="border p-2" name="ax_region"></label>
      <label><span><?php esc_html_e('Message','aqualuxe'); ?></span><textarea class="border p-2" name="ax_msg"></textarea></label>
      <button class="ax-btn"><?php esc_html_e('Submit','aqualuxe'); ?></button>
    </form>
    <?php return ob_get_clean();
});

add_action('init', function(){
    if (!isset($_POST['ax_nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['ax_nonce']), 'ax_franchise_submit')) return;
    $title = sanitize_text_field(($_POST['ax_name'] ?? '').' - '.($_POST['ax_region'] ?? ''));
    $content = 'Email: '.sanitize_email($_POST['ax_email'] ?? '')."\n\n".wp_kses_post($_POST['ax_msg'] ?? '');
    $id = wp_insert_post(['post_type'=>'franchise_inquiry','post_title'=>$title,'post_content'=>$content,'post_status'=>'publish']);
    if ($id && !is_wp_error($id)) { wp_safe_redirect(add_query_arg('franchise','ok', wp_get_referer() ?: home_url('/'))); exit; }
});
