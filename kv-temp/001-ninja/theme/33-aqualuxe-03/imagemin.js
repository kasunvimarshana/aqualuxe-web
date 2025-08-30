/**
 * AquaLuxe Theme - Image Optimization Script
 *
 * This script optimizes images and generates WebP versions.
 * It processes all images in the assets/src/images directory.
 */

const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminGifsicle = require('imagemin-gifsicle');
const imageminSvgo = require('imagemin-svgo');
const imageminWebp = require('imagemin-webp');
const path = require('path');
const fs = require('fs');
const glob = require('glob');
const mkdirp = require('mkdirp');

// Configuration
const srcDir = 'assets/src/images';
const destDir = 'assets/images';

// Ensure destination directory exists
mkdirp.sync(destDir);

// Process regular images
async function optimizeImages() {
  console.log('Optimizing images...');
  
  try {
    // Find all image files (excluding icons which are handled by svg-sprite.js)
    const imageFiles = glob.sync(`${srcDir}/**/*.{jpg,jpeg,png,gif,svg}`, {
      ignore: [`${srcDir}/icons/**/*`]
    });
    
    if (imageFiles.length === 0) {
      console.log('No image files found to optimize');
      return;
    }
    
    // Process images by type
    const jpgFiles = imageFiles.filter(file => /\.(jpg|jpeg)$/i.test(file));
    const pngFiles = imageFiles.filter(file => /\.png$/i.test(file));
    const gifFiles = imageFiles.filter(file => /\.gif$/i.test(file));
    const svgFiles = imageFiles.filter(file => /\.svg$/i.test(file));
    
    // Process JPG files
    if (jpgFiles.length > 0) {
      await imagemin(jpgFiles, {
        destination: destDir,
        plugins: [
          imageminMozjpeg({
            quality: 80,
            progressive: true
          })
        ]
      });
      console.log(`✅ Optimized ${jpgFiles.length} JPG files`);
    }
    
    // Process PNG files
    if (pngFiles.length > 0) {
      await imagemin(pngFiles, {
        destination: destDir,
        plugins: [
          imageminPngquant({
            quality: [0.65, 0.8],
            speed: 1
          })
        ]
      });
      console.log(`✅ Optimized ${pngFiles.length} PNG files`);
    }
    
    // Process GIF files
    if (gifFiles.length > 0) {
      await imagemin(gifFiles, {
        destination: destDir,
        plugins: [
          imageminGifsicle({
            interlaced: true,
            optimizationLevel: 3
          })
        ]
      });
      console.log(`✅ Optimized ${gifFiles.length} GIF files`);
    }
    
    // Process SVG files
    if (svgFiles.length > 0) {
      await imagemin(svgFiles, {
        destination: destDir,
        plugins: [
          imageminSvgo({
            plugins: [
              {
                name: 'preset-default',
                params: {
                  overrides: {
                    removeViewBox: false,
                    cleanupIDs: false
                  }
                }
              },
              'removeDimensions',
              'removeUselessStrokeAndFill'
            ]
          })
        ]
      });
      console.log(`✅ Optimized ${svgFiles.length} SVG files`);
    }
    
    console.log('Image optimization complete!');
  } catch (error) {
    console.error('Error optimizing images:', error);
  }
}

// Generate WebP versions
async function generateWebP() {
  console.log('Generating WebP versions...');
  
  try {
    // Find all raster image files
    const imageFiles = glob.sync(`${srcDir}/**/*.{jpg,jpeg,png}`, {
      ignore: [`${srcDir}/icons/**/*`]
    });
    
    if (imageFiles.length === 0) {
      console.log('No image files found to convert to WebP');
      return;
    }
    
    // Process images
    await imagemin(imageFiles, {
      destination: destDir,
      plugins: [
        imageminWebp({
          quality: 75
        })
      ]
    });
    
    console.log(`✅ Generated WebP versions for ${imageFiles.length} images`);
  } catch (error) {
    console.error('Error generating WebP versions:', error);
  }
}

// Run the optimization process
async function run() {
  try {
    await optimizeImages();
    await generateWebP();
    console.log('Image processing complete!');
  } catch (error) {
    console.error('Image processing failed:', error);
    process.exit(1);
  }
}

run();