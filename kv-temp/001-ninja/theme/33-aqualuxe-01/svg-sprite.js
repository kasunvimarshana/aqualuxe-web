/**
 * AquaLuxe Theme - SVG Sprite Generator
 *
 * This script generates an SVG sprite from individual SVG files.
 * It optimizes SVGs and combines them into a single sprite file.
 */

const SVGSpriter = require('svg-sprite');
const fs = require('fs');
const path = require('path');
const glob = require('glob');
const mkdirp = require('mkdirp');

// Configuration
const srcDir = path.join(__dirname, 'assets/src/images/icons');
const destDir = path.join(__dirname, 'assets/images');
const spriteName = 'sprite.svg';

// Ensure destination directory exists
mkdirp.sync(destDir);

// Configure spriter
const spriter = new SVGSpriter({
  dest: destDir,
  mode: {
    symbol: {
      sprite: spriteName,
      example: false
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
            {
              name: 'preset-default',
              params: {
                overrides: {
                  removeViewBox: false,
                  cleanupIDs: false
                }
              }
            },
            'removeDimensions',
            'removeUselessStrokeAndFill'
          ]
        }
      }
    ]
  },
  svg: {
    xmlDeclaration: false,
    doctypeDeclaration: false,
    namespaceIDs: true,
    namespaceClassnames: true,
    dimensionAttributes: false
  }
});

// Find all SVG files
const svgFiles = glob.sync(`${srcDir}/**/*.svg`);

if (svgFiles.length === 0) {
  console.log('No SVG files found in', srcDir);
  console.log('Creating an empty directory structure...');
  
  // Create the directory structure if it doesn't exist
  mkdirp.sync(srcDir);
  
  // Create a placeholder SVG file
  const placeholderSvg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
</svg>`;
  
  fs.writeFileSync(path.join(srcDir, 'placeholder.svg'), placeholderSvg);
  console.log('Created placeholder SVG file');
  
  // Update the SVG files array
  svgFiles.push(path.join(srcDir, 'placeholder.svg'));
}

// Add each SVG file to the spriter
svgFiles.forEach(file => {
  const svgContent = fs.readFileSync(file, 'utf8');
  spriter.add(file, path.basename(file), svgContent);
});

// Compile the sprite
spriter.compile((error, result) => {
  if (error) {
    console.error('Error generating SVG sprite:', error);
    process.exit(1);
  }
  
  // Write sprite file
  for (const mode in result) {
    for (const resource in result[mode]) {
      const filepath = result[mode][resource].path;
      const contents = result[mode][resource].contents;
      
      fs.writeFileSync(filepath, contents);
      console.log(`✅ Generated SVG sprite: ${filepath}`);
    }
  }
  
  console.log('SVG sprite generation complete!');
});