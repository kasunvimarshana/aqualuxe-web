# AquaLuxe User Guide

## Getting Started
- Activate the theme and go to Appearance → AquaLuxe Setup to import demo pages and menus.
- Customize colors/typography/layout in Appearance → Customize.

## Pages
- Home, About, Services, Blog, Contact, FAQ, Legal pages are created by the importer.
- Use `[aqualuxe_contact]`, `[aqualuxe_booking]`, and `[aqualuxe_services]` shortcodes on respective pages.

## WooCommerce
- Install/activate WooCommerce for shop pages. Categories auto-create on demo import.
- Use `[aqualuxe_filters]` on Shop page to show filter UI.
- Quick View and Wishlist are built-in (wishlist is client-side via browser storage).

## Multilingual & Currency
- Language switcher in header. Provide .mo files in `languages/`.
- Currency switcher in header. Configure rates via filter `aqualuxe_currency_rates`.

## Modules
- Toggle modules using `aqualuxe_modules_enabled` filter in a small plugin or child theme.

## Dark Mode
- Toggle button in header persists preference per-device.

## Contact & Bookings
- Forms submit to admin via WordPress. Emails go to site admin email.

## Accessibility & SEO
- Semantic HTML, ARIA roles, lazy loading. Open Graph meta is added automatically for singular pages.

## Wishlist
- Click the heart on products to add/remove.
- Use `[aqualuxe_wishlist]` to display a wishlist page (works best when logged in; guests persist via cookie/local storage).
