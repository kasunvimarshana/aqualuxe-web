/**
 * AquaLuxe Theme - Critical CSS Generation Script
 * 
 * This script generates critical CSS for key pages to improve page load performance.
 * It extracts the CSS needed for above-the-fold content and inlines it in the page.
 */

const critical = require('critical');
const fs = require('fs');
const path = require('path');
const glob = require('glob');

// Configuration
const config = {
  base: path.join(__dirname, '../'),
  dimensions: [
    {
      width: 375,
      height: 667
    },
    {
      width: 768,
      height: 1024
    },
    {
      width: 1366,
      height: 768
    }
  ],
  concurrency: 3,
  penthouse: {
    timeout: 60000
  },
  extract: true,
  inline: false,
  ignore: {
    atrule: ['@font-face']
  },
  target: {
    css: path.join(__dirname, '../assets/dist/css/critical/'),
    html: path.join(__dirname, '../assets/dist/critical-html/'),
  },
  pages: [
    {
      url: 'index.html',
      template: 'home',
      dest: 'home.css'
    },
    {
      url: 'shop/index.html',
      template: 'shop',
      dest: 'shop.css'
    },
    {
      url: 'product/sample-product/index.html',
      template: 'product',
      dest: 'product.css'
    },
    {
      url: 'blog/index.html',
      template: 'blog',
      dest: 'blog.css'
    },
    {
      url: 'blog/sample-post/index.html',
      template: 'single',
      dest: 'single.css'
    },
    {
      url: 'contact/index.html',
      template: 'contact',
      dest: 'contact.css'
    }
  ]
};

// Ensure output directories exist
if (!fs.existsSync(config.target.css)) {
  fs.mkdirSync(config.target.css, { recursive: true });
}

if (!fs.existsSync(config.target.html)) {
  fs.mkdirSync(config.target.html, { recursive: true });
}

/**
 * Generate critical CSS for a page
 * @param {Object} page - Page configuration
 * @return {Promise} Promise resolving when critical CSS is generated
 */
async function generateCriticalCss(page) {
  console.log(`Generating critical CSS for ${page.template}...`);
  
  try {
    const result = await critical.generate({
      src: page.url,
      target: path.join(config.target.css, page.dest),
      dimensions: config.dimensions,
      extract: config.extract,
      inline: config.inline,
      ignore: config.ignore,
      base: config.base,
      penthouse: config.penthouse
    });
    
    console.log(`✅ Critical CSS generated for ${page.template}`);
    return result;
  } catch (error) {
    console.error(`❌ Error generating critical CSS for ${page.template}:`, error);
    throw error;
  }
}

/**
 * Process all pages in parallel with limited concurrency
 */
async function processPages() {
  const chunks = [];
  const chunkSize = config.concurrency;
  
  // Split pages into chunks for parallel processing with limited concurrency
  for (let i = 0; i < config.pages.length; i += chunkSize) {
    chunks.push(config.pages.slice(i, i + chunkSize));
  }
  
  // Process chunks sequentially, but pages within chunks in parallel
  for (const chunk of chunks) {
    await Promise.all(chunk.map(page => generateCriticalCss(page)));
  }
}

// Main execution
console.log('Starting critical CSS generation...');
processPages()
  .then(() => {
    console.log('✅ All critical CSS files generated successfully!');
  })
  .catch(error => {
    console.error('❌ Error during critical CSS generation:', error);
    process.exit(1);
  });