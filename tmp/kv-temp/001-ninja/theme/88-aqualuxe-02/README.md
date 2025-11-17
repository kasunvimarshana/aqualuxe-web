## AquaLuxe WordPress Theme

Production-ready, modular, WooCommerce-compatible theme. Dual-state architecture works with or without WooCommerce.

- Assets: assets/src -> compiled to assets/dist via webpack.mix.js
- Cache busting: mix-manifest.json
- Tailwind CSS via npm, no CDNs

### Quick start

1. Activate theme in WordPress.
2. Install Node 18+.
3. In this theme folder, run npm install, then npm run build.

### Development

- npm run dev

### Structure

- inc/Core: theme kernel (Theme, Assets, Modules, Config, REST)
- inc/Core/Modules: feature modules (DarkMode, Importer, WooFallback)
- templates in root PHP files (header.php, footer.php, front-page.php)

### Security & Accessibility

- Strict escaping/sanitization, nonces, ARIA, semantic HTML, WCAG 2.1 focus order.
