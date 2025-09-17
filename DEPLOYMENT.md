# AquaLuxe Deployment Guide

This guide covers deployment of the AquaLuxe WordPress theme for production environments.

## 🏗️ System Requirements

### Server Requirements
- **PHP**: 8.0 or higher (8.2+ recommended)
- **MySQL**: 5.7+ or MariaDB 10.3+
- **WordPress**: 6.0+ (6.4+ recommended for FSE features)
- **Memory**: 256MB minimum, 512MB+ recommended
- **Storage**: 1GB minimum for base installation

### Optional Dependencies
- **WooCommerce**: 8.0+ for e-commerce features
- **Redis**: For advanced caching (recommended)
- **Elasticsearch**: For enhanced search (optional)

## 🐳 Docker Deployment

### Using Docker Compose (Recommended)

1. **Clone the repository**:
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web
   ```

2. **Configure environment**:
   ```bash
   cp .env.example .env
   # Edit .env with your settings
   ```

3. **Build and start containers**:
   ```bash
   # Development
   docker-compose --profile development up -d
   
   # Production
   docker-compose up -d
   ```

4. **Access services**:
   - WordPress: http://localhost:8080
   - phpMyAdmin: http://localhost:8081 (dev profile only)
   - MailHog: http://localhost:8025 (dev profile only)

### Production Docker Configuration

The Dockerfile includes:
- ✅ **Fixed autoconf dependency** for ImageMagick
- ✅ **PHP 8.2-FPM** with optimized extensions
- ✅ **Security hardening** with proper user permissions
- ✅ **Performance optimization** with OPcache
- ✅ **Built-in Redis support**

## 🔧 Manual Installation

### 1. Server Setup

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install nginx php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring \
    php8.2-curl php8.2-zip php8.2-gd php8.2-imagick php8.2-redis \
    mysql-server redis-server

# CentOS/RHEL
sudo yum install nginx php82-php-fpm php82-php-mysql php82-php-xml \
    php82-php-mbstring php82-php-curl php82-php-zip php82-php-gd \
    php82-php-imagick php82-php-redis mysql-server redis
```

### 2. WordPress Installation

```bash
# Download WordPress
wget https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz

# Set permissions
sudo chown -R www-data:www-data /var/www/html/wordpress
sudo chmod -R 755 /var/www/html/wordpress
```

### 3. Theme Installation

```bash
cd /var/www/html/wordpress/wp-content/themes/
git clone https://github.com/kasunvimarshana/aqualuxe-web.git aqualuxe
cd aqualuxe

# Install Node.js dependencies
npm install

# Build production assets
npm run production
```

## ⚙️ Configuration

### WordPress Configuration

Add to `wp-config.php`:

```php
// Database
define('DB_NAME', 'aqualuxe_db');
define('DB_USER', 'aqualuxe_user');
define('DB_PASSWORD', 'secure_password');
define('DB_HOST', 'localhost');

// Security keys (generate at https://api.wordpress.org/secret-key/1.1/salt/)
define('AUTH_KEY', 'your-key-here');
// ... other keys

// Performance
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', true);

// Security
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', false); // Allow theme/plugin updates
define('FORCE_SSL_ADMIN', true);

// Debug (disable in production)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// AquaLuxe specific
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_DEBUG', false);
```

### Nginx Configuration

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name aqualuxe.com www.aqualuxe.com;
    
    # SSL redirect
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name aqualuxe.com www.aqualuxe.com;
    
    root /var/www/html/wordpress;
    index index.php index.html;
    
    # SSL configuration
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    
    # Security headers
    add_header X-Frame-Options SAMEORIGIN;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains";
    
    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/json application/xml+rss application/atom+xml image/svg+xml;
    
    # Static assets caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    # WordPress rules
    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Security
    location ~ /\. {
        deny all;
    }
    
    location ~* wp-config.php {
        deny all;
    }
}
```

### PHP-FPM Configuration

Edit `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

; Performance
php_admin_value[memory_limit] = 256M
php_admin_value[upload_max_filesize] = 50M
php_admin_value[post_max_size] = 50M
php_admin_value[max_execution_time] = 300

