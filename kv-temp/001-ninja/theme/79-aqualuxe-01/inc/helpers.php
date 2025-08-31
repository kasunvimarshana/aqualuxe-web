<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

/** Sanitize a boolean-like input */
function sanitize_bool($value): bool
{
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}

/** Output escaped attribute */
function e_attr(string $text): void
{
    echo esc_attr($text);
}

/** Output escaped HTML */
function e_html(string $text): void
{
    echo wp_kses_post($text);
}

/** Get option with default */
function theme_opt(string $key, $default = null)
{
    $opts = get_option('aqualuxe_options', []);
    return $opts[$key] ?? $default;
}
