const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// Set public path
mix.setPublicPath('assets/dist');

// Disable mix-manifest.json versioning in development
if (!mix.inProduction()) {
    mix.options({
        manifest: false
    });
}

// Compile assets
mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/demo-importer.js', 'js')
   .sass('assets/src/css/app.scss', 'css')
   .options({
       processCssUrls: false,
       postCss: [
           require('tailwindcss'),
           require('autoprefixer'),
       ]
   });

// Copy images and fonts
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable source maps in development
if (!mix.inProduction()) {
    mix.sourceMaps();
} else {
    // Versioning for production
    mix.version();
    
    // Minify and optimize for production
    mix.minify('assets/dist/css/app.css');
    mix.minify('assets/dist/js/app.js');
}

// Browser sync for development
mix.browserSync({
    proxy: 'localhost', // Change this to your local development URL
    files: [
        '**/*.php',
        'assets/dist/**/*'
    ],
    watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
});

// Disable notifications
mix.disableNotifications();