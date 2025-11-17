<?php

/**
 * AquaLuxe SEO + Open Graph + Twitter + Schema Tags
 * Supports all page types — with or without WooCommerce
 * Author: Kasun Vimarshana
 * Path: inc/seo/meta-tags.php
 */

if (! defined('ABSPATH')) exit;

add_action('wp_head', 'aqualuxe_output_meta_tags', 5);

function aqualuxe_output_meta_tags()
{
    global $post;

    $site_name     = get_bloginfo('name');
    $locale        = get_locale();
    $fallback_img  = get_stylesheet_directory_uri() . '/assets/images/fallback-og.jpg';

    // Determine context
    if (is_singular() && isset($post)) {
        $title       = get_the_title($post);
        $url         = get_permalink($post);
        $description = get_the_excerpt($post) ?: wp_trim_words(strip_tags($post->post_content), 30);
        $image       = get_the_post_thumbnail_url($post, 'full') ?: $fallback_img;
        $type        = 'article';

        // If WooCommerce product
        if (function_exists('is_product') && is_product() && class_exists('WC_Product')) {
            $product = wc_get_product($post);
            if ($product instanceof WC_Product) {
                $type         = 'product';
                $price        = $product->get_price();
                $currency     = get_option('woocommerce_currency', 'USD');
                $availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
            }
        }
    } elseif (is_home() || is_front_page()) {
        $title       = $site_name;
        $url         = home_url();
        $description = get_bloginfo('description');
        $image       = $fallback_img;
        $type        = 'website';
    } elseif (is_archive()) {
        $term        = get_queried_object();
        $title       = $term->name ?? 'Archives';
        $description = $term->description ?? 'Browse archived content';
        $url         = get_term_link($term);
        $image       = $fallback_img;
        $type        = 'website';
    } elseif (is_search()) {
        $query       = get_search_query();
        $title       = "Search results for: " . $query;
        $description = "Showing search results for: " . $query;
        $url         = get_search_link();
        $image       = $fallback_img;
        $type        = 'website';
    } elseif (is_404()) {
        $title       = "Page Not Found";
        $description = "Sorry, the page you're looking for doesn't exist.";
        $url         = home_url('/404');
        $image       = $fallback_img;
        $type        = 'website';
    } else {
        // Generic fallback
        $title       = $site_name;
        $description = get_bloginfo('description');
        $url         = home_url();
        $image       = $fallback_img;
        $type        = 'website';
    }

?>
    <!-- AquaLuxe: Meta, OG, Twitter, Schema -->
    <meta name="description" content="<?php echo esc_attr($description); ?>" />
    <link rel="canonical" href="<?php echo esc_url($url); ?>" />

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
    <meta property="og:type" content="<?php echo esc_attr($type); ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta property="og:image" content="<?php echo esc_url($image); ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>" />
    <meta property="og:locale" content="<?php echo esc_attr($locale); ?>" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>" />

    <!-- Optional Schema JSON-LD -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "<?php echo esc_js(ucfirst($type)); ?>",
            "name": "<?php echo esc_js($title); ?>",
            "description": "<?php echo esc_js($description); ?>",
            "url": "<?php echo esc_url($url); ?>",
            "image": "<?php echo esc_url($image); ?>",
            "publisher": {
                "@type": "Organization",
                "name": "<?php echo esc_js($site_name); ?>"
            }
            <?php if (isset($price) && isset($currency)): ?>,
                "offers": {
                    "@type": "Offer",
                    "price": "<?php echo esc_js($price); ?>",
                    "priceCurrency": "<?php echo esc_js($currency); ?>",
                    "availability": "https://schema.org/<?php echo esc_js($availability); ?>"
                }
            <?php endif; ?>
        }
    </script>
<?php
}
