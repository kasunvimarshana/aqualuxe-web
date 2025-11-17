const mix = require("laravel-mix");
const path = require("path");

mix.setPublicPath("assets/dist");

mix
  .js("assets/src/js/app.js", "js")
  .js("assets/src/js/admin.js", "js")
  .extract() // vendors.js
  .postCss("assets/src/css/app.css", "css", [
    require("postcss-import"),
    require("tailwindcss"),
    require("autoprefixer"),
  ])
  .postCss("assets/src/css/admin.css", "css", [
    require("postcss-import"),
    require("tailwindcss"),
    require("autoprefixer"),
  ])
  .options({ processCssUrls: false })
  .version()
  .copyDirectory("assets/src/images", "assets/dist/images")
  .copyDirectory("assets/src/fonts", "assets/dist/fonts");

mix.webpackConfig({
  stats: "errors-only",
  resolve: {
    alias: { "@": path.resolve(__dirname, "assets/src/js") },
  },
});
