<?php
namespace AquaLuxe\Core;

/**
 * Accessibility helpers and hooks.
 *
 * Keeps concerns separate from templates and provides sensible a11y defaults
 * such as skip links, landmarks, and aria attribute improvements.
 */
class A11y
{
    public static function init(): void
    {
        \add_action('wp_body_open', [__CLASS__, 'skip_link']);
        \add_filter('nav_menu_link_attributes', [__CLASS__, 'menu_aria_current'], 10, 4);
        \add_filter('the_content_more_link', [__CLASS__, 'more_link_screen_reader_text']);
    }

    /**
     * Renders a skip link to the main content for keyboard/screen-reader users.
     */
    public static function skip_link(): void
    {
        $label = \function_exists('esc_html__') ? \call_user_func('esc_html__', 'Skip to content', 'aqualuxe') : 'Skip to content';
        echo '<a class="skip-link" href="#primary">' . $label . '</a>';
    }

    /**
     * Adds aria-current="page" to the active menu item links for better SR context.
     */
    public static function menu_aria_current(array $atts, $item, $args, $depth): array
    {
        if (!empty($item->current)) {
            $atts['aria-current'] = 'page';
        }
        return $atts;
    }

    /**
     * Ensure the "more" link includes additional screen-reader-only context.
     */
    public static function more_link_screen_reader_text(string $link): string
    {
        $sr = '<span class="screen-reader-text"> ' . (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'of the post', 'aqualuxe') : 'of the post') . '</span>';
        return str_replace('</a>', $sr . '</a>', $link);
    }
}
