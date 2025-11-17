<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;
use Aqualuxe\Core\Config;

class ThemeVariantProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('wp_enqueue_scripts', [$this, 'enqueueSkin'], 20);
	}

	private function manifest(): array
	{
		$path = trailingslashit(get_stylesheet_directory()) . 'assets/manifest.json';
		if (file_exists($path)) {
			$raw = file_get_contents($path);
			$data = json_decode($raw, true);
			return is_array($data) ? $data : [];
		}
		return [];
	}

	public function enqueueSkin(): void
	{
		$theme_url = trailingslashit(get_stylesheet_directory_uri());
		$theme_dir = trailingslashit(get_stylesheet_directory());
		$manifest = $this->manifest();

		$skin = get_option('aqualuxe_skin', Config::get('skin', 'skins/default.css'));
		$skin = apply_filters('aqualuxe_skin', $skin);

		if (defined('AQUALUXE_TENANT_ID')) {
			$tenantSkin = "skins/" . AQUALUXE_TENANT_ID . ".css";
			if (file_exists($theme_dir . 'assets/dist/' . $tenantSkin)) {
				$skin = $tenantSkin;
			}
		}

		$rel = 'assets/dist/' . ltrim($skin, '/');
		$assetRel = $manifest[$rel] ?? $rel;
		$skinPath = $theme_dir . ltrim($assetRel, '/');
		$skinUrl  = $theme_url . ltrim($assetRel, '/');
		if (file_exists($skinPath)) {
			wp_enqueue_style('aqualuxe-skin', $skinUrl, ['aqualuxe'], filemtime($skinPath));
		}
	}
}
