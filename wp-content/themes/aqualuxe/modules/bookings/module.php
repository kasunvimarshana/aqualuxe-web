<?php
namespace AquaLuxe\Modules\Bookings;

class Module {
    public static function init(): void {
        add_action('init', [__CLASS__, 'register_cpt']);
        add_shortcode('aqlx_booking_form', [__CLASS__, 'form']);
        add_action('admin_post_nopriv_aqlx_booking', [__CLASS__, 'handle']);
        add_action('admin_post_aqlx_booking', [__CLASS__, 'handle']);
    }
    public static function register_cpt(): void {
        register_post_type('booking', [
            'label' => __('Bookings','aqualuxe'),
            'public'=> false,
            'show_ui'=> true,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title','editor'],
        ]);
    }
    public static function form($atts=[]): string {
        $action = esc_url(admin_url('admin-post.php'));
        $nonce = wp_create_nonce('aqlx_booking');
        ob_start();
        ?>
        <form class="grid gap-3 max-w-xl" method="post" action="<?php echo $action; ?>">
          <input type="hidden" name="action" value="aqlx_booking" />
          <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>" />
          <label><span><?php esc_html_e('Service','aqualuxe'); ?></span><input class="border rounded px-3 py-2 w-full" name="service" required></label>
          <label><span><?php esc_html_e('Preferred Date','aqualuxe'); ?></span><input class="border rounded px-3 py-2 w-full" type="date" name="date" required></label>
          <label><span><?php esc_html_e('Name','aqualuxe'); ?></span><input class="border rounded px-3 py-2 w-full" name="name" required></label>
          <label><span><?php esc_html_e('Email','aqualuxe'); ?></span><input class="border rounded px-3 py-2 w-full" type="email" name="email" required></label>
          <button class="btn-primary" type="submit"><?php esc_html_e('Request Booking','aqualuxe'); ?></button>
        </form>
        <?php
        return ob_get_clean();
    }
    public static function handle(): void {
        check_admin_referer('aqlx_booking');
        $title = sanitize_text_field(($_POST['service'] ?? '') . ' - ' . ($_POST['date'] ?? ''));
        $content = sprintf("Name: %s\nEmail: %s", sanitize_text_field($_POST['name'] ?? ''), sanitize_email($_POST['email'] ?? ''));
        wp_insert_post(['post_type'=>'booking','post_status'=>'publish','post_title'=>$title,'post_content'=>$content]);
        wp_safe_redirect(add_query_arg('booked','1', wp_get_referer() ?: home_url('/services')));
        exit;
    }
}
