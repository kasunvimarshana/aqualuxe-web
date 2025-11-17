# AquaLuxe Enhanced Asset Pipeline

## Overview

The AquaLuxe theme now includes a comprehensive asset optimization pipeline that ensures all assets (images, fonts, stylesheets, and JavaScript files) are properly optimized, minified, and bundled for production. This document explains how the asset pipeline works and how to use it effectively.

## Key Features

1. **Comprehensive Asset Optimization**
   - JavaScript minification and bundling
   - CSS optimization and minification
   - Image optimization with WebP conversion
   - Font subsetting and optimization
   - SVG sprite generation
   - Critical CSS extraction

2. **Cache Busting**
   - Automatic versioning of assets
   - Content-based hashing for optimal caching
   - Asset manifest generation

3. **Performance Enhancements**
   - Code splitting for optimal loading
   - Vendor chunk extraction
   - Preloading of critical assets
   - Asynchronous loading of non-critical assets

4. **Development Experience**
   - Source maps for easier debugging
   - Hot module replacement
   - Browser sync for live reloading
   - Build process visualization

## Directory Structure

```
assets/
├── css/               # Compiled CSS files
│   ├── critical/      # Critical CSS files
│   └── ...
├── js/                # Compiled JavaScript files
│   ├── components/    # Component-specific JavaScript
│   └── ...
├── fonts/             # Optimized font files
├── images/            # Optimized images
│   └── sprite.svg     # SVG sprite
└── assets-manifest.json  # Asset manifest for cache busting
```

## Build Process

The build process is powered by Laravel Mix (a wrapper around webpack) and includes the following steps:

1. **Clean** - Remove previously built assets
2. **Compile** - Compile SCSS to CSS and transpile modern JavaScript
3. **Optimize** - Minify and optimize all assets
4. **Bundle** - Bundle assets together for optimal loading
5. **Version** - Add content-based hashes for cache busting
6. **Generate** - Create SVG sprites, critical CSS, and service worker

## Using the Asset Pipeline

### Development Build

For development, use the following command:

```bash
npm run dev
```

This will:
- Compile assets without minification
- Generate source maps
- Enable hot module replacement
- Start browser sync for live reloading

### Production Build

For production, use the following command:

```bash
npm run prod
```

This will:
- Compile and minify all assets
- Optimize images and convert to WebP
- Generate critical CSS
- Create SVG sprites
- Add content-based hashing for cache busting
- Generate a service worker

### Admin Interface

The theme includes an admin interface for building assets directly from the WordPress admin panel. To access it:

1. Go to **Tools > Asset Builder**
2. Select the build mode (Production or Development)
3. Choose additional options (Clean first, Generate critical CSS)
4. Click "Build Assets"

## Loading Assets in Templates

The theme includes helper functions for loading assets with proper versioning:

### Enqueuing Scripts

```php
// Traditional WordPress way
wp_enqueue_script(
    'script-handle',
    get_template_directory_uri() . '/assets/js/script.js',
    array('dependency'),
    '1.0.0',
    true
);

// Enhanced way with automatic versioning
aqualuxe_enqueue_script(
    'script-handle',
    'assets/js/script.js',
    array('dependency'),
    true
);
```

### Enqueuing Styles

```php
// Traditional WordPress way
wp_enqueue_style(
    'style-handle',
    get_template_directory_uri() . '/assets/css/style.css',
    array('dependency'),
    '1.0.0'
);

// Enhanced way with automatic versioning
aqualuxe_enqueue_style(
    'style-handle',
    'assets/css/style.css',
    array('dependency')
);
```

### Loading Images with WebP Support

```php
// Output an image with WebP support
aqualuxe_image(
    'assets/images/example.jpg',
    'Alt text',
    array(
        'class' => 'example-image',
        'width' => 800,
        'height' => 600
    )
);
```

### Using SVG Icons from Sprite

```php
// Output an SVG icon from the sprite
aqualuxe_icon(
    'icon-name',
    array(
        'class' => 'additional-class',
        'width' => 24,
        'height' => 24
    )
);
```

### Adding Critical CSS

```php
// Add critical CSS for a specific template
aqualuxe_critical_css('home');
```

### Preloading Key Assets

```php
// Preload key assets
aqualuxe_preload_assets(
    array(
        'assets/fonts/main-font.woff2' => 'font',
        'assets/css/main.css' => 'style',
        'assets/js/app.js' => 'script',
        'assets/images/hero.jpg' => 'image'
    )
);
```

## Configuration

The asset pipeline is configured in the following files:

