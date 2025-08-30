# AquaLuxe WordPress Theme

A premium, production-ready WordPress theme designed for aquatic businesses. Features a modern, responsive design with WooCommerce integration, dark mode support, and comprehensive customization options.

## 🌊 Features

### Core Features
- **Modern Design**: Clean, aquatic-themed design with gradient backgrounds
- **Responsive**: Mobile-first approach, fully responsive across all devices
- **Dark Mode**: Built-in dark/light mode toggle with system preference detection
- **Performance Optimized**: Fast loading with critical CSS, lazy loading, and asset optimization
- **SEO Ready**: Schema markup, Open Graph, Twitter Cards, and SEO-friendly structure
- **Accessibility**: WCAG 2.1 AA compliant with proper ARIA labels and keyboard navigation

### WordPress Integration
- **WordPress 6.0+** compatible
- **Gutenberg** ready with custom block styles
- **Custom Post Types**: Services, Events, Testimonials
- **Widget Areas**: Multiple sidebar and footer widget areas
- **Theme Customizer**: Extensive customization options
- **Translation Ready**: Full i18n support with POT files

### WooCommerce Integration
- **Full WooCommerce Support**: Shop, product pages, cart, checkout
- **Product Gallery**: Zoom, lightbox, and slider functionality
- **Quick View**: AJAX product quick view modal
- **Wishlist**: Built-in wishlist functionality
- **Custom Product Fields**: Care instructions, compatibility info
- **Product Badges**: Sale, new, custom badges
- **Optimized Performance**: Conditional script loading

### Technical Features
- **Modern Build System**: Laravel Mix with Webpack
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Minimal JavaScript framework
- **SASS/SCSS**: Modular stylesheet architecture
- **ES6+ JavaScript**: Modern JavaScript with Babel compilation
- **Asset Optimization**: Minification, cache busting, tree shaking

## 🚀 Installation

### Requirements
- WordPress 6.0 or higher
- PHP 8.0 or higher
- Node.js 16+ and npm (for development)

### Quick Installation
1. Download the theme files
2. Upload to `/wp-content/themes/aqualuxe/`
3. Activate the theme in WordPress admin
4. Configure theme options in Customizer

### Development Installation
1. Clone or download the theme
2. Navigate to theme directory
3. Install dependencies:
   ```bash
   npm install
   ```
4. Build assets:
   ```bash
   npm run dev        # Development build
   npm run production # Production build
   npm run watch      # Watch for changes
   ```

## 🛠️ Development

### File Structure
```
aqualuxe/
├── assets/
│   ├── src/
│   │   ├── js/
│   │   │   └── app.js
│   │   └── sass/
│   │       └── app.scss
│   └── dist/          # Built assets
├── inc/
│   ├── classes/       # PHP classes
│   ├── customizer.php # Theme customizer
│   └── template-functions.php
├── templates/
│   └── content/       # Template parts
├── woocommerce/       # WooCommerce templates
├── functions.php      # Main theme file
├── style.css         # Theme header
├── package.json      # Node dependencies
├── webpack.mix.js    # Build configuration
└── tailwind.config.js # Tailwind configuration
```

### Build Commands
- `npm run dev` - Development build with source maps
- `npm run production` - Production build with minification
- `npm run watch` - Watch files for changes
- `npm run hot` - Hot module replacement for development

### CSS Framework
The theme uses Tailwind CSS with custom aquatic color palette:
- **Primary**: Sky blue shades
- **Secondary**: Teal/aqua shades
- **Accent**: Ocean blue variants
- **Dark Mode**: Automatic dark variants

### JavaScript Framework
- **Alpine.js** for reactive components
- **Vanilla JS** for core functionality
- **jQuery** for WordPress compatibility

## 🎨 Customization

### Theme Customizer Options
- **Site Identity**: Logo, colors, typography
- **Layout Options**: Sidebar positions, container widths
- **WooCommerce**: Shop layouts, product display options
- **Performance**: Enable/disable features
- **Social Media**: Social network links

### Color Customization
Colors are defined in `tailwind.config.js`:
```javascript
colors: {
  primary: { /* sky blues */ },
  secondary: { /* teals */ },
  accent: { /* ocean blues */ }
}
```

