const path = require("path");
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

// Set output directory
mix.setPublicPath("assets/dist").setResourceRoot("../");

// Compile JavaScript
mix.js("assets/src/js/app.js", "js/app.js").js(
    "assets/src/js/customizer.js",
    "js/customizer.js"
);

// Compile CSS with Tailwind
mix.postCss("assets/src/css/app.css", "css/app.css", [
    require("tailwindcss"),
    require("autoprefixer"),
]);

// Copy and optimize images
mix.copyDirectory("assets/src/images", "assets/dist/images");
mix.copyDirectory("assets/src/img", "assets/dist/img");

// Enable versioning for cache busting
mix.version();

// Webpack configuration
mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve("assets/src/js"),
        },
    },
    plugins: [
        new BrowserSyncPlugin({
            proxy: "http://localhost:8080",
            files: [
                "**/*.php",
                "assets/dist/js/**/*.js",
                "assets/dist/css/**/*.css",
            ],
            injectChanges: true,
            open: false,
            notify: false,
        }),
    ],
});

// Options
mix.options({
    processCssUrls: false,
    postCss: [require("tailwindcss")],
});

// Source maps for development
if (!mix.inProduction()) {
    mix.sourceMaps();
}
