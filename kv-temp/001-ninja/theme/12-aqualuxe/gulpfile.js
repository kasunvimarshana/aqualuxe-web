/**
 * AquaLuxe Theme Gulpfile
 *
 * @package AquaLuxe
 */

const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const babel = require('gulp-babel');
const terser = require('gulp-terser');
const concat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const imagemin = require('gulp-imagemin');
const del = require('del');
const browserSync = require('browser-sync').create();
const rename = require('gulp-rename');

// File path variables
const paths = {
  styles: {
    src: 'src/scss/**/*.scss',
    dest: 'assets/css'
  },
  scripts: {
    src: 'src/js/**/*.js',
    dest: 'assets/js'
  },
  images: {
    src: 'src/images/**/*',
    dest: 'assets/images'
  },
  fonts: {
    src: 'src/fonts/**/*',
    dest: 'assets/fonts'
  }
};

// Clean assets
function clean() {
  return del([
    'assets/css/*',
    'assets/js/*',
    '!assets/images',
    '!assets/fonts'
  ]);
}

// Process SCSS files
function styles() {
  return src(paths.styles.src)
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer(), cssnano()]))
    .pipe(rename({ suffix: '.min' }))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

// Process JavaScript files
function scripts() {
  return src(paths.scripts.src)
    .pipe(sourcemaps.init())
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(terser())
    .pipe(concat('main.min.js'))
    .pipe(sourcemaps.write('.'))
    .pipe(dest(paths.scripts.dest))
    .pipe(browserSync.stream());
}

// Optimize images
function images() {
  return src(paths.images.src)
    .pipe(imagemin())
    .pipe(dest(paths.images.dest));
}

// Copy fonts
function fonts() {
  return src(paths.fonts.src)
    .pipe(dest(paths.fonts.dest));
}

// Watch for changes
function watchFiles() {
  // Initialize BrowserSync
  browserSync.init({
    proxy: 'localhost:8000', // Change this to your local development URL
    notify: false
  });

  watch(paths.styles.src, styles);
  watch(paths.scripts.src, scripts);
  watch(paths.images.src, images);
  watch(paths.fonts.src, fonts);
  watch('**/*.php').on('change', browserSync.reload);
}

// Define complex tasks
const build = series(clean, parallel(styles, scripts, images, fonts));
const dev = series(build, watchFiles);

// Export tasks
exports.clean = clean;
exports.styles = styles;
exports.scripts = scripts;
exports.images = images;
exports.fonts = fonts;
exports.build = build;
exports.watch = watchFiles;
exports.default = dev;