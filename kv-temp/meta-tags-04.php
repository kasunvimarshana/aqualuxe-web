<?php

/**
 * AquaLuxe SEO Meta Tags
 * Safe for themes with or without WooCommerce.
 * Handles errors, collisions, and missing data gracefully.
 * Author: Kasun Vimarshana
 * Path: inc/seo/meta-tags.php
 */

if (! defined('ABSPATH')) exit;

// Ensure this is hooked only once
if (! has_action('wp_head', 'aqualuxe_output_meta_tags')) {
    add_action('wp_head', 'aqualuxe_output_meta_tags', 5);
}

function aqualuxe_output_meta_tags()
{
    if (is_feed() || is_admin()) return;

    global $post;

    $site_name    = get_bloginfo('name');
    $site_desc    = get_bloginfo('description');
    $locale       = get_locale();
    $fallback_img = get_stylesheet_directory_uri() . '/assets/images/fallback-og.jpg';

    $title       = $site_name;
    $description = $site_desc;
    $url         = home_url();
    $image       = $fallback_img;
    $type        = 'website';

    try {
        if (is_singular() && isset($post) && is_a($post, 'WP_Post')) {
            $title       = get_the_title($post) ?: $site_name;
            $url         = get_permalink($post);
            $description = get_the_excerpt($post) ?: wp_trim_words(strip_tags($post->post_content), 30, '...');
            $image       = get_the_post_thumbnail_url($post, 'full') ?: $fallback_img;
            $type        = 'article';

            if (function_exists('is_product') && is_product() && class_exists('WC_Product')) {
                $product = wc_get_product($post->ID);
                if ($product instanceof WC_Product) {
                    $type        = 'product';
                    $price       = $product->get_price();
                    $currency    = get_woocommerce_currency();
                    $availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
                }
            }
        } elseif (is_home() || is_front_page()) {
            $title       = $site_name;
            $description = $site_desc;
            $url         = home_url();
            $image       = $fallback_img;
            $type        = 'website';
        } elseif (is_archive()) {
            $term = get_queried_object();
            if (isset($term->name)) {
                $title       = $term->name;
                $description = $term->description ?: $site_desc;
                $url         = get_term_link($term);
                $type        = 'website';
            }
        } elseif (is_search()) {
            $query       = get_search_query();
            $title       = "Search: " . esc_html($query);
            $description = "Results for: " . esc_html($query);
            $url         = get_search_link();
            $type        = 'website';
        } elseif (is_404()) {
            $title       = "Page Not Found";
            $description = "Sorry, this page doesn’t exist.";
            $url         = home_url('/404');
            $type        = 'website';
        }
    } catch (Exception $e) {
        // Fail silently and fall back to defaults
        error_log('[AquaLuxe SEO] Meta tag error: ' . $e->getMessage());
    }

    // Output Meta Tags
?>
    <!-- AquaLuxe: Safe SEO Meta Tags -->
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

    <!-- Optional: Schema -->
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
