<?php
namespace Aqualuxe\Domain\Listings;

class ListingsRepository
{
	public function query(array $args = []): \WP_Query
	{
		$defaults = [
			'post_type' => 'listing',
			'posts_per_page' => 9,
			'paged' => 1,
		];
		$args = wp_parse_args($args, $defaults);
		return new \WP_Query($args);
	}
}
