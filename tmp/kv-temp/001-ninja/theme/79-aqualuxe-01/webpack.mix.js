const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');
mix.setResourceRoot('/assets/dist/');

mix.js('assets/src/js/app.js', 'js')
   .postCss('assets/src/css/app.css', 'css', [
     require('postcss-import'),
     require('tailwindcss'),
     require('autoprefixer')
   ])
   .sass('assets/src/scss/editor.scss', 'editor.css')
   .options({ processCssUrls: false })
   .webpackConfig({
     output: { chunkFilename: 'js/[name].js?id=[chunkhash]' },
     module: {
       rules: [
         { test: /\.m?js$/, exclude: /(node_modules|bower_components)/, use: { loader: 'babel-loader', options: { presets: ['@babel/preset-env'] } } },
         { test: /\.(png|jpe?g|gif|svg)$/i, type: 'asset/resource', generator: { filename: 'images/[name][hash][ext]' } },
         { test: /\.(woff2?|ttf|eot|otf)$/i, type: 'asset/resource', generator: { filename: 'fonts/[name][hash][ext]' } }
       ]
     }
   })
   .version();
