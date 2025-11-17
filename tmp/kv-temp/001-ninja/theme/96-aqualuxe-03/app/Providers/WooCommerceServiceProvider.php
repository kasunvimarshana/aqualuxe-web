<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class WooCommerceServiceProvider extends ServiceProvider {
	public function register() {
		add_action( 'after_setup_theme', [ $this, 'woocommerce_setup' ] );
	}

	/**
	 * WooCommerce setup function.
	 *
	 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
	 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
	 *
	 * @return void
	 */
	public function woocommerce_setup() {
		add_theme_support(
			'woocommerce',
			[
				'thumbnail_image_width' => 150,
				'single_image_width'    => 300,
				'product_grid'          => [
					'default_rows'    => 3,
					'min_rows'        => 1,
					'max_rows'        => 8,
					'default_columns' => 4,
					'min_columns'     => 1,
					'max_columns'     => 4,
				],
			]
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
