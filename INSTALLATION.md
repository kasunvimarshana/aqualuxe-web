# AquaLuxe WordPress Theme - Installation Guide

## Overview

AquaLuxe is a comprehensive, production-ready WordPress theme designed for luxury aquatic businesses. It features a modular architecture, Clean Architecture principles, WCAG 2.1 AA accessibility compliance, advanced SEO optimization, and WooCommerce integration.

## System Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher / MariaDB 10.3 or higher
- **Node.js**: 18.x or higher (for development)
- **npm**: 8.x or higher (for development)

## Installation Methods

### Method 1: Standard WordPress Installation

1. **Download the theme**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web
   ```

2. **Install dependencies and build assets**
   ```bash
   npm install
   npm run production
   ```

3. **Upload to WordPress**
   - Zip the theme folder
   - Upload via WordPress Admin → Appearance → Themes → Add New → Upload Theme
   - Activate the theme

### Method 2: Docker Development Environment

1. **Clone and setup**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web
   ```

2. **Start Docker environment**
   ```bash
   docker-compose up -d
   ```

3. **Install WordPress**
   ```bash
   docker-compose exec wp-cli wp core install \
     --url=http://localhost \
     --title="AquaLuxe" \
     --admin_user=admin \
     --admin_password=admin \
     --admin_email=admin@aqualuxe.local
   ```

4. **Install dependencies and build**
   ```bash
   npm install
   npm run development
   ```

### Method 3: Local Development

1. **Prerequisites**
   - Local WordPress installation (XAMPP, WAMP, MAMP, or Local by Flywheel)
   - Node.js and npm installed

2. **Setup**
   ```bash
   # Clone into your WordPress themes directory
   cd /path/to/wordpress/wp-content/themes/
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git aqualuxe
   cd aqualuxe
   
   # Install and build
   npm install
   npm run production
   ```

3. **Activate in WordPress Admin**

## Initial Configuration

### 1. Theme Activation

1. Go to **Appearance → Themes**
2. Find "AquaLuxe" and click **Activate**
3. Navigate to **Appearance → Customize** for initial setup

### 2. Required Plugins

While AquaLuxe works without plugins, these are recommended:

**Essential:**
- Contact Form 7 (for contact forms)
- Yoast SEO or RankMath (additional SEO features)

**E-commerce:**
- WooCommerce (for full e-commerce functionality)
- WooCommerce Subscriptions (for subscription features)

**Optional:**
- Polylang or WPML (enhanced multilingual features)
- Advanced Custom Fields Pro (enhanced custom fields)

### 3. Demo Content Import

1. Go to **Appearance → Demo Importer**
2. Click **Import All Demo Content**
3. Wait for the process to complete
4. Review imported content and customize as needed

### 4. Theme Customization

Navigate to **Appearance → Customize** to configure:

- **Site Identity**: Logo, site title, tagline
- **Colors**: Primary, secondary, accent colors
- **Typography**: Font families, sizes, weights
- **Layout**: Header, footer, sidebar options
- **Module Settings**: Enable/disable theme modules
- **SEO Settings**: Meta tags, social media
- **Performance**: Caching, optimization options

## Module Configuration

AquaLuxe includes 15+ modules that can be individually enabled/disabled:

### Core Modules (Always Active)
- **Multilingual**: Language switching and i18n
- **Dark Mode**: Theme preference management
- **Performance**: Optimization and caching
- **Security**: Protection and hardening
- **SEO**: Search engine optimization

### Feature Modules (Toggleable)
- **Services**: Service booking and management
- **Subscriptions**: Membership systems
- **Bookings**: Appointment scheduling
- **Events**: Event management and ticketing
- **Auctions**: Auction functionality
- **Wholesale**: B2B features
- **Franchise**: Multi-location management
- **Affiliates**: Referral programs
- **Multivendor**: Marketplace functionality

Configure modules at **Appearance → Customize → Module Settings**.

## WooCommerce Setup

### 1. Install WooCommerce

```bash
# Via WP-CLI
wp plugin install woocommerce --activate

# Or download from WordPress.org
```

### 2. Run WooCommerce Setup Wizard

1. Go to **WooCommerce → Home**
2. Complete the setup wizard
3. Configure payment methods
4. Set up shipping zones and methods

### 3. Import Sample Products

1. Go to **Tools → Import**
2. Choose **WooCommerce products (CSV)**
3. Upload the sample products file (if available)

### 4. Theme Integration

AquaLuxe automatically detects WooCommerce and enables:
- Custom shop layouts
- Product page enhancements
- Cart and checkout styling
- Account dashboard integration

