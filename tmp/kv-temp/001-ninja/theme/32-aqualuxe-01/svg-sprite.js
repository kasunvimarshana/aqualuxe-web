/**
 * AquaLuxe Theme - SVG Sprite Generator
 *
 * This script generates an SVG sprite for the AquaLuxe theme.
 */

const SVGSpriter = require('svg-sprite');
const fs = require('fs');
const path = require('path');
const glob = require('glob');
const mkdirp = require('mkdirp');

// Configuration
const config = {
  // Source directory for SVG icons
  srcDir: './assets/src/images/icons',
  // Output directory for the sprite
  destDir: './assets/images',
  // Output filename
  spriteFilename: 'sprite.svg',
  // SVG sprite configuration
  mode: {
    symbol: {
      dest: '',
      sprite: 'sprite.svg',
      example: {
        dest: 'sprite-example.html'
      }
    }
  },
  shape: {
    id: {
      separator: '-',
      generator: 'icon-%s'
    },
    transform: [
      {
        svgo: {
          plugins: [
            { name: 'removeViewBox', active: false },
            { name: 'cleanupIDs', active: true },
            { name: 'removeDimensions', active: true },
            { name: 'removeUselessStrokeAndFill', active: true },
            { name: 'removeEmptyAttrs', active: true },
            { name: 'removeEmptyContainers', active: true },
            { name: 'removeEmptyText', active: true },
            { name: 'removeHiddenElems', active: true },
            { name: 'removeTitle', active: false }, // Keep title for accessibility
            { name: 'removeDesc', active: false }, // Keep desc for accessibility
            { name: 'removeXMLProcInst', active: true },
            { name: 'removeDoctype', active: true },
            { name: 'removeComments', active: true },
            { name: 'removeMetadata', active: true },
            { name: 'removeEditorsNSData', active: true },
            { name: 'cleanupAttrs', active: true },
            { name: 'inlineStyles', active: true },
            { name: 'minifyStyles', active: true },
            { name: 'convertStyleToAttrs', active: true },
            { name: 'cleanupNumericValues', active: true },
            { name: 'convertColors', active: true },
            { name: 'removeNonInheritableGroupAttrs', active: true },
            { name: 'removeUnusedNS', active: true },
            { name: 'cleanupEnableBackground', active: true },
            { name: 'removeRasterImages', active: false }, // Keep raster images
            { name: 'mergePaths', active: true },
            { name: 'convertShapeToPath', active: false }, // Keep shapes
            { name: 'sortAttrs', active: true },
            { name: 'reusePaths', active: true }
          ]
        }
      }
    ],
    dimension: {
      attributes: false,
      maxWidth: 2000,
      maxHeight: 2000
    }
  },
  svg: {
    xmlDeclaration: false,
    doctypeDeclaration: false,
    namespaceIDs: true,
    dimensionAttributes: false
  }
};

// Create destination directory if it doesn't exist
mkdirp.sync(config.destDir);

/**
 * Generate SVG sprite
 */
function generateSVGSprite() {
  console.log('Generating SVG sprite...');
  
  // Create spriter instance
  const spriter = new SVGSpriter(config);
  
  // Get SVG files
  const files = glob.sync(`${config.srcDir}/**/*.svg`);
  
  if (files.length === 0) {
    console.log('No SVG files found.');
    return;
  }
  
  console.log(`Found ${files.length} SVG icons.`);
  
  // Add SVG files to spriter
  files.forEach(file => {
    try {
      const svgContent = fs.readFileSync(file, 'utf-8');
      const filename = path.basename(file, '.svg');
      
      spriter.add(file, filename, svgContent);
    } catch (error) {
      console.error(`Error reading SVG file ${file}:`, error);
    }
  });
  
  // Compile sprite
  spriter.compile((error, result) => {
    if (error) {
      console.error('Error generating SVG sprite:', error);
      return;
    }
    
    // Write sprite file
    for (const mode in result) {
      for (const resource in result[mode]) {
        const outputPath = path.join(config.destDir, result[mode][resource].relative);
        const outputDir = path.dirname(outputPath);
        
        // Create output directory if it doesn't exist
        mkdirp.sync(outputDir);
        
        // Write file
        fs.writeFileSync(outputPath, result[mode][resource].contents);
        
        console.log(`Generated ${outputPath}`);
      }
    }
    
    console.log(`SVG sprite generated with ${files.length} icons.`);
    
    // Create a usage example file with all icons
    createIconsUsageExample(files);
  });
}

/**
 * Create an HTML file with examples of all icons
 * 
 * @param {Array} files - Array of SVG file paths
 */
function createIconsUsageExample(files) {
  const examplePath = path.join(config.destDir, 'icons-example.html');
  const spriteUrl = path.join(config.destDir, config.spriteFilename).replace(/\\/g, '/');
  
  let html = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AquaLuxe SVG Icons</title>
  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
    }
    h1 {
      color: #0077b6;
      border-bottom: 2px solid #0077b6;
      padding-bottom: 0.5rem;
    }
    .icons-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1.5rem;
    }
    .icon-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 1rem;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
      transition: all 0.2s ease;
    }
    .icon-item:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transform: translateY(-2px);
    }
    .icon-display {
      width: 48px;
      height: 48px;
      margin-bottom: 1rem;
    }
    .icon-name {
      font-size: 0.8rem;
      color: #666;
      text-align: center;
      word-break: break-all;
    }
    .usage-section {
      margin-top: 2rem;
      padding: 1rem;
      background-color: #f9f9f9;
      border-radius: 4px;
    }
    code {
      display: block;
      padding: 1rem;
      background-color: #f0f0f0;
      border-radius: 4px;
      overflow-x: auto;
      font-family: monospace;
      margin: 1rem 0;
    }
  </style>
</head>
<body>
  <h1>AquaLuxe SVG Icons</h1>
  <p>This page displays all available SVG icons in the sprite.</p>
  
  <div class="icons-grid">
`;

  // Add each icon to the grid
  files.forEach(file => {
    const filename = path.basename(file, '.svg');
    const iconId = `icon-${filename}`;
    
    html += `
    <div class="icon-item">
      <svg class="icon-display" aria-hidden="true">
        <use xlink:href="${spriteUrl}#${iconId}"></use>
      </svg>
      <span class="icon-name">${iconId}</span>
    </div>
`;
  });

  html += `
  </div>
  
  <div class="usage-section">
    <h2>How to Use</h2>
    <p>Include the SVG sprite in your HTML:</p>
    <code>&lt;!-- Include at the beginning of your body tag --&gt;
&lt;svg xmlns="http://www.w3.org/2000/svg" style="display: none;"&gt;
  &lt;!-- You can include the sprite inline or via PHP include --&gt;
  &lt;?php include get_template_directory() . '/assets/images/sprite.svg'; ?&gt;
&lt;/svg&gt;</code>
    
    <p>Use an icon in your HTML:</p>
    <code>&lt;svg class="icon" aria-hidden="true"&gt;
  &lt;use xlink:href="#icon-name"&gt;&lt;/use&gt;
&lt;/svg&gt;</code>
    
    <p>Styling icons with CSS:</p>
    <code>.icon {
  width: 24px;
  height: 24px;
  fill: currentColor; /* Inherits color from parent */
}

/* You can also style specific icons */
.icon-search {
  fill: #0077b6;
}</code>
  </div>
</body>
</html>
`;

  // Write the example file
  fs.writeFileSync(examplePath, html);
  console.log(`Icons usage example created at ${examplePath}`);
}

// Run the generator
generateSVGSprite();