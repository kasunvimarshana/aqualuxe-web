<?php
// Roles module: adds a Wholesale Customer role (toggleable via module loader)
if (\function_exists('add_action')) {
    \call_user_func('add_action', 'init', function(){
        if (\function_exists('add_role')) {
            $caps = \function_exists('apply_filters') ? \call_user_func('apply_filters', 'aqualuxe/roles/wholesale_caps', [
                'read' => true,
                'aqlx_wholesale' => true,
            ]) : [ 'read' => true, 'aqlx_wholesale' => true ];
            \call_user_func('add_role', 'wholesale_customer', 'Wholesale Customer', $caps);
        }
    });
}
