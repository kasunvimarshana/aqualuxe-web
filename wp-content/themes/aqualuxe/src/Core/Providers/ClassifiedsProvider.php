<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class ClassifiedsProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('init', function () {
			// Register common classifieds taxonomies for the 'listing' CPT
			$pt = 'listing';
			if (post_type_exists($pt)) {
				register_taxonomy('listing_location', $pt, [
					'label' => __('Listing Locations', 'aqualuxe'),
					'hierarchical' => true,
					'show_in_rest' => true,
					'rewrite' => ['slug' => 'listing-location'],
				]);
				register_taxonomy('listing_condition', $pt, [
					'label' => __('Listing Conditions', 'aqualuxe'),
					'hierarchical' => false,
					'show_in_rest' => true,
					'rewrite' => ['slug' => 'listing-condition'],
				]);
			}
		});
	}
}
