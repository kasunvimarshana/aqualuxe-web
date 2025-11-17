<?php
/**
 * Helper functions
 */

defined('ABSPATH') || exit;

if (!function_exists('aqualuxe_mix')) {
    function aqualuxe_mix(string $file): ?string
    {
        $manifest = AQUALUXE_PATH . 'assets/dist/mix-manifest.json';
        if (!file_exists($manifest)) return null;
        $map = json_decode(file_get_contents($manifest), true);
        $key = '/' . ltrim($file, '/');
        if (!isset($map[$key])) return null;
        return AQUALUXE_ASSETS . ltrim($map[$key], '/');
    }
}

if (!function_exists('aqualuxe_asset')) {
    function aqualuxe_asset(string $file, string $type = 'auto'): ?string
    {
        $url = aqualuxe_mix($file);
        return $url ?: null;
    }
}

if (!function_exists('aqualuxe_the_logo')) {
    function aqualuxe_the_logo(): void
    {
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
            return;
        }
        $name = get_bloginfo('name');
        echo '<a class="site-title" href="' . esc_url(home_url('/')) . '">' . esc_html($name) . '</a>';
    }
}

if (!function_exists('aqualuxe_primary_menu')) {
    function aqualuxe_primary_menu(): void
    {
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'container'      => false,
            'fallback_cb'    => '__return_empty_string',
        ]);
    }
}

if (!function_exists('aqualuxe_schema_type')) {
    function aqualuxe_schema_type(): string
    {
        if (is_home() || is_archive() || is_search()) return 'https://schema.org/Blog';
        if (is_singular('post')) return 'https://schema.org/BlogPosting';
        if (function_exists('is_product') && is_product()) return 'https://schema.org/Product';
        return 'https://schema.org/WebPage';
    }
}

if (!function_exists('aqualuxe_sanitize_color')) {
    function aqualuxe_sanitize_color($color)
    {
        if (is_string($color) && preg_match('/^#?[0-9a-fA-F]{3,8}$/', $color)) {
            return '#' . ltrim($color, '#');
        }
        return '';
    }
}

if (!function_exists('aqualuxe_get_option')) {
    function aqualuxe_get_option(string $key, $default = null)
    {
        $val = get_theme_mod($key, $default);
        return $val;
    }
}

// Contact form handler (admin-post)
add_action('admin_post_nopriv_aqlx_contact_submit', 'aqlx_handle_contact');
add_action('admin_post_aqlx_contact_submit', 'aqlx_handle_contact');
if (!function_exists('aqlx_handle_contact')) {
    function aqlx_handle_contact(): void
    {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? esc_url_raw($_SERVER['HTTP_REFERER']) : home_url('/');
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'aqlx_contact')) {
            wp_safe_redirect(add_query_arg('error', rawurlencode(__('Invalid form token.', 'aqualuxe')), $redirect));
            exit;
        }
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
        $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
        $message = isset($_POST['message']) ? wp_kses_post(wp_unslash($_POST['message'])) : '';
        if (!$name || !$email || !$message || !is_email($email)) {
            wp_safe_redirect(add_query_arg('error', rawurlencode(__('Please fill all fields correctly.', 'aqualuxe')), $redirect));
            exit;
        }
        $to = get_theme_mod('aqualuxe_contact_email', get_option('admin_email'));
        $subject = sprintf(__('New contact from %s', 'aqualuxe'), $name);
        $headers = [
            'Content-Type: text/html; charset=UTF-8',
            'Reply-To: ' . $name . ' <' . $email . '>'
        ];
        $body = wpautop(sprintf('<strong>Name:</strong> %1$s<br><strong>Email:</strong> %2$s<br><strong>Message:</strong><br>%3$s', esc_html($name), esc_html($email), wp_kses_post($message)));
        wp_mail($to, $subject, $body, $headers);
        wp_safe_redirect(add_query_arg('sent', '1', $redirect));
        exit;
    }
}

// Build URL without a specific query param or value
if (!function_exists('aqlx_url_without_param')) {
    function aqlx_url_without_param(string $url, string $key, ?string $value = null): string
    {
        $u = wp_parse_url($url);
        $base = (isset($u['scheme']) ? $u['scheme'] . '://' : '') . ($u['host'] ?? '') . ($u['path'] ?? '');
        $args = [];
        if (!empty($u['query'])) {
            parse_str($u['query'], $args);
        }
        if (!array_key_exists($key, $args)) return $url;
        if ($value === null) {
            unset($args[$key]);
        } else {
            $cur = $args[$key];
            if (is_array($cur)) {
                $args[$key] = array_values(array_diff($cur, [$value]));
                if (empty($args[$key])) unset($args[$key]);
            } else {
                // Comma separated list
                $parts = array_filter(array_map('trim', explode(',', (string) $cur)));
                $parts = array_values(array_diff($parts, [$value]));
                if ($parts) $args[$key] = implode(',', $parts); else unset($args[$key]);
            }
        }
        $q = http_build_query($args);
        return $base . ($q ? ('?' . $q) : '');
    }
}

// Active Woo filters -> chips (label + removeUrl), and clearUrl
if (!function_exists('aqlx_active_filters')) {
    function aqlx_active_filters(): array
    {
        $chips = [];
        $current = home_url(add_query_arg(null, null));
        // Price
        foreach (['min_price' => __('Min', 'aqualuxe'), 'max_price' => __('Max', 'aqualuxe')] as $k => $lab) {
            if (!empty($_GET[$k])) {
                $val = sanitize_text_field(wp_unslash($_GET[$k]));
                $chips[] = [
                    'label' => $lab . ': ' . $val,
                    'url' => aqlx_url_without_param($current, $k, null),
                ];
            }
        }
        // Attribute filters: filter_{taxonomy}
        foreach ($_GET as $key => $val) {
            if (strpos($key, 'filter_') !== 0) continue;
            $tax = sanitize_key(substr($key, 7));
            $vals = is_array($val) ? array_map('sanitize_text_field', wp_unslash($val)) : array_map('trim', explode(',', sanitize_text_field(wp_unslash($val))));
            foreach ($vals as $slug) {
                $term = get_term_by('slug', $slug, $tax);
                $label = $term && !is_wp_error($term) ? $term->name : $slug;
                $chips[] = [
                    'label' => $label,
                    'url' => aqlx_url_without_param($current, $key, $slug),
                ];
            }
        }
        // On sale
        if (!empty($_GET['onsale'])) {
            $chips[] = ['label' => __('On sale', 'aqualuxe'), 'url' => aqlx_url_without_param($current, 'onsale', null)];
        }
        // Rating filter
        if (!empty($_GET['rating_filter'])) {
            $chips[] = ['label' => sprintf(__('Rated %s+', 'aqualuxe'), (int) $_GET['rating_filter']), 'url' => aqlx_url_without_param($current, 'rating_filter', null)];
        }
        // Clear URL: link to shop page
        $clear = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : remove_query_arg(array_keys($_GET));
        return ['chips' => $chips, 'clear_url' => $clear];
    }
}
