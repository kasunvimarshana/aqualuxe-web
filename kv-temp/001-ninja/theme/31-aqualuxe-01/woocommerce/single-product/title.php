<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

the_title( '<h1 class="product_title entry-title text-3xl md:text-4xl font-serif font-medium mb-2">', '</h1>' );