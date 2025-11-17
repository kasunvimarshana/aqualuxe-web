# AquaLuxe WordPress Theme

![AquaLuxe Screenshot](assets/screenshot.png)

A modern, modular, and elegant WordPress theme designed for aquatic businesses, spas, and wellness centers. AquaLuxe is built with performance, flexibility, and ease of use in mind, leveraging modern web technologies.

## Features

- **Modern Design**: Clean, elegant, and fully responsive design built with Tailwind CSS.
- **Dark Mode**: Switch between light and dark modes for comfortable viewing.
- **WooCommerce Ready**: Seamless integration with WooCommerce for e-commerce functionality.
- **Custom Post Types**: Includes a 'Services' custom post type to showcase your offerings.
- **Modular Architecture**: Organized, easy-to-understand, and extendable codebase.
- **Demo Content**: Import demo content to get your site started quickly.
- **Developer Friendly**: Uses Laravel Mix for asset bundling, `npm` for package management, and is ready for `composer`.

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Node.js & npm
- (Optional) Composer for PHP dependency management

## Quick Start & Installation

1. **Clone the Repository**:
   Clone this repository into your WordPress `wp-content/themes` directory.

   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git aqualuxe
   ```

2. **Install Dependencies**:
   Navigate to the theme directory and install the necessary Node.js packages.

   ```bash
   cd wp-content/themes/aqualuxe
   npm install
   ```

   _Note: If you encounter peer dependency issues, you may need to run `npm install --legacy-peer-deps`._

3. **Activate the Theme**:
   In your WordPress admin dashboard, go to `Appearance > Themes`, find "AquaLuxe", and click "Activate".

4. **Import Demo Content (Optional)**:
   To get a feel for the theme with pre-populated content:
   a. Go to `Tools > Import` in the WordPress admin panel.
   b. If the "WordPress" importer is not installed, you will be prompted to install it.
   c. Once installed, run the importer.
   d. Upload the `demo-content/data/content.xml` file from the theme directory.
   e. Follow the prompts to assign authors and import attachments.

## Development

The theme uses Laravel Mix as a build tool to compile assets and Tailwind CSS for styling.

### Build Commands

- `npm run watch`: Compiles assets and watches for changes. This is the recommended command for active development.
- `npm run dev`: Compiles assets for development once.
- `npm run prod`: Compiles and minifies assets for production.

### Directory Structure

- `/assets`: Contains all frontend assets.
  - `/src`: Raw, uncompiled assets (JavaScript, SCSS, images).
  - `/dist`: Compiled assets (automatically generated).
- `/demo-content`: Demo content files for import.
- `/inc`: Core PHP classes and functions.
  - `class-aqualuxe-theme.php`: Main theme setup class.
  - `custom-post-types.php`: CPT registrations.
  - `woocommerce-functions.php`: WooCommerce-related hooks and functions.
- `/languages`: Translation files (.pot).
- `/templates`: Reusable template parts (e.g., `content-page.php`).
- `/woocommerce`: Overridden WooCommerce templates.
- `functions.php`: The main entry point for the theme's PHP logic.
- `tailwind.config.js`: Configuration for Tailwind CSS.
- `webpack.mix.js`: Laravel Mix configuration.

## Contributing

Contributions are welcome! Please feel free to submit a pull request.

## License

This project is licensed under the GPL-2.0-or-later. See the `license.txt` file for details.
