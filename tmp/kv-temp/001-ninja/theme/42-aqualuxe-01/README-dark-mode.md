# AquaLuxe Theme Dark Mode Implementation

## Overview

This update implements a comprehensive dark mode feature for the AquaLuxe WordPress theme. The dark mode functionality allows users to switch between light and dark color schemes, with options for customization through the WordPress Customizer.

## Files Created/Modified

1. **inc/dark-mode.php** - Core functionality for dark mode
2. **header-updated.php** - Updated header template with action hooks for dark mode toggle
3. **functions-updated.php** - Updated functions file that includes dark mode functionality
4. **inc/hooks/hooks-updated.php** - Additional hooks for dark mode toggle placement
5. **docs/dark-mode.md** - Documentation for using and customizing dark mode
6. **install-dark-mode.php** - Installation script to update necessary files

## Implementation Details

The dark mode implementation includes:

- A toggle button that can be positioned in various locations (top bar, header, footer, or floating)
- Customizer settings for controlling dark mode behavior
- System preference detection for automatic switching
- Persistent user preference storage using localStorage
- Comprehensive styling for all theme elements

## Installation Instructions

### Option 1: Using the Installation Script

1. Upload all the files to your theme directory
2. Run the installation script by visiting `/wp-admin/theme-editor.php?file=install-dark-mode.php&theme=aqualuxe-theme` in your browser
3. The script will update all necessary files automatically

### Option 2: Manual Installation

1. Copy `inc/dark-mode.php` to your theme's `inc` directory
2. Replace your existing `header.php` with `header-updated.php` (rename it to `header.php`)
3. Replace your existing `functions.php` with `functions-updated.php` (rename it to `functions.php`)
4. Add the new hooks from `inc/hooks/hooks-updated.php` to your existing `inc/hooks/hooks.php` file

## Customizer Settings

The dark mode settings can be found in the WordPress Customizer under **AquaLuxe Theme Options > Dark Mode**. The following options are available:

- **Enable Dark Mode**: Toggle to enable or disable the dark mode functionality
- **Default Mode**: Choose the default color mode (Light, Dark, or System Preference)
- **Auto Dark Mode**: Automatically switch based on user's system preferences
- **Toggle Position**: Choose where to display the dark mode toggle
- **Dark Mode Primary Color**: Choose the primary accent color for dark mode
- **Dark Mode Background Color**: Choose the background color for dark mode
- **Dark Mode Text Color**: Choose the text color for dark mode

## Testing

After installation, test the dark mode functionality by:

1. Visiting your site and checking if the dark mode toggle appears in the selected position
2. Clicking the toggle to switch between light and dark modes
3. Refreshing the page to verify that your preference is remembered
4. Checking the appearance of all theme elements in dark mode
5. Testing the customizer settings to ensure they work correctly

## Documentation

For detailed information on using and customizing the dark mode functionality, please refer to the `docs/dark-mode.md` file.

## Support

If you encounter any issues with the dark mode implementation, please contact the theme support team.