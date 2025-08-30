/**
 * AquaLuxe Theme - Image Optimization Script
 *
 * This script optimizes images for the AquaLuxe theme.
 */

const imagemin = require('imagemin');
const imageminGifsicle = require('imagemin-gifsicle');
const imageminJpegtran = require('imagemin-jpegtran');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminOptipng = require('imagemin-optipng');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminWebp = require('imagemin-webp');
const glob = require('glob');
const path = require('path');
const fs = require('fs');
const mkdirp = require('mkdirp');

// Configuration
const config = {
  // Source directories to process
  srcDir: './assets/src/images',
  destDir: './assets/images',
  // Also process existing images in the destination directory
  processExistingImages: true,
  // Image quality settings
  webpQuality: 75,
  jpegQuality: 80,
  pngQuality: [0.65, 0.8],
  gifOptimizationLevel: 3,
  // SVG optimization settings
  svgoPlugins: [
    { name: 'removeViewBox', active: false },
    { name: 'cleanupIDs', active: true },
    { name: 'removeDimensions', active: true },
    { name: 'removeUselessStrokeAndFill', active: true }
  ],
  // Exclusions
  exclude: [
    '**/sprite.svg',
    '**/uploads/**'
  ]
};

// Create destination directory if it doesn't exist
mkdirp.sync(config.destDir);

/**
 * Get files to process, excluding specified patterns
 * 
 * @param {string} pattern - Glob pattern to match files
 * @returns {Array} - Array of file paths
 */
function getFilesToProcess(pattern) {
  const files = glob.sync(pattern);
  return files.filter(file => {
    return !config.exclude.some(exclude => {
      const regex = new RegExp(exclude.replace(/\*/g, '.*'));
      return regex.test(file);
    });
  });
}

// Optimize JPEG images
async function optimizeJpeg() {
  console.log('Optimizing JPEG images...');
  
  // Process source directory
  const srcFiles = getFilesToProcess(`${config.srcDir}/**/*.{jpg,jpeg}`);
  
  // Process existing images in destination directory if enabled
  const destFiles = config.processExistingImages 
    ? getFilesToProcess(`${config.destDir}/**/*.{jpg,jpeg}`)
    : [];
  
  // Combine files, removing duplicates
  const files = [...new Set([...srcFiles, ...destFiles])];
  
  if (files.length === 0) {
    console.log('No JPEG images found.');
    return;
  }
  
  try {
    // Group files by directory to maintain structure
    const filesByDir = files.reduce((acc, file) => {
      const dir = path.dirname(file);
      if (!acc[dir]) acc[dir] = [];
      acc[dir].push(file);
      return acc;
    }, {});
    
    // Process each directory
    for (const [dir, dirFiles] of Object.entries(filesByDir)) {
      // Determine output directory
      let outputDir;
      if (dir.startsWith(config.srcDir)) {
        // Move from src to output
        outputDir = dir.replace(config.srcDir, config.destDir);
      } else {
        // Keep in same directory
        outputDir = dir;
      }
      
      // Ensure output directory exists
      if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
      }
      
      // Optimize images
      const optimizedFiles = await imagemin(dirFiles, {
        destination: outputDir,
        plugins: [
          imageminJpegtran({ progressive: true }),
          imageminMozjpeg({ quality: config.jpegQuality, progressive: true })
        ]
      });
      
      console.log(`Optimized ${optimizedFiles.length} JPEG images in ${outputDir}`);
    }
  } catch (error) {
    console.error('Error optimizing JPEG images:', error);
  }
}

// Optimize PNG images
async function optimizePng() {
  console.log('Optimizing PNG images...');
  
  // Process source directory
  const srcFiles = getFilesToProcess(`${config.srcDir}/**/*.png`);
  
  // Process existing images in destination directory if enabled
  const destFiles = config.processExistingImages 
    ? getFilesToProcess(`${config.destDir}/**/*.png`)
    : [];
  
  // Combine files, removing duplicates
  const files = [...new Set([...srcFiles, ...destFiles])];
  
  if (files.length === 0) {
    console.log('No PNG images found.');
    return;
  }
  
  try {
    // Group files by directory to maintain structure
    const filesByDir = files.reduce((acc, file) => {
      const dir = path.dirname(file);
      if (!acc[dir]) acc[dir] = [];
      acc[dir].push(file);
      return acc;
    }, {});
    
    // Process each directory
    for (const [dir, dirFiles] of Object.entries(filesByDir)) {
      // Determine output directory
      let outputDir;
      if (dir.startsWith(config.srcDir)) {
        // Move from src to output
        outputDir = dir.replace(config.srcDir, config.destDir);
      } else {
        // Keep in same directory
        outputDir = dir;
      }
      
      // Ensure output directory exists
      if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
      }
      
      // Optimize images
      const optimizedFiles = await imagemin(dirFiles, {
        destination: outputDir,
        plugins: [
          imageminOptipng({ optimizationLevel: 3 }),
          imageminPngquant({ quality: config.pngQuality })
        ]
      });
      
      console.log(`Optimized ${optimizedFiles.length} PNG images in ${outputDir}`);
    }
  } catch (error) {
    console.error('Error optimizing PNG images:', error);
  }
}

