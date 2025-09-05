<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class LanguageProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('wp_head', [$this, 'hreflang'], 5);
	}

	public function hreflang(): void
	{
		// If Polylang
		if (function_exists('pll_the_languages')) {
			$languages = pll_the_languages(['raw' => 1]);
			if (is_array($languages)) {
				foreach ($languages as $lang) {
					echo '<link rel="alternate" hreflang="' . esc_attr($lang['locale']) . '" href="' . esc_url($lang['url']) . '" />' . "\n";
				}
			}
			return;
		}
		// If WPML
		if (function_exists('icl_get_languages')) {
			$languages = icl_get_languages('skip_missing=1&orderby=code');
			if (is_array($languages)) {
				foreach ($languages as $lang) {
					echo '<link rel="alternate" hreflang="' . esc_attr($lang['language_code']) . '" href="' . esc_url($lang['url']) . '" />' . "\n";
				}
			}
		}
	}
}
