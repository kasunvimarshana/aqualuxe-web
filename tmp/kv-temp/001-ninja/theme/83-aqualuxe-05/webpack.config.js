const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { WebpackManifestPlugin } = require('webpack-manifest-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ImageminPlugin = require('imagemin-webpack-plugin').default;

const isDevelopment = process.env.NODE_ENV === 'development';
const isProduction = process.env.NODE_ENV === 'production';

module.exports = {
  context: path.resolve(__dirname, 'assets/src'),
  
  entry: {
    app: './js/app.js',
    admin: './js/admin.js',
    fishtank: './js/components/fishtank.js',
    woocommerce: './js/woocommerce.js',
    style: './scss/style.scss',
    admin: './scss/admin.scss',
    fishtank: './scss/components/fishtank.scss'
  },

  output: {
    path: path.resolve(__dirname, 'assets/dist'),
    filename: isDevelopment ? 'js/[name].js' : 'js/[name].[contenthash:8].js',
    chunkFilename: isDevelopment ? 'js/[name].chunk.js' : 'js/[name].[contenthash:8].chunk.js',
    publicPath: '',
    clean: true,
  },

  mode: isDevelopment ? 'development' : 'production',
  
  devtool: isDevelopment ? 'eval-source-map' : 'source-map',

  module: {
    rules: [
      // JavaScript/TypeScript
      {
        test: /\.(js|ts)$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              ['@babel/preset-env', {
                targets: {
                  browsers: ['> 1%', 'last 2 versions', 'not ie <= 11']
                },
                modules: false,
                useBuiltIns: 'usage',
                corejs: 3
              }],
              '@babel/preset-typescript'
            ],
            plugins: [
              '@babel/plugin-syntax-dynamic-import'
            ]
          }
        }
      },

      // SCSS/CSS
      {
        test: /\.(scss|css)$/,
        use: [
          isDevelopment ? 'style-loader' : MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              importLoaders: 2,
              sourceMap: isDevelopment,
            }
          },
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  require('tailwindcss'),
                  require('autoprefixer'),
                  ...(isProduction ? [require('cssnano')] : [])
                ]
              },
              sourceMap: isDevelopment,
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: isDevelopment,
              sassOptions: {
                outputStyle: isDevelopment ? 'expanded' : 'compressed',
                includePaths: [
                  path.resolve(__dirname, 'node_modules'),
                  path.resolve(__dirname, 'assets/src/scss')
                ]
              }
            }
          }
        ]
      },

      // Images
      {
        test: /\.(png|jpe?g|gif|svg)$/i,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 8 * 1024 // 8kb
          }
        },
        generator: {
          filename: 'images/[name].[hash:8][ext]'
        }
      },

      // Fonts
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: 'asset/resource',
        generator: {
          filename: 'fonts/[name].[hash:8][ext]'
        }
      },

      // 3D Models
      {
        test: /\.(glb|gltf)$/i,
        type: 'asset/resource',
        generator: {
          filename: 'models/[name].[hash:8][ext]'
        }
      },

      // Audio
      {
        test: /\.(mp3|wav|ogg)$/i,
        type: 'asset/resource',
        generator: {
          filename: 'audio/[name].[hash:8][ext]'
        }
      }
    ]
  },

  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV),
      'process.env.WP_DEBUG': JSON.stringify(process.env.WP_DEBUG || false)
    }),

    ...(isProduction ? [new CleanWebpackPlugin()] : []),

    new MiniCssExtractPlugin({
      filename: isDevelopment ? 'css/[name].css' : 'css/[name].[contenthash:8].css',
      chunkFilename: isDevelopment ? 'css/[name].chunk.css' : 'css/[name].[contenthash:8].chunk.css'
    }),

    new WebpackManifestPlugin({
      fileName: 'manifest.json',
      publicPath: '',
      writeToFileEmit: true,
      generate: (seed, files, entrypoints) => {
        const manifestFiles = files.reduce((manifest, file) => {
          manifest[file.name] = file.path;
          return manifest;
        }, seed);

        const entrypointFiles = entrypoints.app.filter(
          fileName => !fileName.endsWith('.map')
        );

        return {
          files: manifestFiles,
          entrypoints: entrypointFiles
        };
      }
    }),

    new CopyWebpackPlugin({
      patterns: [
        {
          from: 'images',
          to: 'images',
          noErrorOnMissing: true
        },
        {
          from: 'fonts',
          to: 'fonts',
          noErrorOnMissing: true
        },
        {
          from: 'models',
          to: 'models',
          noErrorOnMissing: true
        },
        {
          from: 'audio',
          to: 'audio',
          noErrorOnMissing: true
        }
      ]
    }),

    ...(isProduction ? [
      new ImageminPlugin({
        test: /\.(jpe?g|png|gif|svg)$/i,
        pngquant: {
          quality: '65-90'
        },
        mozjpeg: {
          progressive: true,
          quality: 85
        },
        svgo: {
          plugins: [
            { name: 'removeViewBox', active: false },
            { name: 'removeEmptyAttrs', active: false }
          ]
        }
      })
    ] : [])
  ],

  optimization: {
    splitChunks: {
      chunks: 'all',
      cacheGroups: {
        vendor: {
          test: /[\\/]node_modules[\\/]/,
          name: 'vendors',
          chunks: 'all',
          priority: 10
        },
        common: {
          name: 'common',
          minChunks: 2,
          chunks: 'all',
          priority: 5,
          reuseExistingChunk: true
        }
      }
    },
    
    minimizer: [
      new TerserPlugin({
        terserOptions: {
          compress: {
            drop_console: isProduction,
            drop_debugger: isProduction
          },
          format: {
            comments: false
          }
        },
        extractComments: false
      }),
      new CssMinimizerPlugin()
    ]
  },

  resolve: {
    extensions: ['.js', '.ts', '.scss', '.css'],
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
      '@js': path.resolve(__dirname, 'assets/src/js'),
      '@scss': path.resolve(__dirname, 'assets/src/scss'),
      '@images': path.resolve(__dirname, 'assets/src/images'),
      '@fonts': path.resolve(__dirname, 'assets/src/fonts'),
      '@models': path.resolve(__dirname, 'assets/src/models'),
      '@audio': path.resolve(__dirname, 'assets/src/audio')
    }
  },

  devServer: {
    hot: true,
    port: 3000,
    host: 'localhost',
    static: {
      directory: path.join(__dirname, 'assets/dist')
    },
    watchFiles: [
      '*.php',
      'templates/**/*.php',
      'inc/**/*.php',
      'modules/**/*.php'
    ]
  },

  performance: {
    hints: isProduction ? 'warning' : false,
    maxEntrypointSize: 512000,
    maxAssetSize: 512000
  },

  stats: {
    colors: true,
    chunks: false,
    modules: false,
    children: false,
    timings: true,
    assets: true,
    entrypoints: false
  }
};
