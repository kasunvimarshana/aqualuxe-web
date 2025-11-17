const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('assets/js/app.js', 'assets/dist/js')
   .js('assets/js/admin.js', 'assets/dist/js')
   .postCss('assets/css/app.css', 'assets/dist/css', [
     require('postcss-import'),
     tailwindcss('./tailwind.config.js'),
     require('autoprefixer'),
   ])
   .postCss('assets/css/admin.css', 'assets/dist/css', [
     require('postcss-import'),
     tailwindcss('./tailwind.config.js'),
     require('autoprefixer'),
   ])
   .browserSync({
     proxy: 'localhost',
     files: [
       '**/*.php',
       'assets/dist/css/**/*.css',
       'assets/dist/js/**/*.js'
     ],
     notify: false
   })
   .options({
     processCssUrls: false,
   })
   .sourceMaps()
   .version();

// Only run versioning in production
if (mix.inProduction()) {
  mix.version();
}