# Demo Content Importer - Developer Guide

## Introduction

This guide provides detailed information for developers who want to extend or customize the Demo Content Importer. It covers the architecture, API, hooks, filters, and best practices for working with the importer.

## Architecture

The Demo Content Importer is built with a modular architecture that separates concerns into different components:

### Core Components

1. **Main Importer Class** (`Demo_Content_Importer`) - Coordinates the import process and provides the admin interface
2. **Logger** (`Demo_Content_Importer_Logger`) - Handles logging during the import process
3. **Content Importer** (`Demo_Content_Importer_Content`) - Imports posts, pages, and other content
4. **Customizer Importer** (`Demo_Content_Importer_Customizer`) - Imports theme customizer settings
5. **Widget Importer** (`Demo_Content_Importer_Widgets`) - Imports widget configurations
6. **Options Importer** (`Demo_Content_Importer_Options`) - Imports theme options and settings
7. **Media Importer** (`Demo_Content_Importer_Media`) - Handles media file imports
8. **Backup** (`Demo_Content_Importer_Backup`) - Provides backup and restore functionality
9. **Reset** (`Demo_Content_Importer_Reset`) - Provides site reset functionality
10. **Plugins** (`Demo_Content_Importer_Plugins`) - Handles plugin management

### Directory Structure

```
demo-content-importer/
├── assets/
│   ├── css/
│   └── js/
├── data/
│   └── [demo-package]/
│       ├── config.json
│       ├── content.xml
│       ├── customizer.dat
│       ├── options.json
│       ├── screenshot.png
│       └── widgets.wie
├── docs/
├── includes/
│   ├── class-demo-content-importer-backup.php
│   ├── class-demo-content-importer-content.php
│   ├── class-demo-content-importer-customizer.php
│   ├── class-demo-content-importer-logger.php
│   ├── class-demo-content-importer-media.php
│   ├── class-demo-content-importer-options.php
│   ├── class-demo-content-importer-plugins.php
│   ├── class-demo-content-importer-reset.php
│   ├── class-demo-content-importer-widgets.php
│   └── wordpress-importer/
│       └── class-wp-import.php
├── templates/
│   └── admin-page.php
└── class-demo-content-importer.php
```

## Demo Package Configuration

Demo packages are defined using a JSON configuration file. Here's an example:

```json
{
  "name": "Demo Package Name",
  "description": "Description of the demo package",
  "version": "1.0.0",
  "author": "Author Name",
  "screenshot": "screenshot.png",
  "preview_url": "https://demo.example.com",
  "requires": {
    "wordpress": "5.8",
    "php": "7.4"
  },
  "plugins": {
    "woocommerce": {
      "name": "WooCommerce",
      "slug": "woocommerce",
      "required": true
    },
    "contact-form-7": {
      "name": "Contact Form 7",
      "slug": "contact-form-7",
      "required": true
    }
  },
  "content": {
    "xml": "content.xml",
    "customizer": "customizer.dat",
    "widgets": "widgets.wie",
    "options": "options.json"
  },
  "settings": {
    "homepage": "home",
    "blog_page": "blog",
    "shop_page": "shop",
    "menus": {
      "primary": "Primary Menu",
      "footer": "Footer Menu"
    }
  }
}
```

## API Reference

### Main Class Methods

#### `Demo_Content_Importer::get_instance()`

Get the singleton instance of the importer.

```php
$importer = Demo_Content_Importer::get_instance();
```

#### `Demo_Content_Importer::register_demo_package($args)`

Register a new demo package.

```php
$importer->register_demo_package(array(
    'id' => 'demo-id',
    'name' => 'Demo Name',
    'description' => 'Demo description',
    'screenshot' => 'path/to/screenshot.png',
    'preview_url' => 'https://demo.example.com',
    'config_file' => 'path/to/config.json',
));
```

#### `Demo_Content_Importer::import_demo($demo_id, $options = array())`

Import a demo package.

