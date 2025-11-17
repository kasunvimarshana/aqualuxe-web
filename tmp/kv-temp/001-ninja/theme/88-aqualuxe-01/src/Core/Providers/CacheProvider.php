<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;
use Aqualuxe\Core\Cache;

class CacheProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		// Clear listing-related fragments on content updates
		add_action('save_post_listing', [$this, 'flushListingFragments']);
		add_action('deleted_post', [$this, 'flushListingFragments']);
		add_action('switch_theme', [$this, 'flushAll']);
	}

	public function flushListingFragments(): void
	{
		// Coarse-grained: nuke common listing keys
		Cache::delete('shortcode:listings:*');
	}

	public function flushAll(): void
	{
		// No-op placeholder; hook into your object cache if present
	}
}
