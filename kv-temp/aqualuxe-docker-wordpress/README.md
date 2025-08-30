# AquaLuxe - Premium Ornamental Fish E-commerce Platform

A complete Docker-based WordPress WooCommerce solution for ornamental fish businesses, featuring a custom child theme, Redis caching, and production-ready configuration.

## 🐠 Features

- **Custom AquaLuxe Child Theme** - Premium design optimized for ornamental fish businesses
- **WooCommerce Integration** - Full e-commerce functionality with product filters and comparison
- **Redis Caching** - High-performance caching for optimal speed
- **Responsive Design** - Mobile-first approach with modern UI/UX
- **Docker Containerization** - Easy deployment and scalability
- **Production Ready** - Includes SSL, security headers, and optimization
- **SEO Optimized** - Built-in SEO best practices and structured data

## 🚀 Quick Start

### Prerequisites

- Docker and Docker Compose installed
- Git (for cloning the repository)
- Basic knowledge of WordPress and WooCommerce

### Installation

1. **Clone the repository:**
   \`\`\`bash
   git clone https://github.com/yourusername/aqualuxe-wordpress.git
   cd aqualuxe-wordpress
   \`\`\`

2. **Set up environment variables:**
   \`\`\`bash
   cp .env.example .env
   # Edit .env with your configuration
   \`\`\`

3. **Start the application:**
   \`\`\`bash
   chmod +x start.sh
   ./start.sh
   \`\`\`

4. **Access your site:**
   - WordPress: http://localhost:8080
   - Admin Panel: http://localhost:8080/wp-admin
   - phpMyAdmin: http://localhost:8081

### Default Credentials

- **WordPress Admin:**
  - Username: `admin`
  - Password: `aqualuxe_admin_2024`

- **Database:**
  - Host: `localhost:3306`
  - Database: `aqualuxe_db`
  - Username: `aqualuxe_user`
  - Password: `aqualuxe_secure_password`

## 🏗️ Architecture

### Services

- **WordPress** - Main application server with PHP 8.2 and Apache
- **MySQL 8.0** - Database server with optimized configuration
- **Redis** - Caching layer for improved performance
- **Nginx** - Reverse proxy with SSL termination and compression
- **phpMyAdmin** - Database management interface

### Directory Structure

\`\`\`
aqualuxe-wordpress/
├── themes/aqualuxe-child/          # Custom child theme
│   ├── assets/                     # CSS, JS, and images
│   ├── template-parts/             # Template components
│   └── *.php                       # Theme files
├── docker/                         # Docker configuration
│   ├── nginx/                      # Nginx configs
│   ├── php/                        # PHP configs
│   ├── mysql/                      # MySQL configs
│   └── redis/                      # Redis configs
├── plugins/                        # WordPress plugins
├── uploads/                        # Media uploads
└── scripts/                        # Utility scripts
\`\`\`

## 🎨 Theme Features

### AquaLuxe Child Theme

- **Modern Design** - Clean, professional layout with aquatic color scheme
- **Product Showcase** - Optimized product cards with hover effects
- **Advanced Filters** - Category, price, and color filtering with AJAX
- **Product Comparison** - Compare up to 3 products side-by-side
- **Mobile Responsive** - Fully responsive design for all devices
- **Performance Optimized** - Lazy loading, minified assets, and caching

### Color Scheme

- Primary: `#0077be` (Ocean Blue)
- Secondary: `#00a8cc` (Aqua)
- Accent: `#ffd700` (Gold)
- Dark: `#1a365d` (Deep Blue)
- Light: `#e6f7ff` (Light Blue)

## 🛠️ Development

### Local Development

