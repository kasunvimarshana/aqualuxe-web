<?php
namespace AquaLuxe\Contact;

class Form {
	public static function init() : void {
		add_shortcode('alx_contact_form', [__CLASS__, 'shortcode']);
		add_action('admin_post_nopriv_alx_contact_submit', [__CLASS__, 'handle']);
		add_action('admin_post_alx_contact_submit', [__CLASS__, 'handle']);
	}

	public static function shortcode() : string {
		$action = \esc_url( \admin_url('admin-post.php') );
		ob_start();
		if ( isset($_GET['alx_status']) && 'ok' === $_GET['alx_status'] ) {
			echo '<div class="notice success p-3 mb-4 bg-green-600/20 text-green-100 rounded">' . \esc_html__('Thank you. We will get back to you soon.','aqualuxe') . '</div>';
		}
		?>
		<form action="<?php echo $action; ?>" method="post" class="grid gap-4 max-w-xl">
			<input type="hidden" name="action" value="alx_contact_submit" />
			<?php \wp_nonce_field('alx_contact'); ?>
			<label class="block">
				<span class="block mb-1"><?php \esc_html_e('Name','aqualuxe'); ?></span>
				<input class="w-full p-2 rounded bg-white/10 text-white" type="text" name="alx_name" required aria-required="true" />
			</label>
			<label class="block">
				<span class="block mb-1"><?php \esc_html_e('Email','aqualuxe'); ?></span>
				<input class="w-full p-2 rounded bg-white/10 text-white" type="email" name="alx_email" required aria-required="true" />
			</label>
			<label class="block">
				<span class="block mb-1"><?php \esc_html_e('Phone (optional)','aqualuxe'); ?></span>
				<input class="w-full p-2 rounded bg-white/10 text-white" type="text" name="alx_phone" />
			</label>
			<label class="block">
				<span class="block mb-1"><?php \esc_html_e('Message','aqualuxe'); ?></span>
				<textarea class="w-full p-2 rounded bg-white/10 text-white" name="alx_message" rows="5" required aria-required="true"></textarea>
			</label>
			<label class="inline-flex items-center gap-2">
				<input type="checkbox" name="alx_consent" value="1" required aria-required="true" />
				<span><?php \esc_html_e('I consent to having this website store my submitted information.','aqualuxe'); ?></span>
			</label>
			<button class="btn btn-primary" type="submit"><?php \esc_html_e('Send Message','aqualuxe'); ?></button>
		</form>
		<?php
		return (string) ob_get_clean();
	}

	public static function handle() : void {
		if ( ! isset($_POST['_wpnonce']) || ! \wp_verify_nonce($_POST['_wpnonce'], 'alx_contact') ) {
			\wp_die( \esc_html__('Security check failed.','aqualuxe') );
		}
		$name = \sanitize_text_field( $_POST['alx_name'] ?? '' );
		$email = \sanitize_email( $_POST['alx_email'] ?? '' );
		$phone = \sanitize_text_field( $_POST['alx_phone'] ?? '' );
		$msg = \wp_strip_all_tags( $_POST['alx_message'] ?? '' );
		if ( empty($name) || empty($email) || ! \is_email($email) || empty($msg) ) {
			\wp_safe_redirect( \add_query_arg('alx_status','error', \wp_get_referer() ?: \home_url('/') ) ); exit;
		}
		$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
		$key = 'alx_cf_rl_' . md5($ip);
		if ( \get_transient($key) ) {
			\wp_safe_redirect( \add_query_arg('alx_status','rate', \wp_get_referer() ?: \home_url('/') ) ); exit;
		}
		\set_transient($key, 1, 60); // 1/minute per IP
		$to = \get_option('admin_email');
		$subject = sprintf('[AquaLuxe] %s', $name);
		$body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$msg\n";
		$headers = [ 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ];
		\wp_mail($to, $subject, $body, $headers);
		\wp_safe_redirect( \add_query_arg('alx_status','ok', \wp_get_referer() ?: \home_url('/') ) ); exit;
	}
}
