<?php
namespace Aqualuxe\Modules\Listings;

class CPT
{
	public const POST_TYPE = 'listing';

	public function register(): void
	{
		$labels = [
			'name' => __('Listings', 'aqualuxe'),
			'singular_name' => __('Listing', 'aqualuxe'),
		];
		$args = [
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'show_in_rest' => true,
			'supports' => ['title','editor','thumbnail','excerpt'],
			'menu_icon' => 'dashicons-portfolio',
			'capability_type' => ['listing', 'listings'],
			'map_meta_cap' => true,
			'rewrite' => ['slug' => 'listings'],
		];
		register_post_type(self::POST_TYPE, $args);

		register_taxonomy('listing_category', self::POST_TYPE, [
			'label' => __('Listing Categories', 'aqualuxe'),
			'hierarchical' => true,
			'show_in_rest' => true,
			'rewrite' => ['slug' => 'listing-category'],
		]);
	}
}
