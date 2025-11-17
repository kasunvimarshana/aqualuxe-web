const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

// Set public path
mix.setPublicPath('assets/dist');

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .js('assets/src/js/customizer.js', 'js')
   .js('assets/src/js/editor.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js');

// Process WooCommerce JavaScript if needed
if (require('fs').existsSync('assets/src/js/woocommerce.js')) {
    mix.js('assets/src/js/woocommerce.js', 'js');
}

// Process module JavaScript files
const modulesDir = 'modules';
if (require('fs').existsSync(modulesDir)) {
    const modules = require('fs').readdirSync(modulesDir);
    modules.forEach(module => {
        const moduleJsPath = `${modulesDir}/${module}/assets/src/js/${module}.js`;
        if (require('fs').existsSync(moduleJsPath)) {
            mix.js(moduleJsPath, `js/modules/${module}.js`);
        }
    });
}

// Process SCSS files
mix.sass('assets/src/scss/main.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sass('assets/src/scss/editor.scss', 'css')
   .sass('assets/src/scss/dark-mode.scss', 'css')
   .sass('assets/src/scss/rtl.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ],
   });

// Process WooCommerce SCSS if needed
if (require('fs').existsSync('assets/src/scss/woocommerce.scss')) {
    mix.sass('assets/src/scss/woocommerce.scss', 'css')
       .options({
           processCssUrls: false,
           postCss: [
               tailwindcss('./tailwind.config.js'),
               require('autoprefixer'),
           ],
       });
}

// Process module SCSS files
if (require('fs').existsSync(modulesDir)) {
    const modules = require('fs').readdirSync(modulesDir);
    modules.forEach(module => {
        const moduleScssPath = `${modulesDir}/${module}/assets/src/scss/${module}.scss`;
        if (require('fs').existsSync(moduleScssPath)) {
            mix.sass(moduleScssPath, `css/modules/${module}.css`)
               .options({
                   processCssUrls: false,
                   postCss: [
                       tailwindcss('./tailwind.config.js'),
                       require('autoprefixer'),
                   ],
               });
        }
    });
}

// Copy images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
if (mix.inProduction()) {
    mix.version();
}

// Generate source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
}

// Disable OS notifications
mix.disableNotifications();