### Adding Custom Styles
1. Add styles to `assets/src/sass/components/`
2. Import in `assets/src/sass/app.scss`
3. Run build command

### Custom Post Types
The theme includes custom post types:
- **Services**: Aquarium services
- **Events**: Aquatic events and workshops
- **Testimonials**: Customer reviews

## 🛒 WooCommerce Setup

### Recommended Settings
1. **Shop Page**: Create a page called "Shop"
2. **Product Categories**: Set up aquatic categories
3. **Payment Methods**: Configure payment gateways
4. **Shipping**: Set up shipping zones and methods

### Custom Features
- **Product Inquiry**: Contact form for product questions
- **Care Instructions**: Product care information tab
- **Compatibility**: Product compatibility information
- **Quick View**: AJAX product preview modal

## 📱 Responsive Design

### Breakpoints (Tailwind CSS)
- **sm**: 640px and up
- **md**: 768px and up  
- **lg**: 1024px and up
- **xl**: 1280px and up
- **2xl**: 1536px and up

### Mobile Features
- Touch-friendly navigation
- Optimized image sizes
- Swipeable galleries
- Mobile-specific layouts

## ⚡ Performance

### Optimization Features
- **Critical CSS**: Inline above-the-fold styles
- **Lazy Loading**: Images and videos
- **Asset Minification**: CSS and JavaScript
- **Cache Busting**: Automated asset versioning
- **Font Optimization**: Efficient web font loading

### Performance Tips
1. Use a caching plugin
2. Optimize images before upload
3. Enable CDN for assets
4. Configure object caching

## 🔧 Configuration

### Required Plugins
- **WooCommerce** (for e-commerce functionality)
- **Advanced Custom Fields** (optional, for custom fields)
- **Yoast SEO** (recommended for SEO)

### Recommended Plugins
- **WP Rocket** (caching)
- **Smush** (image optimization)
- **UpdraftPlus** (backups)
- **Wordfence** (security)

## 🌐 Multilingual Support

### Translation
- **POT file included** for easy translation
- **WPML compatible**
- **Polylang compatible**
- **Translation strings** properly escaped

### RTL Support
- Full RTL (right-to-left) language support
- Automatic direction detection
- RTL-optimized layouts

## 🔒 Security

### Security Features
- **Nonce verification** for AJAX requests
- **Data sanitization** for all inputs
- **SQL injection protection**
- **XSS prevention**
- **CSRF protection**

### Security Headers
- Content Security Policy
- X-Frame-Options
- X-Content-Type-Options
- Referrer Policy

## 📊 SEO Features

### Built-in SEO
- **Schema.org markup** for structured data
- **Open Graph** meta tags
- **Twitter Cards** support
- **XML sitemap** compatible
- **Breadcrumb navigation**

### Performance SEO
- **Fast loading times**
- **Mobile-first indexing** ready
- **Core Web Vitals** optimized
- **AMP compatibility** (with plugin)

## 🐛 Troubleshooting

### Common Issues

**Assets not loading:**
- Run `npm run production` to build assets
- Check file permissions
- Verify webpack.mix.js configuration

**WooCommerce styling issues:**
- Ensure WooCommerce templates are up to date
- Check for plugin conflicts
- Verify theme WooCommerce support

**Performance issues:**
- Enable caching
- Optimize images
- Use production build of assets

## 📝 Changelog

### Version 1.0.0
- Initial release
- Complete WooCommerce integration
- Dark mode support
- Mobile responsive design
- Performance optimizations

## 🤝 Support

### Documentation
- Theme documentation included
- Code comments for developers
- Customization examples

### Support Channels
- Theme support forum
- Developer documentation
- Video tutorials

## 📄 License

This theme is licensed under the GPL v2 or later.

### Credits
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Minimal JavaScript framework
- **Laravel Mix**: Asset compilation
- **Font Awesome**: Icon library
- **Unsplash**: Demo images

## 🔄 Updates

### Automatic Updates
- WordPress admin update notifications
- Version checking system
- Backup reminder before updates

### Manual Updates
1. Download latest version
2. Backup current theme
3. Upload new theme files
4. Run `npm run production` if developing

---

**AquaLuxe Theme** - Dive into the future of aquatic web design! 🌊
