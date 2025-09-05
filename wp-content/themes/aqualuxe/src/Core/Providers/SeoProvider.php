<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class SeoProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		\add_action('wp_head', [$this, 'meta'], 1);
		\add_action('wp_head', [$this, 'schema'], 20);
		\add_filter('document_title_parts', [$this, 'titleParts'], 10);
	}

	public function meta(): void
	{
		$base = (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
		$is_vendor_archive = (bool) \get_query_var('vendors');
		$vendor_slug = (string) \get_query_var('vendor_store');

		if ($is_vendor_archive) {
			$canonical = \home_url(\user_trailingslashit($base));
			$desc = \sprintf(\esc_html__('Browse vendors at %s', 'aqualuxe'), \get_bloginfo('name'));
		} elseif (!empty($vendor_slug)) {
			$canonical = \home_url(\user_trailingslashit($base . '/' . $vendor_slug));
			$user = \get_user_by('slug', $vendor_slug);
			$bio = $user ? \get_user_meta($user->ID, 'description', true) : '';
			$desc = $bio ? $bio : \get_bloginfo('description');
		} else {
			$desc = \get_bloginfo('description');
			$canonical = (\is_singular() ? \get_permalink() : \home_url(\add_query_arg([]))); // strip query args
		}

		echo "\n<link rel=\"canonical\" href=\"" . \esc_url($canonical) . "\" />\n";
		echo "<meta name=\"description\" content=\"" . \esc_attr($desc) . "\" />\n";
		$og_type = (!empty($vendor_slug) ? 'profile' : (\is_singular() ? 'article' : 'website'));
		echo '<meta property="og:type" content="' . \esc_attr($og_type) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . \esc_attr(\get_bloginfo('name')) . '" />' . "\n";
		echo '<meta property="og:title" content="' . \esc_attr(\wp_get_document_title()) . '" />' . "\n";
		echo '<meta property="og:url" content="' . \esc_url($canonical) . '" />' . "\n";
		if (!empty($vendor_slug)) {
			$user = isset($user) && $user ? $user : \get_user_by('slug', $vendor_slug);
			$banner = $user ? \get_user_meta($user->ID, 'aqlx_vendor_banner', true) : '';
			$logo = $user ? \get_user_meta($user->ID, 'aqlx_vendor_logo', true) : '';
			$ogImage = $banner ?: $logo;
			if (!$ogImage && $user) { $ogImage = \get_avatar_url($user->ID, ['size' => 512]); }
			if ($ogImage) { echo '<meta property="og:image" content="' . \esc_url($ogImage) . '" />' . "\n"; }
		} elseif (\is_singular() && \has_post_thumbnail()) {
			$img = \wp_get_attachment_image_src(\get_post_thumbnail_id(), 'large');
			if ($img) { echo '<meta property="og:image" content="' . \esc_url($img[0]) . '" />' . "\n"; }
		}
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	}

	public function schema(): void
	{
		$base = (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
		$vendor_slug = (string) \get_query_var('vendor_store');
		if (!empty($vendor_slug)) {
			$user = \get_user_by('slug', $vendor_slug);
			if ($user) {
				$schema = [
					'@context' => 'https://schema.org',
					'@type' => 'Person',
					'name' => $user->display_name ?: $user->user_nicename,
					'url' => \home_url(\user_trailingslashit($base . '/' . $vendor_slug)),
				];
				$avatar = \get_avatar_url($user->ID, ['size' => 512]);
				if ($avatar) { $schema['image'] = $avatar; }
				$bio = \get_user_meta($user->ID, 'description', true);
				if (!empty($bio)) { $schema['description'] = $bio; }
				echo "\n<script type=\"application/ld+json\">" . \wp_json_encode($schema) . "</script>\n";
				return;
			}
		}

		$schema = [
			'@context' => 'https://schema.org',
			'@type' => 'WebSite',
			'name' => \get_bloginfo('name'),
			'url' => \home_url('/'),
		];
		echo "\n<script type=\"application/ld+json\">" . \wp_json_encode($schema) . "</script>\n";
	}

	public function titleParts(array $parts): array
	{
		$base = (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
		$is_vendor_archive = (bool) \get_query_var('vendors');
		$vendor_slug = (string) \get_query_var('vendor_store');
		if ($is_vendor_archive) {
			$parts['title'] = \__('Vendors', 'aqualuxe');
		} elseif (!empty($vendor_slug)) {
			$user = \get_user_by('slug', $vendor_slug);
			if ($user) { $parts['title'] = $user->display_name ?: $user->user_nicename; }
		}
		return $parts;
	}
}
