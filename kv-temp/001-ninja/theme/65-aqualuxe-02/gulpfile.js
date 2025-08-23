/**
 * AquaLuxe Theme - Gulp Tasks for Image Optimization
 * 
 * This file contains Gulp tasks for optimizing images in the AquaLuxe theme.
 * It works alongside webpack.mix.js to provide comprehensive asset management.
 */

const { src, dest, watch, series, parallel } = require('gulp');
const imagemin = require('gulp-imagemin');
const webp = require('gulp-webp');
const responsive = require('gulp-responsive');
const rename = require('gulp-rename');
const newer = require('gulp-newer');
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const del = require('del');
const path = require('path');
const gulpif = require('gulp-if');
const flatten = require('gulp-flatten');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminGifsicle = require('imagemin-gifsicle');

// Configuration
const config = {
  // Source and destination paths
  src: {
    images: 'assets/src/images/**/*.{jpg,jpeg,png,gif,svg}',
    screenshots: 'screenshots/**/*.{jpg,jpeg,png}',
    webpSource: 'assets/dist/images/**/*.{jpg,jpeg,png}',
  },
  dest: {
    images: 'assets/dist/images',
    webp: 'assets/dist/images',
    responsive: 'assets/dist/images/responsive',
  },
  // Responsive image sizes
  responsiveSizes: [320, 640, 768, 1024, 1366, 1920],
  // Image optimization quality
  quality: {
    jpg: 85,
    png: 85,
    webp: 80,
  },
  // Production mode flag
  production: process.env.NODE_ENV === 'production',
};

// Error handling
const errorHandler = (title) => {
  return {
    errorHandler: notify.onError({
      title: title || 'Error',
      message: '<%= error.message %>',
      sound: 'Beep',
    }),
  };
};

// Clean image directories
function cleanImages() {
  return del([
    `${config.dest.images}/**/*`,
  ]);
}

// Optimize images
function optimizeImages() {
  return src(config.src.images)
    .pipe(plumber(errorHandler('Image Optimization Error')))
    .pipe(newer(config.dest.images))
    .pipe(
      imagemin(
        [
          imageminMozjpeg({
            quality: config.quality.jpg,
            progressive: true,
          }),
          imageminPngquant({
            quality: [config.quality.png / 100, Math.min(config.quality.png + 5, 100) / 100],
            speed: config.production ? 1 : 3,
          }),
          imageminSvgo({
            plugins: [
              { removeViewBox: false },
              { cleanupIDs: false },
              { removeUselessDefs: false },
            ],
          }),
          imageminGifsicle({
            optimizationLevel: config.production ? 3 : 1,
            interlaced: true,
          }),
        ],
        {
          verbose: !config.production,
        }
      )
    )
    .pipe(dest(config.dest.images))
    .pipe(notify({ message: 'Images optimized successfully', onLast: true }));
}

// Generate WebP versions
function generateWebP() {
  return src(config.src.webpSource)
    .pipe(plumber(errorHandler('WebP Conversion Error')))
    .pipe(newer({
      dest: config.dest.webp,
      ext: '.webp',
    }))
    .pipe(
      webp({
        quality: config.quality.webp,
        method: config.production ? 6 : 4,
      })
    )
    .pipe(dest(config.dest.webp))
    .pipe(notify({ message: 'WebP images generated successfully', onLast: true }));
}

// Generate responsive images
function generateResponsiveImages() {
  return src([
    'assets/src/images/responsive/**/*.{jpg,jpeg,png}',
    'assets/src/images/hero/**/*.{jpg,jpeg,png}',
    'assets/src/images/backgrounds/**/*.{jpg,jpeg,png}',
  ])
    .pipe(plumber(errorHandler('Responsive Images Error')))
    .pipe(
      responsive(
        {
          '**/*': config.responsiveSizes.map(size => ({
            width: size,
            rename: { suffix: `-${size}w` },
            quality: config.quality.jpg,
          })),
        },
        {
          errorOnUnusedConfig: false,
          errorOnUnusedImage: false,
          errorOnEnlargement: false,
          progressive: true,
          withMetadata: false,
          skipOnEnlargement: true,
        }
      )
    )
    .pipe(dest(config.dest.responsive))
    .pipe(notify({ message: 'Responsive images generated successfully', onLast: true }));
}

// Optimize screenshot images
function optimizeScreenshots() {
  return src(config.src.screenshots)
    .pipe(plumber(errorHandler('Screenshot Optimization Error')))
    .pipe(newer('screenshots'))
    .pipe(
      imagemin(
        [
          imageminMozjpeg({
            quality: 90,
            progressive: true,
          }),
          imageminPngquant({
            quality: [0.9, 0.95],
            speed: 1,
          }),
        ],
        {
          verbose: !config.production,
        }
      )
    )
    .pipe(dest('screenshots'))
    .pipe(notify({ message: 'Screenshots optimized successfully', onLast: true }));
}

// Generate image placeholders (LQIP)
function generatePlaceholders() {
  return src([
    'assets/src/images/responsive/**/*.{jpg,jpeg,png}',
    'assets/src/images/hero/**/*.{jpg,jpeg,png}',
  ])
    .pipe(plumber(errorHandler('Placeholder Generation Error')))
    .pipe(
      responsive(
        {
          '**/*': {
            width: 40,
            rename: { suffix: '-placeholder' },
            quality: 20,
          },
        },
        {
          errorOnUnusedConfig: false,
          errorOnUnusedImage: false,
          errorOnEnlargement: false,
          progressive: true,
          withMetadata: false,
        }
      )
    )
    .pipe(dest(config.dest.responsive))
    .pipe(notify({ message: 'Image placeholders generated successfully', onLast: true }));
}

// Watch for image changes
function watchImages() {
  watch(config.src.images, series(optimizeImages, generateWebP));
  watch([
    'assets/src/images/responsive/**/*.{jpg,jpeg,png}',
    'assets/src/images/hero/**/*.{jpg,jpeg,png}',
    'assets/src/images/backgrounds/**/*.{jpg,jpeg,png}',
  ], series(generateResponsiveImages, generatePlaceholders));
}

// Export tasks
exports.images = series(
  cleanImages,
  parallel(optimizeImages, optimizeScreenshots),
  generateWebP,
  generateResponsiveImages,
  generatePlaceholders
);

exports.watchImages = watchImages;

// Default task
exports.default = series(
  cleanImages,
  parallel(optimizeImages, optimizeScreenshots),
  generateWebP,
  generateResponsiveImages,
  generatePlaceholders
);