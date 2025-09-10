<?php

namespace App\Modules\DemoImporter\Importers;

use App\Modules\DemoImporter\Util\Logger;

/**
 * Class WidgetImporter
 *
 * Imports widgets.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class WidgetImporter implements ImporterInterface
{
    public function import(array $data = [])
    {
        Logger::log('---');
        Logger::log('Starting widget import...');

        $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/widgets.json";
        if (!file_exists($file_path)) {
            Logger::log("Data file not found for 'widgets', skipping.");
            return;
        }

        $widget_data = json_decode(file_get_contents($file_path), true);
        if (empty($widget_data)) {
            Logger::log("No widget data found, skipping.");
            return;
        }

        $this->import_widgets($widget_data);

        Logger::log('Widget import finished.');
        Logger::log('---');
    }

    private function import_widgets(array $widget_data)
    {
        $sidebars_widgets = get_option('sidebars_widgets');
        if (!is_array($sidebars_widgets)) {
            $sidebars_widgets = [];
        }

        foreach ($widget_data as $sidebar_id => $widgets) {
            if (!isset($sidebars_widgets[$sidebar_id])) {
                $sidebars_widgets[$sidebar_id] = [];
            }

            foreach ($widgets as $widget) {
                $widget_id_base = $this->get_widget_id_base($widget['id']);
                if (!$widget_id_base) continue;

                // Get all existing widgets of this type
                $existing_widgets = get_option('widget_' . $widget_id_base, []);

                // Find the next available ID
                $new_widget_id = $this->get_next_widget_id($widget_id_base, $existing_widgets);

                // Special handling for nav_menu widget
                if ($widget_id_base === 'nav_menu' && isset($widget['settings']['nav_menu'])) {
                    $menu_name = $widget['settings']['nav_menu'];
                    $menu = wp_get_nav_menu_object($menu_name);
                    if ($menu) {
                        $widget['settings']['nav_menu'] = $menu->term_id;
                    }
                }

                // Add the new widget's settings
                $existing_widgets[$new_widget_id] = $widget['settings'];

                // Save the updated widgets
                update_option('widget_' . $widget_id_base, $existing_widgets);

                // Add the new widget to the sidebar
                $sidebars_widgets[$sidebar_id][] = $widget_id_base . '-' . $new_widget_id;
                Logger::log("Added widget '{$widget_id_base}-{$new_widget_id}' to sidebar '{$sidebar_id}'.");
            }
        }

        // Save the updated sidebars
        update_option('sidebars_widgets', $sidebars_widgets);
    }

    private function get_widget_id_base(string $id): ?string
    {
        if (preg_match('/^([a-zA-Z0-9_-]+)-[0-9]+$/', $id, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private function get_next_widget_id(string $id_base, array $widgets): int
    {
        $numeric_ids = [1]; // Start with 1 in case there are no widgets yet
        foreach ($widgets as $id => $settings) {
            if (is_int($id)) {
                $numeric_ids[] = $id;
            }
        }
        return max($numeric_ids) + 1;
    }

    public function get_name(): string
    {
        return 'widgets';
    }
}
