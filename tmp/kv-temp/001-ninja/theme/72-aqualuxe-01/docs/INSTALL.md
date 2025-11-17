# AquaLuxe Theme - Installation

1) Copy the `aqualuxe` folder into `wp-content/themes/`.
2) In a terminal at the theme root, install Node deps and build assets.
3) Activate the theme in WordPress admin.
4) (Optional) Run Demo Importer under Appearance > AquaLuxe Demo Importer.

Requirements:
- WordPress 6.5+
- PHP 8.1+
- Node 18+
- WooCommerce (optional)

Build assets:

```cmd
npm ci
npm run build
```

If developing:
```cmd
npm run watch
```

Notes:
- The theme enqueues only compiled assets from `assets/dist` via `mix-manifest.json`.
- No external CDNs are used.
