const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

// Set public path
mix.setPublicPath('assets/dist');

// Configure options
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
    }
});

// Process JavaScript files
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/dark-mode.js', 'js')
   .js('assets/src/js/admin.js', 'js');

// Process CSS files
mix.sass('assets/src/css/main.scss', 'css')
   .sass('assets/src/css/admin.scss', 'css')
   .options({
       postCss: [
           require('postcss-import'),
           require('postcss-nested'),
           tailwindcss('./tailwind.config.js'),
           require('autoprefixer'),
       ]
   });

// Copy and optimize images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable versioning in production
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