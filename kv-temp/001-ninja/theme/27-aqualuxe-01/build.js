/**
 * AquaLuxe Theme Build Script
 * This script optimizes assets for production
 */

const fs = require('fs');
const path = require('path');
const { exec } = require('child_process');

// Configuration
const config = {
  cssInput: './src/tailwind.css',
  cssOutput: './assets/css/tailwind.css',
  jsFiles: [
    './assets/js/navigation.js',
    './assets/js/main.js',
    './assets/js/dark-mode.js'
  ],
  imageDir: './assets/images'
};

// Create build directory if it doesn't exist
const buildDir = path.join(__dirname, 'build');
if (!fs.existsSync(buildDir)) {
  fs.mkdirSync(buildDir);
}

// Function to run shell commands
function runCommand(command) {
  return new Promise((resolve, reject) => {
    exec(command, (error, stdout, stderr) => {
      if (error) {
        console.error(`Error: ${error.message}`);
        reject(error);
        return;
      }
      if (stderr) {
        console.error(`stderr: ${stderr}`);
      }
      console.log(`stdout: ${stdout}`);
      resolve(stdout);
    });
  });
}

// Build CSS with Tailwind
async function buildCSS() {
  console.log('Building CSS...');
  try {
    await runCommand(`npx tailwindcss -i ${config.cssInput} -o ${config.cssOutput} --minify`);
    console.log('CSS built successfully!');
  } catch (error) {
    console.error('CSS build failed:', error);
  }
}

// Minify JavaScript
async function minifyJS() {
  console.log('Minifying JavaScript...');
  try {
    for (const file of config.jsFiles) {
      const outputFile = file.replace('.js', '.min.js');
      await runCommand(`npx terser ${file} -o ${outputFile} --compress --mangle`);
      console.log(`Minified: ${file} -> ${outputFile}`);
    }
    console.log('JavaScript minified successfully!');
  } catch (error) {
    console.error('JavaScript minification failed:', error);
  }
}

// Optimize images
async function optimizeImages() {
  console.log('Optimizing images...');
  try {
    await runCommand(`npx imagemin ${config.imageDir}/* --out-dir=${config.imageDir}`);
    console.log('Images optimized successfully!');
  } catch (error) {
    console.error('Image optimization failed:', error);
  }
}

// Create theme zip package
async function createZip() {
  console.log('Creating theme package...');
  try {
    // Create a list of files to include (excluding development files)
    const excludePatterns = [
      'node_modules',
      '.git',
      '.github',
      'package-lock.json',
      'build.js',
      'tailwind.config.js',
      'src',
      '*.log'
    ].join(' ');
    
    await runCommand(`zip -r build/aqualuxe.zip . -x "${excludePatterns}"`);
    console.log('Theme package created successfully!');
  } catch (error) {
    console.error('Theme packaging failed:', error);
  }
}

// Main build process
async function build() {
  console.log('Starting build process...');
  
  try {
    await buildCSS();
    await minifyJS();
    await optimizeImages();
    await createZip();
    console.log('Build completed successfully!');
  } catch (error) {
    console.error('Build failed:', error);
  }
}

// Run the build process
build();