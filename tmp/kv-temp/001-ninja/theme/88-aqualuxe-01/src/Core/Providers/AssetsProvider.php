<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class AssetsProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('wp_enqueue_scripts', [$this, 'enqueue']);
		add_filter('script_loader_tag', [$this, 'deferScripts'], 10, 3);
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

	private function assetUrl(string $defaultRelative): string
	{
		$manifest = $this->manifest();
		$themeUrl = trailingslashit(get_stylesheet_directory_uri());
		if (!empty($manifest[$defaultRelative])) {
			$rel = ltrim($manifest[$defaultRelative], '/');
			return $themeUrl . $rel;
		}
		return $themeUrl . $defaultRelative;
	}

	private function assetPath(string $defaultRelative): string
	{
		$manifest = $this->manifest();
		$themeDir = trailingslashit(get_stylesheet_directory());
		if (!empty($manifest[$defaultRelative])) {
			return $themeDir . ltrim($manifest[$defaultRelative], '/');
		}
		return $themeDir . $defaultRelative;
	}

	public function enqueue(): void
	{
		$cssRel = 'assets/dist/styles.css';
		$jsRel  = 'assets/dist/main.js';
		$cssUrl = $this->assetUrl($cssRel);
		$jsUrl  = $this->assetUrl($jsRel);
		$cssPath = $this->assetPath($cssRel);
		$jsPath  = $this->assetPath($jsRel);

		$css_ver = file_exists($cssPath) ? filemtime($cssPath) : AQUALUXE_VERSION;
		$js_ver  = file_exists($jsPath) ? filemtime($jsPath) : AQUALUXE_VERSION;

		wp_enqueue_style('aqualuxe', $cssUrl, [], $css_ver, 'all');
		wp_enqueue_script('aqualuxe', $jsUrl, [], $js_ver, true);

		wp_localize_script('aqualuxe', 'AQUALUXE', [
			'restRoot' => esc_url_raw(rest_url('aqualuxe/v1')),
			'nonce'    => wp_create_nonce('wp_rest'),
		]);
	}

	public function deferScripts($tag, $handle, $src)
	{
		if ($handle === 'aqualuxe') {
			$tag = str_replace('<script ', '<script defer ', $tag);
		}
		return $tag;
	}
}
