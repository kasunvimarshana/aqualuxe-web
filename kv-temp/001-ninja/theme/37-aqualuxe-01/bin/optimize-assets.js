#!/usr/bin/env node

/**
 * AquaLuxe Asset Optimization Script
 * This script optimizes assets for production
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');
const imagemin = require('imagemin');
const imageminMozjpeg = require('imagemin-mozjpeg');
const imageminPngquant = require('imagemin-pngquant');
const imageminSvgo = require('imagemin-svgo');
const imageminGifsicle = require('imagemin-gifsicle');
const imageminWebp = require('imagemin-webp');
const chalk = require('chalk');

// Configuration
const assetsDir = path.join(__dirname, '../assets');
const srcDir = path.join(__dirname, '../src');
const imgDir = path.join(assetsDir, 'img');
const cssDir = path.join(assetsDir, 'css');
const jsDir = path.join(assetsDir, 'js');

// Create a log function
const log = {
  info: (message) => console.log(chalk.blue(`ℹ️ ${message}`)),
  success: (message) => console.log(chalk.green(`✅ ${message}`)),
  warning: (message) => console.log(chalk.yellow(`⚠️ ${message}`)),
  error: (message) => console.log(chalk.red(`❌ ${message}`))
};

// Ensure directories exist
function ensureDirectoryExists(dir) {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
    log.info(`Created directory: ${dir}`);
  }
}

// Run production build
async function runProductionBuild() {
  log.info('Running production build with Laravel Mix...');
  try {
    execSync('npm run production', { stdio: 'inherit' });
    log.success('Production build completed');
    return true;
  } catch (error) {
    log.error(`Production build failed: ${error.message}`);
    return false;
  }
}

// Optimize images
async function optimizeImages() {
  log.info('Optimizing images...');
  
  // Ensure image directory exists
  ensureDirectoryExists(imgDir);
  
  try {
    // Optimize JPG, PNG, GIF, and SVG images
    const files = await imagemin([`${imgDir}/**/*.{jpg,jpeg,png,gif,svg}`], {
      destination: imgDir,
      plugins: [
        imageminMozjpeg({ quality: 80 }),
        imageminPngquant({ quality: [0.65, 0.8] }),
        imageminGifsicle({ optimizationLevel: 3 }),
        imageminSvgo({
          plugins: [
            { name: 'removeViewBox', active: false },
            { name: 'cleanupIDs', active: false }
          ]
        })
      ]
    });
    
    // Generate WebP versions of JPG and PNG images
    await imagemin([`${imgDir}/**/*.{jpg,jpeg,png}`], {
      destination: imgDir,
      plugins: [
        imageminWebp({ quality: 80 })
      ]
    });
    
    log.success(`Optimized ${files.length} images`);
    return true;
  } catch (error) {
    log.error(`Image optimization failed: ${error.message}`);
    return false;
  }
}

// Generate critical CSS
async function generateCriticalCSS() {
  log.info('Generating critical CSS...');
  
  try {
    // Create critical CSS directory if it doesn't exist
    const criticalDir = path.join(cssDir, 'critical');
    ensureDirectoryExists(criticalDir);
    
    // Generate critical CSS for different templates
    const templates = ['home', 'single', 'archive', 'page', 'product'];
    const mainCssPath = path.join(cssDir, 'main.css');
    
    if (!fs.existsSync(mainCssPath)) {
      log.warning('Main CSS file not found. Skipping critical CSS generation.');
      return false;
    }
    
    const mainCss = fs.readFileSync(mainCssPath, 'utf8');
    
    // Extract critical CSS for each template
    // This is a simplified version - in a real scenario, you would use a tool like critical or penthouse
    templates.forEach(template => {
      // Create a simplified critical CSS for demonstration
      // In a real scenario, this would analyze the HTML and extract only the CSS needed for above-the-fold content
      let criticalCss = '/* Critical CSS for ' + template + ' */\n';
      
      // Add normalize and base styles
      criticalCss += extractSection(mainCss, '/* Tailwind CSS Base Styles */', '/* Tailwind CSS Components */');
      
      // Add critical components based on template
      switch (template) {
        case 'home':
          criticalCss += extractComponent(mainCss, '.site-header');
          criticalCss += extractComponent(mainCss, '.main-navigation');
          criticalCss += extractComponent(mainCss, '.hero-section');
          break;
        case 'single':
          criticalCss += extractComponent(mainCss, '.site-header');
          criticalCss += extractComponent(mainCss, '.main-navigation');
          criticalCss += extractComponent(mainCss, '.entry-header');
          break;
        case 'archive':
          criticalCss += extractComponent(mainCss, '.site-header');
          criticalCss += extractComponent(mainCss, '.main-navigation');
          criticalCss += extractComponent(mainCss, '.archive-header');
          break;
        case 'page':
          criticalCss += extractComponent(mainCss, '.site-header');
          criticalCss += extractComponent(mainCss, '.main-navigation');
          criticalCss += extractComponent(mainCss, '.page-header');
          break;
        case 'product':
          criticalCss += extractComponent(mainCss, '.site-header');
          criticalCss += extractComponent(mainCss, '.main-navigation');
          criticalCss += extractComponent(mainCss, '.product');
          break;
      }
      
      // Write critical CSS file
      fs.writeFileSync(path.join(criticalDir, `${template}.css`), criticalCss);
    });
    
    log.success(`Generated critical CSS for ${templates.length} templates`);
    return true;
  } catch (error) {
    log.error(`Critical CSS generation failed: ${error.message}`);
    return false;
  }
}

// Helper function to extract a section of CSS
function extractSection(css, startMarker, endMarker) {
  const startIndex = css.indexOf(startMarker);
  const endIndex = css.indexOf(endMarker);
  
  if (startIndex === -1 || endIndex === -1) {
    return '';
  }
  
  return css.substring(startIndex, endIndex);
}

// Helper function to extract a component from CSS
function extractComponent(css, selector) {
  const regex = new RegExp(`${selector}\\s*{[^}]*}`, 'g');
  const matches = css.match(regex);
  
  if (!matches) {
    return '';
  }
  
  return matches.join('\n');
}

// Create asset manifest
async function createAssetManifest() {
  log.info('Creating asset manifest...');
  
  try {
    const manifest = {
      version: require('../package.json').version,
      timestamp: new Date().toISOString(),
      assets: {}
    };
    
    // Add CSS files to manifest
    fs.readdirSync(cssDir).forEach(file => {
      if (file.endsWith('.css')) {
        const filePath = path.join(cssDir, file);
        const stats = fs.statSync(filePath);
        manifest.assets[`css/${file}`] = {
          size: stats.size,
          lastModified: stats.mtime
        };
      }
    });
    
    // Add JS files to manifest
    fs.readdirSync(jsDir).forEach(file => {
      if (file.endsWith('.js')) {
        const filePath = path.join(jsDir, file);
        const stats = fs.statSync(filePath);
        manifest.assets[`js/${file}`] = {
          size: stats.size,
          lastModified: stats.mtime
        };
      }
    });
    
    // Write manifest file
    fs.writeFileSync(path.join(assetsDir, 'manifest.json'), JSON.stringify(manifest, null, 2));
    log.success('Asset manifest created');
    return true;
  } catch (error) {
    log.error(`Asset manifest creation failed: ${error.message}`);
    return false;
  }
}

// Main function
async function main() {
  log.info('Starting asset optimization...');
  
  // Run production build
  const buildSuccess = await runProductionBuild();
  if (!buildSuccess) {
    log.error('Asset optimization aborted due to build failure');
    process.exit(1);
  }
  
  // Optimize images
  await optimizeImages();
  
  // Generate critical CSS
  await generateCriticalCSS();
  
  // Create asset manifest
  await createAssetManifest();
  
  log.success('Asset optimization completed successfully');
}

// Run the main function
main().catch(error => {
  log.error(`Asset optimization failed: ${error.message}`);
  process.exit(1);
});