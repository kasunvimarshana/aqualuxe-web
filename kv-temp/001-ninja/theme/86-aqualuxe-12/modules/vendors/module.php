<?php
// Vendors module scaffold: expose a filterable adapter layer for vendor data
if (\function_exists('add_action')) {
    \call_user_func('add_action', 'plugins_loaded', function(){
        // Adapter detection can be placed here (Dokan, WCFM, WC Vendors)
        // Example filter: aqualuxe/vendors/enabled -> bool
        \call_user_func('apply_filters', 'aqualuxe/vendors/ready', true);
    });
}
