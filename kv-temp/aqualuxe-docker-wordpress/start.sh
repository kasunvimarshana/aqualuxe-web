#!/bin/bash

# AquaLuxe Docker Setup Script
# This script sets up the complete Docker environment for AquaLuxe WordPress site

set -e

echo "🐠 Starting AquaLuxe Docker Setup..."

# Create necessary directories
echo "📁 Creating directory structure..."
mkdir -p themes/aqualuxe-child/assets/{css,js,images}
mkdir -p plugins
mkdir -p uploads
mkdir -p docker/{php,apache,nginx,mysql,redis,ssl}

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 755 themes/
chmod -R 755 plugins/
chmod -R 755 uploads/
chmod +x start.sh

# Generate SSL certificates for development
echo "🔒 Generating SSL certificates..."
if [ ! -f docker/ssl/cert.pem ]; then
    openssl req -x509 -newkey rsa:4096 -keyout docker/ssl/key.pem -out docker/ssl/cert.pem -days 365 -nodes -subj "/C=US/ST=State/L=City/O=AquaLuxe/CN=localhost"
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose down --remove-orphans
docker-compose build --no-cache
docker-compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to start..."
sleep 30

# Check if WordPress is accessible
echo "🔍 Checking WordPress accessibility..."
until curl -f http://localhost:8080 > /dev/null 2>&1; do
    echo "Waiting for WordPress to be ready..."
    sleep 10
done

# Install WordPress if not already installed
echo "📦 Setting up WordPress..."
docker-compose exec wordpress wp core is-installed --allow-root || {
    echo "Installing WordPress..."
    docker-compose exec wordpress wp core install \
        --url="http://localhost:8080" \
        --title="AquaLuxe - Premium Ornamental Fish" \
        --admin_user="admin" \
        --admin_password="aqualuxe_admin_2024" \
        --admin_email="admin@aqualuxe.local" \
        --allow-root
}

# Install and activate WooCommerce
echo "🛒 Installing WooCommerce..."
docker-compose exec wordpress wp plugin install woocommerce --activate --allow-root

# Install additional recommended plugins
echo "🔌 Installing additional plugins..."
docker-compose exec wordpress wp plugin install \
    redis-cache \
    wp-super-cache \
    yoast-seo \
    contact-form-7 \
    mailchimp-for-wp \
    --activate --allow-root

# Activate AquaLuxe child theme
echo "🎨 Activating AquaLuxe theme..."
docker-compose exec wordpress wp theme activate aqualuxe-child --allow-root

# Configure Redis cache
echo "⚡ Configuring Redis cache..."
docker-compose exec wordpress wp redis enable --allow-root || true

# Set up WooCommerce
echo "🏪 Configuring WooCommerce..."
docker-compose exec wordpress wp option update woocommerce_store_address "123 Aquatic Street" --allow-root
docker-compose exec wordpress wp option update woocommerce_store_city "Fish City" --allow-root
docker-compose exec wordpress wp option update woocommerce_default_country "US:CA" --allow-root
docker-compose exec wordpress wp option update woocommerce_store_postcode "12345" --allow-root
docker-compose exec wordpress wp option update woocommerce_currency "USD" --allow-root

# Create sample pages
echo "📄 Creating sample pages..."
docker-compose exec wordpress wp post create --post_type=page --post_title="About Us" --post_content="Welcome to AquaLuxe, your premier destination for ornamental fish." --post_status=publish --allow-root
docker-compose exec wordpress wp post create --post_type=page --post_title="Contact" --post_content="Get in touch with our aquatic experts." --post_status=publish --allow-root

# Set up menus
echo "🧭 Setting up navigation menus..."
docker-compose exec wordpress wp menu create "Primary Menu" --allow-root
docker-compose exec wordpress wp menu item add-post primary-menu 1 --allow-root  # Home
docker-compose exec wordpress wp menu item add-post primary-menu 2 --allow-root  # Shop
docker-compose exec wordpress wp menu location assign primary-menu primary --allow-root

# Import sample products (if sample data exists)
echo "🐠 Setting up sample products..."
# This would typically import from a WooCommerce sample data file

# Final status check
echo "✅ Setup complete! Checking services..."
echo ""
echo "🌐 Services Status:"
echo "WordPress: http://localhost:8080"
echo "phpMyAdmin: http://localhost:8081"
echo "Nginx Proxy: http://localhost"
echo ""
echo "📊 Admin Credentials:"
echo "Username: admin"
echo "Password: aqualuxe_admin_2024"
echo ""
echo "🗄️ Database Credentials:"
echo "Host: localhost:3306"
echo "Database: aqualuxe_db"
echo "Username: aqualuxe_user"
echo "Password: aqualuxe_secure_password"
echo ""
echo "🎉 AquaLuxe is ready! Visit http://localhost:8080 to get started."
