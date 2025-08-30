# AquaLuxe Theme Implementation Guide

This guide provides step-by-step instructions for implementing the fixes and improvements to the AquaLuxe WordPress theme. Follow these instructions carefully to ensure a smooth transition to the improved codebase.

## Table of Contents

1. [Backup](#1-backup)
2. [Fix Duplicate Function Definitions](#2-fix-duplicate-function-definitions)
3. [Fix Dark Mode Implementation](#3-fix-dark-mode-implementation)
4. [Fix WooCommerce Integration](#4-fix-woocommerce-integration)
5. [Consolidate Sanitization Functions](#5-consolidate-sanitization-functions)
6. [Fix Theme Class](#6-fix-theme-class)
7. [Implement Unified Architecture](#7-implement-unified-architecture)
8. [Update .gitignore](#8-update-gitignore)
9. [Testing](#9-testing)
10. [Documentation](#10-documentation)

## 1. Backup

Before making any changes, create a backup of the theme:

```bash
# Navigate to the WordPress themes directory
cd wp-content/themes

# Create a backup of the theme
cp -r aqualuxe aqualuxe-backup
```

## 2. Fix Duplicate Function Definitions

Replace the original `functions.php` file with the fixed version:

```bash
# Copy the fixed functions.php file
cp fixes/functions-fix.php aqualuxe/functions.php
```

This fix removes duplicate function definitions and ensures that template tag functions are only defined in their respective files.

## 3. Fix Dark Mode Implementation

Replace the original dark mode early detection file with the fixed version:

```bash
# Copy the fixed dark-mode-early.php file
cp fixes/dark-mode-early-fix.php aqualuxe/inc/core/dark-mode-early.php
```

This fix implements proper function_exists checks to prevent function redefinition and maintains the early loading capability for dark mode detection.

## 4. Fix WooCommerce Integration

Replace the original WooCommerce integration file with the fixed version:

```bash
# Copy the fixed woocommerce.php file
cp fixes/woocommerce-fix.php aqualuxe/inc/woocommerce/woocommerce.php
```

This fix centralizes WooCommerce theme support declarations and implements a consistent approach to WooCommerce integration.

## 5. Consolidate Sanitization Functions

Replace the original sanitization functions file with the consolidated version:

```bash
# Copy the fixed sanitize.php file
cp fixes/sanitize-fix.php aqualuxe/inc/helpers/sanitize.php
```

This fix provides a single source of truth for all sanitization functions and includes comprehensive documentation for each function.

## 6. Fix Theme Class

Replace the original theme class file with the fixed version:

```bash
# Copy the fixed class-aqualuxe-theme.php file
cp fixes/class-aqualuxe-theme-fix.php aqualuxe/inc/core/class-aqualuxe-theme.php
```

This fix removes duplicate constant definitions and relies on constants defined in functions.php.

## 7. Implement Unified Architecture

Copy the new unified architecture files to the theme:

```bash
# Create directories if they don't exist
mkdir -p aqualuxe/inc/core/unified

# Copy the unified architecture files
cp fixes/class-assets-fix.php aqualuxe/inc/core/unified/class-assets.php
cp fixes/class-template-fix.php aqualuxe/inc/core/unified/class-template.php
cp fixes/class-woocommerce-fix.php aqualuxe/inc/woocommerce/class-woocommerce-unified.php
cp fixes/implementation-bridge.php aqualuxe/inc/core/unified/implementation-bridge.php
```

Update the theme's autoloader to include the new unified architecture files:

```php
// In inc/core/class-aqualuxe-autoloader.php

// Add the unified namespace mapping
$this->namespace_mapping = array(
    'AquaLuxe\\Core\\' => AQUALUXE_DIR . '/inc/core/',
    'AquaLuxe\\Core\\Unified\\' => AQUALUXE_DIR . '/inc/core/unified/',
    'AquaLuxe\\WooCommerce\\' => AQUALUXE_DIR . '/inc/woocommerce/',
    // ... existing mappings ...
);
```

Include the implementation bridge in functions.php:

```php
// In functions.php, after loading the autoloader

// Include the implementation bridge
require_once AQUALUXE_DIR . '/inc/core/unified/implementation-bridge.php';
```

## 8. Update .gitignore

Replace the original .gitignore file with the improved version:

```bash
# Copy the improved .gitignore file
cp fixes/.gitignore aqualuxe/.gitignore
```

This fix provides a comprehensive .gitignore file that excludes common files and directories that should not be tracked by version control.

## 9. Testing

After implementing all fixes, thoroughly test the theme to ensure everything works correctly:

1. **Basic Functionality**:
   - Verify that the theme loads correctly
   - Check that all pages display properly
   - Test navigation and menus
   - Verify that widgets work correctly

2. **WooCommerce Integration**:
   - Test with WooCommerce active and inactive
   - Verify product pages display correctly
   - Test cart and checkout functionality
   - Check that shop pages display properly

3. **Dark Mode**:
   - Test dark mode toggle functionality
   - Verify that dark mode preference is saved
   - Check that dark mode applies correctly to all elements

4. **Customizer**:
   - Test all customizer options
   - Verify that changes apply correctly
   - Check that sanitization functions work properly

5. **Performance**:
   - Test page load times
   - Verify that assets are loaded correctly
   - Check that critical CSS is applied properly

6. **Responsive Design**:
   - Test on different screen sizes
   - Verify that the theme is responsive
   - Check that mobile navigation works correctly

7. **Accessibility**:
   - Test keyboard navigation
   - Verify that screen readers can access all content
   - Check that ARIA attributes are applied correctly

## 10. Documentation

Copy the documentation files to the theme:

```bash
# Create documentation directory if it doesn't exist
mkdir -p aqualuxe/documentation

# Copy the documentation files
cp fixes/UNIFIED-ARCHITECTURE.md aqualuxe/documentation/
cp fixes/IMPLEMENTATION-GUIDE.md aqualuxe/documentation/
```

Update the theme's README.md file to include information about the fixes and improvements:

```markdown
# AquaLuxe WordPress Theme

## Recent Improvements

The theme has undergone significant improvements to enhance code quality, maintainability, and performance:

1. **Fixed Duplicate Function Definitions**: Removed duplicate function definitions and ensured that template tag functions are only defined in their respective files.
2. **Improved Dark Mode Implementation**: Implemented proper function_exists checks to prevent function redefinition and maintained the early loading capability for dark mode detection.
3. **Enhanced WooCommerce Integration**: Centralized WooCommerce theme support declarations and implemented a consistent approach to WooCommerce integration.
4. **Consolidated Sanitization Functions**: Provided a single source of truth for all sanitization functions and included comprehensive documentation for each function.
5. **Fixed Theme Class**: Removed duplicate constant definitions and relied on constants defined in functions.php.
6. **Implemented Unified Architecture**: Created a unified architecture for asset loading, template handling, and WooCommerce integration to eliminate duplicate code and improve maintainability.
7. **Improved .gitignore**: Provided a comprehensive .gitignore file that excludes common files and directories that should not be tracked by version control.

For more information, see the documentation in the `documentation` directory.
```

## Conclusion

By following this implementation guide, you have successfully applied all the fixes and improvements to the AquaLuxe WordPress theme. The theme now has improved code quality, maintainability, and performance, making it easier to extend and maintain in the future.

If you encounter any issues during implementation, please refer to the documentation or contact the theme developer for assistance.