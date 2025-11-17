const mix = require("laravel-mix");
const glob = require("glob");

mix
  .js("assets/src/js/app.js", "assets/dist/js")
  .sass("assets/src/scss/app.scss", "assets/dist/css")
  .options({
    postCss: [require("tailwindcss")],
  });

// Process module assets
const moduleAssets = glob
  .sync("modules/*/assets/js/*.js")
  .concat(glob.sync("modules/*/assets/css/*.css"));
moduleAssets.forEach((asset) => {
  if (asset.endsWith(".js")) {
    mix.js(asset, "assets/dist/js");
  } else if (asset.endsWith(".css")) {
    mix.postCss(asset, "assets/dist/css");
  }
});

mix
  .setPublicPath("assets/dist")
  .browserSync({
    proxy: "http://localhost:8000", // Your local WordPress URL
    files: ["**/*.php", "assets/dist/css/**/*.css", "assets/dist/js/**/*.js"],
    injectChanges: true,
  })
  .version();

if (mix.inProduction()) {
  mix.version();
}