; OPcache
php_admin_value[opcache.enable] = 1
php_admin_value[opcache.memory_consumption] = 128
php_admin_value[opcache.interned_strings_buffer] = 8
php_admin_value[opcache.max_accelerated_files] = 4000
php_admin_value[opcache.revalidate_freq] = 60
```

## 🔒 Security Setup

### 1. File Permissions

```bash
# WordPress core files
find /var/www/html/wordpress/ -type f -exec chmod 644 {} \;
find /var/www/html/wordpress/ -type d -exec chmod 755 {} \;

# wp-config.php
chmod 600 /var/www/html/wordpress/wp-config.php

# Uploads directory
chmod 755 /var/www/html/wordpress/wp-content/uploads
find /var/www/html/wordpress/wp-content/uploads -type f -exec chmod 644 {} \;
find /var/www/html/wordpress/wp-content/uploads -type d -exec chmod 755 {} \;
```

### 2. Firewall Configuration

```bash
# UFW (Ubuntu)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# Fail2ban
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

### 3. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d aqualuxe.com -d www.aqualuxe.com
sudo systemctl enable certbot.timer
```

## 📊 Performance Optimization

### 1. Redis Configuration

Edit `/etc/redis/redis.conf`:

```conf
# Memory
maxmemory 256mb
maxmemory-policy allkeys-lru

# Security
bind 127.0.0.1
requirepass your_redis_password

# Persistence
save 900 1
save 300 10
save 60 10000
```

Add to WordPress `wp-config.php`:

```php
// Redis cache
define('WP_REDIS_HOST', '127.0.0.1');
define('WP_REDIS_PORT', 6379);
define('WP_REDIS_PASSWORD', 'your_redis_password');
define('WP_REDIS_DATABASE', 0);
define('WP_REDIS_TIMEOUT', 1);
define('WP_REDIS_READ_TIMEOUT', 1);
```

### 2. Database Optimization

```sql
-- MySQL optimization
SET GLOBAL innodb_buffer_pool_size = 256M;
SET GLOBAL query_cache_type = ON;
SET GLOBAL query_cache_size = 32M;

-- Indexes for AquaLuxe
CREATE INDEX idx_post_type_status ON wp_posts(post_type, post_status);
CREATE INDEX idx_meta_key_value ON wp_postmeta(meta_key, meta_value(100));
```

### 3. CDN Configuration

For CloudFlare:

```nginx
# Real IP restoration
set_real_ip_from 173.245.48.0/20;
set_real_ip_from 103.21.244.0/22;
set_real_ip_from 103.22.200.0/22;
set_real_ip_from 103.31.4.0/22;
set_real_ip_from 141.101.64.0/18;
set_real_ip_from 108.162.192.0/18;
set_real_ip_from 190.93.240.0/20;
set_real_ip_from 188.114.96.0/20;
set_real_ip_from 197.234.240.0/22;
set_real_ip_from 198.41.128.0/17;
set_real_ip_from 162.158.0.0/15;
set_real_ip_from 104.16.0.0/13;
set_real_ip_from 104.24.0.0/14;
set_real_ip_from 172.64.0.0/13;
set_real_ip_from 131.0.72.0/22;
real_ip_header CF-Connecting-IP;
```

## 🔄 CI/CD Pipeline

### GitHub Actions Workflow

`.github/workflows/deploy.yml`:

```yaml
name: Deploy AquaLuxe

on:
  push:
    branches: [ main ]
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v4
    
    - name: Setup Node.js
      uses: actions/setup-node@v4
      with:
        node-version: '18'
        cache: 'npm'
    
    - name: Install dependencies
      run: npm ci
    
    - name: Build assets
      run: npm run production
    
    - name: Run tests
      run: npm test
    
    - name: Deploy to server
      uses: appleboy/ssh-action@v1.0.0
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.USERNAME }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/html/wordpress/wp-content/themes/aqualuxe
          git pull origin main
          npm ci
          npm run production
          sudo systemctl reload nginx
          sudo systemctl reload php8.2-fpm
