# AquaLuxe WordPress Theme - Build Process Documentation

This document provides comprehensive information about the build process for the AquaLuxe WordPress theme.

## Table of Contents

1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Build Commands](#build-commands)
4. [Asset Pipeline](#asset-pipeline)
5. [Font Processing](#font-processing)
6. [SVG Sprite Generation](#svg-sprite-generation)
7. [Critical CSS Generation](#critical-css-generation)
8. [Troubleshooting](#troubleshooting)

## Requirements

- Node.js >= 18.0.0
- npm >= 8.0.0
- WordPress >= 6.0
- PHP >= 7.4
- WooCommerce >= 7.0 (if using WooCommerce features)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/aqualuxe.git
   ```

2. Navigate to the theme directory:
   ```bash
   cd aqualuxe
   ```

3. Install dependencies:
   ```bash
   npm install
   ```

## Build Commands

The following npm scripts are available for building the theme:

- **Development Build**:
  ```bash
  npm run dev
  ```
  or
  ```bash
  npm run development
  ```

- **Production Build**:
  ```bash
  npm run build
  ```
  or
  ```bash
  npm run production && npm run critical
  ```

- **Watch for Changes**:
  ```bash
  npm run watch
  ```

- **Hot Module Replacement**:
  ```bash
  npm run hot
  ```

- **Analyze Bundle**:
  ```bash
  npm run analyze
  ```

- **Clean Assets**:
  ```bash
  npm run clean
  ```

- **Individual Asset Generation**:
  - Critical CSS: `npm run critical`
  - Image Optimization: `npm run imagemin`
  - SVG Sprite: `npm run svg-sprite`

## Asset Pipeline

The build process uses Laravel Mix (a wrapper around webpack) to compile and optimize assets:

### JavaScript Processing

- ES6+ transpilation with Babel
- Module bundling
- Minification in production mode
- Source maps in development mode
- Vendor extraction in production mode

### CSS/SCSS Processing

- SCSS compilation
- PostCSS processing
- Autoprefixer for browser compatibility
- PurgeCSS for removing unused CSS in production
- Minification in production mode
- Source maps in development mode

### Image Processing

Images are optimized using the `imagemin.js` script, which:

1. Optimizes JPEG, PNG, GIF, and SVG images
2. Converts images to WebP format
3. Maintains directory structure
4. Preserves user uploads

## Font Processing

Fonts are processed using a simple copy approach:

1. Place font files in `assets/src/fonts/`
2. The build process copies them to `assets/fonts/`
3. No additional optimization is performed to ensure maximum compatibility

## SVG Sprite Generation

SVG icons are combined into a single sprite file using the `svg-sprite.js` script:

1. Place individual SVG icons in `assets/src/images/icons/`
2. Run `npm run svg-sprite`
3. The sprite is generated at `assets/images/sprite.svg`
4. An example usage HTML file is created at `assets/images/icons-example.html`

### Using SVG Sprites

```html
<svg class="icon" aria-hidden="true">
  <use xlink:href="#icon-name"></use>
</svg>
```

## Critical CSS Generation

Critical CSS is generated using the `critical-css-simple.js` script:

1. The script creates placeholder critical CSS files for various templates
2. Files are generated for both mobile and desktop viewports
3. Critical CSS is stored in `assets/css/critical/`

### Using Critical CSS

```php
<?php aqualuxe_critical_css('home'); ?>
```

## Troubleshooting

### Common Issues

#### Node.js Version Errors

If you encounter errors related to Node.js version, ensure you're using Node.js 18.0.0 or higher:

```bash
node -v
```

If needed, update Node.js or use a version manager like nvm:

```bash
nvm install 18
nvm use 18
```

#### Build Process Fails

If the build process fails:

1. Clear the node_modules directory and reinstall:
   ```bash
   rm -rf node_modules
   npm install
   ```

2. Clear any cached assets:
   ```bash
   npm run clean
   ```

3. Try building in development mode first:
   ```bash
   npm run dev
   ```

#### SVG Sprite Issues

If SVG sprite generation fails:

1. Ensure SVG files are properly formatted
2. Check that the icons directory exists: `assets/src/images/icons/`
3. Run the SVG sprite generator separately: `npm run svg-sprite`

#### Critical CSS Issues

If critical CSS generation fails:

1. Use the simplified generator: `node critical-css-simple.js`
2. Check that the output directory exists: `assets/css/critical/`

### Getting Help

If you encounter issues not covered in this guide:

1. Check the [GitHub repository issues](https://github.com/your-repo/aqualuxe/issues)
2. Contact the theme developer at support@example.com