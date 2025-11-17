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
  { name: 'home', url: 'http://localhost/', template: 'front-page' },
  { name: 'blog', url: 'http://localhost/blog/', template: 'blog' },
  { name: 'shop', url: 'http://localhost/shop/', template: 'woocommerce' },
  { name: 'product', url: 'http://localhost/product/sample-product/', template: 'woocommerce-single' },
  { name: 'about', url: 'http://localhost/about/', template: 'page' },
  { name: 'contact', url: 'http://localhost/contact/', template: 'page' },
  { name: 'services', url: 'http://localhost/services/', template: 'page' },
  { name: 'faq', url: 'http://localhost/faq/', template: 'page' }
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

// Generate critical CSS for each template and dimension
async function generateCriticalCSS() {
  console.log('Generating critical CSS...');
  
  for (const template of templates) {
    for (const dimension of dimensions) {
      const outputPath = path.join(
        criticalCssDir,
        `${template.template}.${dimension.suffix}.css`
      );
      
      console.log(`Processing ${template.name} (${dimension.suffix})...`);
      
      try {
        const result = await critical.generate({
          src: template.url,
          target: outputPath,
          width: dimension.width,
          height: dimension.height,
          ...baseOptions
        });
        
        console.log(`✅ Generated critical CSS for ${template.name} (${dimension.suffix})`);
      } catch (error) {
        console.error(`❌ Error generating critical CSS for ${template.name} (${dimension.suffix}):`, error);
        
        // Create an empty file to prevent errors in the theme
        fs.writeFileSync(
          outputPath,
          `/* Critical CSS generation failed for ${template.name} (${dimension.suffix}) */`
        );
      }
    }
    
    // Also generate a combined version without dimension suffix
    try {
      const outputPath = path.join(criticalCssDir, `${template.template}.css`);
      
      const result = await critical.generate({
        src: template.url,
        target: outputPath,
        ...baseOptions
      });
      
      console.log(`✅ Generated combined critical CSS for ${template.name}`);
    } catch (error) {
      console.error(`❌ Error generating combined critical CSS for ${template.name}:`, error);
      
      // Create an empty file to prevent errors in the theme
      fs.writeFileSync(
        path.join(criticalCssDir, `${template.template}.css`),
        `/* Combined critical CSS generation failed for ${template.name} */`
      );
    }
  }
  
  console.log('Critical CSS generation completed!');
}

// Run the critical CSS generation
generateCriticalCSS();