# AquaLuxe Developer Guide

## Services (DI)
Use the Container to resolve shared services:

```php
$logger = \AquaLuxe\Core\Container::get('logger');
if ($logger) { $logger->info('Hello from a module'); }
```

Override log level in `wp-config.php`:

```php
define('AQUALUXE_DEBUG', true);
```

Or via filter:

```php
add_filter('aqualuxe/logger/level', fn() => 'debug');
```

## Creating a Module
1. Create `modules/awesome/module.php` and optionally `module.json` (name, description, enabled, requires/requiresAny).
2. Hook into WP actions/filters inside `module.php`. Keep side effects isolated.
3. Add requirements like `{"requiresAny":["class:WooCommerce","defined:MY_PLUGIN"]}` to avoid failing loads.

## Caching
For repeated markup, wrap with `fragment_cache`:

```php
echo \AquaLuxe\fragment_cache('aqlx_block_'.md5($args), 300, function() use ($args) {
  ob_start();
  // render ...
  return (string) ob_get_clean();
});
```

Invalidation hooks belong in the performance module (not in view files).

## Permissions
Gate features with capabilities:
- `aqlx_manage_modules` for Modules UI
- `aqlx_import` for importer

Map caps via `aqualuxe/permissions/policy` filter.

## REST
Wrap handlers in try/catch, log errors, and return compact arrays on failure. Avoid assuming WP classes at load time in core files.
