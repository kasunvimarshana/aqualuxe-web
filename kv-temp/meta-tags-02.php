<?php
/**
 * AquaLuxe Meta Tags: SEO + Open Graph + Twitter + Schema
 * Compatible with or without WooCommerce
 * Path: inc/seo/meta-tags.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', 'aqualuxe_output_meta_tags', 5 );

function aqualuxe_output_meta_tags() {
	if ( ! is_singular() ) return;

	global $post;

	$title       = get_the_title( $post );
	$url         = get_permalink( $post );
	$description = get_the_excerpt( $post ) ?: wp_trim_words( strip_tags( $post->post_content ), 30, '...' );
	$image       = get_the_post_thumbnail_url( $post, 'full' );
	$site_name   = get_bloginfo( 'name' );
	$locale      = get_locale();
	$canonical   = $url;

	// Fallback image if no featured image exists
	if ( ! $image ) {
		$image = get_stylesheet_directory_uri() . '/assets/images/fallback-og.jpg';
	}

	// Detect post type and WooCommerce presence
	$post_type     = get_post_type( $post );
	$is_product    = function_exists( 'is_product' ) && is_product();
	$product_price = null;
	$product_stock = 'in stock';
	$product_currency = get_option( 'woocommerce_currency', 'USD' );

	if ( $is_product && class_exists( 'WooCommerce' ) ) {
		$product = wc_get_product( $post );

		if ( $product instanceof WC_Product ) {
			$product_price  = $product->get_price();
			$product_stock  = $product->is_in_stock() ? 'in stock' : 'out of stock';
		}
	}

	$og_type = $is_product ? 'product' : 'article';
	$schema_type = $is_product ? 'Product' : 'Article';
	$schema_availability = $product_stock === 'in stock' ? 'InStock' : 'OutOfStock';

	?>
	<!-- AquaLuxe: SEO + Open Graph + Twitter + Schema -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="robots" content="index, follow" />
	<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
	<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>" />

	<!-- Open Graph -->
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
	<meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
	<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>" />
	<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
	<meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
	<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>" />
	<meta property="og:locale" content="<?php echo esc_attr( $locale ); ?>" />
	<?php if ( $is_product && $product_price ): ?>
		<meta property="product:price:amount" content="<?php echo esc_attr( $product_price ); ?>" />
		<meta property="product:price:currency" content="<?php echo esc_attr( $product_currency ); ?>" />
		<meta property="product:availability" content="<?php echo esc_attr( $product_stock ); ?>" />
	<?php endif; ?>

	<!-- Twitter Card -->
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
	<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
	<meta name="twitter:image" content="<?php echo esc_url( $image ); ?>" />

	<!-- Schema.org JSON-LD -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "<?php echo esc_js( $schema_type ); ?>",
		"name": "<?php echo esc_js( $title ); ?>",
		"description": "<?php echo esc_js( $description ); ?>",
		"url": "<?php echo esc_url( $url ); ?>",
		"image": "<?php echo esc_url( $image ); ?>",
		"publisher": {
			"@type": "Organization",
			"name": "<?php echo esc_js( $site_name ); ?>"
		}
		<?php if ( $is_product && $product_price ): ?>,
		"offers": {
			"@type": "Offer",
			"price": "<?php echo esc_js( $product_price ); ?>",
			"priceCurrency": "<?php echo esc_js( $product_currency ); ?>",
			"availability": "https://schema.org/<?php echo esc_js( $schema_availability ); ?>"
		}
		<?php endif; ?>
	}
	</script>
	<?php
}
