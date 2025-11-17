<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider {
	public function register() {
		add_shortcode( 'aqualuxe_placeholder', [ $this, 'placeholder_shortcode' ] );
	}

	public function placeholder_shortcode( $atts ) {
		$atts = shortcode_atts(
			[
				'content_id' => '',
			],
			$atts,
			'aqualuxe_placeholder'
		);

		$content = '';

		switch ( $atts['content_id'] ) {
			case 'home':
				$content = '<!-- Placeholder for home page content -->';
				break;
			case 'projects_gallery':
				$content = '<!-- Placeholder for projects gallery -->';
				break;
			case 'contact_form':
				$content = '<!-- Placeholder for contact form -->';
				break;
		}

		return $content;
	}
}
