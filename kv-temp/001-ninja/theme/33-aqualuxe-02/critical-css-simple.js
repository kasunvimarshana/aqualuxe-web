/**
 * AquaLuxe Theme - Critical CSS Generator
 *
 * This script generates critical CSS for key templates using the critical package.
 * It extracts the critical-path CSS needed for above-the-fold content.
 */

const { generate } = require('critical');
const fs = require('fs');
const path = require('path');
const mkdirp = require('mkdirp');

// Configuration
const baseUrl = 'http://localhost'; // Change this to your development URL
const outputDir = path.join(__dirname, 'assets/css/critical');
const cssSource = path.join(__dirname, 'assets/css/main.css');

// Ensure output directory exists
mkdirp.sync(outputDir);

// Templates to generate critical CSS for
const templates = [
  { name: 'home', url: '/' },
  { name: 'blog', url: '/blog/' },
  { name: 'shop', url: '/shop/' },
  { name: 'product', url: '/product/sample-product/' }
];

// Generate critical CSS for each template
async function generateCriticalCSS() {
  console.log('Generating critical CSS...');
  
  for (const template of templates) {
    try {
      console.log(`Processing ${template.name}...`);
      
      // Generate critical CSS
      const criticalCSS = await generate({
        src: `${baseUrl}${template.url}`,
        css: [cssSource],
        width: 1300,
        height: 900,
        target: {
          css: path.join(outputDir, `${template.name}.css`),
        },
        inline: false,
        ignore: {
          atrule: ['@font-face']
        },
        penthouse: {
          timeout: 120000, // Increase timeout for complex pages
          renderWaitTime: 1000
        }
      });
      
      console.log(`✅ Generated critical CSS for ${template.name}`);
    } catch (error) {
      console.error(`❌ Error generating critical CSS for ${template.name}:`, error);
      
      // Create a fallback file with a comment
      const fallbackContent = `/* Critical CSS generation failed for ${template.name} */`;
      fs.writeFileSync(path.join(outputDir, `${template.name}.css`), fallbackContent);
    }
  }
  
  console.log('Critical CSS generation complete!');
}

// Run the generator
generateCriticalCSS().catch(error => {
  console.error('Critical CSS generation failed:', error);
  process.exit(1);
});