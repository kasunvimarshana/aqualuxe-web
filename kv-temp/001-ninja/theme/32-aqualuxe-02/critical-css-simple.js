/**
 * AquaLuxe Theme - Simple Critical CSS Generator
 *
 * This script creates placeholder critical CSS files since the original critical package
 * now uses ESM modules which are incompatible with the current build setup.
 */

const fs = require('fs');
const path = require('path');
const mkdirp = require('mkdirp');

// Ensure the critical CSS directory exists
const criticalCssDir = path.join(__dirname, 'assets/css/critical');
if (!fs.existsSync(criticalCssDir)) {
  mkdirp.sync(criticalCssDir);
}

// Define templates for critical CSS generation
const templates = [
  { name: 'front-page', template: 'front-page' },
  { name: 'blog', template: 'blog' },
  { name: 'woocommerce', template: 'woocommerce' },
  { name: 'woocommerce-single', template: 'woocommerce-single' },
  { name: 'page', template: 'page' },
  { name: 'single', template: 'single' },
  { name: 'home', template: 'home' },
  { name: 'product', template: 'product' },
  { name: 'shop', template: 'shop' }
];

// Define dimensions for responsive critical CSS
const dimensions = [
  {
    width: 375,
    height: 667,
    suffix: 'mobile'
  },
  {
    width: 1200,
    height: 1200,
    suffix: 'desktop'
  }
];

/**
 * Generate placeholder critical CSS for a template
 * 
 * @param {Object} template Template configuration
 * @param {Object} dimension Dimension configuration
 */
function generatePlaceholderCriticalCSS(template, dimension = null) {
  try {
    // Determine output path
    let outputPath;
    
    if (dimension) {
      outputPath = path.join(criticalCssDir, `${template.template}.${dimension.suffix}.css`);
    } else {
      outputPath = path.join(criticalCssDir, `${template.template}.css`);
    }
    
    console.log(`Generating placeholder critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}...`);
    
    // Basic critical CSS content
    const criticalCssContent = `/* Placeholder critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''} */
.site-header, .site-footer, .main-navigation, .hero, .entry-title {
  display: block;
}
.screen-reader-text {
  border: 0;
  clip: rect(1px, 1px, 1px, 1px);
  clip-path: inset(50%);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute !important;
  width: 1px;
  word-wrap: normal !important;
}
.skip-link {
  background-color: #f1f1f1;
  box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.2);
  color: #0077b6;
  display: block;
  font-weight: 700;
  left: -9999em;
  outline: none;
  padding: 15px 23px 14px;
  text-decoration: none;
  text-transform: none;
  top: -9999em;
}
.skip-link:focus {
  clip: auto;
  height: auto;
  left: 6px;
  top: 7px;
  width: auto;
  z-index: 100000;
}`;
    
    // Write the file
    fs.writeFileSync(outputPath, criticalCssContent);
    
    console.log(`✅ Generated placeholder critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}`);
    return true;
  } catch (error) {
    console.error(`❌ Error generating placeholder critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}:`, error);
    return false;
  }
}

/**
 * Generate placeholder critical CSS for all templates and dimensions
 */
function generateAllPlaceholderCriticalCSS() {
  console.log('Starting placeholder critical CSS generation...');
  
  // Create a fallback CSS file
  fs.writeFileSync(
    path.join(criticalCssDir, 'fallback.css'),
    `/* Fallback critical CSS */
.site-header, .site-footer, .main-navigation, .hero, .entry-title {
  display: block;
}
.screen-reader-text {
  border: 0;
  clip: rect(1px, 1px, 1px, 1px);
  clip-path: inset(50%);
  height: 1px;
  margin: -1px;
  overflow: hidden;
  padding: 0;
  position: absolute !important;
  width: 1px;
  word-wrap: normal !important;
}
.skip-link {
  background-color: #f1f1f1;
  box-shadow: 0 0 1px 1px rgba(0, 0, 0, 0.2);
  color: #0077b6;
  display: block;
  font-weight: 700;
  left: -9999em;
  outline: none;
  padding: 15px 23px 14px;
  text-decoration: none;
  text-transform: none;
  top: -9999em;
}
.skip-link:focus {
  clip: auto;
  height: auto;
  left: 6px;
  top: 7px;
  width: auto;
  z-index: 100000;
}`
  );
  
  // Generate critical CSS for each template and dimension
  for (const template of templates) {
    // Generate responsive versions
    for (const dimension of dimensions) {
      generatePlaceholderCriticalCSS(template, dimension);
    }
    
    // Generate combined version
    generatePlaceholderCriticalCSS(template);
  }
  
  console.log('Placeholder critical CSS generation completed!');
}

// Run the generator
generateAllPlaceholderCriticalCSS();