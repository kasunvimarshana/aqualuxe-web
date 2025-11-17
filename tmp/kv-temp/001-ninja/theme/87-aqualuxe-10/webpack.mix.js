const mix = require('laravel-mix');
const glob = require('glob');
const path = require('path');

// --- Configuration ---
const themeName = 'aqualuxe';
const publicPath = 'assets/dist';
const resourceRoot = `/wp-content/themes/${themeName}/${publicPath}/`;

mix.setPublicPath(publicPath);
mix.setResourceRoot(resourceRoot);

// --- Main Theme Assets ---
mix.js('assets/src/app.js', 'assets/dist')
   .postCss('assets/src/app.css', 'assets/dist', [
       require('tailwindcss'),
   ])
   .postCss('assets/src/woocommerce.css', 'assets/dist', [
      require('tailwindcss'),
   ])
   .extract(['three', 'gsap', 'd3', 'alpinejs']);

// --- Admin-Specific Assets ---
mix.js('assets/src/admin/importer.js', 'js/admin')
  .postCss('assets/src/admin/importer.css', 'css/admin', [
    require('postcss-import'),
    require('autoprefixer')
  ]);

// --- Dynamic Module Assets ---
// Find all css/js files in module asset source folders
const moduleAssets = glob.sync('modules/*/assets/src/*.{js,css}');

moduleAssets.forEach(assetPath => {
    const isJs = assetPath.endsWith('.js');
    const isCss = assetPath.endsWith('.css');
    const moduleName = path.basename(path.dirname(path.dirname(path.dirname(assetPath))));
    const outputDir = `js/modules/${moduleName}`;
    const cssOutputDir = `css/modules/${moduleName}`;

    if (isJs) {
        mix.js(assetPath, outputDir);
    } else if (isCss) {
        mix.postCss(assetPath, cssOutputDir, [
            require('postcss-import'),
            require('tailwindcss'), // Apply tailwind to module CSS as well
            require('autoprefixer')
        ]);
    }
});


// --- Static Asset Copying ---
mix.copyDirectory('assets/src/img', `${publicPath}/img`);
mix.copyDirectory('assets/src/fonts', `${publicPath}/fonts`);

// --- Build Optimizations ---
if (mix.inProduction()) {
  // Image Optimization
  try {
    const ImageminPlugin = require('imagemin-webpack-plugin').default;
    const imageminMozjpeg = require('imagemin-mozjpeg');
    mix.webpackConfig({
      plugins: [
        new ImageminPlugin({
          test: /\.(jpe?g|png|gif|svg)$/i,
          cacheFolder: path.resolve(__dirname, '.img-cache'),
          pngquant: { quality: '65-80' },
          plugins: [imageminMozjpeg({ quality: 78 })],
        })
      ]
    });
  } catch (e) {
    console.log('Skipping image optimization. imagemin-webpack-plugin not found.');
  }
} else {
  // Use source maps in development
  mix.sourceMaps();
}

// --- Final Touches ---
mix.version();
mix.options({ processCssUrls: false });
