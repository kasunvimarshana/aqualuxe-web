<?php
namespace Aqualuxe\Shortcodes;

use Aqualuxe\Core\Cache;
use Aqualuxe\Domain\Listings\ListingsRepository;
use Aqualuxe\Domain\Listings\ListingsService;

class Listings
{
	public function register(): void
	{
		add_shortcode('aqualuxe_listings', [$this, 'render']);
	}

	public function render($atts = []): string
	{
		$atts = shortcode_atts([
			'per_page' => 9,
		], $atts, 'aqualuxe_listings');
		$paged = max(1, (int) ($_GET['pg'] ?? 1)); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$key = sprintf('shortcode:listings:per_page=%d:paged=%d', (int) $atts['per_page'], $paged);
		return Cache::remember($key, 5 * MINUTE_IN_SECONDS, function () use ($atts, $paged) {
			$service = new ListingsService(new ListingsRepository());
			$ctx = $service->getGridData((int) $atts['per_page'], $paged);
			ob_start();
			set_query_var('aqlx_listings_ctx', $ctx);
			get_template_part('templates/listings/grid');
			wp_reset_postdata();
			return ob_get_clean();
		});
	}
}
