/**
 * AquaLuxe Theme - Image Optimization Script
 * 
 * This script optimizes images for web use, reducing file sizes while maintaining quality.
 * It processes JPG, PNG, GIF, and SVG files, creating optimized versions and WebP alternatives.
 */

const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminGifsicle = require('imagemin-gifsicle');
const imageminSvgo = require('imagemin-svgo');
const imageminWebp = require('imagemin-webp');
const glob = require('glob');
const path = require('path');
const fs = require('fs');
const chalk = require('chalk');

// Configuration
const config = {
  // Source directories to scan for images
  src: [
    './assets/src/images/**/*.{jpg,jpeg,png,gif,svg}',
    './assets/src/img/**/*.{jpg,jpeg,png,gif,svg}'
  ],
  // Destination directory for optimized images
  dest: './assets/dist/images',
  // WebP output directory
  webpDest: './assets/dist/images',
  // JPEG optimization settings
  jpeg: {
    quality: 80,
    progressive: true
  },
  // PNG optimization settings
  png: {
    quality: [0.65, 0.8],
    speed: 4
  },
  // GIF optimization settings
  gif: {
    optimizationLevel: 3,
    interlaced: true
  },
  // SVG optimization settings
  svg: {
    plugins: [
      { name: 'preset-default' },
      { name: 'removeDimensions', active: true },
      { name: 'removeViewBox', active: false },
      { name: 'cleanupIDs', active: true }
    ]
  },
  // WebP conversion settings
  webp: {
    quality: 80,
    method: 6
  }
};

// Ensure destination directories exist
if (!fs.existsSync(config.dest)) {
  fs.mkdirSync(config.dest, { recursive: true });
}

if (!fs.existsSync(config.webpDest)) {
  fs.mkdirSync(config.webpDest, { recursive: true });
}

/**
 * Get all image files from source directories
 * @return {Promise<string[]>} Array of file paths
 */
function getImageFiles() {
  return new Promise((resolve, reject) => {
    const files = [];
    
    // Process each source pattern
    const promises = config.src.map(pattern => {
      return new Promise((resolveGlob, rejectGlob) => {
        glob(pattern, (err, matches) => {
          if (err) {
            rejectGlob(err);
            return;
          }
          files.push(...matches);
          resolveGlob();
        });
      });
    });
    
    Promise.all(promises)
      .then(() => {
        // Remove duplicates
        const uniqueFiles = [...new Set(files)];
        resolve(uniqueFiles);
      })
      .catch(reject);
  });
}

/**
 * Optimize images
 * @param {string[]} files - Array of file paths
 * @return {Promise} Promise resolving when optimization is complete
 */
async function optimizeImages(files) {
  if (!files.length) {
    console.log(chalk.yellow('No image files found to optimize.'));
    return;
  }
  
  console.log(chalk.blue(`Optimizing ${files.length} images...`));
  
  try {
    // Optimize JPG, PNG, GIF, SVG
    const optimizedFiles = await imagemin(files, {
      destination: config.dest,
      plugins: [
        imageminMozjpeg(config.jpeg),
        imageminPngquant(config.png),
        imageminGifsicle(config.gif),
        imageminSvgo({
          plugins: config.svg.plugins
        })
      ]
    });
    
    console.log(chalk.green(`✅ Optimized ${optimizedFiles.length} images`));
    
    // Convert to WebP (only JPG and PNG)
    const webpFiles = files.filter(file => {
      const ext = path.extname(file).toLowerCase();
      return ext === '.jpg' || ext === '.jpeg' || ext === '.png';
    });
    
    if (webpFiles.length) {
      const webpResults = await imagemin(webpFiles, {
        destination: config.webpDest,
        plugins: [
          imageminWebp(config.webp)
        ]
      });
      
      console.log(chalk.green(`✅ Created ${webpResults.length} WebP images`));
    }
  } catch (error) {
    console.error(chalk.red('Error optimizing images:'), error);
    throw error;
  }
}

/**
 * Calculate size reduction
 * @param {string[]} files - Array of original file paths
 */
function calculateSizeReduction(files) {
  let originalSize = 0;
  let optimizedSize = 0;
  
  files.forEach(file => {
    const originalPath = file;
    const fileName = path.basename(file);
    const optimizedPath = path.join(config.dest, fileName);
    
    if (fs.existsSync(originalPath) && fs.existsSync(optimizedPath)) {
      const originalStats = fs.statSync(originalPath);
      const optimizedStats = fs.statSync(optimizedPath);
      
      originalSize += originalStats.size;
      optimizedSize += optimizedStats.size;
    }
  });
  
  if (originalSize > 0) {
    const reduction = originalSize - optimizedSize;
    const percentage = ((reduction / originalSize) * 100).toFixed(2);
    
    console.log(chalk.blue('Original size:'), chalk.yellow(formatBytes(originalSize)));
    console.log(chalk.blue('Optimized size:'), chalk.green(formatBytes(optimizedSize)));
    console.log(chalk.blue('Size reduction:'), chalk.green(formatBytes(reduction)), chalk.green(`(${percentage}%)`));
  }
}

/**
 * Format bytes to human-readable format
 * @param {number} bytes - Number of bytes
 * @param {number} decimals - Number of decimal places
 * @return {string} Formatted string
 */
function formatBytes(bytes, decimals = 2) {
  if (bytes === 0) return '0 Bytes';
  
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Main execution
console.log(chalk.blue('Starting image optimization...'));

getImageFiles()
  .then(files => optimizeImages(files))
  .then(() => getImageFiles().then(files => calculateSizeReduction(files)))
  .then(() => {
    console.log(chalk.green('✅ Image optimization completed successfully!'));
  })
  .catch(error => {
    console.error(chalk.red('❌ Error during image optimization:'), error);
    process.exit(1);
  });