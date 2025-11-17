<?php
namespace Aqualuxe\Shortcodes;

class Wishlist_Shortcode
{
    public static function render($atts = [], $content = ''): string
    {
        $esc_html = \function_exists('esc_html') ? 'esc_html' : null;
        $esc_url = \function_exists('esc_url') ? 'esc_url' : null;
        $get_permalink = \function_exists('get_permalink') ? 'get_permalink' : null;
        $get_the_title = \function_exists('get_the_title') ? 'get_the_title' : null;
        $is_user_logged_in = \function_exists('is_user_logged_in') ? 'is_user_logged_in' : null;
        $get_current_user_id = \function_exists('get_current_user_id') ? 'get_current_user_id' : null;
        $get_user_meta = \function_exists('get_user_meta') ? 'get_user_meta' : null;
        $esc_html__ = \function_exists('esc_html__') ? 'esc_html__' : null;

        $out = '<div class="aqlx-wishlist" data-aqlx-wishlist="1">';
        $out .= '<h2 class="aqlx-wishlist__title">' . ($esc_html__ ? \call_user_func($esc_html__, 'Wishlist', 'aqualuxe') : 'Wishlist') . '</h2>';

        $items = [];
        if ($is_user_logged_in && \call_user_func($is_user_logged_in) && $get_current_user_id && $get_user_meta) {
            $uid = \call_user_func($get_current_user_id);
            $items = (array) \call_user_func($get_user_meta, $uid, '_aqlx_wishlist', true);
            $items = array_values(array_unique(array_map('intval', $items)));
        }

        if (!empty($items)) {
            $out .= '<ul class="aqlx-wishlist__list">';
            foreach ($items as $pid) {
                $title = $get_the_title ? (string) \call_user_func($get_the_title, $pid) : ('#' . $pid);
                $link = $get_permalink ? (string) \call_user_func($get_permalink, $pid) : ('?p=' . (int) $pid);
                $out .= '<li class="aqlx-wishlist__item"><a href="' . ($esc_url ? \call_user_func($esc_url, $link) : $link) . '">' . ($esc_html ? \call_user_func($esc_html, $title) : $title) . '</a></li>';
            }
            $out .= '</ul>';
        } else {
            $msg = $esc_html__ ? \call_user_func($esc_html__, 'Your wishlist is empty. Browse the shop and add some favorites.', 'aqualuxe') : 'Your wishlist is empty. Browse the shop and add some favorites.';
            $out .= '<p class="aqlx-wishlist__empty">' . $msg . '</p>';
        }

        $out .= '</div>';
        return $out;
    }
}
