# AquaLuxe Theme – TODO

## Core Theme
- [x] Finalize theme supports and setup in `core/setup.php`
- [x] Complete all helper functions in `core/helpers.php`
- [x] Implement all hooks and template tags in `core/hooks.php` and `core/template-tags.php`
- [x] Ensure all Customizer options (logo, colors, typography, layout) are present in `core/customizer.php`
- [x] Integrate demo content importer in `core/demo-importer.php`

## Modular Features (modules/)
- [x] Multilingual: Add language switcher, translation hooks, and .pot files
- [x] Dark Mode: Toggle with persistent preference, ARIA support
- [x] Subscriptions: WooCommerce Subscriptions integration, fallback if plugin missing
- [x] Auctions: Custom post type, bidding logic, WC integration
- [x] Bookings: Calendar, booking forms, WC Bookings support
- [x] Events: Events calendar, ticketing, templates
- [x] Wholesale/B2B: Pricing, registration, approval workflow
- [x] Trade-ins/Auctions: Submission forms, moderation, auction logic
- [x] Services: Booking, scheduling, service listing
- [x] Franchise/Licensing: Inquiry forms, partner portal
- [x] R&D/Sustainability: Content, reporting, showcase
- [x] Affiliate/Referrals: Referral tracking, rewards

## Assets & Build
- [x] Place all raw assets in `assets/src/`
- [x] Configure `webpack.mix.js` for JS, CSS, Sass, images, fonts
- [x] Ensure Tailwind is set up via npm only
- [x] Output all compiled assets to `assets/dist/` with hashed filenames
- [x] Never enqueue raw files; use `mix-manifest.json` for cache-busting

## Templates
- [x] Create all core page templates in `templates/`
- [x] Override WooCommerce templates in `woocommerce/` with graceful fallback
- [x] Add semantic HTML5, ARIA, schema.org, Open Graph meta
- [x] Implement lazy loading for images

## Security, SEO, Performance
- [x] Strict sanitization, escaping, and nonces for all user input
- [x] SEO meta, Open Graph, schema.org via hooks
- [x] Minify and optimize all assets
- [x] Ensure no external CDNs are used

## Admin UX
- [x] Intuitive admin panels for content, layouts, services, events, products, settings
- [x] Theme Customizer: logo, colors, typography, layout
- [x] Demo content importer (WXR/JSON)

## WooCommerce
- [x] Shop, product types (physical, digital, variable, grouped)
- [x] Quick-view, advanced filtering, wishlist, multicurrency readiness
- [x] Optimized international shipping & checkout
- [x] Shop categories: rare fish, plants, equipment, care supplies
- [x] Product details: high-res images, reviews, related products

## Core Pages & Demo Content
- [x] Home: hero, featured, testimonials, newsletter
- [x] About: history, mission, values, sustainability, team
- [x] Services: design, maintenance, quarantine, breeding, consultation
- [x] Blog: care guides, aquascaping, news
- [x] Contact: map, form, contact methods
- [x] FAQ: shipping, care, purchasing, export/import
- [x] Legal: privacy, terms, shipping/returns, cookies

## Testing & CI
- [x] Add unit and E2E tests in `tests/`
- [x] Set up CI pipeline (build, lint, test)

## Documentation
- [x] Installation/setup guide
- [x] Build/deploy instructions
- [x] User & developer documentation
- [x] License file

## Code Audit
- [x] Remove duplicate definitions, hooks, enqueues, template issues
- [x] Fix all PHP parse errors
- [x] Ensure cross-browser/device compatibility
- [x] LTS maintainability, security hardening, scalability