1. **Start development environment:**
   \`\`\`bash
   docker-compose up -d
   \`\`\`

2. **Watch for file changes:**
   \`\`\`bash
   # The theme files are mounted as volumes for live editing
   \`\`\`

3. **Access logs:**
   \`\`\`bash
   docker-compose logs -f wordpress
   \`\`\`

### Customization

#### Adding New Features

1. **Custom Post Types** - Add in `functions.php`
2. **Custom Fields** - Use ACF or custom meta boxes
3. **New Templates** - Create in theme directory
4. **Styling** - Edit `style.css` or add new CSS files

#### Plugin Development

\`\`\`php
// Example: Custom product features
function add_product_features_meta_box() {
    add_meta_box(
        'product_features',
        'Product Features',
        'product_features_callback',
        'product'
    );
}
add_action('add_meta_boxes', 'add_product_features_meta_box');
\`\`\`

## 🚀 Production Deployment

### Using Production Configuration

1. **Set up production environment:**
   \`\`\`bash
   cp .env.example .env.prod
   # Configure production values
   \`\`\`

2. **Deploy with production settings:**
   \`\`\`bash
   docker-compose -f docker-compose.prod.yml up -d
   \`\`\`

### SSL Configuration

1. **Generate SSL certificates:**
   \`\`\`bash
   # Using Let's Encrypt
   certbot certonly --webroot -w /var/www/html -d yourdomain.com
   \`\`\`

2. **Update Nginx configuration:**
   ```nginx
   server {
       listen 443 ssl http2;
       ssl_certificate /etc/nginx/ssl/cert.pem;
       ssl_certificate_key /etc/nginx/ssl/key.pem;
   }
   \`\`\`

## 📊 Performance Optimization

### Caching Strategy

- **Redis Object Cache** - Database query caching
- **Nginx Caching** - Static file caching with long expiry
- **Browser Caching** - Optimized cache headers
- **Image Optimization** - WebP support and lazy loading

### Database Optimization

- **Optimized MySQL Configuration** - Tuned for WordPress
- **Regular Backups** - Automated backup system
- **Index Optimization** - Custom indexes for better performance

## 🔧 Maintenance

### Backup

\`\`\`bash
# Create backup
./backup.sh

# Restore from backup
./restore.sh backup_name
\`\`\`

### Updates

\`\`\`bash
# Update WordPress core
docker-compose exec wordpress wp core update --allow-root

# Update plugins
docker-compose exec wordpress wp plugin update --all --allow-root

# Update themes
docker-compose exec wordpress wp theme update --all --allow-root
\`\`\`

### Monitoring

- **Health Checks** - Built-in container health monitoring
- **Log Management** - Centralized logging with rotation
- **Performance Monitoring** - Redis and MySQL metrics

## 🔒 Security

### Security Features

- **SSL/TLS Encryption** - HTTPS everywhere
- **Security Headers** - HSTS, CSP, X-Frame-Options
- **File Permissions** - Proper WordPress file permissions
- **Database Security** - Separate user accounts with limited privileges
- **Regular Updates** - Automated security updates

### Security Best Practices

1. **Change Default Passwords** - Use strong, unique passwords
2. **Limit Login Attempts** - Install security plugins
3. **Regular Backups** - Automated daily backups
4. **File Monitoring** - Monitor file changes
5. **Security Scanning** - Regular vulnerability scans

## 📈 SEO & Marketing

### Built-in SEO Features

- **Structured Data** - Product schema markup
- **Meta Tags** - Optimized meta descriptions and titles
- **XML Sitemaps** - Automatic sitemap generation
- **Social Media** - Open Graph and Twitter Card support
- **Page Speed** - Optimized for Core Web Vitals

### Marketing Integration

- **Google Analytics** - Built-in GA4 support
- **Facebook Pixel** - Conversion tracking
- **Email Marketing** - Mailchimp integration
- **Newsletter Signup** - Custom signup forms

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

- **Documentation** - Check this README and inline comments
- **Issues** - Report bugs via GitHub Issues
- **Community** - Join our Discord server
- **Professional Support** - Contact us for custom development

## 🙏 Acknowledgments

- WordPress Community
- WooCommerce Team
- Storefront Theme (parent theme)
- Docker Community
- All contributors and testers

---

**AquaLuxe** - Bringing luxury to the world of ornamental fish 🐠✨
