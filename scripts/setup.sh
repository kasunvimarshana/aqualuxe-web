#!/bin/bash

# AquaLuxe Development Environment Setup Script
# This script sets up the complete WordPress development environment

set -e

echo "🌊 Setting up AquaLuxe Development Environment..."

# Start Docker containers
echo "🐳 Starting Docker containers..."
docker compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 30

# Install WordPress if not already installed
echo "🚀 Setting up WordPress..."
docker compose exec -T wp-cli wp core install \
    --url=http://localhost \
    --title="AquaLuxe" \
    --admin_user=admin \
    --admin_password=admin \
    --admin_email=admin@aqualuxe.local \
    --allow-root || echo "WordPress already installed"

# Copy theme to WordPress themes directory
echo "🎨 Installing AquaLuxe theme..."
docker compose exec -T wordpress sh -c "rm -rf /var/www/html/wp-content/themes/aqualuxe && cp -r /var/www/theme /var/www/html/wp-content/themes/aqualuxe"

# Activate the theme
echo "✨ Activating AquaLuxe theme..."
docker compose exec -T wp-cli wp theme activate aqualuxe --allow-root

# Install Node dependencies and build assets
echo "📦 Installing Node dependencies..."
docker compose exec -T node npm install

echo "🔨 Building initial assets..."
docker compose exec -T node npm run development

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker compose exec -T wordpress chown -R www-data:www-data /var/www/html/wp-content/themes/aqualuxe

echo "✅ Setup complete!"
echo ""
echo "🌐 WordPress is available at: http://localhost"
echo "👤 Admin credentials: admin / admin"
echo "🗄️ phpMyAdmin: http://localhost:8080 (development profile)"
echo "📧 MailHog: http://localhost:8025 (development profile)"
echo ""
echo "To start development with asset watching:"
echo "docker compose exec node npm run watch"