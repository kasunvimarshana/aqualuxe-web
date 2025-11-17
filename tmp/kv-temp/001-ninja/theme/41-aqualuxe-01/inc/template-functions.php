<?php
/** 
 * Template functions for AquaLuxe theme 
 * 
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add body classes
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add color mode class
    $classes[] = 'color-mode-' . aqualuxe_get_color_mode();

    // Add layout class
    $classes[] = 'layout-' . aqualuxe_get_layout();

    // Add header style class
    $classes[] = 'header-style-' . aqualuxe_get_header_style();

    // Add footer style class
    $classes[] = 'footer-style-' . aqualuxe_get_footer_style();

    // Add WooCommerce class
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add language class
    $classes[] = 'lang-' . aqualuxe_get_current_language();

    // Add currency class
    $classes[] = 'currency-' . aqualuxe_get_current_currency();

    // Add page template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $template = str_replace('.php', '', $template);
        $template = str_replace('template-', '', $template);
        $classes[] = 'template-' . $template;
    }

    // Add singular class
    if (is_singular()) {
        $classes[] = 'singular';
    }

    // Add archive class
    if (is_archive()) {
        $classes[] = 'archive';
    }

    // Add home class
    if (is_home()) {
        $classes[] = 'blog-home';
    }

    // Add front page class
    if (is_front_page()) {
        $classes[] = 'front-page';
    }

    // Add search class
    if (is_search()) {
        $classes[] = 'search-results';
    }

    // Add 404 class
    if (is_404()) {
        $classes[] = 'error-404';
    }

    // Add WooCommerce classes
    if (aqualuxe_is_woocommerce_active()) {
        // Shop class
        if (is_shop()) {
            $classes[] = 'woocommerce-shop';
        }

        // Product class
        if (is_product()) {
            $classes[] = 'woocommerce-product';

            // Product type
            global $product;

            if ($product) {
                $classes[] = 'product-type-' . $product->get_type();
            }
        }

        // Product category class
        if (is_product_category()) {
            $classes[] = 'woocommerce-product-category';
        }

        // Product tag class
        if (is_product_tag()) {
            $classes[] = 'woocommerce-product-tag';
        }

        // Cart class
        if (is_cart()) {
            $classes[] = 'woocommerce-cart';
        }

        // Checkout class
        if (is_checkout()) {
            $classes[] = 'woocommerce-checkout';
        }

        // Account class
        if (is_account_page()) {
            $classes[] = 'woocommerce-account';
        }
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add page slug to body class
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_slug_to_body_class($classes) {
    global $post;

    if (isset($post) && is_singular()) {
        $classes[] = 'page-' . $post->post_name;
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_add_slug_to_body_class');

/**
 * Add schema.org markup to HTML tag
 *
 * @param string $output HTML tag output
 * @return string
 */
function aqualuxe_add_schema_to_html_tag($output) {
    $schema = aqualuxe_get_schema_markup();

    if (isset($schema['html'])) {
        $attributes = '';

        foreach ($schema['html'] as $key => $value) {
            $attributes .= ' ' . $key . '="' . esc_attr($value) . '"';
        }

        $output = str_replace('<html', '<html' . $attributes, $output);
    }

    return $output;
}
add_filter('language_attributes', 'aqualuxe_add_schema_to_html_tag');

/**
 * Add Open Graph metadata to head
 */
