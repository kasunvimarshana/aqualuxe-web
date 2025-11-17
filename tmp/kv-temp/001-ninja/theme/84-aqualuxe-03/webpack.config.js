const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const fs = require('fs');

class ManifestPlugin {
  apply(compiler) {
    compiler.hooks.done.tap('ManifestPlugin', (stats) => {
      const assets = stats.toJson({ assets: true }).assetsByChunkName;
      const manifest = {};
    for (const [chunk, files] of Object.entries(assets)) {
        const arr = Array.isArray(files) ? files : [files];
        for (const f of arr) {
      if (f.endsWith('.js')) manifest[`js/${chunk}.js`] = `assets/dist/${f}`;
      if (f.endsWith('.css')) manifest[`css/${chunk}.css`] = `assets/dist/${f}`;
        }
      }
      const out = path.resolve(__dirname, 'assets/dist/manifest.json');
      fs.mkdirSync(path.dirname(out), { recursive: true });
      fs.writeFileSync(out, JSON.stringify(manifest, null, 2));
    });
  }
}

module.exports = (env, argv) => {
  const isProd = argv.mode === 'production';
  return {
    entry: {
      app: './assets/src/js/app.js',
      hero: './assets/src/js/hero.js',
      style: './assets/src/scss/style.scss',
      editor: './assets/src/scss/editor.scss',
      'skin-default': './assets/src/scss/skin-default.scss',
      'skin-dark': './assets/src/scss/skin-dark.scss',
      vendor: './assets/src/js/vendor.js'
    },
    output: {
      path: path.resolve(__dirname, 'assets/dist'),
      filename: 'js/[name]' + (isProd ? '.[contenthash:8]' : '') + '.js',
      clean: true
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: 'babel-loader',
            options: { presets: [['@babel/preset-env', { targets: 'defaults' }]] }
          }
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader']
        }
      ]
    },
    plugins: [
      new MiniCssExtractPlugin({ filename: 'css/[name]' + (isProd ? '.[contenthash:8]' : '') + '.css' }),
      new ManifestPlugin()
    ],
    devtool: isProd ? 'source-map' : 'eval-source-map'
  };
};
