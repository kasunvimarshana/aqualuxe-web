#!/usr/bin/env node

/**
 * AquaLuxe Theme Packaging Script
 * 
 * Creates a production-ready distribution package of the theme
 * for marketplace distribution or client delivery.
 */

const fs = require('fs');
const path = require('path');
const archiver = require('archiver');

class ThemePackager {
    constructor() {
        this.themeDir = path.resolve(__dirname, '..');
        this.distDir = path.resolve(__dirname, '../dist');
        this.packageName = 'aqualuxe-theme.zip';
        
        this.excludePatterns = [
            'node_modules',
            'assets/src',
            'scripts',
            '.git',
            '.gitignore',
            '.env',
            '.env.*',
            'webpack.config.js',
            'tailwind.config.js',
            'package.json',
            'package-lock.json',
            'yarn.lock',
            'README.md',
            'ARCHITECTURE_SUMMARY.md',
            'ENTERPRISE_THEME_SUMMARY.md',
            '*.log',
            '.DS_Store',
            'Thumbs.db',
            '*.tmp',
            '*.temp'
        ];
    }

    async package() {
        console.log('🎁 Starting AquaLuxe theme packaging...');
        
        try {
            // Ensure dist directory exists
            await this.ensureDistDirectory();
            
            // Create the archive
            await this.createArchive();
            
            console.log('✅ Theme packaging completed successfully!');
            console.log(`📦 Package location: ${path.join(this.distDir, this.packageName)}`);
            
        } catch (error) {
            console.error('❌ Packaging failed:', error);
            process.exit(1);
        }
    }

    async ensureDistDirectory() {
        if (!fs.existsSync(this.distDir)) {
            fs.mkdirSync(this.distDir, { recursive: true });
        }
    }

    async createArchive() {
        return new Promise((resolve, reject) => {
            const output = fs.createWriteStream(path.join(this.distDir, this.packageName));
            const archive = archiver('zip', {
                zlib: { level: 9 } // Maximum compression
            });

            output.on('close', () => {
                const sizeInMB = (archive.pointer() / 1024 / 1024).toFixed(2);
                console.log(`📦 Package size: ${sizeInMB} MB`);
                resolve();
            });

            archive.on('error', (err) => {
                reject(err);
            });

            archive.pipe(output);

            // Add files to archive
            this.addFilesToArchive(archive);

            archive.finalize();
        });
    }

    addFilesToArchive(archive) {
        console.log('📁 Adding files to archive...');
        
        // Get all files in theme directory
        const files = this.getThemeFiles();
        
        files.forEach(file => {
            const relativePath = path.relative(this.themeDir, file);
            console.log(`  + ${relativePath}`);
            archive.file(file, { name: `aqualuxe/${relativePath}` });
        });

        // Add documentation
        this.addDocumentation(archive);
    }

    getThemeFiles() {
        const files = [];
        
        const walkDir = (dir) => {
            const items = fs.readdirSync(dir);
            
            items.forEach(item => {
                const fullPath = path.join(dir, item);
                const relativePath = path.relative(this.themeDir, fullPath);
                
                // Skip excluded patterns
                if (this.shouldExclude(relativePath)) {
                    return;
                }
                
                const stat = fs.statSync(fullPath);
                
                if (stat.isDirectory()) {
                    walkDir(fullPath);
                } else {
                    files.push(fullPath);
                }
            });
        };
        
        walkDir(this.themeDir);
        return files;
    }

    shouldExclude(relativePath) {
        return this.excludePatterns.some(pattern => {
            // Handle wildcard patterns
            if (pattern.includes('*')) {
                const regex = new RegExp(pattern.replace(/\*/g, '.*'));
                return regex.test(relativePath);
            }
            
            // Handle directory patterns
            return relativePath.startsWith(pattern + '/') || relativePath === pattern;
        });
    }

    addDocumentation(archive) {
        console.log('📚 Adding documentation...');
        
        // Create installation guide
        const installationGuide = this.generateInstallationGuide();
        archive.append(installationGuide, { name: 'aqualuxe/INSTALLATION.md' });
        
        // Create changelog
        const changelog = this.generateChangelog();
        archive.append(changelog, { name: 'aqualuxe/CHANGELOG.md' });
        
        // Create feature list
        const features = this.generateFeatureList();
        archive.append(features, { name: 'aqualuxe/FEATURES.md' });
        
        // Create license
        const license = this.generateLicense();
        archive.append(license, { name: 'aqualuxe/LICENSE.txt' });
    }

