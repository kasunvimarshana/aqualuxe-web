# AquaLuxe WordPress + WooCommerce Theme v1.3.3

## Overview

AquaLuxe is a premium WordPress + WooCommerce theme designed specifically for luxury aquatic retail businesses. The theme combines elegant design with powerful e-commerce functionality to create a seamless shopping experience for customers looking for high-end aquatic products.

## Package Contents

- `aqualuxe/` - The main theme directory to be installed in WordPress
- `INSTALLATION.md` - Detailed installation instructions
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

## Version 1.3.3 Improvements

This version includes important bug fixes and code quality improvements:

1. **Fixed Remaining Duplicate Function Declarations**: 
   - Removed duplicate `aqualuxe_sanitize_checkbox` function from accessibility.php
   - Removed duplicate `aqualuxe_sanitize_checkbox` function from helpers.php
   - Removed duplicate `aqualuxe_sanitize_select` function from customizer.php

2. **Enhanced Function Checks**: 
   - Added proper `function_exists()` checks to all sanitization functions
   - Improved code organization in sanitization functions

3. **Optimized File Loading Order**:
   - Updated file loading order to ensure sanitize.php is loaded before other files
   - Centralized all sanitization functions in sanitize.php

4. **Version Updates**:
   - Updated version numbers across all files for consistency to 1.3.3

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.6 or higher (or MariaDB 10.0 or higher)
- WooCommerce 7.0 or higher (optional, but recommended for full functionality)

## Quick Start

1. Upload the `aqualuxe` directory to your WordPress themes directory (`wp-content/themes/`)
2. Activate the theme through the WordPress admin panel
3. Follow the setup instructions in the `INSTALLATION.md` file

## Documentation

Please refer to the documentation files in the `aqualuxe/documentation/` directory for detailed installation and usage instructions:

- **INSTALLATION.md**: Step-by-step installation guide
- **UNIFIED-ARCHITECTURE.md**: Details on the theme's unified architecture
- **IMPLEMENTATION-GUIDE.md**: Guide for implementing theme customizations
- **ASSET-PIPELINE.md**: Documentation for the enhanced asset pipeline

## Support

For support, please contact us at support@example.com or visit our support forum at https://example.com/support.

## License

This theme is licensed under the GPL v2 or later. See LICENSE.txt for more information.