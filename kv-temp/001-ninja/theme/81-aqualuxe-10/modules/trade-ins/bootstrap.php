<?php
/** Trade-ins module: CPT & form shortcode */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_post_type('tradein', [
        'label' => __('Trade-ins', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor','custom-fields'],
        'show_in_rest' => true,
    ]);
});

add_shortcode('ax_tradein_form', function(){
    $nonce = wp_create_nonce('ax_tradein_submit');
    ob_start();
    ?>
    <form class="ax-form grid gap-4" method="post">
        <input type="hidden" name="ax_nonce" value="<?php echo esc_attr($nonce); ?>" />
        <label>
            <span><?php esc_html_e('Your Name','aqualuxe'); ?></span>
            <input class="border p-2 w-full" required name="ax_name" />
        </label>
        <label>
            <span><?php esc_html_e('Email','aqualuxe'); ?></span>
            <input class="border p-2 w-full" type="email" required name="ax_email" />
        </label>
        <label>
            <span><?php esc_html_e('Details','aqualuxe'); ?></span>
            <textarea class="border p-2 w-full" name="ax_details"></textarea>
        </label>
        <button class="bg-cyan-600 text-white px-4 py-2 rounded" type="submit" name="ax_tradein_submit" value="1"><?php esc_html_e('Submit','aqualuxe'); ?></button>
    </form>
    <?php
    return ob_get_clean();
});

add_action('init', function(){
    if (!isset($_POST['ax_tradein_submit'])) return;
    if (!isset($_POST['ax_nonce']) || !wp_verify_nonce(sanitize_text_field($_POST['ax_nonce']), 'ax_tradein_submit')) return;
    $name = sanitize_text_field($_POST['ax_name'] ?? '');
    $email = sanitize_email($_POST['ax_email'] ?? '');
    $details = wp_kses_post($_POST['ax_details'] ?? '');
    $id = wp_insert_post([
        'post_type' => 'tradein',
        'post_title' => $name . ' - ' . $email,
        'post_content' => $details,
        'post_status' => 'publish',
    ]);
    if ($id && !is_wp_error($id)) {
        wp_safe_redirect(add_query_arg('tradein', 'success', wp_get_referer() ?: home_url('/')));
        exit;
    }
});
