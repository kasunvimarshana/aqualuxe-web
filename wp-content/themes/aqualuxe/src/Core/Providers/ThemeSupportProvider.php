<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class ThemeSupportProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('after_setup_theme', function () {
			add_theme_support('title-tag');
			add_theme_support('post-thumbnails');
			add_theme_support('html5', ['search-form','comment-list','comment-form','gallery','caption','style','script']);
			add_theme_support('custom-logo');
			add_theme_support('automatic-feed-links');
			add_theme_support('responsive-embeds');
			add_theme_support('align-wide');

			register_nav_menus([
				'primary' => __('Primary Menu', 'aqualuxe'),
				'footer'  => __('Footer Menu', 'aqualuxe'),
			]);
		});
	}
}
