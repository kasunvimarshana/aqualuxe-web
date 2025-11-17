const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');

mix.setPublicPath('assets/dist');

mix.webpackConfig({
  output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
  resolve: { alias: { '@': path.resolve(__dirname, 'assets/src/js') } },
  module: {
    rules: [
      { test: /\.svg$/i, type: 'asset', generator: { filename: 'svg/[name][ext]' } },
      { test: /\.(png|jpe?g|gif|webp)$/i, type: 'asset', generator: { filename: 'images/[name][ext]' } },
      { test: /\.(woff2?|ttf|eot|otf)$/i, type: 'asset', generator: { filename: 'fonts/[name][ext]' } },
    ]
  }
});

mix.js('assets/src/js/app.js', 'js')
  .js('assets/src/js/vendor.js', 'js')
  .js('assets/src/js/admin.js', 'js')
   .sass('assets/src/scss/app.scss', 'css')
   .sass('assets/src/scss/admin.scss', 'css')
   .options({ processCssUrls: false, postCss: [ require('tailwindcss'), require('autoprefixer') ] })
   .version();

mix.then(() => {
  // Ensure mix-manifest has leading slashes so aqualuxe_mix works
  const manifestPath = path.resolve(__dirname, 'assets/dist/mix-manifest.json');
  if (fs.existsSync(manifestPath)) {
    const manifest = JSON.parse(fs.readFileSync(manifestPath));
    const fixed = {};
    Object.keys(manifest).forEach(k => { fixed[k.startsWith('/')?k:`/${k}`] = manifest[k]; });
    fs.writeFileSync(manifestPath, JSON.stringify(fixed, null, 2));
  }
});
