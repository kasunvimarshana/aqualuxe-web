const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackAssetsManifest = require('webpack-assets-manifest');

module.exports = {
	mode: process.env.NODE_ENV || 'development',
	entry: {
		main: path.resolve(__dirname, 'assets/src/js/main.js'),
		styles: path.resolve(__dirname, 'assets/src/css/main.css'),
		"skins/default": path.resolve(__dirname, 'assets/src/css/skins/default.css'),
		"skins/dark": path.resolve(__dirname, 'assets/src/css/skins/dark.css'),
	},
	output: {
		filename: 'assets/dist/[name].js',
		path: path.resolve(__dirname),
		clean: true,
	},
	module: {
		rules: [
			{ test: /\.css$/, use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader'] },
		]
	},
	plugins: [
		new MiniCssExtractPlugin({ filename: 'assets/dist/[name].css' }),
		new WebpackAssetsManifest({
			output: 'assets/manifest.json',
			publicPath: true,
			writeToDisk: true
		})
	],
	devtool: 'source-map'
};
