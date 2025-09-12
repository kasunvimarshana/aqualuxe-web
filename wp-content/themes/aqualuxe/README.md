# AquaLuxe WordPress Theme

**Bringing elegance to aquatic life – globally.**

A comprehensive, modular WordPress theme designed for luxury aquatic retail, following modern development practices and WordPress standards.

## 🌟 Features

### 🏗️ Architecture
- **Modular Design**: Clean separation between core functionality and feature modules
- **SOLID Principles**: Follows Single Responsibility, Open-Closed, Liskov Substitution, Interface Segregation, and Dependency Inversion principles
- **DRY (Don't Repeat Yourself)**: Minimal code duplication with reusable components
- **KISS (Keep It Simple, Stupid)**: Clean, maintainable codebase
- **PSR Standards**: Follows PHP coding standards where applicable

### 🛒 WooCommerce Integration
- **Dual-State Architecture**: Works with or without WooCommerce
- **Complete Shop Support**: Product pages, cart, checkout, account dashboard
- **Product Types**: Physical, digital, variable, and grouped products
- **Advanced Features**: Quick view, wishlist, product comparison, advanced filtering
- **Multicurrency Ready**: International commerce support
- **Optimized Checkout**: Streamlined checkout process

### 🎨 Design & User Experience
- **Mobile-First**: Responsive design optimized for all devices
- **Dark Mode**: Persistent user preference with system detection
- **Accessibility**: WCAG 2.1 AA compliant
- **Performance**: Optimized for speed and SEO
- **Modern UI**: Tailwind CSS with custom aquatic-themed design system

### 🌐 Multilingual & Internationalization
- **Translation Ready**: Full gettext support with POT files
- **RTL Support**: Right-to-left language compatibility
- **Multicurrency**: Multiple currency support for global markets
- **Cultural Adaptation**: Flexible layout and content adaptation

### 🔧 Developer Features
- **Modern Build System**: Webpack with Laravel Mix
- **Asset Optimization**: Minification, tree-shaking, and cache busting
- **Code Splitting**: Efficient loading with vendor separation
- **Hot Reloading**: Development server with live updates
- **Security**: Input sanitization, CSRF protection, and security headers

### 📦 Modules
- **Dark Mode**: Toggle with persistent preferences
- **Multilingual**: Language switching and content translation
- **Demo Importer**: One-click demo content installation
- **WooCommerce Integration**: Enhanced e-commerce functionality
- **Booking System**: Appointment and service booking
- **Events & Ticketing**: Event management and ticket sales
- **Subscriptions**: Recurring payment support
- **Marketplace**: Multi-vendor functionality

## 🚀 Installation

### Requirements
- WordPress 6.0+
- PHP 8.0+
- Node.js 16+ (for development)
- npm or yarn

### Quick Installation
1. Download the theme package
2. Upload to `/wp-content/themes/aqualuxe/`
3. Activate the theme in WordPress admin
4. Install recommended plugins (WooCommerce, etc.)
5. Import demo content (optional)

### Development Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web/wp-content/themes/aqualuxe
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start development:
   ```bash
   npm run dev
   ```

4. Build for production:
   ```bash
   npm run build
   ```

## 🔧 Configuration

### Theme Customizer
Access **Appearance > Customize** to configure:
- **Site Identity**: Logo, site title, tagline
- **Colors**: Primary, secondary, accent colors
- **Typography**: Font selections and sizing
- **Header**: Layout and navigation options
- **Footer**: Widget areas and copyright
- **Dark Mode**: Enable/disable and default settings
- **WooCommerce**: Shop layout and product display

### WooCommerce Setup
1. Install and activate WooCommerce
2. Run the WooCommerce setup wizard
3. Configure payment gateways
4. Set up shipping zones
5. Add products and test checkout

### Demo Content
Import demo content via **Appearance > Demo Importer**:
- **Basic**: Essential pages and navigation
- **Shop**: Sample products and categories
- **Blog**: Example posts and categories
- **Complete**: Full demo with all content

## 📁 File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source assets
│   │   ├── css/               # Sass/CSS files
│   │   ├── js/                # JavaScript files
│   │   ├── images/            # Source images
│   │   └── fonts/             # Font files
│   └── dist/                  # Compiled assets
├── inc/
│   ├── core/                  # Core theme functionality
│   ├── helpers/               # Helper functions
│   ├── customizer/            # Theme Customizer
│   └── admin/                 # Admin interface
├── modules/                   # Feature modules
│   ├── dark-mode/            # Dark mode functionality
│   ├── multilingual/         # Language support
│   ├── woocommerce-integration/ # WooCommerce features
│   └── demo-importer/        # Demo content importer
├── templates/
│   ├── partials/             # Template parts
│   └── woocommerce/          # WooCommerce templates
├── languages/                # Translation files
├── functions.php             # Main functions file
├── style.css                 # Theme header and basic styles
└── *.php                     # Template files
```

## 🛠️ Development

### Build Commands
- `npm run dev` - Development build with watch
- `npm run build` - Production build
- `npm run watch` - Watch for changes
- `npm run hot` - Hot module replacement
- `npm run lint` - Code linting

### Coding Standards
- **WordPress Coding Standards**: Follow WordPress PHP coding standards
- **ESLint**: JavaScript linting with WordPress configuration
- **Stylelint**: CSS/Sass linting
- **PHP_CodeSniffer**: PHP code quality checks

### Asset Management
- **Webpack**: Module bundling and optimization
- **Tailwind CSS**: Utility-first CSS framework
- **PostCSS**: CSS processing and optimization
- **Babel**: JavaScript transpilation

## 🔒 Security

### Built-in Security Features
- **Input Sanitization**: All user inputs sanitized
- **Output Escaping**: All outputs properly escaped
- **Nonce Verification**: CSRF protection on forms
- **Security Headers**: XSS, clickjacking protection
- **Login Protection**: Basic brute force protection
- **File Upload Filtering**: Restricted file types

### Security Best Practices
- Regular security updates
- Strong password requirements
- Two-factor authentication (via plugins)
- Regular backups
- SSL certificate installation
- Security plugin integration

## 🎯 Performance

### Optimization Features
- **Asset Minification**: CSS and JavaScript compression
- **Image Optimization**: WebP support and lazy loading
- **Caching**: Browser and server-side caching headers
- **Critical CSS**: Above-the-fold CSS inlining
- **Tree Shaking**: Unused code elimination
- **Code Splitting**: Efficient resource loading

### Performance Monitoring
- **Lighthouse Scores**: Target 90+ on all metrics
- **Core Web Vitals**: Optimized for Google's metrics
- **Loading Speed**: Fast initial page loads
- **Runtime Performance**: Smooth interactions

## 🌍 Multilingual Support

### Translation
- **POT File**: Complete translation template
- **RTL Support**: Right-to-left language layouts
- **Date/Time Formats**: Localized formatting
- **Currency**: Regional currency support
- **Address Formats**: International address handling

### Supported Languages
Ready for translation to any language. Includes sample translations for:
- English (default)
- Spanish
- French
- German
- Arabic (RTL example)

## 🛒 E-commerce Features

### Product Management
- **Product Types**: Simple, variable, grouped, downloadable
- **Inventory**: Stock management and tracking
- **Categories**: Hierarchical product organization
- **Attributes**: Custom product attributes
- **Reviews**: Customer review system

### Shopping Experience
- **Product Search**: Advanced search and filtering
- **Quick View**: Product preview without page reload
- **Wishlist**: Save products for later
- **Comparison**: Side-by-side product comparison
- **Related Products**: Automatic suggestions

### Checkout Process
- **Guest Checkout**: No registration required
- **Multiple Payments**: Various payment gateways
- **Shipping Options**: Multiple shipping methods
- **Tax Calculation**: Automatic tax handling
- **Order Tracking**: Customer order status

## 📱 Responsive Design

### Breakpoints
- **Mobile**: 320px - 640px
- **Tablet**: 641px - 1024px
- **Desktop**: 1025px - 1440px
- **Large Desktop**: 1441px+

### Mobile Features
- **Touch Optimized**: Large tap targets
- **Swipe Gestures**: Product galleries
- **Mobile Menu**: Collapsible navigation
- **Fast Loading**: Optimized for mobile networks

## ♿ Accessibility

### WCAG 2.1 AA Compliance
- **Keyboard Navigation**: Full keyboard support
- **Screen Readers**: Proper ARIA labels
- **Color Contrast**: Sufficient contrast ratios
- **Text Scaling**: Up to 200% text zoom
- **Focus Indicators**: Visible focus states

### Accessibility Features
- **Skip Links**: Jump to main content
- **Alt Text**: Image descriptions
- **Form Labels**: Proper form labeling
- **Error Messages**: Clear error indication
- **Language Declaration**: Proper lang attributes

## 🔧 Customization

### Theme Options
Access via **Appearance > Theme Options**:
- **Layout Settings**: Container width, spacing
- **Color Schemes**: Predefined color combinations
- **Typography**: Font pairings and sizes
- **Module Settings**: Enable/disable features
- **Advanced Options**: Developer settings

### CSS Custom Properties
The theme uses CSS custom properties for easy customization:
```css
:root {
  --color-primary: #0ea5e9;
  --color-secondary: #22c55e;
  --color-accent: #eab308;
  --font-family-heading: 'Playfair Display';
  --font-family-body: 'Inter';
}
```

### Hooks and Filters
The theme provides numerous hooks for customization:
- `aqualuxe_before_header`
- `aqualuxe_after_content`
- `aqualuxe_footer_widgets`
- `aqualuxe_product_meta`
- `aqualuxe_custom_styles`

## 🧪 Testing

### Automated Testing
- **Unit Tests**: PHPUnit for PHP functions
- **Integration Tests**: WordPress integration testing
- **E2E Tests**: Cypress for user workflows
- **Performance Tests**: Lighthouse CI
- **Accessibility Tests**: axe-core testing

### Manual Testing
- **Cross-browser**: Chrome, Firefox, Safari, Edge
- **Device Testing**: Mobile, tablet, desktop
- **Screen Readers**: NVDA, JAWS, VoiceOver
- **Performance**: PageSpeed Insights, GTmetrix

## 📈 SEO Features

### Built-in SEO
- **Semantic HTML**: Proper heading hierarchy
- **Meta Tags**: Open Graph and Twitter Cards
- **Schema Markup**: Structured data
- **XML Sitemaps**: Search engine indexing
- **Breadcrumbs**: Navigation structure
- **Page Speed**: Fast loading times

### SEO Plugin Compatibility
- **Yoast SEO**: Full compatibility
- **RankMath**: Supported features
- **All in One SEO**: Compatible
- **SEOPress**: Full support

## 🤝 Contributing

### Development Process
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

### Code Style
- Follow WordPress coding standards
- Use meaningful variable names
- Comment complex functionality
- Write unit tests for new features

## 📄 License

This theme is licensed under the GPL v2 or later.

### Third-party Assets
- **Tailwind CSS**: MIT License
- **Alpine.js**: MIT License
- **GSAP**: Commercial license required for commercial use
- **Font families**: SIL Open Font License

## 🆘 Support

### Documentation
- **User Guide**: Complete usage documentation
- **Developer Docs**: Technical implementation
- **Video Tutorials**: Step-by-step guides
- **FAQ**: Common questions and answers

### Community Support
- **GitHub Issues**: Bug reports and feature requests
- **Support Forum**: Community assistance
- **Discord**: Real-time chat support
- **Email**: Priority support for license holders

### Professional Services
- **Custom Development**: Theme customization
- **Migration Services**: Site migration assistance
- **Training**: Team training sessions
- **Maintenance**: Ongoing theme updates

---

## 🚀 Getting Started

1. **Install WordPress** (6.0 or higher)
2. **Upload the theme** to `/wp-content/themes/`
3. **Activate** the theme in WordPress admin
4. **Install WooCommerce** (optional but recommended)
5. **Import demo content** via Theme Options
6. **Customize** colors, fonts, and layout
7. **Add your content** and go live!

For detailed setup instructions, see the [Installation Guide](docs/installation.md).

---

**AquaLuxe Theme** - Bringing elegance to aquatic life, globally. 🐠✨