```php
$importer->import_demo('demo-id', array(
    'content' => true,
    'media' => true,
    'widgets' => true,
    'customizer' => true,
    'options' => true,
    'menus' => true,
    'plugins' => true,
    'homepage' => true,
    'replace' => false,
));
```

#### `Demo_Content_Importer::backup($options = array())`

Create a backup.

```php
$importer->backup(array(
    'database' => true,
    'files' => true,
    'filename' => 'backup-' . date('Y-m-d-H-i-s'),
));
```

#### `Demo_Content_Importer::restore($file)`

Restore a backup.

```php
$importer->restore('path/to/backup.zip');
```

#### `Demo_Content_Importer::reset($options = array())`

Reset the site.

```php
$importer->reset(array(
    'content' => true,
    'media' => true,
    'widgets' => true,
    'customizer' => true,
    'options' => true,
    'menus' => true,
    'plugins' => false,
));
```

### Logger Methods

#### `Demo_Content_Importer_Logger::log($message, $type = 'info')`

Log a message.

```php
$logger = new Demo_Content_Importer_Logger();
$logger->log('Message', 'info'); // info, warning, error, success
```

#### `Demo_Content_Importer_Logger::get_logs()`

Get all logs.

```php
$logs = $logger->get_logs();
```

## Hooks and Filters

### Actions

#### `dci_before_import`

Fired before importing a demo package.

```php
add_action('dci_before_import', function($demo_id, $import_options) {
    // Do something before import
}, 10, 2);
```

#### `dci_after_import`

Fired after importing a demo package.

```php
add_action('dci_after_import', function($demo_id, $import_options) {
    // Do something after import
}, 10, 2);
```

#### `dci_before_reset`

Fired before resetting the site.

```php
add_action('dci_before_reset', function($reset_options) {
    // Do something before reset
}, 10, 1);
```

#### `dci_after_reset`

Fired after resetting the site.

```php
add_action('dci_after_reset', function($reset_options) {
    // Do something after reset
}, 10, 1);
```

#### `dci_before_backup`

Fired before creating a backup.

```php
add_action('dci_before_backup', function($backup_options) {
    // Do something before backup
}, 10, 1);
```

#### `dci_after_backup`

Fired after creating a backup.

```php
add_action('dci_after_backup', function($backup_options, $backup_file) {
    // Do something after backup
}, 10, 2);
```

#### `dci_before_restore`

Fired before restoring a backup.

```php
add_action('dci_before_restore', function($backup_file) {
    // Do something before restore
}, 10, 1);
```

#### `dci_after_restore`

Fired after restoring a backup.

```php
add_action('dci_after_restore', function($backup_file) {
    // Do something after restore
}, 10, 1);
```

### Filters

#### `dci_demo_packages`

Filter the list of demo packages.

```php
add_filter('dci_demo_packages', function($packages) {
    // Modify packages
    return $packages;
});
```

#### `dci_import_options`

Filter the import options.

```php
add_filter('dci_import_options', function($options, $demo_id) {
    // Modify options
    return $options;
}, 10, 2);
```

#### `dci_reset_options`

Filter the reset options.

```php
add_filter('dci_reset_options', function($options) {
    // Modify options
    return $options;
});
```

#### `dci_backup_options`

Filter the backup options.

```php
add_filter('dci_backup_options', function($options) {
    // Modify options
    return $options;
});
```

## Theme Integration

### Creating a Theme Integration

To integrate your theme with the Demo Content Importer, create a new class that extends the importer's functionality:

