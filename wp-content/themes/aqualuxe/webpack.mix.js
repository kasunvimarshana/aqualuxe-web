const mix = require("laravel-mix");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");

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

mix.setPublicPath("assets/dist")
    .js("assets/src/js/app.js", "js")
    .sass("assets/src/scss/app.scss", "css")
    .postCss("assets/src/css/app.css", "assets/dist/css", [
        require("tailwindcss"),
    ])
    .version();

mix.webpackConfig({
    plugins: [
        new BrowserSyncPlugin({
            proxy: "http://localhost:8000",
            files: [
                "**/*.php",
                "assets/dist/js/**/*.js",
                "assets/dist/css/**/*.css",
            ],
            injectChanges: true,
            open: false,
        }),
    ],
});

if (mix.inProduction()) {
    mix.version();
}
