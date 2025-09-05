<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class BreadcrumbProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('aqualuxe/breadcrumbs', [$this, 'render']);
	}

	public function render(): void
	{
		get_template_part('templates/parts/seo/breadcrumbs');
	}
}
