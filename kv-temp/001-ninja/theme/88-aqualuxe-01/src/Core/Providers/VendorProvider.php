<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class VendorProvider implements ServiceProviderInterface
{
	private function base(): string
	{
		return (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
	}

	public function register(): void
	{
		\add_action('after_switch_theme', [$this, 'ensureVendorRole']);
		\add_action('after_switch_theme', [$this, 'flushRewrites']);
		\add_action('init', [$this, 'addRewrites']);
		\add_filter('query_vars', [$this, 'queryVars']);
		\add_filter('template_include', [$this, 'templateRouter']);

		// Vendor profile fields
		\add_action('show_user_profile', [$this, 'profileFields']);
		\add_action('edit_user_profile', [$this, 'profileFields']);
		\add_action('personal_options_update', [$this, 'saveProfileFields']);
		\add_action('edit_user_profile_update', [$this, 'saveProfileFields']);
	}

	public function ensureVendorRole(): void
	{
		$capabilities = [
			'read' => true,
			'edit_listings' => true,
			'edit_published_listings' => true,
			'publish_listings' => true,
			'upload_files' => true,
		];
		if (!\get_role('vendor')) {
			\add_role('vendor', \__( 'Vendor', 'aqualuxe'), $capabilities);
		}
		$shop = \get_role('shop_manager');
		if ($shop) {
			foreach (array_keys($capabilities) as $cap) { if (!$shop->has_cap($cap)) { $shop->add_cap($cap); } }
		}
	}

	public function addRewrites(): void
	{
		$base = $this->base();
		\add_rewrite_rule('^' . $base . '/?$', 'index.php?vendors=1', 'top');
		\add_rewrite_rule('^' . $base . '/([^/]+)/?$', 'index.php?vendor_store=$matches[1]', 'top');
	}

	public function queryVars(array $vars): array
	{
		$vars[] = 'vendors';
		$vars[] = 'vendor_store';
		return $vars;
	}

	public function templateRouter(string $template): string
	{
		$vendors = \get_query_var('vendors');
		$store = \get_query_var('vendor_store');
		if ($vendors) {
			$tpl = \locate_template('templates/vendors/archive.php', false, false);
			if (!empty($tpl) && \file_exists($tpl)) { return $tpl; }
		}
		if (!empty($store)) {
			$tpl = \locate_template('templates/vendors/store.php', false, false);
			if (!empty($tpl) && \file_exists($tpl)) { return $tpl; }
		}
		return $template;
	}

	public function flushRewrites(): void
	{
		$this->addRewrites();
		\flush_rewrite_rules();
	}

	public function profileFields($user): void
	{
		if (!($user instanceof \WP_User)) { return; }
		$roles = (array) ($user->roles ?? []); if (!in_array('vendor', $roles, true)) { return; }
		$logo = \get_user_meta($user->ID, 'aqlx_vendor_logo', true);
		$banner = \get_user_meta($user->ID, 'aqlx_vendor_banner', true);
		$website = \get_user_meta($user->ID, 'aqlx_vendor_website', true);
		$socials = [
			'facebook' => \get_user_meta($user->ID, 'aqlx_vendor_facebook', true),
			'instagram' => \get_user_meta($user->ID, 'aqlx_vendor_instagram', true),
			'twitter' => \get_user_meta($user->ID, 'aqlx_vendor_twitter', true),
		];
		\wp_nonce_field('aqlx_vendor_profile', 'aqlx_vendor_profile_nonce');
		?>
		<h2><?php echo \esc_html__('Vendor Profile', 'aqualuxe'); ?></h2>
		<table class="form-table" role="presentation">
			<tr><th><label for="aqlx_vendor_banner"><?php echo \esc_html__('Banner URL', 'aqualuxe'); ?></label></th>
			<td><input type="url" name="aqlx_vendor_banner" id="aqlx_vendor_banner" class="regular-text" value="<?php echo \esc_attr($banner); ?>" placeholder="https://" /></td></tr>
			<tr><th><label for="aqlx_vendor_logo"><?php echo \esc_html__('Logo URL', 'aqualuxe'); ?></label></th>
			<td><input type="url" name="aqlx_vendor_logo" id="aqlx_vendor_logo" class="regular-text" value="<?php echo \esc_attr($logo); ?>" placeholder="https://" /></td></tr>
			<tr><th><label for="aqlx_vendor_website"><?php echo \esc_html__('Website', 'aqualuxe'); ?></label></th>
			<td><input type="url" name="aqlx_vendor_website" id="aqlx_vendor_website" class="regular-text" value="<?php echo \esc_attr($website); ?>" placeholder="https://" /></td></tr>
			<tr><th><?php echo \esc_html__('Social links', 'aqualuxe'); ?></th>
			<td>
				<label><?php echo \esc_html__('Facebook', 'aqualuxe'); ?> <input type="url" name="aqlx_vendor_facebook" value="<?php echo \esc_attr($socials['facebook']); ?>" class="regular-text" placeholder="https://facebook.com/..." /></label><br />
				<label><?php echo \esc_html__('Instagram', 'aqualuxe'); ?> <input type="url" name="aqlx_vendor_instagram" value="<?php echo \esc_attr($socials['instagram']); ?>" class="regular-text" placeholder="https://instagram.com/..." /></label><br />
				<label><?php echo \esc_html__('Twitter / X', 'aqualuxe'); ?> <input type="url" name="aqlx_vendor_twitter" value="<?php echo \esc_attr($socials['twitter']); ?>" class="regular-text" placeholder="https://twitter.com/..." /></label>
			</td></tr>
		</table>
		<?php
	}

	public function saveProfileFields($user_id): void
	{
		if (!isset($_POST['aqlx_vendor_profile_nonce']) || !\wp_verify_nonce(sanitize_text_field($_POST['aqlx_vendor_profile_nonce']), 'aqlx_vendor_profile')) { return; }
		if (!\current_user_can('edit_user', (int) $user_id)) { return; }
		$keys = ['aqlx_vendor_banner','aqlx_vendor_logo','aqlx_vendor_website','aqlx_vendor_facebook','aqlx_vendor_instagram','aqlx_vendor_twitter'];
		foreach ($keys as $k) {
			$val = isset($_POST[$k]) ? \esc_url_raw((string) $_POST[$k]) : '';
			\update_user_meta((int) $user_id, $k, $val);
		}
	}
}
