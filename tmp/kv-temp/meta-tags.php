<?php

/**
 * AquaLuxe SEO + Open Graph + Twitter + Schema.org Meta Tags
 * Author: Kasun Vimarshana
 * Path: inc/seo/meta-tags.php
 */

if (! defined('ABSPATH')) exit;

add_action('wp_head', 'aqualuxe_output_meta_tags', 5);

function aqualuxe_output_meta_tags()
{
    if (! is_singular()) return;

    global $post;

    $title       = get_the_title($post);
    $url         = get_permalink($post);
    $description = get_the_excerpt($post) ?: wp_trim_words(strip_tags($post->post_content), 30, '...');
    $image       = get_the_post_thumbnail_url($post, 'full');
    $site_name   = get_bloginfo('name');
    $locale      = get_locale();
    $canonical   = $url;

    if (! $image) {
        $image = get_stylesheet_directory_uri() . '/assets/images/fallback-og.jpg';
    }

    // WooCommerce-specific: If product
    $product_price = null;
    $product_currency = get_woocommerce_currency();
    $product_availability = 'in stock';

    if (function_exists('is_product') && is_product()) {
        global $product;

        if ($product instanceof WC_Product) {
            $product_price = $product->get_price();
            $product_availability = $product->is_in_stock() ? 'in stock' : 'out of stock';
        }
    }

?>
    <!-- AquaLuxe Meta Tags for SEO, OG, Twitter, Schema -->
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="<?php echo esc_attr($description); ?>" />
    <link rel="canonical" href="<?php echo esc_url($canonical); ?>" />

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
    <meta property="og:type" content="<?php echo is_product() ? 'product' : 'article'; ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta property="og:image" content="<?php echo esc_url($image); ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
    <meta property="og:locale" content="<?php echo esc_attr($locale); ?>" />
    <?php if (is_product() && $product_price): ?>
        <meta property="product:price:amount" content="<?php echo esc_attr($product_price); ?>" />
        <meta property="product:price:currency" content="<?php echo esc_attr($product_currency); ?>" />
        <meta property="product:availability" content="<?php echo esc_attr($product_availability); ?>" />
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>" />

    <!-- JSON-LD Schema (Basic Article/Product) -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "<?php echo is_product() ? 'Product' : 'Article'; ?>",
            "name": "<?php echo esc_js($title); ?>",
            "description": "<?php echo esc_js($description); ?>",
            "url": "<?php echo esc_url($url); ?>",
            <?php if ($image): ?> "image": "<?php echo esc_url($image); ?>",
            <?php endif; ?>
            <?php if (is_product() && $product_price): ?> "offers": {
                    "@type": "Offer",
                    "priceCurrency": "<?php echo esc_js($product_currency); ?>",
                    "price": "<?php echo esc_js($product_price); ?>",
                    "availability": "https://schema.org/<?php echo $product_availability === 'in stock' ? 'InStock' : 'OutOfStock'; ?>"
                },
            <?php endif; ?> "publisher": {
                "@type": "Organization",
                "name": "<?php echo esc_js($site_name); ?>"
            }
        }
    </script>
<?php
}
