const mix = require('laravel-mix');

mix.setPublicPath('assets/dist');

mix.js('assets/src/js/app.js', 'js')
  .postCss('assets/src/css/app.css', 'css', [
    require('tailwindcss'),
    require('autoprefixer'),
  ])
  .options({ processCssUrls: false })
  .version();

if (mix.inProduction()) {
  mix.webpackConfig({
    optimization: { splitChunks: { chunks: 'all' } },
    output: { chunkFilename: 'js/[name].[chunkhash].js' },
  });
}
