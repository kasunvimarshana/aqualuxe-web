# AquaLuxe WordPress Theme

[![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/kasunvimarshana/aqualuxe)
[![WordPress](https://img.shields.io/badge/WordPress-6.4+-green.svg)](https://wordpress.org)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-8.3+-purple.svg)](https://woocommerce.com)
[![License](https://img.shields.io/badge/license-GPL--2.0+-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

AquaLuxe is a premium WordPress + WooCommerce child theme designed specifically for ornamental fish businesses. Built with luxury, elegance, and the beauty of aquatic life in mind, this theme provides a complete e-commerce solution for fish retailers and aquarium enthusiasts.

## 🐠 Features

### Core Functionality
- **Full WooCommerce Integration** - Complete e-commerce functionality
- **Responsive Design** - Works perfectly on all devices
- **Child Theme Architecture** - Built on Storefront for maximum compatibility
- **SEO Optimized** - Schema markup and clean code structure
- **Performance Focused** - Optimized for speed and efficiency
- **Security Enhanced** - Built with security best practices

### Theme Features
- **Custom Hero Section** - Eye-catching homepage banner
- **Product Quick View** - AJAX product previews
- **Custom Product Fields** - Fish origin, size, and care level
- **Advanced Product Filters** - Enhanced shopping experience
- **Wishlist Functionality** - Customer favorite products
- **Mobile-First Design** - Progressive enhancement approach

### Technical Features
- **SOLID Principles** - Clean, maintainable code
- **Object-Oriented PHP** - Modern development practices
- **Custom Post Types** - Extensible content structure
- **Widget Areas** - Flexible content placement
- **Theme Customizer** - Easy customization options
- **Demo Content Import** - Quick setup with sample data

## 🚀 Quick Start

### Prerequisites
- WordPress 5.0 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher
- Storefront parent theme

### Installation

1. **Download and Install Storefront Theme**
   ```bash
   # Install via WordPress admin or upload manually
   ```

2. **Install AquaLuxe Child Theme**
   ```bash
   # Upload the aqualuxe folder to wp-content/themes/
   # Or install via WordPress admin
   ```

3. **Activate the Theme**
   - Go to Appearance → Themes
   - Activate "AquaLuxe"

4. **Import Demo Content** (Optional)
   - Go to Appearance → Demo Import
   - Click "Import Demo Content"

## 🐳 Docker Development Setup

For a complete development environment with all services:

```bash
# Clone the repository
git clone https://github.com/kasunvimarshana/aqualuxe.git
cd aqualuxe

# Start all services
docker-compose up -d

# Access the services
# WordPress: http://localhost:8080
# phpMyAdmin: http://localhost:8081
# Redis Commander: http://localhost:8082
# MailHog: http://localhost:8025
```

### Services Included
- **WordPress** - Main application (port 8080)
- **MySQL 8.0** - Database server (port 3306)
- **phpMyAdmin** - Database management (port 8081)
- **Redis** - Caching server (port 6379)
- **Redis Commander** - Redis management (port 8082)
- **MailHog** - Email testing (port 8025)
- **Nginx** - Reverse proxy (optional)

### Docker Commands
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f wordpress

# Restart a service
docker-compose restart wordpress

# Access WordPress container
docker exec -it aqualuxe_wordpress bash

# Database backup
docker exec aqualuxe_mysql mysqldump -u root -p aqualuxe_db > backup.sql
```

## 🎨 Customization

### Theme Colors
The theme uses CSS custom properties for easy color customization:

```css
:root {
  --aqualuxe-primary: #0066cc;      /* Primary brand color */
  --aqualuxe-secondary: #00ccaa;    /* Secondary color */
  --aqualuxe-accent: #ff6600;       /* Accent color */
  --aqualuxe-gold: #ffd700;         /* Gold highlights */
}
```

### Customizer Options
Access via Appearance → Customize → AquaLuxe Settings:

- **Colors** - Primary and secondary colors
- **Typography** - Font selections
- **Hero Section** - Homepage banner content
- **Header Settings** - Logo and navigation

### Custom Fields
Products include specialized fields for fish:

- **Fish Origin** - Country of origin
- **Adult Size** - Maximum size in centimeters
- **Care Level** - Beginner, Intermediate, or Expert

## 🔧 Development

### File Structure
```
aqualuxe/
├── style.css                 # Main stylesheet with theme header
├── functions.php             # Theme functionality and hooks
├── template-parts/           # Reusable template components
├── woocommerce/             # WooCommerce template overrides
├── assets/                  # CSS, JS, and image files
├── inc/                     # PHP includes and classes
├── languages/               # Translation files
└── docker-compose.yml       # Docker development setup
```

### PHP Classes
- `AquaLuxe_Theme` - Main theme class
- `AquaLuxe_Security` - Security enhancements
- `AquaLuxe_Demo_Content` - Demo import functionality
- `AquaLuxe_SEO` - SEO optimizations

### JavaScript Modules
- `main.js` - Core theme functionality
- `woocommerce.js` - E-commerce enhancements

### Hooks and Filters
The theme uses WordPress hooks extensively:

```php
// Example: Add custom product fields
add_action('woocommerce_product_options_general_product_data', 'aqualuxe_add_custom_product_fields');
add_action('woocommerce_process_product_meta', 'aqualuxe_save_custom_product_fields');
```

## 🛡️ Security Features

### Built-in Security
- Input sanitization and validation
- Nonce verification for forms
- SQL injection prevention
- XSS protection
- CSRF protection
- Rate limiting
- Security headers

### Security Functions
```php
// Validate and sanitize input
$clean_input = AquaLuxe_Security::validate_input($input, 'email');

// Generate secure tokens
$token = AquaLuxe_Security::generate_secure_token();

// Check rate limits
$allowed = AquaLuxe_Security::check_rate_limit($user_ip);
```

## ⚡ Performance Optimization

### Built-in Optimizations
- Minified CSS and JavaScript
- Image lazy loading
- Database query optimization
- Redis caching support
- Gzip compression
- CDN ready

### Performance Best Practices
- Use child theme for customizations
- Optimize images before upload
- Enable caching plugins
- Use a CDN for static assets
- Monitor database performance

## 🌐 SEO Features

### Schema Markup
- Product schema for e-commerce
- Organization schema
- Website schema
- Breadcrumb schema

### SEO Best Practices
- Semantic HTML5 structure
- Clean URL structure
- Meta tag optimization
- Image alt attributes
- Sitemap compatibility

## 🧪 Testing

### Browser Testing
- Chrome/Chromium
- Firefox
- Safari
- Edge
- Mobile browsers

### Compatibility Testing
- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4 - 8.2
- MySQL 5.7+

### Performance Testing
- GTMetrix
- Google PageSpeed Insights
- Pingdom Tools
- WebPageTest

## 🌍 Internationalization

The theme is translation-ready with:
- Text domain: `aqualuxe`
- POT file included
- RTL language support
- Number and date localization

### Adding Translations
1. Use Poedit or similar tool
2. Create `.po` and `.mo` files
3. Place in `/languages/` directory

## 📝 Changelog

### Version 1.0.0 (2024-01-01)
- Initial release
- Full WooCommerce integration
- Responsive design implementation
- Security enhancements
- Performance optimizations
- Demo content system
- Docker development environment

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### Coding Standards
- Follow WordPress Coding Standards
- Use SOLID principles
- Write clean, documented code
- Include unit tests where appropriate

## 📞 Support

For support and questions:

- **Documentation**: [Theme Documentation](https://github.com/kasunvimarshana/aqualuxe/wiki)
- **Issues**: [GitHub Issues](https://github.com/kasunvimarshana/aqualuxe/issues)
- **Email**: support@aqualuxe.com

## 📄 License

This theme is licensed under the GPL v2 or later.

```
AquaLuxe WordPress Theme
Copyright (C) 2024 Kasun Vimarshana

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🙏 Credits

- **Parent Theme**: [Storefront](https://woocommerce.com/storefront/) by WooCommerce
- **Icons**: Font Awesome
- **Fonts**: Google Fonts
- **Images**: Pexels (for demo content)

---

**AquaLuxe** - *Luxury and elegance of water life, perfect for premium ornamental fish branding.*

Made with 💙 by [Kasun Vimarshana](https://github.com/kasunvimarshana)