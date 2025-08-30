#!/bin/bash

# AquaLuxe Theme Deployment Script
# This script prepares the theme for production deployment

set -e

echo "🌊 AquaLuxe Theme Deployment Script"
echo "=================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Functions
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "style.css" ] || [ ! -f "functions.php" ]; then
    print_error "This script must be run from the theme root directory"
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed. Please install Node.js first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    print_error "npm is not installed. Please install npm first."
    exit 1
fi

print_status "Starting deployment process..."

# Install dependencies
print_status "Installing Node.js dependencies..."
if npm install; then
    print_success "Dependencies installed successfully"
else
    print_error "Failed to install dependencies"
    exit 1
fi

# Build production assets
print_status "Building production assets..."
if npm run production; then
    print_success "Assets built successfully"
else
    print_error "Failed to build assets"
    exit 1
fi

# Check if assets were created
if [ ! -d "assets/dist" ]; then
    print_error "Assets directory not found after build"
    exit 1
fi

print_success "Assets created in assets/dist/"

# Create deployment package
THEME_NAME="aqualuxe"
VERSION=$(grep "Version:" style.css | head -1 | awk '{print $2}')
PACKAGE_NAME="${THEME_NAME}-${VERSION}"
TEMP_DIR="/tmp/${PACKAGE_NAME}"

print_status "Creating deployment package: ${PACKAGE_NAME}"

# Create temporary directory
rm -rf "$TEMP_DIR"
mkdir -p "$TEMP_DIR"

# Copy theme files (excluding development files)
print_status "Copying theme files..."

# Files and directories to include
cp -r \
    *.php \
    *.css \
    assets/ \
    inc/ \
    templates/ \
    woocommerce/ \
    languages/ \
    README.md \
    "$TEMP_DIR/" 2>/dev/null || true

# Remove development files from the package
print_status "Cleaning up development files..."
rm -rf "$TEMP_DIR/assets/src" 2>/dev/null || true
rm -rf "$TEMP_DIR/node_modules" 2>/dev/null || true
rm -f "$TEMP_DIR/package.json" 2>/dev/null || true
rm -f "$TEMP_DIR/package-lock.json" 2>/dev/null || true
rm -f "$TEMP_DIR/webpack.mix.js" 2>/dev/null || true
rm -f "$TEMP_DIR/tailwind.config.js" 2>/dev/null || true
rm -f "$TEMP_DIR/.gitignore" 2>/dev/null || true
rm -f "$TEMP_DIR/deploy.sh" 2>/dev/null || true

# Create zip package
print_status "Creating ZIP package..."
cd /tmp
if zip -r "${PACKAGE_NAME}.zip" "$PACKAGE_NAME"; then
    print_success "Package created: /tmp/${PACKAGE_NAME}.zip"
else
    print_error "Failed to create ZIP package"
    exit 1
fi

# Copy back to current directory
cp "/tmp/${PACKAGE_NAME}.zip" "$(pwd)/"
print_success "Package copied to current directory"

# Cleanup
rm -rf "$TEMP_DIR"
rm "/tmp/${PACKAGE_NAME}.zip"

# File size information
PACKAGE_SIZE=$(ls -lh "${PACKAGE_NAME}.zip" | awk '{print $5}')
print_success "Deployment package ready: ${PACKAGE_NAME}.zip (${PACKAGE_SIZE})"

# Deployment checklist
echo ""
echo "📋 Pre-deployment Checklist:"
echo "=============================="
echo "✅ Assets built for production"
echo "✅ Development files removed"
echo "✅ Package created and compressed"
echo ""
echo "📦 Next Steps:"
echo "1. Test the package on a staging site"
echo "2. Upload to WordPress themes directory"
echo "3. Activate the theme"
echo "4. Configure theme settings in Customizer"
echo "5. Set up WooCommerce if using e-commerce features"
echo ""

# WordPress deployment instructions
echo "🚀 WordPress Deployment:"
echo "========================"
echo "Method 1 - Admin Upload:"
echo "1. Go to WordPress Admin → Appearance → Themes"
echo "2. Click 'Add New' → 'Upload Theme'"
echo "3. Choose ${PACKAGE_NAME}.zip"
echo "4. Click 'Install Now'"
echo ""
echo "Method 2 - FTP Upload:"
echo "1. Extract ${PACKAGE_NAME}.zip"
echo "2. Upload the folder to /wp-content/themes/"
echo "3. Activate in WordPress Admin"
echo ""

# Performance recommendations
echo "⚡ Performance Recommendations:"
echo "==============================="
echo "1. Install a caching plugin (WP Rocket, W3 Total Cache)"
echo "2. Use a CDN for static assets"
echo "3. Optimize images before upload"
echo "4. Enable GZIP compression"
echo "5. Use PHP 8.0+ for better performance"
echo ""

# Security recommendations
echo "🔒 Security Recommendations:"
echo "============================"
echo "1. Keep WordPress core updated"
echo "2. Use strong admin passwords"
echo "3. Install a security plugin (Wordfence, Sucuri)"
echo "4. Enable SSL/HTTPS"
echo "5. Regular backups"
echo ""

print_success "Deployment script completed successfully!"
print_warning "Remember to test thoroughly before going live!"

echo ""
echo "🌊 Thank you for using AquaLuxe Theme!"
echo "======================================"
