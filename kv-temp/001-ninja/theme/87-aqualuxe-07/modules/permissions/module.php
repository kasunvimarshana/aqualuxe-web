<?php
// Centralized granular permissions and roles.

// Register custom capabilities and map to roles via filterable policy.
add_action('init', function(){
    // Ensure roles exist
    $roles = [ 'administrator', 'editor', 'author', 'contributor', 'subscriber', 'wholesale_customer' ];
    // Capability policy: role => [caps]
    $policy = apply_filters('aqualuxe/permissions/policy', [
        'administrator' => [ 'aqlx_manage_modules', 'aqlx_import', 'aqlx_view_reports' ],
        'editor'        => [ 'aqlx_view_reports' ],
        'wholesale_customer' => [ 'aqlx_wholesale' ],
    ]);
    foreach ($roles as $roleName) {
        $role = get_role($roleName);
        if (!$role) { continue; }
        $caps = (array) ($policy[$roleName] ?? []);
        foreach ($caps as $cap) { $role->add_cap($cap); }
    }
});

// Capability gates for importer and modules UI
add_filter('user_has_cap', function(array $allcaps, array $caps, array $args, \WP_User $user){
    // Map virtual caps to core when needed
    if (in_array('aqlx_manage_modules', $caps, true)) { $allcaps['aqlx_manage_modules'] = user_can($user, 'manage_options'); }
    if (in_array('aqlx_import', $caps, true)) { $allcaps['aqlx_import'] = user_can($user, 'manage_options'); }
    return $allcaps;
}, 10, 4);
