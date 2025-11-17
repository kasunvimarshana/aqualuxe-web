<?php

/**
 * AquaLuxe Mega Menu
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) exit;

class AquaLuxe_Mega_Menu
{

    public function __construct()
    {
        add_filter('nav_menu_css_class', [$this, 'add_mega_menu_class'], 10, 2);
    }

    public function add_mega_menu_class($classes, $item)
    {
        if (in_array('mega-menu', $classes)) {
            $classes[] = 'aqualuxe-mega-menu';
        }
        return $classes;
    }
}
