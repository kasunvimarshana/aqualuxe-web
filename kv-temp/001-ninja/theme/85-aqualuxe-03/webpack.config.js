const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env, argv) => {
  const isProd = argv.mode === 'production';
  return {
    entry: {
      theme: path.resolve(__dirname, 'assets/src/index.js'),
    },
    output: {
      path: path.resolve(__dirname, 'assets/dist'),
      filename: '[name].js',
      clean: true,
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  '@babel/preset-env',
                  {
                    targets: {
                      browsers: ['>0.25%', 'not dead'],
                    },
                  },
                ],
              ],
            },
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            MiniCssExtractPlugin.loader,
            {
              loader: 'css-loader',
              options: { sourceMap: !isProd },
            },
            {
              loader: 'postcss-loader',
              options: {
                postcssOptions: {
                  plugins: [
                    ['autoprefixer'],
                    isProd ? ['cssnano', { preset: 'default' }] : null,
                  ].filter(Boolean),
                },
                sourceMap: !isProd,
              },
            },
            {
              loader: 'sass-loader',
              options: { sourceMap: !isProd },
            },
          ],
        },
        {
          test: /\.(png|jpe?g|gif|svg|webp)$/i,
          type: 'asset',
        },
      ],
    },
    plugins: [
      new MiniCssExtractPlugin({ filename: '[name].css' }),
    ],
    devtool: isProd ? false : 'source-map',
  };
};