```

## 📋 Pre-Launch Checklist

### Performance
- [ ] **Page Speed**: Lighthouse score ≥90
- [ ] **Core Web Vitals**: LCP <2.5s, FID <100ms, CLS <0.1
- [ ] **Image Optimization**: WebP format, lazy loading enabled
- [ ] **Caching**: Redis/Memcached configured
- [ ] **CDN**: CloudFlare or similar configured
- [ ] **Compression**: Gzip/Brotli enabled

### Security
- [ ] **SSL Certificate**: Valid and configured
- [ ] **Security Headers**: All headers implemented
- [ ] **File Permissions**: Properly secured
- [ ] **Firewall**: UFW/iptables configured
- [ ] **Fail2ban**: Login protection enabled
- [ ] **Backups**: Automated backup solution

### SEO
- [ ] **XML Sitemap**: Generated and submitted
- [ ] **Search Console**: Verified and configured
- [ ] **Analytics**: Google Analytics 4 implemented
- [ ] **Schema Markup**: All schemas validated
- [ ] **Meta Tags**: Complete and optimized
- [ ] **Open Graph**: Social media sharing configured

### Accessibility
- [ ] **WCAG 2.1 AA**: Compliance verified
- [ ] **Screen Reader**: Testing completed
- [ ] **Keyboard Navigation**: Fully accessible
- [ ] **Color Contrast**: 4.5:1 minimum ratio
- [ ] **Focus Indicators**: Visible and consistent
- [ ] **Alt Text**: All images have descriptive alt text

### Functionality
- [ ] **Contact Forms**: Working and secure
- [ ] **WooCommerce**: Products, cart, checkout tested
- [ ] **Search**: Functionality working
- [ ] **Mobile**: Responsive design verified
- [ ] **Cross-browser**: Tested in major browsers
- [ ] **Error Pages**: 404, 500 pages styled

## 🔍 Monitoring & Maintenance

### Log Monitoring

```bash
# WordPress error logs
tail -f /var/www/html/wordpress/wp-content/debug.log

# Nginx access logs
tail -f /var/log/nginx/access.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log

# System logs
journalctl -f -u nginx
journalctl -f -u php8.2-fpm
```

### Performance Monitoring

Use tools like:
- **New Relic** for APM
- **GTmetrix** for speed testing
- **Pingdom** for uptime monitoring
- **Google PageSpeed Insights** for Core Web Vitals

### Regular Maintenance

```bash
#!/bin/bash
# Weekly maintenance script

# Update WordPress core
wp core update

# Update plugins
wp plugin update --all

# Optimize database
wp db optimize

# Clear caches
wp cache flush
redis-cli FLUSHDB

# Update search index (if using Elasticsearch)
wp elasticsearch index --setup

# Generate sitemap
wp sitemap generate

# Security scan
wp security scan
```

## 🆘 Troubleshooting

### Common Issues

1. **White Screen of Death**
   ```bash
   # Check PHP error logs
   tail -f /var/log/php8.2-fpm.log
   
   # Increase memory limit
   php_admin_value[memory_limit] = 512M
   ```

2. **Slow Loading**
   ```bash
   # Check slow queries
   wp db query "SHOW PROCESSLIST;"
   
   # Enable query debugging
   define('SAVEQUERIES', true);
   ```

3. **SSL Issues**
   ```bash
   # Test SSL configuration
   openssl s_client -connect aqualuxe.com:443
   
   # Renew Let's Encrypt certificate
   sudo certbot renew
   ```

## 📞 Support

For deployment support:
- **Documentation**: [GitHub Wiki](https://github.com/kasunvimarshana/aqualuxe-web/wiki)
- **Issues**: [GitHub Issues](https://github.com/kasunvimarshana/aqualuxe-web/issues)
- **Discussions**: [GitHub Discussions](https://github.com/kasunvimarshana/aqualuxe-web/discussions)

---

**🌊 AquaLuxe - Bringing elegance to aquatic life – globally**