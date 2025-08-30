# AquaLuxe Theme - Image Optimization Workflow

This document outlines the image optimization workflow for the AquaLuxe WordPress theme, including best practices, tools, and implementation details.

## Table of Contents

1. [Overview](#overview)
2. [Tools and Technologies](#tools-and-technologies)
3. [Image Optimization Features](#image-optimization-features)
4. [Directory Structure](#directory-structure)
5. [Usage Guidelines](#usage-guidelines)
6. [Build Process](#build-process)
7. [Implementation in Templates](#implementation-in-templates)
8. [Performance Considerations](#performance-considerations)
9. [Troubleshooting](#troubleshooting)

## Overview

The AquaLuxe theme includes a comprehensive image optimization workflow designed to improve page load times, reduce bandwidth usage, and enhance the overall user experience. The workflow handles image compression, responsive image generation, WebP conversion, lazy loading, and Low Quality Image Placeholders (LQIP).

## Tools and Technologies

The image optimization workflow uses the following tools and technologies:

- **Gulp**: Task runner for image processing
- **gulp-imagemin**: Compresses and optimizes images
- **gulp-webp**: Converts images to WebP format
- **gulp-responsive**: Generates responsive image variants
- **lazysizes**: JavaScript library for lazy loading images
- **Laravel Mix**: Asset compilation integration

## Image Optimization Features

### 1. Image Compression

All images are automatically compressed during the build process using optimal settings for each image format:

- **JPEG**: Progressive encoding with configurable quality (default: 85%)
- **PNG**: Optimized with pngquant (default: 85% quality)
- **SVG**: Optimized with SVGO
- **GIF**: Optimized with Gifsicle

### 2. Responsive Images

The theme automatically generates multiple sizes of images for responsive design:

- Default sizes: 320px, 640px, 768px, 1024px, 1366px, 1920px
- Appropriate `srcset` and `sizes` attributes are added to images
- Only images in specific directories are processed for responsive variants

### 3. WebP Conversion

All JPG and PNG images are automatically converted to WebP format for browsers that support it:

- WebP images are 25-35% smaller than equivalent JPEG/PNG images
- Original format is served as a fallback for browsers without WebP support
- WebP detection is handled via JavaScript and CSS

### 4. Lazy Loading

Images are lazy loaded using the lazysizes library:

- Images load only when they enter the viewport
- Reduces initial page load time
- Saves bandwidth for images that are never viewed
- Falls back to native lazy loading when available

### 5. Low Quality Image Placeholders (LQIP)

Small, blurry placeholder images are shown while the full image loads:

- Improves perceived performance
- Reduces layout shifts during page load
- Placeholders are tiny (typically < 2KB)

## Directory Structure

```
aqualuxe/
├── assets/
│   ├── src/
│   │   ├── images/           # Source images
│   │   │   ├── responsive/   # Images to be processed for responsive sizes
│   │   │   ├── hero/         # Hero images (also processed for responsive sizes)
│   │   │   ├── backgrounds/  # Background images (also processed for responsive sizes)
│   │   │   └── icons/        # Icon images (not processed for responsive sizes)
│   │   └── js/
│   │       └── image-optimization.js  # JavaScript for lazy loading and WebP detection
│   └── dist/
│       └── images/           # Optimized output images
│           ├── responsive/   # Responsive image variants
│           └── ...           # Other optimized images
├── gulpfile.js               # Gulp tasks for image optimization
└── webpack.mix.js            # Laravel Mix configuration
```

## Usage Guidelines

### Adding New Images

1. Place source images in the appropriate directory under `assets/src/images/`
2. Run `npm run images` to process the images
3. Use the optimized images from `assets/dist/images/` in your templates

### Responsive Images

For images that need responsive variants:

1. Place them in one of these directories:
   - `assets/src/images/responsive/`
   - `assets/src/images/hero/`
   - `assets/src/images/backgrounds/`
2. Run `npm run images` to generate responsive variants
3. Use the responsive image helper functions in your templates

### Image Formats

- Use **JPEG** for photographs and complex images with many colors
- Use **PNG** for images with transparency or simple graphics with few colors
- Use **SVG** for icons, logos, and simple illustrations
- Use **GIF** only for simple animations

## Build Process

### Development

During development, you can use the following commands:

```bash
# Process images once
npm run images

# Watch for image changes and process automatically
npm run watch-images

# Run the complete build process including images
npm run build
```

### Production

For production builds, the image optimization settings are more aggressive:

```bash
# Set environment to production
NODE_ENV=production npm run build
```

## Implementation in Templates

### Basic Lazy Loading

```php
<img class="lazyload" 
     src="<?php echo get_template_directory_uri(); ?>/assets/dist/images/responsive/image-placeholder.jpg" 
     data-src="<?php echo get_template_directory_uri(); ?>/assets/dist/images/responsive/image.jpg" 
     alt="Description">
```

### Responsive Images with Lazy Loading

```php
<?php
$image_base = get_template_directory_uri() . '/assets/dist/images/responsive/image';
$sizes = '(min-width: 1024px) 50vw, 100vw';
$srcset = "{$image_base}-320w.jpg 320w, {$image_base}-640w.jpg 640w, {$image_base}-768w.jpg 768w, {$image_base}-1024w.jpg 1024w, {$image_base}-1366w.jpg 1366w, {$image_base}-1920w.jpg 1920w";
?>

<img class="lazyload" 
     src="<?php echo $image_base; ?>-placeholder.jpg" 
     data-srcset="<?php echo $srcset; ?>" 
     data-sizes="<?php echo $sizes; ?>" 
     alt="Description">
```

### Using the Helper Function

```php
<?php
// In your template file
echo aqualuxe_responsive_image(
    'image-name', 
    'Description', 
    array(
        'class' => 'additional-class',
        'sizes' => '(min-width: 1024px) 50vw, 100vw',
    )
);
?>
```

### WebP with Fallback

```php
<picture>
    <source type="image/webp" data-srcset="<?php echo $image_base; ?>-1024w.webp" media="(min-width: 1024px)">
    <source type="image/webp" data-srcset="<?php echo $image_base; ?>-640w.webp" media="(max-width: 1023px)">
    <source data-srcset="<?php echo $image_base; ?>-1024w.jpg" media="(min-width: 1024px)">
    <source data-srcset="<?php echo $image_base; ?>-640w.jpg" media="(max-width: 1023px)">
    <img class="lazyload" src="<?php echo $image_base; ?>-placeholder.jpg" alt="Description">
</picture>
```

## Performance Considerations

- **Image Dimensions**: Always use appropriately sized images
- **Art Direction**: Use different images for different screen sizes when appropriate
- **Image Quality**: Balance quality and file size based on the image content
- **Critical Images**: Consider loading critical above-the-fold images eagerly
- **Cumulative Layout Shift**: Always specify width and height attributes on images

## Troubleshooting

### Common Issues

1. **Images not being processed**
   - Check that the images are in the correct source directory
   - Verify that the file extensions are lowercase (.jpg, .png, not .JPG, .PNG)

2. **Responsive images not generating**
   - Ensure images are in one of the responsive directories
   - Check for error messages in the console during the build process

3. **Lazy loading not working**
   - Verify that the correct classes are applied to the images
   - Check that the JavaScript is loading properly
   - Look for JavaScript errors in the browser console

4. **WebP images not serving**
   - Verify that the WebP detection script is working
   - Check that the server is configured to serve WebP files with the correct MIME type

### Debugging

For detailed debugging information, run the build process with verbose output:

```bash
DEBUG=true npm run images
```

This will show additional information about the image processing steps.