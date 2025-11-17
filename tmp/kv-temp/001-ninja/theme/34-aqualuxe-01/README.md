# AquaLuxe WordPress Theme

AquaLuxe is a premium WordPress + WooCommerce theme designed specifically for luxury aquatic retail businesses. The theme features a modern, elegant design with full WooCommerce integration and advanced functionality.

![AquaLuxe Theme](screenshot.png)

## Features

- **Modern Design**: Clean, elegant design with attention to detail and luxury aesthetics
- **Fully Responsive**: Optimized for all devices from mobile phones to large desktop screens
- **WooCommerce Integration**: Seamless integration with WooCommerce for a premium shopping experience
- **Advanced Product Filtering**: Sophisticated filtering system with AJAX support, multiple layouts, and accessibility features
- **Schema.org Markup**: Built-in structured data for better search engine visibility and rich snippets
- **Accessibility Compliant**: WCAG 2.1 AA compliant with keyboard navigation, screen reader support, and customizable accessibility options
- **Tailwind CSS**: Built with Tailwind CSS for modern, utility-first styling
- **Dark Mode**: Built-in dark mode toggle with user preference persistence
- **Custom Page Templates**: Specialized templates for homepage, about, contact, and more
- **Advanced Product Features**: Quick view, filtering, wishlist, and comparison functionality
- **Performance Optimized**: Fast loading times and optimized assets
- **Multilingual Ready**: Full support for translation and multilingual plugins
- **SEO Friendly**: Structured data and best practices for search engine optimization
- **Demo Content**: One-click demo content import with sample products and settings

## Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- WooCommerce 6.0 or higher (for e-commerce functionality)
- Modern browser support (Chrome, Firefox, Safari, Edge)

## Installation

1. **Upload the theme**
   - Navigate to Appearance > Themes > Add New > Upload Theme
   - Choose the aqualuxe.zip file and click "Install Now"
   - Activate the theme after installation

2. **Install required plugins**
   - After activation, you'll be prompted to install recommended plugins
   - We recommend installing WooCommerce, Elementor, and Contact Form 7

3. **Import demo content** (optional)
   - Go to Appearance > Demo Import
   - Choose what to import: WordPress Content, Customizer Settings, Widgets, WooCommerce Settings, Products
   - Click "Import All" to set up your site like our demo

4. **Configure theme settings**
   - Navigate to Appearance > Customize to configure theme options
   - Set up your logo, colors, typography, and other settings

## Theme Structure

```
aqualuxe/
├── assets/
│   ├── dist/           # Compiled assets (CSS, JS, images)
│   └── src/            # Source files (SCSS, JS, images)
├── inc/                # Theme functionality
│   ├── core.php        # Core theme functions
│   ├── customizer.php  # Theme customizer options
│   ├── schema/         # Schema.org implementation
│   ├── woocommerce/    # WooCommerce integration
│   │   └── filtering/  # Product filtering system
│   └── ...
├── template-parts/     # Reusable template parts
├── woocommerce/        # WooCommerce template overrides
├── functions.php       # Theme functions and setup
├── index.php           # Main template file
└── style.css           # Theme information
```

## Development

### Prerequisites

- Node.js 14.x or higher
- npm 7.x or higher

### Setup Development Environment

1. Clone the repository
   ```
   git clone https://github.com/ninjatech/aqualuxe.git
   ```

2. Install dependencies
   ```
   cd aqualuxe
   npm install
   ```

3. Start development server
   ```
   npm run dev
   ```

4. Build for production
   ```
   npm run build
   ```

### Customization

#### Tailwind Configuration

The theme uses Tailwind CSS for styling. You can customize the Tailwind configuration in `tailwind.config.js`.

#### JavaScript Modules

The theme's JavaScript is organized in modules located in `assets/src/js/modules/`. Each module handles specific functionality.

#### WooCommerce Templates

WooCommerce templates are overridden in the `woocommerce/` directory. You can customize these templates to match your design requirements.

#### Advanced Product Filtering

The product filtering system is implemented in `inc/woocommerce/filtering/`. The main class is `AquaLuxe_Product_Filter` in `class-aqualuxe-product-filter.php`. Accessibility enhancements are in `class-aqualuxe-product-filter-a11y.php`.

#### Schema.org Implementation

Schema.org structured data is implemented in `inc/schema/`. The main class is `AquaLuxe_Schema` in `class-aqualuxe-schema.php`.

### Hooks and Filters

The theme provides various hooks and filters for extending functionality:

- `aqualuxe_before_header`: Action before header
- `aqualuxe_after_header`: Action after header
- `aqualuxe_before_footer`: Action before footer
- `aqualuxe_after_footer`: Action after footer
- `aqualuxe_sidebar`: Action for sidebar content
- `aqualuxe_filter_products`: Filter for product query
- `aqualuxe_schema_data`: Filter for schema.org data

## Testing and Quality Assurance

The theme includes comprehensive testing and quality assurance tools to ensure the highest quality standards:

### Testing Tools

- **Browser Compatibility Testing**: Test the theme across Chrome, Firefox, Safari, and Edge
- **Responsive Design Testing**: Verify layout and functionality across various screen sizes
- **Performance Testing**: Measure Core Web Vitals and optimize performance
- **Accessibility Testing**: Ensure WCAG 2.1 AA compliance
- **Validation Testing**: Validate HTML, CSS, and JavaScript
- **Schema.org Testing**: Verify structured data implementation

### Code Audit Tools

- **PHP Code Audit**: Review PHP code for standards compliance, security, etc.
- **JavaScript Code Audit**: Ensure JavaScript follows best practices
- **CSS Code Audit**: Validate CSS for optimization and standards compliance
- **Security Audit**: Comprehensive security review

### Running Tests

The testing tools are located in the `outputs/` directory:

```
./run-tests.sh
```

This script provides a menu to run individual tests or all tests at once. After running tests, you can generate a final report:

```
./generate-report.sh
```

## Support

For theme support, please contact:

- Email: support@aqualuxe.example.com
- Website: https://aqualuxe.example.com/support
- Documentation: https://aqualuxe.example.com/docs

## License

This theme is licensed under the GNU General Public License v2 or later.

## Credits

- Tailwind CSS: https://tailwindcss.com/
- Alpine.js: https://alpinejs.dev/
- noUiSlider: https://refreshless.com/nouislider/
- Font Awesome: https://fontawesome.com/
- Google Fonts: https://fonts.google.com/

## Changelog

### Version 1.1.0 (2025-08-15)
- Added advanced product filtering system with AJAX support
- Implemented schema.org markup for better SEO
- Added comprehensive accessibility features (WCAG 2.1 AA compliant)
- Created demo content importer
- Added user guide documentation
- Added testing and quality assurance tools

### Version 1.0.0 (2025-08-01)
- Initial release

---

Developed by NinjaTech AI | https://ninjatech.ai