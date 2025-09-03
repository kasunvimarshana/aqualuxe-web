<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Vendors_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('init', [$this, 'register_taxonomy']);
    }

    public function boot(Container $c): void {}

    public function register_taxonomy(): void
    {
        $labels = [
            'name' => __('Vendors', 'aqualuxe'),
            'singular_name' => __('Vendor', 'aqualuxe'),
        ];
        register_taxonomy('vendor', ['listing'], [
            'labels' => $labels,
            'public' => true,
            'hierarchical' => false,
            'show_in_rest' => true,
            'rewrite' => ['slug' => 'vendor'],
        ]);
    }
}
