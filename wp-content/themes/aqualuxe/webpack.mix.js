const mix = require("laravel-mix");
const path = require("path");

mix.setPublicPath("assets/dist");

mix
  .js("assets/src/js/app.js", "js")
  .sass("assets/src/scss/app.scss", "css")
  .options({
    processCssUrls: false,
    postCss: [require("postcss-import"), require("tailwindcss")],
  });

mix.copyDirectory("assets/src/images", "assets/dist/images");
mix.copyDirectory("assets/src/fonts", "assets/dist/fonts");

mix.browserSync({
  proxy: "http://localhost:8000", // Your local WordPress URL
  files: ["**/*.php", "assets/dist/js/**/*.js", "assets/dist/css/**/*.css"],
  injectChanges: true,
  open: false,
});

if (mix.inProduction()) {
  mix.version();
} else {
  mix.sourceMaps();
}

mix.webpackConfig({
  stats: {
    children: true,
  },
});
