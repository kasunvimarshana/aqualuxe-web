#!/bin/bash

# AquaLuxe WordPress Initialization Script
set -e

echo "🚀 Initializing AquaLuxe WordPress Environment..."

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
while ! wp db check --allow-root 2>/dev/null; do
    echo "Waiting for database connection..."
    sleep 3
done

echo "✅ Database connection established"

# Check if WordPress is already installed
if wp core is-installed --allow-root 2>/dev/null; then
    echo "✅ WordPress is already installed"
else
    echo "📥 Downloading WordPress core..."
    
    # Remove any existing files that might conflict
    find /var/www/html -maxdepth 1 -name "wp-*" -type f -delete 2>/dev/null || true
    
    # Download WordPress core
    wp core download --allow-root --force
    
    echo "📝 Creating wp-config.php..."
    # Create wp-config.php
    wp config create \
        --dbname="${WORDPRESS_DB_NAME}" \
        --dbuser="${WORDPRESS_DB_USER}" \
        --dbpass="${WORDPRESS_DB_PASSWORD}" \
        --dbhost="${WORDPRESS_DB_HOST}" \
        --dbcharset="utf8mb4" \
        --dbcollate="utf8mb4_unicode_ci" \
        --allow-root \
        --force
    
    # Add additional wp-config settings
    wp config set WP_DEBUG true --raw --allow-root
    wp config set WP_DEBUG_LOG true --raw --allow-root
    wp config set WP_DEBUG_DISPLAY false --raw --allow-root
    wp config set SCRIPT_DEBUG true --raw --allow-root
    wp config set WP_CACHE true --raw --allow-root
    wp config set AUTOMATIC_UPDATER_DISABLED true --raw --allow-root
    
    # Add Redis cache settings
    wp config set WP_REDIS_HOST redis --allow-root
    wp config set WP_REDIS_PORT 6379 --raw --allow-root
    wp config set WP_REDIS_DATABASE 0 --raw --allow-root
    
    # Add security keys
    wp config shuffle-salts --allow-root
    
    echo "🏗️ Installing WordPress..."
    # Install WordPress
    wp core install \
        --url="${WP_URL:-http://localhost}" \
        --title="${WP_TITLE:-AquaLuxe}" \
        --admin_user="${WP_ADMIN_USER:-admin}" \
        --admin_password="${WP_ADMIN_PASSWORD:-admin}" \
        --admin_email="${WP_ADMIN_EMAIL:-admin@aqualuxe.local}" \
        --allow-root
fi

echo "🎨 Setting up AquaLuxe theme..."

# Create wp-content/themes directory if it doesn't exist
mkdir -p /var/www/html/wp-content/themes/aqualuxe

# Copy theme files to the correct location
echo "📂 Copying theme files..."
if [ -d "/tmp/theme" ]; then
    # Copy theme files from mounted volume
    rsync -av --exclude='wp-*' --exclude='docker*' --exclude='node_modules' --exclude='.git' --exclude='vendor' \
        /tmp/theme/ /var/www/html/wp-content/themes/aqualuxe/
else
    echo "⚠️ Theme files not found in /tmp/theme"
fi

# Activate the theme
echo "✨ Activating AquaLuxe theme..."
wp theme activate aqualuxe --allow-root

# Install and activate essential plugins
echo "🔌 Installing essential plugins..."

# Install WooCommerce (optional, for full e-commerce functionality)
if [ "${INSTALL_WOOCOMMERCE:-true}" = "true" ]; then
    echo "🛒 Installing WooCommerce..."
    wp plugin install woocommerce --activate --allow-root
fi

# Install Redis Object Cache
echo "🚀 Installing Redis Object Cache..."
wp plugin install redis-cache --activate --allow-root
wp redis enable --allow-root 2>/dev/null || echo "⚠️ Redis cache enable failed (may already be enabled)"

# Set proper file permissions
echo "🔐 Setting file permissions..."
find /var/www/html -type d -exec chmod 755 {} \;
find /var/www/html -type f -exec chmod 644 {} \;
chmod 600 /var/www/html/wp-config.php

# Set ownership to www-data
chown -R www-data:www-data /var/www/html

echo "🎉 AquaLuxe WordPress environment initialized successfully!"
echo ""
echo "📋 Access Information:"
echo "  🌐 Website: http://localhost"
echo "  👤 Admin: ${WP_ADMIN_USER:-admin}"
echo "  🔑 Password: ${WP_ADMIN_PASSWORD:-admin}"
echo "  📧 Email: ${WP_ADMIN_EMAIL:-admin@aqualuxe.local}"
echo "  🗄️ phpMyAdmin: http://localhost:8080 (development profile)"
echo ""
echo "🚀 Ready to develop!"