const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your WordPress theme. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.setPublicPath("assets/dist");
mix.setResourceRoot("../"); // Needed for url() in CSS

mix
  .js("assets/src/js/app.js", "js")
  .sass("assets/src/scss/app.scss", "css")
  .options({
    processCssUrls: true,
    postCss: [require("tailwindcss")],
  });

if (mix.inProduction()) {
  mix.version();
}

mix.webpackConfig({
  stats: {
    children: true,
  },
});

// Browsersync
mix.browserSync({
  proxy: "localhost:8000", // Your local WordPress URL
  files: ["**/*.php", "assets/dist/js/**/*.js", "assets/dist/css/**/*.css"],
  injectChanges: true,
  open: false,
});
