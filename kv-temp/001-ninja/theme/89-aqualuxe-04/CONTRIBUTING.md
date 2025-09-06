# Contributing to AquaLuxe Theme

- Follow WordPress Coding Standards and PHP 8.0+.
- Namespaced PHP; in namespaced files, wrap WP globals using function_exists/call_user_func.
- Keep modules self-contained under `modules/<feature>/` with a `bootstrap.php`.
- Never enqueue raw `assets/src` files; only `assets/dist` via mix-manifest.
- Add tests/docs for new features; wire feature flags via filters/options.
