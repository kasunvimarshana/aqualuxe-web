# Demo Content Importer - User Guide

## Introduction

The Demo Content Importer is a powerful tool that allows you to transform your WordPress site into a fully configured demonstration environment with just a few clicks. This guide will walk you through the process of using the importer to set up your site.

## Getting Started

### Installation

1. Upload the `demo-content-importer` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance > Demo Content Importer to access the importer interface

### System Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Memory limit of at least 128MB (256MB recommended)
- Max execution time of at least 300 seconds
- File upload size of at least 8MB

## Using the Importer

### Main Interface

The importer interface is divided into several tabs:

1. **Demo Content** - Browse and import available demo packages
2. **Backup & Restore** - Create and restore backups of your site
3. **Reset** - Reset your site to a clean state
4. **Settings** - Configure importer settings
5. **Logs** - View import and reset logs

### Importing Demo Content

1. Go to the Demo Content tab
2. Browse the available demo packages
3. Click "Preview" to see a live preview of the demo
4. Click "Import" on the demo you want to import
5. Select the content you want to import:
   - Content (posts, pages, etc.)
   - Media (images, videos, etc.)
   - Widgets
   - Customizer settings
   - Theme options
   - Menus
   - Plugins
6. Click "Start Import" to begin the import process
7. Wait for the import to complete

### Import Options

When importing demo content, you can customize the import process with the following options:

- **Import content** - Import posts, pages, and custom post types
- **Import media** - Import images and other media files
- **Import widgets** - Import widget configurations
- **Import customizer settings** - Import theme customizer settings
- **Import theme options** - Import theme options
- **Set up menus** - Set up navigation menus
- **Install and activate plugins** - Install and activate required plugins
- **Set up homepage** - Set the front page to the demo homepage
- **Replace existing content** - Replace existing content with demo content

### Backup & Restore

#### Creating a Backup

1. Go to the Backup & Restore tab
2. Click "Create Backup"
3. Select what you want to backup:
   - Database
   - Files
   - Both
4. Click "Start Backup"
5. Wait for the backup to complete
6. Download the backup file for safekeeping

#### Restoring a Backup

1. Go to the Backup & Restore tab
2. Click "Restore Backup"
3. Select a backup file to restore
4. Click "Start Restore"
5. Wait for the restore to complete

### Resetting Your Site

1. Go to the Reset tab
2. Select what you want to reset:
   - Content (posts, pages, etc.)
   - Media (images, videos, etc.)
   - Widgets
   - Customizer settings
   - Theme options
   - Menus
   - Plugins
3. Click "Reset Site"
4. Confirm that you want to reset your site
5. Wait for the reset to complete

## Troubleshooting

### Common Issues

1. **Import fails with memory error**
   - Increase PHP memory limit in wp-config.php
   - Try importing content in smaller chunks

2. **Images not importing**
   - Check server permissions
   - Verify that the server can access external URLs

3. **Import process times out**
   - Increase max execution time in php.ini
   - Try importing content in smaller chunks

4. **Plugins not installing**
   - Check if your server can connect to wordpress.org
   - Try installing plugins manually

### Error Logs

If you encounter issues during the import process, you can check the error logs in the Logs tab. The logs provide detailed information about the import process and any errors that occurred.

## Support

If you need additional help, please contact our support team:

- Email: support@demo-content-importer.com
- Website: https://demo-content-importer.com/support
- Documentation: https://demo-content-importer.com/docs

## FAQ

### Will importing demo content overwrite my existing content?

By default, the importer will add demo content alongside your existing content. However, you can choose to replace existing content with demo content by selecting the "Replace existing content" option.

### Can I undo an import?

Yes, you can restore a backup or reset your site to undo an import. We recommend creating a backup before importing demo content.

### Can I import only specific parts of a demo?

Yes, you can select which parts of a demo to import (content, media, widgets, customizer settings, theme options, menus, plugins).

### Will the importer work with any theme?

The importer works with any WordPress theme, but some features may be theme-specific. For the best experience, use the importer with themes that have been specifically integrated with it.

### Can I import demo content multiple times?

Yes, you can import demo content multiple times. However, this may result in duplicate content. We recommend resetting your site or restoring a backup before importing demo content again.

### Is it safe to use the importer on a live site?

We recommend using the importer on a staging or development site first. If you must use it on a live site, make sure to create a backup before importing demo content.