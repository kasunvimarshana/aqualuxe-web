<?php
/**
 * Title: Product Grid
 * Slug: aqualuxe/product-grid
 * Categories: aqualuxe, query, ecommerce
 * Description: A 4-column product grid with optional filters shortcode.
 */
?>
<!-- wp:group {"className":"mx-auto px-4 max-w-7xl"} -->
<div class="wp-block-group mx-auto px-4 max-w-7xl"><!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Featured Products</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[ax_product_filters]
<!-- /wp:shortcode -->

<!-- wp:woocommerce/product-collection {"query":{"perPage":8,"order":"desc","orderBy":"date"},"layout":{"type":"grid","columns":4}} /--></div>
<!-- /wp:group -->
