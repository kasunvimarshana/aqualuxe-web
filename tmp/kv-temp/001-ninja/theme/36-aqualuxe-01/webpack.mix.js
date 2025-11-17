const mix = require('laravel-mix');
const path = require('path');

// Set public path for assets
mix.setPublicPath('assets/dist');

// Source and destination paths
const srcPath = 'assets/src';
const distPath = 'assets/dist';

// Process JavaScript
mix.js(`${srcPath}/js/app.js`, `${distPath}/js`)
   .js(`${srcPath}/js/admin.js`, `${distPath}/js`)
   .js(`${srcPath}/js/customizer.js`, `${distPath}/js`);

// Process CSS with Tailwind
mix.postCss(`${srcPath}/css/tailwind.css`, `${distPath}/css`, [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Process SCSS if needed (for WooCommerce overrides)
mix.sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css`);

// Handle images
mix.copyDirectory(`${srcPath}/images`, `${distPath}/images`);

// Handle fonts
mix.copyDirectory(`${srcPath}/fonts`, `${distPath}/fonts`);

// Handle icons
mix.copyDirectory(`${srcPath}/icons`, `${distPath}/icons`);

// Source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// BrowserSync for local development
mix.browserSync({
    proxy: 'localhost',
    open: false,
    files: [
        'assets/dist/**/*',
        '**/*.php',
    ]
});

// Disable OS notifications
mix.disableNotifications();