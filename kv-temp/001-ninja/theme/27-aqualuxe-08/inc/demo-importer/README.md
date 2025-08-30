# AquaLuxe Demo Content Importer

This module provides a safe and secure way to import demo content for the AquaLuxe WordPress theme. It allows users to quickly set up their site with sample content, including posts, pages, products, and theme settings.

## Features

- **Selective Import**: Choose which content types to import
- **Security Measures**: Nonce verification, capability checks, and data sanitization
- **Error Handling**: Comprehensive logging and error reporting
- **Media Management**: Secure handling of demo images with proper attribution
- **Admin Interface**: User-friendly interface for importing demo content
- **Progress Tracking**: Real-time progress updates during import

## File Structure

```
inc/demo-importer/
├── class-aqualuxe-demo-importer.php       # Main importer class with admin UI
├── class-aqualuxe-demo-content-processor.php  # Content processing class
├── css/
│   └── demo-importer.css                  # Styles for the admin interface
├── js/
│   └── demo-importer.js                   # JavaScript for the admin interface
├── demo-content/                          # Demo content files
│   ├── posts.json                         # Sample blog posts
│   ├── pages.json                         # Sample pages
│   ├── services.json                      # Sample services
│   ├── care_guides.json                   # Sample care guides
│   ├── auctions.json                      # Sample auctions
│   ├── products.json                      # Sample WooCommerce products
│   ├── settings.json                      # Theme settings
│   ├── widgets.json                       # Widget configurations
│   ├── menus.json                         # Menu structures
│   └── images/                            # Demo images
│       └── README.md                      # Image guidelines and attribution
├── demo-importer.php                      # Main integration file
└── README.md                              # Documentation
```

## Usage

### Admin Interface

1. Navigate to **Appearance > Import Demo Content** in the WordPress admin
2. Select the content types you want to import
3. Click "Import Demo Content"
4. Wait for the import process to complete
5. Review the import log for any errors or warnings

### Programmatic Usage

```php
// Get the demo content processor instance
$demo_processor = AquaLuxe_Demo_Content_Processor::get_instance();

// Import specific content types
$demo_processor->import_posts();
$demo_processor->import_pages();
$demo_processor->import_products();
```

## Security Measures

The demo importer includes several security measures:

1. **Nonce Verification**: All AJAX requests are verified using WordPress nonces
2. **Capability Checks**: Only users with the `manage_options` capability can import demo content
3. **Data Sanitization**: All imported data is sanitized before being inserted into the database
4. **Rate Limiting**: Import operations are limited to prevent abuse
5. **Error Logging**: Comprehensive logging of all import operations

## Adding Custom Demo Content

To add custom demo content:

1. Create a JSON file in the `demo-content` directory
2. Follow the structure of existing demo content files
3. Add any required images to the `demo-content/images` directory
4. Update the importer class to handle the new content type

Example JSON structure for custom content:

```json
[
    {
        "demo_id": "custom_item_1",
        "post_title": "Custom Item Title",
        "post_content": "Custom item content...",
        "post_excerpt": "Custom item excerpt...",
        "post_status": "publish",
        "featured_image": "custom-image.jpg",
        "meta": {
            "_custom_field_1": "Custom value 1",
            "_custom_field_2": "Custom value 2"
        }
    }
]
```

## Hooks and Filters

The demo importer provides several hooks and filters for customization:

### Actions

- `aqualuxe_before_demo_import`: Fired before the import process begins
- `aqualuxe_after_demo_import`: Fired after the import process completes
- `aqualuxe_before_import_{content_type}`: Fired before importing a specific content type
- `aqualuxe_after_import_{content_type}`: Fired after importing a specific content type

### Filters

- `aqualuxe_demo_content_types`: Filter the available content types
- `aqualuxe_demo_content_data_{content_type}`: Filter the demo content data for a specific type
- `aqualuxe_demo_import_options`: Filter the import options

## Troubleshooting

### Common Issues

1. **Import fails with an error**: Check the error log for details. Common causes include insufficient permissions, timeout issues, or missing dependencies.

2. **Images not importing**: Ensure the images exist in the `demo-content/images` directory and are referenced correctly in the JSON files.

3. **WooCommerce products not importing**: Verify that WooCommerce is active and properly configured.

4. **Memory limit exceeded**: Increase the PHP memory limit in your wp-config.php file.

### Debug Mode

To enable debug mode, add the following to your wp-config.php file:

```php
define('AQUALUXE_DEMO_IMPORTER_DEBUG', true);
```

This will provide more detailed logging during the import process.

## Credits

- Demo images sourced from [Unsplash](https://unsplash.com), [Pexels](https://pexels.com), and [Pixabay](https://pixabay.com)
- Icons by [FontAwesome](https://fontawesome.com)
- JavaScript libraries: jQuery

## License

This module is part of the AquaLuxe theme and is licensed under the GNU General Public License v2 or later.