    generateInstallationGuide() {
        return `# AquaLuxe WordPress Theme - Installation Guide

## Requirements
- WordPress 5.0+
- PHP 7.4+
- WooCommerce 5.0+ (optional, for e-commerce features)

## Installation Steps

### Method 1: WordPress Admin Dashboard
1. Download the theme zip file
2. Go to WordPress Admin → Appearance → Themes
3. Click "Add New" → "Upload Theme"
4. Select the aqualuxe-theme.zip file
5. Click "Install Now"
6. Activate the theme

### Method 2: FTP Upload
1. Extract the theme zip file
2. Upload the 'aqualuxe' folder to /wp-content/themes/
3. Activate the theme from WordPress Admin

## Post-Installation Setup

### 1. Install Required Plugins
- WooCommerce (for shop functionality)
- Contact Form 7 (for contact forms)

### 2. Import Demo Content
1. Go to Appearance → Demo Importer
2. Click "Import Demo Content"
3. Wait for the import to complete

### 3. Configure Theme Settings
1. Go to Customizer
2. Configure colors, fonts, and layout options
3. Set up your logo and favicon

### 4. Set Up Menus
1. Go to Appearance → Menus
2. Create your navigation menu
3. Assign to "Primary Menu" location

## Support
For support and documentation, visit: https://aqualuxe.com/support
`;
    }

    generateChangelog() {
        return `# AquaLuxe Theme Changelog

## Version 1.0.0 - Initial Release

### ✨ New Features
- Interactive Three.js fish tank hero animation
- Complete WooCommerce integration
- Responsive design with mobile-first approach
- Dark mode support
- Advanced accessibility features (WCAG 2.1 AA compliant)
- Performance optimizations and lazy loading
- Modern build system with Webpack and Tailwind CSS
- Comprehensive demo content importer
- Multi-language support ready
- SEO optimized structure

### 🎨 Design Features
- AquaLuxe brand color palette
- Glass morphism effects
- Aquatic-themed animations
- Professional typography system
- Flexible layout options

### ⚡ Performance
- Optimized assets and code splitting
- Lazy loading for images and components
- Reduced motion support
- Connection-aware optimizations

### 🛒 WooCommerce Features
- Enhanced product pages
- Advanced cart functionality
- Wishlist and compare features
- Quick view modals
- AJAX filtering and sorting

### ♿ Accessibility
- Screen reader support
- Keyboard navigation
- Focus management
- High contrast mode support
- Reduced motion preferences

## Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)
`;
    }

    generateFeatureList() {
        return `# AquaLuxe Theme Features

## 🎭 Interactive Elements
- **Three.js Fish Tank Hero**: Immersive underwater scene with swimming fish, bubbles, and user interactions
- **GSAP Animations**: Smooth micro-interactions and scroll-triggered animations
- **Modal System**: Flexible modal components for various content types
- **Dark Mode Toggle**: Seamless light/dark theme switching

## 🛒 E-commerce Ready
- **WooCommerce Integration**: Complete shop functionality
- **Product Quick View**: Modal product previews
- **Wishlist & Compare**: Advanced product comparison features
- **AJAX Cart**: Real-time cart updates without page refresh
- **Payment Integration**: Support for all major payment gateways

## 📱 Responsive Design
- **Mobile-First**: Optimized for all device sizes
- **Touch-Friendly**: Intuitive touch interactions
- **Progressive Enhancement**: Works on all browsers
- **Retina Ready**: Sharp graphics on high-DPI displays

## ⚡ Performance Optimized
- **Webpack Build System**: Modern asset compilation
- **Code Splitting**: Load only what's needed
- **Lazy Loading**: Images and components load on demand
- **Cache Optimization**: Browser and server caching strategies

## ♿ Accessibility Features
- **WCAG 2.1 AA Compliant**: Meets accessibility standards
- **Screen Reader Support**: Proper ARIA labels and structure
- **Keyboard Navigation**: Full keyboard accessibility
- **Focus Management**: Clear focus indicators and trapping

## 🎨 Customization Options
- **Live Customizer**: Real-time preview of changes
- **Color Schemes**: Multiple pre-defined palettes
- **Typography Options**: Google Fonts integration
- **Layout Variations**: Flexible page layouts

## 🌐 Developer Features
- **Modern PHP**: PHP 7.4+ with OOP architecture
- **ES6+ JavaScript**: Modern JavaScript features
- **SCSS/Tailwind**: Powerful styling frameworks
- **Component-Based**: Modular, reusable components

## 📊 SEO Optimized
- **Schema Markup**: Rich snippets for better search results
- **Clean Code**: Semantic HTML structure
- **Fast Loading**: Optimized for Core Web Vitals
- **Meta Tags**: Proper meta tag implementation

## 🔧 Easy Setup
- **Demo Content Importer**: One-click demo installation
- **Theme Options**: Comprehensive settings panel
- **Documentation**: Detailed setup guides
- **Support**: Professional support available
`;
    }

    generateLicense() {
        return `MIT License

Copyright (c) 2024 AquaLuxe Theme

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

Third-party licenses:
- Three.js: MIT License
- GSAP: Commercial license (separate license required for commercial use)
- Tailwind CSS: MIT License
- All other dependencies: See individual package licenses
`;
    }
}

// Run the packager
if (require.main === module) {
    const packager = new ThemePackager();
    packager.package();
}

module.exports = ThemePackager;
