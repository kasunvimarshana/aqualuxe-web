# AquaLuxe Theme Integration

This document describes how the Demo Content Importer integrates with the AquaLuxe WordPress theme.

## Overview

The Demo Content Importer provides enhanced functionality for importing demo content into the AquaLuxe theme. It extends the basic WordPress importer with features like:

- One-click demo content installation
- Multiple demo content packages (Main, Shop-focused, Blog-focused)
- Automatic plugin installation and activation
- Customizer settings import
- Widget configuration import
- Theme options import
- WooCommerce setup and configuration
- Backup and restore functionality
- Complete site reset capability

## Demo Content Packages

The importer provides three demo content packages for the AquaLuxe theme:

1. **AquaLuxe Main Demo** - Complete demo content with all features
2. **AquaLuxe Shop Demo** - E-commerce focused demo content
3. **AquaLuxe Blog Demo** - Blog-focused demo content

## Installation

1. Install and activate the Demo Content Importer plugin
2. Go to Appearance > Demo Content Importer
3. Select the desired demo package
4. Click "Import" to begin the import process

## Import Options

The importer provides several options for customizing the import process:

- **Content** - Import posts, pages, and custom post types
- **Media** - Import images and other media files
- **Widgets** - Import widget configurations
- **Customizer** - Import theme customizer settings
- **Options** - Import theme options
- **Menus** - Set up navigation menus
- **Plugins** - Install and activate required plugins

## Required Plugins

Depending on the selected demo package, the following plugins may be required:

- WooCommerce (required for all demos)
- Contact Form 7 (required for all demos)
- Yoast SEO (optional)

## Post-Import Setup

After importing the demo content, the importer will:

1. Set up theme mods based on the selected demo package
2. Configure WooCommerce settings (if applicable)
3. Set up homepage and blog page
4. Configure navigation menus
5. Set up widgets
6. Flush rewrite rules

## Troubleshooting

### Common Issues

1. **Import fails with memory error**
   - Increase PHP memory limit in wp-config.php
   - Try importing content in smaller chunks

2. **Images not importing**
   - Check server permissions
   - Verify that the server can access external URLs

3. **Customizer settings not applying**
   - Make sure the theme is activated before importing
   - Try resetting the customizer and importing again

4. **WooCommerce pages not set up**
   - Make sure WooCommerce is activated before importing
   - Try manually setting up WooCommerce pages

### Support

For additional support, please contact:

- Theme Support: support@aqualuxe-theme.com
- Importer Support: support@demo-content-importer.com

## Developer Information

### Hooks and Filters

The importer provides several hooks and filters for developers to extend its functionality:

- `dci_demo_packages` - Filter demo packages
- `dci_import_options` - Filter import options
- `dci_before_import` - Action before import
- `dci_after_import` - Action after import
- `dci_before_reset` - Action before site reset
- `dci_after_reset` - Action after site reset

### Adding Custom Demo Packages

Developers can add custom demo packages by using the `dci_demo_packages` filter:

```php
add_filter('dci_demo_packages', 'my_custom_demo_package');

function my_custom_demo_package($packages) {
    $packages[] = array(
        'id' => 'aqualuxe-custom',
        'name' => 'AquaLuxe Custom Demo',
        'description' => 'Custom demo content for the AquaLuxe theme.',
        'screenshot' => 'path/to/screenshot.png',
        'preview_url' => 'https://custom.aqualuxe.example.com',
        'config_file' => 'path/to/config.json',
    );
    return $packages;
}
```

### Custom Post-Import Actions

Developers can perform custom actions after import by using the `dci_after_import` action:

```php
add_action('dci_after_import', 'my_custom_after_import', 10, 2);

function my_custom_after_import($demo_id, $import_options) {
    if ($demo_id === 'aqualuxe-custom') {
        // Perform custom actions
    }
}
```