1. **webpack.mix.js** - Main configuration file for Laravel Mix
2. **package.json** - NPM dependencies and scripts
3. **tailwind.config.js** - Tailwind CSS configuration
4. **postcss.config.js** - PostCSS configuration

### Key Configuration Options

#### JavaScript Bundles

JavaScript bundles are defined in `webpack.mix.js`:

```javascript
const jsBundles = {
  'app': [
    `${srcPath}/js/navigation.js`,
    `${srcPath}/js/dark-mode.js`,
    `${srcPath}/js/custom.js`
  ],
  'woocommerce': [
    `${srcPath}/js/checkout-steps.js`,
    `${srcPath}/js/country-selector.js`,
    `${srcPath}/js/woocommerce.js`
  ]
};
```

#### SCSS Files

SCSS files are defined in `webpack.mix.js`:

```javascript
mix.sass(`${srcPath}/scss/main.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/dark-mode.scss`, `${distPath}/css`)
   .sass(`${srcPath}/scss/woocommerce.scss`, `${distPath}/css`);
```

#### Critical CSS Templates

Critical CSS templates are defined in `webpack.mix.js`:

```javascript
const templates = [
  { name: 'home', url: '/' },
  { name: 'blog', url: '/blog/' },
  { name: 'shop', url: '/shop/' },
  { name: 'product', url: '/product/sample-product/' }
];
```

## Best Practices

1. **Use the Helper Functions**
   - Always use the provided helper functions (`aqualuxe_enqueue_script`, `aqualuxe_enqueue_style`, etc.) to ensure proper versioning and cache busting.

2. **Optimize Images Before Adding**
   - While the build process will optimize images, it's best to start with optimized images to reduce build time.

3. **Use SVG for Icons**
   - Add SVG icons to the `assets/src/images/icons` directory to have them automatically included in the sprite.

4. **Organize SCSS Files**
   - Follow the 7-1 pattern for organizing SCSS files (abstracts, base, components, layout, pages, themes, vendors).

5. **Split JavaScript into Modules**
   - Create small, focused JavaScript modules that can be combined as needed.

6. **Use Critical CSS**
   - Identify critical CSS for key templates to improve initial load performance.

7. **Preload Key Assets**
   - Use the `aqualuxe_preload_assets` function to preload key assets for better performance.

8. **Run Production Build Before Deployment**
   - Always run the production build before deploying to ensure all optimizations are applied.

## Troubleshooting

### Common Issues

1. **Assets Not Updating**
   - Clear browser cache
   - Run with the "Clean first" option
   - Check for errors in the build process

2. **Missing Assets**
   - Ensure the file paths are correct
   - Check if the files are being excluded by .gitignore
   - Verify the build process completed successfully

3. **JavaScript Errors**
   - Check the browser console for errors
   - Enable source maps in development mode
   - Verify dependencies are correctly loaded

4. **CSS Issues**
   - Check for syntax errors in SCSS files
   - Verify PostCSS plugins are correctly configured
   - Check for conflicts in Tailwind configuration

### Build Process Logs

Build process logs are available in the following locations:

1. **Command Line** - When running npm scripts directly
2. **Admin Interface** - In the build result message
3. **Error Log** - Check PHP error log for server-side issues

## Extending the Asset Pipeline

### Adding New JavaScript Bundles

To add a new JavaScript bundle:

1. Add your JavaScript files to `assets/src/js`
2. Update the `jsBundles` object in `webpack.mix.js`:

```javascript
const jsBundles = {
  // Existing bundles...
  'new-bundle': [
    `${srcPath}/js/new-file1.js`,
    `${srcPath}/js/new-file2.js`
  ]
};
```

### Adding New SCSS Files

To add a new SCSS file:

1. Add your SCSS file to `assets/src/scss`
2. Update the SCSS compilation in `webpack.mix.js`:

```javascript
mix.sass(`${srcPath}/scss/new-file.scss`, `${distPath}/css`);
```

### Adding New Critical CSS Templates

To add a new critical CSS template:

1. Update the templates array in `webpack.mix.js`:

```javascript
const templates = [
  // Existing templates...
  { name: 'new-template', url: '/new-template/' }
];
```

### Adding Custom PostCSS Plugins

To add custom PostCSS plugins:

1. Install the plugin: `npm install postcss-plugin-name --save-dev`
2. Update the PostCSS options in `webpack.mix.js`:

```javascript
.options({
  postCss: [
    // Existing plugins...
    require('postcss-plugin-name')(options)
  ]
});
```

## Conclusion

The enhanced asset pipeline provides a comprehensive solution for optimizing all assets in the AquaLuxe theme. By following the guidelines in this document, you can ensure that your theme's assets are properly optimized, versioned, and loaded for optimal performance.