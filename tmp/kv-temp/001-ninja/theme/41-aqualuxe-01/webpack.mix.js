const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

// Set public path
mix.setPublicPath('assets/dist');

// Compile SCSS
mix.sass('assets/src/css/main.scss', 'css')
   .options({
      processCssUrls: false,
      postCss: [
         tailwindcss('./tailwind.config.js'),
         require('autoprefixer')
      ],
   });

// Compile JavaScript
mix.js('assets/src/js/main.js', 'js')
   .js('assets/src/js/darkmode.js', 'js')
   .js('assets/src/js/navigation.js', 'js');

// WooCommerce specific scripts
if (process.env.WITH_WOOCOMMERCE === 'true') {
   mix.js('assets/src/js/woocommerce/product-gallery.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/quick-view.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/cart.js', 'js/woocommerce')
      .js('assets/src/js/woocommerce/checkout.js', 'js/woocommerce');
}

// Copy fonts
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Copy images
mix.copyDirectory('assets/src/images', 'assets/dist/images');

// Version files in production
if (mix.inProduction()) {
   mix.version();
}

// BrowserSync for local development
mix.browserSync({
   proxy: 'localhost',
   files: [
      'assets/dist/css/**/*.css',
      'assets/dist/js/**/*.js',
      '**/*.php'
   ]
});

// Disable mix-manifest.json
mix.disableNotifications();