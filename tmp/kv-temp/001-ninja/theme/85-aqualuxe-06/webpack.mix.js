const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');
mix.setResourceRoot('/');

// JS & SCSS entries
mix.js('assets/src/index.js', 'assets/dist/theme.js')
   .sass('assets/src/styles/theme.scss', 'assets/dist/theme.css')
   .sass('assets/src/styles/skin-dark.scss', 'assets/dist/skin-dark.css')
   .postCss('assets/src/styles/tailwind.css', 'assets/dist/tailwind.css', [
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   // Editor stylesheet (shares Tailwind tokens)
   .postCss('assets/src/styles/tailwind.css', 'assets/dist/editor.css', [
     require('tailwindcss'),
     require('autoprefixer'),
   ])
   .options({ processCssUrls: false });

// Copy static assets
mix.copyDirectory('assets/src/images', 'assets/dist/images');
mix.copyDirectory('assets/src/fonts', 'assets/dist/fonts');

// Enable sourcemaps in dev, versioning in prod
if (!mix.inProduction()) {
  mix.sourceMaps();
} else {
  mix.version();
}

// Resolve aliases
mix.webpackConfig({
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/src'),
    },
  },
});
