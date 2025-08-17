const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path for assets
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js');

// Process CSS/SCSS files
mix.sass('assets/src/css/app.scss', 'css')
   .sass('assets/src/css/admin.scss', 'css')
   .sass('assets/src/css/editor-style.scss', 'css')
   .sass('assets/src/css/woocommerce.scss', 'css')
   .sass('assets/src/css/dark-mode.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         require('postcss-import'),
         require('tailwindcss'),
         require('autoprefixer'),
      ],
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
   mix.version();
}

// Generate mix-manifest.json for cache busting
mix.then(() => {
   // Ensure the manifest file exists
   if (fs.existsSync('assets/dist/mix-manifest.json')) {
      // Read the manifest file
      const manifest = JSON.parse(fs.readFileSync('assets/dist/mix-manifest.json', 'utf8'));
      
      // Update paths to be relative to the theme root
      const updatedManifest = {};
      Object.keys(manifest).forEach(key => {
         const newKey = key.replace(/^\//, '');
         updatedManifest[newKey] = manifest[key].replace(/^\//, '');
      });
      
      // Write the updated manifest file
      fs.writeFileSync('assets/dist/mix-manifest.json', JSON.stringify(updatedManifest, null, 2));
   }
});

// Disable success notifications
mix.disableSuccessNotifications();

// Enable BrowserSync for local development
if (!mix.inProduction()) {
   mix.browserSync({
      proxy: 'localhost',
      files: [
         'assets/dist/**/*',
         '**/*.php',
      ],
      notify: false
   });
}