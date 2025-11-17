const mix = require('laravel-mix');
require('laravel-mix-versionhash');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
   .js('assets/src/js/admin.js', 'js')
   .sass('assets/src/scss/app.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .options({
     processCssUrls: false,
     postCss: [require('postcss-import'), require('tailwindcss'), require('autoprefixer')],
   })
   .versionHash();

if (!mix.inProduction()) {
  mix.webpackConfig({
    plugins: [
      new BrowserSyncPlugin({
        proxy: 'http://localhost', // change to your WP dev URL
        files: ['**/*.php', 'assets/dist/**/*.*'],
        injectChanges: true,
      }, { reload: false })
    ]
  });
}
const mix = require('laravel-mix');
require('laravel-mix-versionhash');
const path = require('path');

mix.setPublicPath('assets/dist');
mix.options({ processCssUrls: false, terser: { extractComments: false } });

mix.js('assets/src/js/app.js', 'js')
   .sass('assets/src/scss/app.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .sourceMaps(false, 'source-map')
   .options({ postCss: [ require('postcss-import'), require('tailwindcss')('./tailwind.config.js'), require('autoprefixer') ] })
   .webpackConfig({
     output: { chunkFilename: 'js/[name].chunk.[contenthash].js' },
     resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } },
   })
   .versionHash();
