<?php
namespace Aqualuxe\Domain\Listings;

class ListingsService
{
	public function __construct(private ListingsRepository $repo)
	{
	}

	public function getGridData(int $perPage = 9, int $paged = 1): array
	{
		$q = $this->repo->query([
			'posts_per_page' => $perPage,
			'paged' => $paged,
		]);
		return [ 'query' => $q ];
	}
}
