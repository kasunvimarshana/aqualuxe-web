# AquaLuxe WordPress Theme - Troubleshooting Guide

This guide provides solutions for common issues you might encounter when working with the AquaLuxe WordPress theme, particularly related to the build process.

## Table of Contents

1. [Build Process Issues](#build-process-issues)
2. [Asset Generation Issues](#asset-generation-issues)
3. [WordPress Integration Issues](#wordpress-integration-issues)
4. [WooCommerce Integration Issues](#woocommerce-integration-issues)
5. [Performance Issues](#performance-issues)

## Build Process Issues

### npm install Fails

**Issue**: Dependencies fail to install properly.

**Solutions**:
- Clear npm cache: `npm cache clean --force`
- Use legacy peer dependencies flag: `npm install --legacy-peer-deps`
- Check Node.js version compatibility: `node -v` (should be ≥ 18.0.0)
- Delete package-lock.json and try again: `rm package-lock.json && npm install`

### Build Process Errors

**Issue**: The build process fails with errors.

**Solutions**:
- Start with development build: `npm run dev`
- Check for syntax errors in SCSS or JS files
- Update dependencies: `npm update`
- Check webpack.mix.js for configuration issues
- Run specific tasks separately: `npm run critical`, `npm run svg-sprite`, etc.

### "Cannot find module" Errors

**Issue**: Build fails with "Cannot find module" errors.

**Solutions**:
- Reinstall dependencies: `rm -rf node_modules && npm install`
- Check if the module is listed in package.json
- Install the specific missing module: `npm install [module-name]`
- Check for typos in import statements

### Memory Issues During Build

**Issue**: Build process runs out of memory.

**Solutions**:
- Increase Node.js memory limit: `NODE_OPTIONS=--max_old_space_size=4096 npm run build`
- Build specific assets separately
- Close other memory-intensive applications
- Use a machine with more RAM

## Asset Generation Issues

### SVG Sprite Generation Fails

**Issue**: SVG sprite generation fails or produces empty sprites.

**Solutions**:
- Check that SVG files exist in `assets/src/images/icons/`
- Validate SVG files for proper formatting
- Run the SVG sprite generator separately: `npm run svg-sprite`
- Check for errors in the SVG files (invalid XML, etc.)

### Critical CSS Generation Fails

**Issue**: Critical CSS generation fails.

**Solutions**:
- Use the simplified generator: `node critical-css-simple.js`
- Ensure the output directory exists: `mkdir -p assets/css/critical`
- Check for errors in the critical CSS configuration
- If using the original critical package, ensure compatibility with your Node.js version

### Image Optimization Issues

**Issue**: Image optimization fails or images are not optimized.

**Solutions**:
- Check that source images exist in `assets/src/images/`
- Run the image optimizer separately: `npm run imagemin`
- Install missing image optimization dependencies: `npm install --save-dev imagemin-*`
- Check for file permission issues

### Font Processing Issues

**Issue**: Fonts are not being processed or copied correctly.

**Solutions**:
- Ensure font files exist in `assets/src/fonts/`
- Check file permissions
- Manually copy fonts: `cp -r assets/src/fonts/ assets/fonts/`
- Verify font paths in SCSS files

## WordPress Integration Issues

### Theme Not Loading Styles

**Issue**: Theme styles are not loading in WordPress.

**Solutions**:
- Check if the build process completed successfully
- Verify that style.css exists and has the correct theme header
- Check for PHP errors in functions.php
- Verify that WordPress enqueue functions are correct
- Clear browser cache and WordPress cache

### JavaScript Errors

**Issue**: JavaScript errors in the browser console.

**Solutions**:
- Check browser console for specific errors
- Verify that scripts are properly enqueued in functions.php
- Check for jQuery dependencies if using jQuery
- Test in different browsers
- Build in development mode for better error messages: `npm run dev`

### Asset Paths Issues

**Issue**: Assets (images, fonts, etc.) not loading due to incorrect paths.

**Solutions**:
- Check the asset loader helper function in `inc/helpers/asset-loader.php`
- Verify that the manifest file is being generated correctly
- Use the `aqualuxe_asset()` function for all asset URLs
- Check for hardcoded paths in templates

## WooCommerce Integration Issues

### WooCommerce Styles Not Loading

**Issue**: WooCommerce styles are not applied correctly.

**Solutions**:
- Verify that woocommerce.css is being generated
- Check that WooCommerce templates are in the correct location
- Ensure WooCommerce support is declared in functions.php
- Check for WooCommerce hook usage in the theme

### WooCommerce Template Overrides

**Issue**: Custom WooCommerce templates not working.

**Solutions**:
- Verify template location: should be in `woocommerce/` directory
- Check template version numbers match WooCommerce version
- Use WooCommerce template debug mode
- Check for errors in custom template files

## Performance Issues

### Slow Page Load Times

**Issue**: Pages load slowly despite optimization.

**Solutions**:
- Verify that production build was used: `npm run production`
- Check that critical CSS is being used correctly
- Optimize images further
- Use a caching plugin
- Check for render-blocking resources
- Analyze with tools like Lighthouse or WebPageTest

### Large Asset Files

**Issue**: CSS or JS files are too large.

**Solutions**:
- Check that PurgeCSS is configured correctly
- Verify that code splitting is working
- Use the analyzer to identify large dependencies: `npm run analyze`
- Consider loading non-critical scripts asynchronously
- Split CSS into smaller, more specific files

### Browser Compatibility Issues

**Issue**: Theme doesn't work correctly in some browsers.

**Solutions**:
- Check browserslist configuration in package.json
- Verify that autoprefixer is working correctly
- Test in multiple browsers
- Use feature detection instead of browser detection
- Add polyfills for older browsers if needed

---

If you encounter issues not covered in this guide, please:
1. Check the [GitHub repository issues](https://github.com/your-repo/aqualuxe/issues)
2. Contact the theme developer at support@example.com