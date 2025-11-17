/**
 * AquaLuxe Theme - Critical CSS Generator
 *
 * This script generates critical CSS for key templates to improve page load performance.
 */

const critical = require('critical');
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
  { name: 'front-page', template: 'front-page', url: 'http://localhost/' },
  { name: 'blog', template: 'blog', url: 'http://localhost/blog/' },
  { name: 'woocommerce', template: 'woocommerce', url: 'http://localhost/shop/' },
  { name: 'woocommerce-single', template: 'woocommerce-single', url: 'http://localhost/product/sample-product/' },
  { name: 'page', template: 'page', url: 'http://localhost/about/' },
  { name: 'single', template: 'single', url: 'http://localhost/sample-post/' }
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

// Base options for critical CSS generation
const baseOptions = {
  minify: true,
  extract: false,
  inline: false,
  timeout: 60000, // Increase timeout for complex pages
  penthouse: {
    renderWaitTime: 1000, // Wait for page to fully render
    forceInclude: [
      '.site-header',
      '.site-footer',
      '.main-navigation',
      '.hero',
      '.screen-reader-text',
      '.skip-link',
      '.woocommerce-products-header',
      '.woocommerce-breadcrumb',
      '.product_title',
      '.entry-title'
    ]
  },
  ignore: {
    atrule: ['@font-face'],
    rule: [/url\(/],
    decl: (node, value) => {
      // Ignore specific declarations if needed
      return false;
    },
  }
};

/**
 * Generate critical CSS for a template
 * 
 * @param {Object} template Template configuration
 * @param {Object} dimension Dimension configuration
 * @returns {Promise} Promise resolving when critical CSS is generated
 */
async function generateCriticalCSS(template, dimension = null) {
  try {
    // Determine output path and options
    let outputPath;
    let options = { ...baseOptions };
    
    if (dimension) {
      outputPath = path.join(criticalCssDir, `${template.template}.${dimension.suffix}.css`);
      options.width = dimension.width;
      options.height = dimension.height;
    } else {
      outputPath = path.join(criticalCssDir, `${template.template}.css`);
    }
    
    console.log(`Generating critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}...`);
    
    // Generate critical CSS
    await critical.generate({
      src: template.url,
      target: outputPath,
      ...options
    });
    
    console.log(`✅ Generated critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}`);
    return true;
  } catch (error) {
    console.error(`❌ Error generating critical CSS for ${template.name}${dimension ? ` (${dimension.suffix})` : ''}:`, error);
    
    // Create an empty file to prevent errors in the theme
    fs.writeFileSync(
      dimension ? 
        path.join(criticalCssDir, `${template.template}.${dimension.suffix}.css`) : 
        path.join(criticalCssDir, `${template.template}.css`),
      `/* Critical CSS generation failed for ${template.name}${dimension ? ` (${dimension.suffix})` : ''} */`
    );
    
    return false;
  }
}

/**
 * Generate critical CSS for all templates and dimensions
 */
async function generateAllCriticalCSS() {
  console.log('Starting critical CSS generation...');
  
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
      await generateCriticalCSS(template, dimension);
    }
    
    // Generate combined version
    await generateCriticalCSS(template);
  }
  
  console.log('Critical CSS generation completed!');
}

// Run the generator
generateAllCriticalCSS();