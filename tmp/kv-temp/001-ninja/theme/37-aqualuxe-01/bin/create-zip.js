#!/usr/bin/env node

/**
 * AquaLuxe Theme Packaging Script
 * This script creates a production-ready zip file of the theme
 */

const fs = require('fs');
const path = require('path');
const archiver = require('archiver');
const { execSync } = require('child_process');

// Configuration
const themeName = 'aqualuxe';
const themeVersion = require('../package.json').version;
const outputDir = path.join(__dirname, '../..', 'outputs');
const zipName = `${themeName}-${themeVersion}.zip`;
const zipPath = path.join(outputDir, zipName);

// Ensure output directory exists
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true });
}

// Files and directories to exclude from the zip
const excludePatterns = [
  'node_modules/**',
  'src/**',
  '.git/**',
  '.github/**',
  '.vscode/**',
  'bin/**',
  'tests/**',
  'vendor/**',
  '.DS_Store',
  'composer.json',
  'composer.lock',
  'package.json',
  'package-lock.json',
  'webpack.mix.js',
  'tailwind.config.js',
  'postcss.config.js',
  '.eslintrc.json',
  '.stylelintrc.json',
  '.gitignore',
  '.editorconfig',
  'README.md',
  'CONTRIBUTING.md',
  '*.zip'
];

// Create a file to stream archive data to
const output = fs.createWriteStream(zipPath);
const archive = archiver('zip', {
  zlib: { level: 9 } // Maximum compression
});

// Listen for all archive data to be written
output.on('close', function() {
  console.log(`✅ Archive created: ${zipPath}`);
  console.log(`📦 Total size: ${(archive.pointer() / 1024 / 1024).toFixed(2)} MB`);
});

// Good practice to catch warnings
archive.on('warning', function(err) {
  if (err.code === 'ENOENT') {
    console.warn('⚠️ Warning:', err);
  } else {
    throw err;
  }
});

// Good practice to catch errors
archive.on('error', function(err) {
  throw err;
});

// Pipe archive data to the file
archive.pipe(output);

// Run production build first
console.log('🔨 Building production assets...');
try {
  execSync('npm run production', { stdio: 'inherit' });
  console.log('✅ Production build completed');
} catch (error) {
  console.error('❌ Production build failed:', error);
  process.exit(1);
}

// Function to check if a file should be excluded
function shouldExclude(filePath) {
  return excludePatterns.some(pattern => {
    if (pattern.endsWith('/**')) {
      const dir = pattern.slice(0, -3);
      return filePath.startsWith(dir);
    }
    return filePath === pattern;
  });
}

// Function to add directory to archive
function addDirectoryToArchive(dirPath, baseDir) {
  const files = fs.readdirSync(dirPath);
  
  for (const file of files) {
    const filePath = path.join(dirPath, file);
    const relativePath = path.relative(baseDir, filePath);
    
    if (shouldExclude(relativePath)) {
      continue;
    }
    
    const stat = fs.statSync(filePath);
    
    if (stat.isDirectory()) {
      addDirectoryToArchive(filePath, baseDir);
    } else {
      archive.file(filePath, { name: path.join(themeName, relativePath) });
    }
  }
}

// Add theme files to the archive
console.log('📁 Adding files to archive...');
const themeDir = path.join(__dirname, '..');
addDirectoryToArchive(themeDir, themeDir);

// Finalize the archive
archive.finalize();