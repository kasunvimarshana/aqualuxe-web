# Demo Content Importer

A comprehensive, production-ready demo content importer system that completely transforms any WordPress installation into a fully configured demonstration environment.

## Features

- **One-Click Demo Import** - Import complete demo content with a single click
- **Multiple Demo Packages** - Support for multiple demo content packages
- **Customizer Settings** - Import theme customizer settings
- **Widget Configurations** - Import widget configurations
- **Theme Options** - Import theme options and settings
- **Plugin Management** - Automatic installation and activation of required plugins
- **Media Import** - Import images and other media files
- **Menu Setup** - Set up navigation menus
- **Backup & Restore** - Create and restore backups of your site
- **Site Reset** - Reset your site to a clean state
- **Logging System** - Comprehensive logging for troubleshooting
- **User-Friendly Interface** - Intuitive admin interface with progress indicators
- **Developer API** - Extensive API for developers to extend functionality
- **Theme Integration** - Easy integration with any WordPress theme

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Memory limit of at least 128MB (256MB recommended)
- Max execution time of at least 300 seconds
- File upload size of at least 8MB

## Installation

1. Upload the `demo-content-importer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance > Demo Content Importer to access the importer interface

## Usage

### Importing Demo Content

1. Go to Appearance > Demo Content Importer
2. Browse the available demo packages
3. Click "Preview" to see a live preview of the demo
4. Click "Import" on the demo you want to import
5. Select the content you want to import
6. Click "Start Import" to begin the import process
7. Wait for the import to complete

### Creating Backups

1. Go to Appearance > Demo Content Importer > Backup & Restore
2. Click "Create Backup"
3. Select what you want to backup
4. Click "Start Backup"
5. Wait for the backup to complete
6. Download the backup file for safekeeping

### Restoring Backups

1. Go to Appearance > Demo Content Importer > Backup & Restore
2. Click "Restore Backup"
3. Select a backup file to restore
4. Click "Start Restore"
5. Wait for the restore to complete

### Resetting Your Site

1. Go to Appearance > Demo Content Importer > Reset
2. Select what you want to reset
3. Click "Reset Site"
4. Confirm that you want to reset your site
5. Wait for the reset to complete

## Documentation

- [User Guide](docs/user-guide.md) - Comprehensive guide for users
- [Developer Guide](docs/developer-guide.md) - Documentation for developers
- [AquaLuxe Integration](docs/aqualuxe-integration.md) - Guide for AquaLuxe theme integration

## Theme Integration

The Demo Content Importer is designed to work with any WordPress theme. It includes built-in integration with the AquaLuxe theme, providing enhanced functionality and multiple demo content packages.

To integrate your theme with the Demo Content Importer, see the [Developer Guide](docs/developer-guide.md).

## Support

For support, please contact:

- Email: support@demo-content-importer.com
- Website: https://demo-content-importer.com/support

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
```

## Credits

- WordPress Importer - https://wordpress.org/plugins/wordpress-importer/
- WP-CLI - https://wp-cli.org/
- WordPress - https://wordpress.org/

## Changelog

### 1.0.0
- Initial release
- One-click demo import functionality
- Multiple demo content packages
- Customizer settings import
- Widget configurations import
- Theme options import
- Plugin management
- Media import
- Menu setup
- Backup & restore functionality
- Site reset capability
- Logging system
- User-friendly interface
- Developer API
- AquaLuxe theme integration