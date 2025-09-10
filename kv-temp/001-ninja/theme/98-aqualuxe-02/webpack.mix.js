const mix = require("laravel-mix");
const path = require("path");

mix.setPublicPath("assets/dist");
mix.webpackConfig({
  output: {
    chunkFilename: "js/[name].[contenthash].js",
  },
});

mix
  .js("assets/src/js/app.js", "js/app.js")
  .sass("assets/src/scss/app.scss", "css/app.css")
  .options({
    processCssUrls: false,
    postCss: [
      require("postcss-import"),
      require("tailwindcss")("./tailwind.config.js"),
      require("autoprefixer"),
      ...(process.env.NODE_ENV === "production"
        ? [require("cssnano")({ preset: "default" })]
        : []),
    ],
  })
  .sourceMaps(false, "source-map")
  .version();
