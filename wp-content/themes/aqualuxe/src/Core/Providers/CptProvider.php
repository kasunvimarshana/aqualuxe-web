<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;
use Aqualuxe\Modules\Listings\CPT as ListingsCPT;

class CptProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('init', function () {
			(new ListingsCPT())->register();
		});
	}
}
