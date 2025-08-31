const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/theme.js', 'js')
  .postCss('assets/src/css/theme.css', 'css')
  .options({
    postCss: [
      require('postcss-import'),
      require('tailwindcss'),
      require('autoprefixer'),
    ],
    processCssUrls: false,
  })
  .extract(['three','gsap','d3'])
  .sourceMaps(process.env.NODE_ENV !== 'production', 'source-map')
  .version();

// Hashing handled by .version() -> mix-manifest.json

mix.webpackConfig({
  stats: 'minimal',
  output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
  resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } }
});
