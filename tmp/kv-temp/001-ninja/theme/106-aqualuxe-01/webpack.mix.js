const mix = require("laravel-mix");
const path = require("path");

mix.setPublicPath("assets/dist");

mix.webpackConfig({
	output: { chunkFilename: "js/[name].[chunkhash].js" },
	resolve: { alias: { "@": path.resolve(__dirname, "assets/src/js") } },
});

mix.js("assets/src/js/theme.js", "js/theme.js")
	.js("assets/src/js/dark-mode.js", "js/dark-mode.js")
	.js("assets/src/js/admin.js", "js/admin.js")
	.sass("assets/src/scss/theme.scss", "css/theme.css")
	.sass("assets/src/scss/admin.scss", "css/admin.css")
	.options({
		processCssUrls: false,
		postCss: [
			require("postcss-import"),
			require("tailwindcss"),
			require("autoprefixer"),
		],
	})
	.version();

if (!mix.inProduction()) {
	mix.sourceMaps();
}
