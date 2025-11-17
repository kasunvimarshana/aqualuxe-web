# AquaLuxe Architecture

This theme embraces modular, multi‑state design with clean separation of concerns:

- Core (inc/core): Theme setup, assets, REST, CPTs, Customizer
- Modules (modules/*): Self‑contained features, discoverable and toggleable
- Admin (inc/admin): Setup/importer and module manager UIs
- Templates: UI composition; reusable partials
- Assets: Built via Laravel Mix; hashed dist with a manifest

Principles:
- SOLID: Modules provide single responsibilities; dependencies inverted via hooks/filters
- DRY: Shared helpers live in inc/helpers.php and template-tags.php
- KISS/YAGNI: Minimal surface per module; advanced variants opt‑in
- Progressive Enhancement: Shortcodes and forms work server‑side; JS augments UX

Core Services & DI:
- Container (inc/core/container.php): tiny static service locator for theme internals.
- Logger (inc/core/logger.php): minimal error_log–backed logger with levels; resolved via Container.
- Override logger verbosity: `add_filter('aqualuxe/logger/level', fn()=> 'info');`

Permissions:
- Centralized in `modules/permissions/`. Default caps:
	- `aqlx_manage_modules` → Modules UI
	- `aqlx_import` → Importer endpoints
- Map caps via `aqualuxe/permissions/policy` filter.

Caching:
- Fragment cache helper `AquaLuxe\fragment_cache($key, $ttl, $cb)` for shortcodes/partials.
- Invalidation hooks live in `modules/performance` (best‑effort transient cleanup on content changes).

Error Handling:
- Module loader logs unmet requirements and load errors.
- REST endpoints wrap handlers in try/catch, log failures, and return compact error payloads.

Extensibility:
- Filters: aqualuxe/modules/*, aqualuxe/currency/*, aqualuxe/tenant/*, aqualuxe/vendors/*
- REST: aqualuxe/v1 endpoints; add your own in modules
- Assets: import sources and rebuild; no CDN coupling

Security:
- Nonces and sanitization helpers in inc/security.php
- Admin endpoints check capabilities; JSON outputs escaped

Testing:
- PHP lint in CI; PHPUnit scaffold (see tests/).