function aqualuxe_add_open_graph_metadata() {
    $metadata = aqualuxe_get_open_graph_metadata();

    foreach ($metadata as $property => $content) {
        if (is_array($content)) {
            foreach ($content as $item) {
                echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($item) . '" />' . "\n";
            }
        } else {
            echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_metadata', 5);

/**
 * Add Twitter Card metadata to head
 */
function aqualuxe_add_twitter_card_metadata() {
    $metadata = aqualuxe_get_twitter_card_metadata();

    foreach ($metadata as $name => $content) {
        echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($content) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_card_metadata', 5);

/**
 * Add color mode script to head
 */
function aqualuxe_add_color_mode_script() {
    ?>
    <script>
        // Check for saved color mode preference or use default
        (function() {
            var colorMode = localStorage.getItem('aqualuxeColorMode');
            var defaultColorMode = '<?php echo esc_js(aqualuxe_get_option('default_color_mode', 'light')); ?>';

            // If no preference found, use default
            if (!colorMode) {
                colorMode = defaultColorMode;
            }

            // Apply color mode class to document
            document.documentElement.classList.add('color-mode-' + colorMode);

            // Store preference in cookie for server-side access
            document.cookie = 'aqualuxe_color_mode=' + colorMode + '; path=/; max-age=31536000';
        })();
    </script>
    <?php
}
add_action('wp_head', 'aqualuxe_add_color_mode_script', 0);

/**
 * Add custom CSS variables to head
 */
function aqualuxe_add_custom_css_variables() {
    // Get theme options
    $color_scheme = aqualuxe_get_color_scheme();
    $typography = aqualuxe_get_typography();
    $spacing = aqualuxe_get_spacing();
    $border_radius = aqualuxe_get_border_radius();
    $box_shadow = aqualuxe_get_box_shadow();
    $transition = aqualuxe_get_transition();
    $container_width = aqualuxe_get_container_width();
    $grid_gutter = aqualuxe_get_grid_gutter();
    $container_padding = aqualuxe_get_container_padding();

    // Start CSS
    $css = '<style id="aqualuxe-custom-css-variables">';

    // Root variables
    $css .= ':root {';

    // Colors
    foreach ($color_scheme as $key => $value) {
        $css .= '--color-' . $key . ': ' . $value . ';';
    }

    // Typography
    $css .= '--font-body: ' . $typography['body'] . ';';
    $css .= '--font-heading: ' . $typography['heading'] . ';';
    $css .= '--font-base-size: ' . $typography['base_size'] . ';';
    $css .= '--font-scale: ' . $typography['scale'] . ';';

    // Spacing
    foreach ($spacing as $key => $value) {
        $css .= '--spacing-' . $key . ': ' . $value . ';';
    }

    // Border radius
    foreach ($border_radius as $key => $value) {
        $css .= '--border-radius-' . $key . ': ' . $value . ';';
    }

    // Box shadow
    foreach ($box_shadow as $key => $value) {
        $css .= '--box-shadow-' . $key . ': ' . $value . ';';
    }

    // Transition
    foreach ($transition as $key => $value) {
        $css .= '--transition-' . $key . ': ' . $value . ';';
    }

    // Layout
    $css .= '--container-width: ' . $container_width . ';';
    $css .= '--grid-gutter: ' . $grid_gutter . ';';
    $css .= '--container-padding: ' . $container_padding . ';';

    $css .= '}';

    // Dark mode variables
    $css .= '.color-mode-dark {';
    $css .= '--color-dark: #f8f9fa;';
    $css .= '--color-light: #111111;';
    $css .= '}';

    $css .= '</style>';

    echo $css;
}
add_action('wp_head', 'aqualuxe_add_custom_css_variables', 1);

/**
 * Add custom fonts to head
 */
function aqualuxe_add_custom_fonts() {
    $typography = aqualuxe_get_typography();

    // Get unique fonts
    $fonts = array_unique([
        $typography['body'],
        $typography['heading'],
    ]);

    // Clean font names
    $clean_fonts = [];

    foreach ($fonts as $font) {
        // Remove quotes
        $font = str_replace(["'", '"'], '', $font);

        // Remove fallbacks
        if (strpos($font, ',') !== false) {
            $font = substr($font, 0, strpos($font, ','));
        }

        // Add to clean fonts
        $clean_fonts[] = $font;
    }

    // Build Google Fonts URL
    if (!empty($clean_fonts)) {
        $google_fonts_url = 'https://fonts.googleapis.com/css2?';
        $font_families = [];

        foreach ($clean_fonts as $font) {
            $font_families[] = 'family=' . urlencode($font) . ':wght@400;500;600;700&display=swap';
        }

        $google_fonts_url .= implode('&', $font_families);

        // Add preconnect
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

        // Add font stylesheet
        echo '<link rel="stylesheet" href="' . esc_url($google_fonts_url) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_custom_fonts', 0);

/**
 * Add pingback URL to head
 */
function aqualuxe_add_pingback_url() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="' . esc_url(get_bloginfo('pingback_url')) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_pingback_url');

/**
 * Add mobile viewport meta to head
 */
function aqualuxe_add_mobile_viewport_meta() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
}
add_action('wp_head', 'aqualuxe_add_mobile_viewport_meta', 0);

/**
 * Add theme color meta to head
 */
function aqualuxe_add_theme_color_meta() {
    $color_scheme = aqualuxe_get_color_scheme();

    echo '<meta name="theme-color" content="' . esc_attr($color_scheme['primary']) . '">' . "\n";
}
add_action('wp_head', 'aqualuxe_add_theme_color_meta');

/**
 * Add favicon to head
 */
function aqualuxe_add_favicon() {
    // Check if site icon is set
    if (!has_site_icon()) {
        // Get favicon from theme options
        $favicon = aqualuxe_get_option('favicon');

        if ($favicon) {
            echo '<link rel="icon" href="' . esc_url($favicon) . '" sizes="32x32">' . "\n";
            echo '<link rel="icon" href="' . esc_url($favicon) . '" sizes="192x192">' . "\n";
            echo '<link rel="apple-touch-icon" href="' . esc_url($favicon) . '">' . "\n";
            echo '<meta name="msapplication-TileImage" content="' . esc_url($favicon) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_favicon');
?>