```php
class My_Theme_Demo_Importer_Integration {
    protected $importer;
    
    public function __construct() {
        $this->importer = Demo_Content_Importer::get_instance();
        
        // Register hooks
        add_action('after_setup_theme', array($this, 'register_demo_packages'));
        add_filter('dci_import_options', array($this, 'filter_import_options'), 10, 2);
        add_action('dci_after_import', array($this, 'after_import_setup'), 10, 2);
    }
    
    public function register_demo_packages() {
        $this->importer->register_demo_package(array(
            'id' => 'my-theme-demo',
            'name' => 'My Theme Demo',
            'description' => 'Demo content for My Theme',
            'screenshot' => get_template_directory_uri() . '/screenshot.png',
            'preview_url' => 'https://demo.example.com',
            'config_file' => get_template_directory() . '/demo/config.json',
        ));
    }
    
    public function filter_import_options($options, $demo_id) {
        if ($demo_id === 'my-theme-demo') {
            // Modify options for this demo
            $options['setup_homepage'] = true;
        }
        return $options;
    }
    
    public function after_import_setup($demo_id, $import_options) {
        if ($demo_id === 'my-theme-demo') {
            // Perform additional setup
            set_theme_mod('my_theme_option', 'value');
        }
    }
}

// Initialize the integration
new My_Theme_Demo_Importer_Integration();
```

## Best Practices

### Demo Content Creation

1. **Use realistic content** - Create demo content that represents real-world use cases
2. **Optimize images** - Compress images to reduce file size
3. **Use placeholder services** - Use services like [Lorem Picsum](https://picsum.photos/) for placeholder images
4. **Include all content types** - Include posts, pages, menus, widgets, etc.
5. **Test thoroughly** - Test the demo content on multiple environments

### Performance Optimization

1. **Chunk large imports** - Split large imports into smaller chunks
2. **Optimize database queries** - Minimize database queries during import
3. **Use transients** - Cache data using transients
4. **Batch processing** - Use batch processing for large imports
5. **Background processing** - Use background processing for time-consuming tasks

### Security Considerations

1. **Validate input** - Validate and sanitize all input
2. **Check permissions** - Check user capabilities before performing actions
3. **Secure file operations** - Use secure file operations
4. **Prevent direct access** - Prevent direct access to PHP files
5. **Use nonces** - Use nonces for form submissions

## Extending the Importer

### Adding Custom Import Types

To add a custom import type, create a new class that handles the import process:

```php
class My_Custom_Importer {
    public function import($file, $options = array()) {
        // Import logic here
        return true;
    }
}

// Register the custom importer
add_filter('dci_import_types', function($types) {
    $types['my_custom_type'] = array(
        'name' => 'My Custom Type',
        'description' => 'Import my custom data',
        'class' => 'My_Custom_Importer',
        'method' => 'import',
    );
    return $types;
});
```

### Adding Custom Admin Pages

To add a custom admin page to the importer, use the `dci_admin_tabs` filter:

```php
add_filter('dci_admin_tabs', function($tabs) {
    $tabs['my_tab'] = array(
        'name' => 'My Tab',
        'callback' => 'my_tab_callback',
    );
    return $tabs;
});

function my_tab_callback() {
    // Tab content here
    echo '<h2>My Custom Tab</h2>';
    echo '<p>This is my custom tab content.</p>';
}
```

## Troubleshooting

### Common Development Issues

1. **Memory limits** - Increase memory limits for large imports
2. **Timeout issues** - Use background processing for time-consuming tasks
3. **Plugin conflicts** - Deactivate conflicting plugins during import
4. **Theme compatibility** - Test with multiple themes
5. **Server limitations** - Check server limitations (file upload size, execution time, etc.)

### Debugging

1. **Enable logging** - Use the logger to track the import process
2. **Check error logs** - Check PHP error logs for errors
3. **Use WP_DEBUG** - Enable WP_DEBUG in wp-config.php
4. **Step-by-step testing** - Test each component separately
5. **Use var_dump and die** - Use var_dump() and die() for debugging

## Conclusion

This developer guide provides a comprehensive overview of the Demo Content Importer's architecture, API, hooks, filters, and best practices. By following these guidelines, you can extend and customize the importer to meet your specific needs.

For additional support, please contact our developer support team at dev-support@demo-content-importer.com.