# AquaLuxe Demo Importer

A comprehensive, restartable importer to set up a full demo site with realistic mock data and optional WooCommerce products.

Features
- Selective import: Media, Pages, Posts, Services CPT, Taxonomies, Users & Roles, Menus, Widgets, Woo Products (Simple/Variable), Settings
- Progress tracking (AJAX), live log, and rollback on errors
- Flush/reset: demo-only cleanup or full wipe with confirmation
- Export: JSON snapshot of demo-tagged entities
- Scheduling: Automated re-initializations via WP-Cron (hourly, twice daily, daily, weekly)
- Security: capability checks, nonces, demo-tag `_aqlx_demo` for safe cleanup

Usage
1. WP Admin → Appearance → Demo Import
2. Select sections and count per type
3. Start Import and watch progress
4. Use Export for backups; use Flush to reset (demo-only or full wipe)
5. Schedule periodic re-initializations if desired

Notes
- All WooCommerce steps are skipped when Woo is inactive.
- Demo-created content, media, terms, and users are tagged for safe removal.
