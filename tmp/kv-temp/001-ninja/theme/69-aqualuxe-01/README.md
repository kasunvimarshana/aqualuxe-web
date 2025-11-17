# AquaLuxe Theme

## Installation & Setup Guide
1. Copy the `aqualuxe/` folder to `wp-content/themes/`.
2. Run `npm install` in the theme directory.
3. Run `npm run prod` to build assets.
4. Activate the theme in WordPress admin.
5. (Optional) Import demo content via Appearance > Demo Import.

## Build/Deploy Instructions
- For development: `npm run dev` or `npm run watch`
- For production: `npm run prod`
- All assets are output to `assets/dist` and cache-busted.

## User Guide
- Customize logo, colors, typography via Appearance > Customize.
- Import demo content for a ready-to-use site.
- Enable/disable modules via `functions.php` or filters.
- Manage content, products, services, events, and settings via WP admin.

## Developer Guide
- Add new modules in `modules/` (copy an existing module as a template).
- All assets must be placed in `assets/src` and built via npm.
- Use hooks and filters for extensibility.
- Follow WordPress coding standards and best practices.

## License
See LICENSE file for details.
