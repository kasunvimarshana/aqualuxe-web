<?php
/**
 * Template for displaying the tenant banner.
 *
 * @var array $args {
 *     @type string $tenant_name The name of the current tenant.
 * }
 */

$tenant_name = $args['tenant_name'] ?? 'Current Tenant';
?>
<div class="aqualuxe-tenant-banner bg-yellow-400 dark:bg-yellow-600 text-black dark:text-white text-center p-2 text-sm font-semibold">
    <?php echo \sprintf(\__('You are viewing the site for: %s', 'aqualuxe'), \esc_html($tenant_name)); ?>
</div>