## Performance Optimization

### 1. Caching Setup

**Plugin Options:**
- WP Rocket (premium)
- W3 Total Cache (free)
- WP Super Cache (free)

**Server-level:**
- Nginx/Apache caching
- Redis/Memcached
- CDN (Cloudflare, MaxCDN)

### 2. Image Optimization

**Plugins:**
- Smush
- Imagify
- ShortPixel

**Theme Features:**
- Automatic lazy loading
- WebP support
- Responsive images

### 3. Database Optimization

**Plugins:**
- WP-Optimize
- Advanced Database Cleaner

**Theme Tools:**
- **Appearance → File Organizer**
- **Appearance → Code Review**

## Security Hardening

### 1. Theme Security Features

- Input sanitization and validation
- CSRF protection with nonces
- XSS prevention
- SQL injection protection
- File upload restrictions

### 2. Recommended Security Plugins

- Wordfence Security
- Sucuri Security
- iThemes Security

### 3. Server Security

- SSL certificate
- Firewall configuration
- Regular backups
- Strong passwords
- Two-factor authentication

## SEO Configuration

### 1. Built-in SEO Features

- Schema.org markup
- OpenGraph meta tags
- XML sitemaps
- Meta tag optimization
- Breadcrumb navigation

### 2. SEO Tools

Access **Appearance → SEO Tools** for:
- SEO analysis
- Sitemap management
- Structured data validation

### 3. Content Optimization

- Use descriptive titles and meta descriptions
- Optimize images with alt text
- Create internal linking structure
- Submit sitemaps to search engines

## Accessibility Features

### 1. WCAG 2.1 AA Compliance

- Keyboard navigation
- Screen reader support
- Color contrast compliance
- Focus indicators
- Skip links

### 2. Accessibility Tools

Access **Appearance → Accessibility** for:
- Accessibility audit
- Quick fixes
- Compliance testing

### 3. Best Practices

- Use semantic HTML
- Provide alt text for images
- Ensure sufficient color contrast
- Test with keyboard navigation
- Use ARIA labels appropriately

## Troubleshooting

### Common Issues

**1. Build Errors**
```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install

# Rebuild assets
npm run production
```

**2. Permission Issues**
```bash
# Fix file permissions
sudo chown -R $USER:$USER .
chmod -R 755 .
```

**3. Docker Issues**
```bash
# Reset containers
docker-compose down -v
docker system prune -f
docker-compose up -d
```

**4. Module Conflicts**
- Disable problematic modules in **Appearance → Customize → Module Settings**
- Check PHP error logs
- Test with default WordPress theme

### Debug Mode

Enable WordPress debug mode for development:

```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Getting Help

1. **Documentation**: Check theme documentation files
2. **GitHub Issues**: Report bugs and feature requests
3. **WordPress Forums**: Community support
4. **Code Review**: Use built-in **Appearance → Code Review** tool

## Maintenance

### Regular Tasks

**Weekly:**
- Update WordPress core
- Update plugins and themes
- Review security logs
- Check site performance

**Monthly:**
- Database optimization
- Image optimization
- Backup verification
- SEO analysis

**Quarterly:**
- Accessibility audit
- Code review
- Performance testing
- Security audit

### Monitoring

**Tools:**
- Google Search Console
- Google Analytics
- WordPress Site Health
- Theme admin tools

**Metrics:**
- Page load times
- SEO rankings
- Accessibility compliance
- Security status

## Updates and Maintenance

### Automatic Updates

AquaLuxe supports WordPress automatic updates for:
- Security patches
- Minor version updates
- Translation updates

### Manual Updates

1. **Backup your site**
2. **Download latest version**
3. **Replace theme files** (preserve customizations)
4. **Run build process** if needed
5. **Test functionality**

### Child Themes

For heavy customizations, create a child theme:

```php
// child-theme/style.css
/*
Theme Name: AquaLuxe Child
Template: aqualuxe
*/

@import url("../aqualuxe/style.css");

/* Your custom styles here */
```

## Support and Community

- **GitHub Repository**: https://github.com/kasunvimarshana/aqualuxe-web
- **Documentation**: Available in theme files
- **Issues**: Report on GitHub Issues
- **Discussions**: GitHub Discussions tab

## License

AquaLuxe is licensed under GPL v2 or later. See LICENSE file for details.

---

**Version**: 1.0.0  
**Last Updated**: September 2024  
**Minimum WordPress**: 6.0  
**Tested up to**: 6.3