# AquaLuxe WordPress + WooCommerce Theme v1.4.0

## Overview
AquaLuxe is a premium WordPress + WooCommerce theme designed specifically for luxury aquatic retail businesses. The theme combines elegant design with powerful e-commerce functionality to create a seamless shopping experience for customers looking for high-end aquatic products.

## Version 1.4.0 Improvements

This version includes significant build process improvements and bug fixes:

1. **Enhanced Build Process**: Completely revamped for better performance and reliability
   - Fixed issues with fontmin-ttf dependency using alternative packages
   - Enhanced webpack.mix.js configuration for better asset optimization
   - Improved critical CSS generation with better template support
   - Enhanced image optimization with better directory handling
   - Improved SVG sprite generation with accessibility features

2. **Demo Importer Fixes**: Resolved issues with the demo content importer
   - Fixed duplicate constants in demo importer files
   - Added conditional checks before defining constants
   - Created missing demo importer assets (CSS and JS files)
   - Enhanced demo importer UI and functionality

3. **Asset Management**: Improved asset management and optimization
   - Enhanced image optimization with WebP conversion
   - Added support for processing existing images in destination directory
   - Improved SVG sprite generation with better icon usage documentation
   - Added comprehensive icons usage example page

4. **Development Improvements**: Better developer experience
   - Added comprehensive .gitignore file with proper exclusions
   - Improved code documentation throughout the theme
   - Enhanced function documentation for better code understanding

## Version 1.3.3 Improvements

This version includes important bug fixes and code quality improvements:

1. **Fixed Duplicate Function Declarations**: Resolved remaining duplicate function declarations
   - Added proper function_exists checks to all sanitization functions
   - Removed duplicate aqualuxe_sanitize_checkbox function from accessibility.php
   - Removed duplicate aqualuxe_sanitize_checkbox function from helpers.php
   - Removed duplicate aqualuxe_sanitize_select function from customizer.php

2. **Code Organization**: Improved code structure and organization
   - Updated file loading order to ensure sanitize.php is loaded before other files
   - Enhanced function_exists checks for better compatibility
   - Updated version numbers across all files for consistency

## Version 1.3.0 Improvements

This version includes significant code quality, architecture, and performance improvements:

1. **Unified Architecture**: Implemented a service container pattern with unified systems for:
   - Asset loading (scripts and styles)
   - Template handling (body classes, template parts)
   - WooCommerce integration

2. **Enhanced Asset Pipeline**: Comprehensive asset optimization system:
   - JavaScript minification and bundling
   - CSS optimization with critical CSS extraction
   - Image optimization with WebP conversion
   - Font subsetting and optimization
   - SVG sprite generation
   - Cache busting with content-based hashing

3. **Code Quality Improvements**:
   - Eliminated duplicate function definitions
   - Removed duplicate constant definitions
   - Improved dark mode implementation
   - Consolidated sanitization functions
   - Enhanced configuration files

4. **Performance Optimizations**:
   - Critical CSS for faster initial rendering
   - Optimized asset loading
   - Improved caching strategy
   - Reduced JavaScript and CSS file sizes

## Package Contents
- `aqualuxe/` - The main theme directory to be installed in WordPress
- `documentation/` - Comprehensive documentation files including architecture guides
- `CHANGELOG.md` - Detailed list of changes in this version
- `LICENSE.txt` - License information
- `README.md` - This file

## Features

- **Modern Architecture**: Service container pattern for dependency injection
- **Dual-State Architecture**: Seamless functionality with or without WooCommerce
- **Dark Mode Support**: System preference detection and user toggle
- **Critical CSS**: Performance optimization with critical CSS
- **Accessibility**: WCAG 2.1 AA compliance
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Customizer Options**: Comprehensive theme customization
- **Demo Content**: One-click demo content import

## Quick Start
1. Upload the `aqualuxe` directory to your WordPress themes directory (`wp-content/themes/`)
2. Activate the theme through the WordPress admin panel
3. Follow the setup instructions in the documentation

## Development Setup

### Prerequisites
- Node.js 20.x or later
- npm 10.x or later
- WordPress 6.0 or later
- PHP 8.0 or later

### Installation for Development
1. Clone the repository to your WordPress themes directory
2. Navigate to the theme directory: `cd wp-content/themes/aqualuxe`
3. Install dependencies: `npm install`
4. Run development build: `npm run dev`
5. For production build: `npm run build`

### Build Commands
- `npm run dev` - Development build
- `npm run watch` - Development build with watch mode
- `npm run prod` - Production build
- `npm run build` - Production build with critical CSS generation
- `npm run lint` - Run ESLint on JavaScript files
- `npm run stylelint` - Run Stylelint on SCSS files
- `npm run critical` - Generate critical CSS
- `npm run imagemin` - Optimize images
- `npm run svg-sprite` - Generate SVG sprite

## Documentation
Please refer to the documentation files in the `documentation/` directory for detailed installation and usage instructions:

- **INSTALLATION.md**: Step-by-step installation guide
- **UNIFIED-ARCHITECTURE.md**: Details on the theme's unified architecture
- **IMPLEMENTATION-GUIDE.md**: Guide for implementing theme customizations
- **ASSET-PIPELINE.md**: Documentation for the enhanced asset pipeline
- **DEVELOPMENT.md**: Guide for theme development and customization

## Changelog

See the [CHANGELOG.md](CHANGELOG.md) file for a detailed list of changes in each version.

## Support
For support, please contact us at support@example.com or visit our support forum at https://example.com/support.

## License
This theme is licensed under the GPL v2 or later. See LICENSE.txt for more information.