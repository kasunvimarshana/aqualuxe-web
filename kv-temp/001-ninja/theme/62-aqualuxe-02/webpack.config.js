/**
 * AquaLuxe Webpack Configuration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

// Detect environment mode
const isProduction = process.env.NODE_ENV === 'production';

// Define paths
const PATHS = {
  src: path.resolve(__dirname, 'src'),
  dist: path.resolve(__dirname, 'assets'),
  modules: path.resolve(__dirname, 'modules'),
};

// Base config
const config = {
  mode: isProduction ? 'production' : 'development',
  entry: {
    // Main theme assets
    'js/theme': `${PATHS.src}/js/theme.js`,
    'css/theme': `${PATHS.src}/scss/theme.scss`,
    'css/admin': `${PATHS.src}/scss/admin.scss`,
    'css/editor': `${PATHS.src}/scss/editor.scss`,
    
    // WooCommerce specific assets
    'js/woocommerce': `${PATHS.src}/js/woocommerce.js`,
    'css/woocommerce': `${PATHS.src}/scss/woocommerce.scss`,
    
    // Dark mode assets
    'js/dark-mode': `${PATHS.modules}/dark-mode/src/js/dark-mode.js`,
    'css/dark-mode': `${PATHS.modules}/dark-mode/src/scss/dark-mode.scss`,
  },
  output: {
    path: PATHS.dist,
    filename: '[name].js',
    publicPath: '/wp-content/themes/aqualuxe/assets/',
  },
  devtool: isProduction ? false : 'source-map',
  module: {
    rules: [
      // JavaScript
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
            plugins: ['@babel/plugin-transform-runtime'],
          },
        },
      },
      // SCSS/CSS
      {
        test: /\.(scss|css)$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: !isProduction,
              importLoaders: 2,
            },
          },
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: !isProduction,
              postcssOptions: {
                plugins: [
                  require('autoprefixer'),
                  require('postcss-flexbugs-fixes'),
                  isProduction ? require('cssnano')({
                    preset: ['default', {
                      discardComments: {
                        removeAll: true,
                      },
                    }],
                  }) : null,
                ].filter(Boolean),
              },
            },
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: !isProduction,
              sassOptions: {
                outputStyle: 'expanded',
              },
            },
          },
        ],
      },
      // Images
      {
        test: /\.(png|jpg|jpeg|gif|svg)$/,
        type: 'asset/resource',
        generator: {
          filename: 'images/[name][ext]',
        },
      },
      // Fonts
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/,
        type: 'asset/resource',
        generator: {
          filename: 'fonts/[name][ext]',
        },
      },
    ],
  },
  plugins: [
    // Extract CSS into separate files
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
    
    // Clean dist folder
    new CleanWebpackPlugin({
      cleanStaleWebpackAssets: false,
    }),
    
    // Copy static assets
    new CopyPlugin({
      patterns: [
        { 
          from: `${PATHS.src}/images`, 
          to: 'images',
          noErrorOnMissing: true
        },
        { 
          from: `${PATHS.src}/fonts`, 
          to: 'fonts',
          noErrorOnMissing: true
        },
      ],
    }),
    
    // Extract WordPress dependencies
    new DependencyExtractionWebpackPlugin({
      injectPolyfill: true,
    }),
  ],
  optimization: {
    minimizer: [
      // Minimize JS
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          compress: {
            drop_console: isProduction,
          },
        },
      }),
      
      // Minimize CSS
      new CssMinimizerPlugin(),
    ],
    splitChunks: {
      cacheGroups: {
        vendor: {
          test: /[\\/]node_modules[\\/]/,
          name: 'js/vendors',
          chunks: 'all',
        },
      },
    },
  },
  resolve: {
    extensions: ['.js', '.scss', '.css'],
    alias: {
      '@src': PATHS.src,
      '@modules': PATHS.modules,
    },
  },
};

// Development specific configuration
if (!isProduction) {
  // Add BrowserSync
  config.plugins.push(
    new BrowserSyncPlugin({
      host: 'localhost',
      port: 3000,
      proxy: 'http://localhost:8888', // Change this to your local development URL
      files: [
        './**/*.php',
        './assets/js/**/*.js',
        './assets/css/**/*.css',
      ],
      injectChanges: true,
      notify: false,
    })
  );
}

module.exports = config;