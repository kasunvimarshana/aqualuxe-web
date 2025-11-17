const mix = require('laravel-mix');

// Set public path
mix.setPublicPath('assets');

// Compile CSS with Tailwind
mix.postCss('assets/src/css/main.css', 'css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

// Compile JavaScript
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/admin.js', 'js');

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/fonts');

// Version files in production
if (mix.inProduction()) {
    mix.version();
}

// BrowserSync for local development
mix.browserSync({
    proxy: 'localhost',
    files: [
        'assets/css/**/*.css',
        'assets/js/**/*.js',
        '**/*.php'
    ],
    notify: false
});