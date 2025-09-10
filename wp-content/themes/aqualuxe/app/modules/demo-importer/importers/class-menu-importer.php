<?php

namespace App\Modules\DemoImporter\Importers;

use App\Modules\DemoImporter\Util\Logger;

/**
 * Class MenuImporter
 *
 * Imports menus.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class MenuImporter implements ImporterInterface
{
    public function import(array $data = [])
    {
        Logger::log('---');
        Logger::log('Starting menu import...');

        $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/menus.json";
        if (!file_exists($file_path)) {
            Logger::log("Data file not found for 'menus', skipping.");
            return;
        }

        $menus = json_decode(file_get_contents($file_path), true);
        if (empty($menus)) {
            Logger::log("No menus found, skipping.");
            return;
        }

        $theme_locations = [];

        foreach ($menus as $menu_data) {
            $menu_id = $this->create_menu($menu_data);
            if ($menu_id && isset($menu_data['location'])) {
                $theme_locations[$menu_data['location']] = $menu_id;
            }
        }

        if (!empty($theme_locations)) {
            set_theme_mod('nav_menu_locations', $theme_locations);
            Logger::log('Assigned menus to theme locations.');
        }

        Logger::log('Menu import finished.');
        Logger::log('---');
    }

    private function create_menu(array $menu_data): ?int
    {
        $menu_name = $menu_data['name'];
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if ($menu_exists) {
            Logger::log("Menu '{$menu_name}' already exists. Skipping creation.");
            $menu_id = $menu_exists->term_id;
        } else {
            $menu_id = wp_create_nav_menu($menu_name);
            if (is_wp_error($menu_id)) {
                Logger::log("Failed to create menu '{$menu_name}'.");
                return null;
            }
            Logger::log("Created menu '{$menu_name}' (ID: {$menu_id}).");
        }

        if (isset($menu_data['items'])) {
            foreach ($menu_data['items'] as $item) {
                $this->add_menu_item($menu_id, $item);
            }
        }

        return $menu_id;
    }

    private function add_menu_item(int $menu_id, array $item_data, int $parent_id = 0)
    {
        $args = [
            'menu-item-title'  => $item_data['title'],
            'menu-item-status' => 'publish',
            'menu-item-parent-id' => $parent_id,
        ];

        switch ($item_data['type']) {
            case 'custom':
                $args['menu-item-url'] = $item_data['url'];
                $args['menu-item-type'] = 'custom';
                break;
            case 'post_type':
                $post = get_page_by_path($item_data['object_id'], OBJECT, $item_data['object']);
                if ($post) {
                    $args['menu-item-object-id'] = $post->ID;
                    $args['menu-item-object'] = $item_data['object'];
                    $args['menu-item-type'] = 'post_type';
                }
                break;
            case 'post_type_archive':
                $args['menu-item-object'] = $item_data['object'];
                $args['menu-item-type'] = 'post_type_archive';
                break;
        }

        $item_id = wp_update_nav_menu_item($menu_id, 0, $args);

        if (is_wp_error($item_id)) {
            Logger::log("Failed to add menu item '{$item_data['title']}'.");
        } else {
            Logger::log("...added menu item '{$item_data['title']}'.");
            if (isset($item_data['children'])) {
                foreach ($item_data['children'] as $child_item) {
                    $this->add_menu_item($menu_id, $child_item, $item_id);
                }
            }
        }
    }

    public function get_name(): string
    {
        return 'menus';
    }
}
