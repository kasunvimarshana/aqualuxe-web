<?php
/**
 * Dark Mode Module Configuration
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

return array(
    'name'           => __('Dark Mode', 'aqualuxe'),
    'description'    => __('Adds a dark mode toggle to your site with customizable colors and settings.', 'aqualuxe'),
    'version'        => '1.0.0',
    'author'         => 'NinjaTech AI',
    'author_uri'     => 'https://ninjatech.ai',
    'default_status' => false,
    'requires'       => array(),
    'icon'           => 'dashicons-admin-appearance',
    'settings_page'  => true,
);