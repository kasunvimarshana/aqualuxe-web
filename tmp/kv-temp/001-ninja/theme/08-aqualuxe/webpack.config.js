const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
  mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
  entry: {
    main: './assets/js/main.js',
    woocommerce: './assets/js/woocommerce.js',
    homepage: './assets/js/homepage.js',
    'dark-mode': './assets/js/dark-mode.js',
    navigation: './assets/js/navigation.js',
    'lazy-loading': './assets/js/lazy-loading.js',
  },
  output: {
    filename: '[name].min.js',
    path: path.resolve(__dirname, 'assets/js/dist'),
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      }
    ]
  },
  optimization: {
    minimizer: [
      new TerserPlugin({
        extractComments: false,
        terserOptions: {
          format: {
            comments: false,
          },
          compress: {
            drop_console: process.env.NODE_ENV === 'production',
          },
        },
      }),
    ],
  },
  devtool: process.env.NODE_ENV === 'production' ? false : 'source-map',
};