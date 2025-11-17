# AquaLuxe Deliverables Checklist (Scaffold Stage)

Status legend: [ ] pending | [x] implemented (scaffold/minimal) | [~] partial/in-progress

## Architecture & Core

-   [x] Modular directory structure
-   [x] Autoloader (namespace AquaLuxe)
-   [x] Module Manager with discovery
-   [x] Helpers (assets, security, template)
-   [ ] Service Container / Dependency Inversion layer
-   [~] Customizer options (basic)
-   [ ] Hooks registry abstraction

## Features / Modules

-   [x] Dark Mode toggle (JS + button)
-   [x] Services CPT
-   [x] Events CPT
-   [x] Auctions CPT
-   [x] Marketplace vendor role stub
-   [x] Subscriptions stub
-   [x] Importer admin page (demo pages only)
-   [ ] Multilingual module (textdomain only currently)
-   [ ] WooCommerce integration & fallbacks
-   [ ] Bookings / Scheduling
-   [ ] Tickets / Events advanced fields
-   [ ] Auctions bidding logic
-   [ ] Membership tiers
-   [ ] Multicurrency handling

## Templates & Presentation

-   [x] Header/Footer
-   [x] Index loop
-   [x] Pagination partial
-   [x] Content partial
-   [x] Empty state partial
-   [ ] Front page custom template / hero sections
-   [ ] Accessibility audit (WCAG 2.1 AA full)

## Assets & Build

-   [x] Tailwind config
-   [x] SCSS + Mix pipeline
-   [x] Hashed assets via mix-manifest.json helper
-   [ ] Critical CSS extraction
-   [ ] Image optimization process

## Performance / Security

-   [x] Emoji removal
-   [x] Basic sanitization helper
-   [ ] CSP headers & security headers module
-   [ ] Caching strategy hooks

## Internationalization

-   [~] Textdomain loading
-   [ ] POT generation script

## Importer

-   [x] Basic page import (AJAX)
-   [ ] Batch processing
-   [ ] Rollback & logging
-   [ ] Progress indicators (granular)
-   [ ] Selective import / export

## Testing & CI

-   [ ] PHPUnit setup
-   [ ] Example unit tests
-   [ ] GitHub Actions CI pipeline

## Documentation

-   [x] README (scaffold)
-   [x] Checklist (this)
-   [ ] Developer Guide (detailed)
-   [ ] User Guide (setup / customization)

## Compliance

-   [ ] PHPCS config (WPCS)
-   [ ] Lint pass verification
-   [ ] Accessibility validation scripts

## WooCommerce (Pending)

-   [ ] Product templates overrides
-   [ ] Quick view / wishlist
-   [ ] Advanced filtering
-   [ ] Multicurrency abstraction

---

This file will evolve as remaining items are implemented.