// Optimize GIF images
async function optimizeGif() {
  console.log('Optimizing GIF images...');
  
  // Process source directory
  const srcFiles = getFilesToProcess(`${config.srcDir}/**/*.gif`);
  
  // Process existing images in destination directory if enabled
  const destFiles = config.processExistingImages 
    ? getFilesToProcess(`${config.destDir}/**/*.gif`)
    : [];
  
  // Combine files, removing duplicates
  const files = [...new Set([...srcFiles, ...destFiles])];
  
  if (files.length === 0) {
    console.log('No GIF images found.');
    return;
  }
  
  try {
    // Group files by directory to maintain structure
    const filesByDir = files.reduce((acc, file) => {
      const dir = path.dirname(file);
      if (!acc[dir]) acc[dir] = [];
      acc[dir].push(file);
      return acc;
    }, {});
    
    // Process each directory
    for (const [dir, dirFiles] of Object.entries(filesByDir)) {
      // Determine output directory
      let outputDir;
      if (dir.startsWith(config.srcDir)) {
        // Move from src to output
        outputDir = dir.replace(config.srcDir, config.destDir);
      } else {
        // Keep in same directory
        outputDir = dir;
      }
      
      // Ensure output directory exists
      if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
      }
      
      // Optimize images
      const optimizedFiles = await imagemin(dirFiles, {
        destination: outputDir,
        plugins: [
          imageminGifsicle({ optimizationLevel: config.gifOptimizationLevel, interlaced: true })
        ]
      });
      
      console.log(`Optimized ${optimizedFiles.length} GIF images in ${outputDir}`);
    }
  } catch (error) {
    console.error('Error optimizing GIF images:', error);
  }
}

// Optimize SVG images
async function optimizeSvg() {
  console.log('Optimizing SVG images...');
  
  // Process source directory
  const srcFiles = getFilesToProcess(`${config.srcDir}/**/*.svg`);
  
  // Process existing images in destination directory if enabled
  const destFiles = config.processExistingImages 
    ? getFilesToProcess(`${config.destDir}/**/*.svg`)
    : [];
  
  // Combine files, removing duplicates
  const files = [...new Set([...srcFiles, ...destFiles])];
  
  if (files.length === 0) {
    console.log('No SVG images found.');
    return;
  }
  
  try {
    // Group files by directory to maintain structure
    const filesByDir = files.reduce((acc, file) => {
      const dir = path.dirname(file);
      if (!acc[dir]) acc[dir] = [];
      acc[dir].push(file);
      return acc;
    }, {});
    
    // Process each directory
    for (const [dir, dirFiles] of Object.entries(filesByDir)) {
      // Determine output directory
      let outputDir;
      if (dir.startsWith(config.srcDir)) {
        // Move from src to output
        outputDir = dir.replace(config.srcDir, config.destDir);
      } else {
        // Keep in same directory
        outputDir = dir;
      }
      
      // Ensure output directory exists
      if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
      }
      
      // Optimize images
      const optimizedFiles = await imagemin(dirFiles, {
        destination: outputDir,
        plugins: [
          imageminSvgo({ plugins: config.svgoPlugins })
        ]
      });
      
      console.log(`Optimized ${optimizedFiles.length} SVG images in ${outputDir}`);
    }
  } catch (error) {
    console.error('Error optimizing SVG images:', error);
  }
}

// Convert images to WebP
async function convertToWebP() {
  console.log('Converting images to WebP...');
  
  // Process source directory
  const srcFiles = getFilesToProcess(`${config.srcDir}/**/*.{jpg,jpeg,png}`);
  
  // Process existing images in destination directory if enabled
  const destFiles = config.processExistingImages 
    ? getFilesToProcess(`${config.destDir}/**/*.{jpg,jpeg,png}`)
    : [];
  
  // Combine files, removing duplicates
  const files = [...new Set([...srcFiles, ...destFiles])];
  
  if (files.length === 0) {
    console.log('No images to convert to WebP.');
    return;
  }
  
  try {
    // Group files by directory to maintain structure
    const filesByDir = files.reduce((acc, file) => {
      const dir = path.dirname(file);
      if (!acc[dir]) acc[dir] = [];
      acc[dir].push(file);
      return acc;
    }, {});
    
    // Process each directory
    for (const [dir, dirFiles] of Object.entries(filesByDir)) {
      // Determine output directory
      let outputDir;
      if (dir.startsWith(config.srcDir)) {
        // Move from src to output
        outputDir = dir.replace(config.srcDir, config.destDir);
      } else {
        // Keep in same directory
        outputDir = dir;
      }
      
      // Ensure output directory exists
      if (!fs.existsSync(outputDir)) {
        fs.mkdirSync(outputDir, { recursive: true });
      }
      
      // Convert images to WebP
      const webpFiles = await imagemin(dirFiles, {
        destination: outputDir,
        plugins: [
          imageminWebp({ quality: config.webpQuality })
        ]
      });
      
      console.log(`Converted ${webpFiles.length} images to WebP in ${outputDir}`);
    }
  } catch (error) {
    console.error('Error converting images to WebP:', error);
  }
}

// Run all optimization tasks
async function optimizeImages() {
  console.log('Starting image optimization...');
  
  await optimizeJpeg();
  await optimizePng();
  await optimizeGif();
  await optimizeSvg();
  await convertToWebP();
  
  console.log('Image optimization complete!');
}

// Run the optimizer
optimizeImages();