<?php
/**
 * Language Switcher Template
 *
 * @package AquaLuxe\Modules\Multilingual
 */

// Get parameters
$style = isset($style) ? $style : 'dropdown';
$show_flags = isset($show_flags) ? $show_flags : true;
$show_names = isset($show_names) ? $show_names : true;

// Get module instance
$theme = \AquaLuxe\Theme::get_instance();
$module = $theme->get_active_modules()['multilingual'] ?? null;

if (!$module) {
    return;
}

// Create switcher
$switcher = new \AquaLuxe\Modules\Multilingual\Switcher($module->get_languages(), $module->get_current_language());
$switcher->render($style, $show_flags, $show_names);