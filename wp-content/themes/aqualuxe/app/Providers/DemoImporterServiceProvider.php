<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Modules\DemoImporter\ImportManager;
use App\Modules\DemoImporter\Admin\AdminManager;

class DemoImporterServiceProvider extends ServiceProvider
{
    public function register()
    {
        // We only want to load the importer functionality in the admin area
        if (!is_admin()) {
            return;
        }

        // Load the main importer classes
        require_once AQUALUXE_DIR . '/app/modules/demo-importer/class-import-manager.php';
        require_once AQUALUXE_DIR . '/app/modules/demo-importer/admin/class-admin-manager.php';

        // Load the Importer interface first, as other classes depend on it.
        require_once AQUALUXE_DIR . '/app/modules/demo-importer/importers/interface-importer.php';

        // Load all the individual importer class implementations.
        foreach (glob(AQUALUXE_DIR . '/app/modules/demo-importer/importers/class-*.php') as $filename) {
            require_once $filename;
        }

        // Load utility classes
        require_once AQUALUXE_DIR . '/app/modules/demo-importer/util/Logger.php';
        require_once AQUALUXE_DIR . '/app/modules/demo-importer/util/WpImporterUtil.php';


        // Instantiate the managers.
        // The ImportManager hooks into 'rest_api_init', so it needs to be instantiated directly.
        // The AdminManager hooks into 'admin_menu' and 'admin_enqueue_scripts', which fire later.
        new \App\Modules\DemoImporter\ImportManager();
        new \App\Modules\DemoImporter\Admin\AdminManager();
    }
}
