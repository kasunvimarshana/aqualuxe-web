const mix = require('laravel-mix');

// Set public path
mix.setPublicPath('./assets');

// Compile CSS with Tailwind
mix.postCss('./src/css/main.css', 'css/main.css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile JavaScript
mix.js('src/js/main.js', 'js/main.js')
   .js('src/js/customizer.js', 'js/customizer.js')
   .js('src/js/woocommerce.js', 'js/woocommerce.js');

// Copy fonts
mix.copy('src/fonts', 'assets/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// Set up BrowserSync for development
mix.browserSync({
    proxy: 'localhost',
    files: [
        '**/*.php',
        'assets/css/**/*.css',
        'assets/js/**/*.js'
    ],
    notify: false
});