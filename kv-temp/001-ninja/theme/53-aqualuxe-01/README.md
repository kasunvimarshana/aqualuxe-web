# AquaLuxe WordPress Theme

AquaLuxe is a premium WordPress theme designed specifically for aquatic businesses with WooCommerce integration. It's a modular, multilingual, multicurrency, mobile-first, and fully responsive theme that provides a beautiful and functional online presence for businesses in the aquatic industry.

## Features

### Modular Architecture
- **Plug-and-Play Modules**: Easily enable or disable features as needed
- **Custom Module Development**: Extend the theme with your own modules
- **Module Dependencies**: Smart handling of module requirements

### Multilingual Support
- **WPML Compatible**: Full integration with WPML for multilingual sites
- **RTL Support**: Perfect display for right-to-left languages
- **Translation Ready**: All strings are properly prepared for translation

### WooCommerce Integration
- **Custom Product Templates**: Beautifully styled product pages
- **Enhanced Shop Features**: Advanced filtering, sorting, and display options
- **Cart & Checkout Optimization**: Streamlined purchasing process

### Multicurrency Support
- **Currency Switcher**: Allow customers to shop in their preferred currency
- **Real-time Exchange Rates**: Automatic currency conversion
- **Price Format Localization**: Proper display of prices in different formats

### Mobile-First Design
- **Responsive Layout**: Perfect display on all device sizes
- **Touch-Friendly Elements**: Optimized for touch interactions
- **Mobile Performance**: Fast loading on mobile networks

### Performance Optimization
- **Lazy Loading**: Images and videos load only when needed
- **Resource Preloading**: Critical assets are preloaded for faster rendering
- **Script Optimization**: Minimized and deferred JavaScript loading

### Dark Mode
- **User Preference**: Remembers user's theme preference
- **System Detection**: Can follow system dark mode setting
- **Custom Styling**: Carefully designed dark theme for all elements

### SEO Features
- **Schema Markup**: Structured data for better search visibility
- **Performance Metrics**: Optimized for Core Web Vitals
- **Breadcrumbs**: Improved site navigation and SEO

## Theme Structure

```
aqualuxe/
├── assets/
│   ├── dist/           # Compiled assets
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── src/            # Source files
│       ├── scss/
│       ├── js/
│       └── images/
├── core/               # Core theme functionality
│   ├── Admin/
│   ├── Customizer/
│   └── Setup/
├── inc/                # Helper functions and classes
│   ├── classes/
│   └── functions/
├── modules/            # Theme modules
│   ├── dark-mode/
│   ├── multilingual/
│   ├── multicurrency/
│   ├── performance/
│   ├── seo/
│   └── woocommerce/
├── templates/          # Template parts
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── sidebar.php
├── single.php
└── style.css
```

## Module System

AquaLuxe uses a modular architecture that allows you to enable or disable specific features as needed. Each module is self-contained and can be activated through the theme options.

### Available Modules

#### Dark Mode Module
Adds a toggle for users to switch between light and dark themes. The module remembers user preferences and can also follow system settings.

#### Multilingual Module
Provides integration with WPML and other translation plugins. Includes language switcher and RTL support.

#### WooCommerce Module
Enhances the WooCommerce experience with custom templates, advanced product filtering, and optimized checkout process.

#### Multicurrency Module
Allows customers to browse and shop in their preferred currency with real-time exchange rates.

#### Performance Module
Implements various performance optimizations including lazy loading, resource preloading, and script optimization.

#### SEO Module
Adds SEO features such as schema markup, breadcrumbs, and other optimizations for better search engine visibility.

## Customization

### Theme Options
AquaLuxe provides extensive customization options through the WordPress Customizer:

- **Colors**: Primary, secondary, and accent colors with light and dark variants
- **Typography**: Font family, size, weight, and line height for different elements
- **Layout**: Container width, sidebar position, header and footer layouts
- **Modules**: Enable/disable and configure individual modules

### CSS Variables
The theme uses CSS variables for easy styling. You can override these variables in your child theme or custom CSS:

```css
:root {
  --primary-color: #0ea5e9;
  --secondary-color: #1e293b;
  --accent-color: #f59e0b;
  /* and many more */
}
```

### Hooks and Filters
AquaLuxe provides numerous action hooks and filters for developers to extend and customize the theme:

```php
// Example: Add content before the footer
add_action('aqualuxe_before_footer', function() {
  echo '<div class="pre-footer">Custom content before footer</div>';
});

// Example: Modify the excerpt length
add_filter('aqualuxe_excerpt_length', function($length) {
  return 30; // Change excerpt length to 30 words
});
```

## Utility Classes

AquaLuxe includes a comprehensive set of utility classes for quick styling:

### Spacing Utilities
Control margin and padding with classes like `mt-3` (margin-top: 1rem) or `px-4` (padding-left and padding-right: 1.5rem).

### Text Utilities
Control text alignment, size, weight, and color with classes like `text-center`, `text-lg`, `font-weight-bold`, or `text-primary`.

### Display Utilities
Control how elements are displayed with classes like `d-flex`, `d-none`, or `d-md-block` (display: block on medium screens and up).

### Flex Utilities
Control flexbox layouts with classes like `flex-row`, `justify-content-between`, or `align-items-center`.

### Color Utilities
Apply colors to text, backgrounds, and borders with classes like `text-primary`, `bg-light`, or `border-accent`.

## Browser Support

AquaLuxe supports all modern browsers:

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)
- iOS Safari (latest)
- Android Chrome (latest)

## Credits

- **Font Awesome**: Icon font and CSS toolkit
- **Inter**: Primary font family
- **Playfair Display**: Heading font family
- **Unsplash**: Demo images

## License

AquaLuxe is licensed under the GNU General Public License v2 or later.

## Support

For theme support, please contact our support team at support@aqualuxe.com or visit our support forum at https://aqualuxe.com/support.