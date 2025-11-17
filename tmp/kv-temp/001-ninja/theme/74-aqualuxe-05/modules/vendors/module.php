<?php
namespace AquaLuxe\Modules\Vendors;

\add_action('plugins_loaded', function(){
    if (defined('WCV_VERSION')) {
        // WC Vendors detected
    }
    if (defined('WCFMmp_VERSION')) {
        // WCFM Marketplace detected
    }
    if (defined('DOKAN_PLUGIN_VERSION')) {
        // Dokan detected
    }
});
