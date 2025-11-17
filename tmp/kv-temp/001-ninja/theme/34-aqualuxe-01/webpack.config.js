const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

// Define paths
const SRC_DIR = path.resolve(__dirname, 'assets/src');
const DIST_DIR = path.resolve(__dirname, 'assets/dist');

// Configuration
module.exports = (env, argv) => {
  const isDevelopment = argv.mode === 'development';
  
  return {
    entry: {
      main: `${SRC_DIR}/js/main.js`,
      admin: `${SRC_DIR}/js/admin.js`,
      'product-filter': `${SRC_DIR}/js/product-filter.js`,
      'woocommerce': `${SRC_DIR}/js/woocommerce.js`,
    },
    output: {
      path: DIST_DIR,
      filename: 'js/[name].js',
    },
    devtool: isDevelopment ? 'source-map' : false,
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
            },
          },
        },
        // CSS, PostCSS, and Sass
        {
          test: /\.(scss|css)$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: {
                importLoaders: 2,
                sourceMap: isDevelopment,
              },
            },
            {
              loader: 'postcss-loader',
              options: {
                sourceMap: isDevelopment,
                postcssOptions: {
                  plugins: [
                    'tailwindcss',
                    'autoprefixer',
                    ...(isDevelopment ? [] : ['cssnano']),
                  ],
                },
              },
            },
            {
              loader: 'sass-loader',
              options: {
                sourceMap: isDevelopment,
              },
            },
          ],
        },
        // Fonts
        {
          test: /\.(woff|woff2|eot|ttf|otf)$/i,
          type: 'asset/resource',
          generator: {
            filename: 'fonts/[name][ext]',
          },
        },
        // Images
        {
          test: /\.(png|svg|jpg|jpeg|gif)$/i,
          type: 'asset/resource',
          generator: {
            filename: 'images/[name][ext]',
          },
        },
      ],
    },
    optimization: {
      minimizer: [
        new TerserPlugin({
          extractComments: false,
          terserOptions: {
            format: {
              comments: false,
            },
          },
        }),
      ],
    },
    plugins: [
      // Clean dist directory
      new CleanWebpackPlugin({
        cleanStaleWebpackAssets: false,
      }),
      // Extract CSS
      new MiniCssExtractPlugin({
        filename: 'css/[name].css',
      }),
      // Copy images and fonts
      new CopyPlugin({
        patterns: [
          {
            from: `${SRC_DIR}/images`,
            to: `${DIST_DIR}/images`,
            noErrorOnMissing: true,
          },
          {
            from: `${SRC_DIR}/fonts`,
            to: `${DIST_DIR}/fonts`,
            noErrorOnMissing: true,
          },
        ],
      }),
      // BrowserSync for development
      ...(isDevelopment
        ? [
            new BrowserSyncPlugin({
              host: 'localhost',
              port: 3000,
              proxy: 'http://localhost:8888', // Change this to your local development URL
              files: [
                '**/*.php',
                'assets/dist/css/**/*.css',
                'assets/dist/js/**/*.js',
              ],
              injectChanges: true,
              notify: false,
            }),
          ]
        : []),
    ],
  };
};