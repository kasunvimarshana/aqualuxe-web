<?php

/**
 * AquaLuxe Asset Minification
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) exit;

class AquaLuxe_Minify
{

    public static function minify_css($css)
    {
        $css = preg_replace('/\s+/', ' ', $css);
        $css = preg_replace('/\/\*.*?\*\//', '', $css);
        $css = str_replace('; ', ';', $css);
        $css = str_replace(': ', ':', $css);
        return trim($css);
    }

    public static function minify_js($js)
    {
        $js = preg_replace('/\s+/', ' ', $js);
        $js = preg_replace('/\/\*.*?\*\//', '', $js);
        return trim($js);
    }
}
