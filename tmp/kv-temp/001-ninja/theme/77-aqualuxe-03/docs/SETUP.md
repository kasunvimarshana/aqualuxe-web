# AquaLuxe Setup

1. Activate the AquaLuxe theme in WordPress.
2. Open a terminal at `wp-content/themes/aqualuxe`.
3. Run:
   - npm install
   - npm run build
4. In WP Admin, go to Appearance > AquaLuxe Demo Import to create core pages.
5. Configure Theme Customizer (logo, colors, typography).
6. Toggle modules in Appearance > AquaLuxe Modules.

Notes:
- No external CDNs. All assets are local and versioned.
- Works with or without WooCommerce. Woo features gracefully degrade when WC is inactive.
