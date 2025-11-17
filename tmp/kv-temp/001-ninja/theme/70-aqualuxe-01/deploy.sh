#!/bin/bash

# AquaLuxe Theme Deployment Script
# This script prepares the theme for production deployment

set -e

echo "🚀 Starting AquaLuxe Theme Deployment Process..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
THEME_NAME="aqualuxe"
THEME_VERSION="1.0.0"
BUILD_DIR="build"
DIST_DIR="dist"
PACKAGE_NAME="${THEME_NAME}-${THEME_VERSION}"

# Functions
print_status() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

print_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

check_requirements() {
    print_status "Checking requirements..."
    
    # Check Node.js
    if ! command -v node &> /dev/null; then
        print_error "Node.js is required but not installed."
        exit 1
    fi
    
    # Check npm
    if ! command -v npm &> /dev/null; then
        print_error "npm is required but not installed."
        exit 1
    fi
    
    # Check if we're in the right directory
    if [ ! -f "style.css" ] || [ ! -f "functions.php" ]; then
        print_error "Please run this script from the theme root directory."
        exit 1
    fi
    
    print_success "All requirements met."
}

install_dependencies() {
    print_status "Installing dependencies..."
    
    if [ -f "package.json" ]; then
        npm install --production=false
        print_success "Dependencies installed."
    else
        print_warning "No package.json found, skipping dependency installation."
    fi
}

build_assets() {
    print_status "Building production assets..."
    
    if [ -f "webpack.mix.js" ]; then
        npm run production
        print_success "Assets built successfully."
    else
        print_warning "No webpack.mix.js found, skipping asset build."
    fi
}

run_tests() {
    print_status "Running tests..."
    
    # PHP Syntax check
    find . -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"
    
    # JavaScript linting
    if [ -f "package.json" ] && grep -q "lint:js" package.json; then
        npm run lint:js
    fi
    
    # CSS linting
    if [ -f "package.json" ] && grep -q "lint:css" package.json; then
        npm run lint:css
    fi
    
    print_success "Tests completed."
}

optimize_images() {
    print_status "Optimizing images..."
    
    # Find and optimize images (requires imagemin-cli)
    if command -v imagemin &> /dev/null; then
        find assets/src/images -type f \( -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" \) -exec imagemin {} --out-dir=assets/dist/images \;
        print_success "Images optimized."
    else
        print_warning "imagemin not found, skipping image optimization."
    fi
}

generate_pot_file() {
    print_status "Generating translation template..."
    
    if command -v wp &> /dev/null; then
        wp i18n make-pot . languages/${THEME_NAME}.pot --domain=${THEME_NAME}
        print_success "Translation template generated."
    else
        print_warning "WP-CLI not found, skipping POT file generation."
    fi
}

clean_development_files() {
    print_status "Cleaning development files..."
    
    # Create temporary directory for clean build
    rm -rf ${BUILD_DIR}
    mkdir -p ${BUILD_DIR}/${THEME_NAME}
    
    # Copy theme files (excluding development files)
    rsync -av --progress . ${BUILD_DIR}/${THEME_NAME}/ \
        --exclude=node_modules \
        --exclude=.git \
        --exclude=.gitignore \
        --exclude=.env \
        --exclude=.DS_Store \
        --exclude=Thumbs.db \
        --exclude=assets/src \
        --exclude=tests \
        --exclude=phpunit.xml \
        --exclude=composer.json \
        --exclude=composer.lock \
        --exclude=package.json \
        --exclude=package-lock.json \
        --exclude=webpack.mix.js \
        --exclude=tailwind.config.js \
        --exclude=.eslintrc.js \
        --exclude=.stylelintrc.js \
        --exclude=build \
        --exclude=dist \
        --exclude=deploy.sh
    
    print_success "Development files cleaned."
}

create_documentation() {
    print_status "Creating documentation..."
    
    # Create installation guide
    cat > ${BUILD_DIR}/${THEME_NAME}/INSTALL.md << 'EOF'
# AquaLuxe Theme Installation Guide

## Quick Installation

1. Download the theme zip file
2. Go to WordPress Admin → Appearance → Themes
3. Click "Add New" → "Upload Theme"
4. Select the zip file and click "Install Now"
5. Activate the theme
6. Go to Appearance → Customize to configure your site

## Initial Setup

1. **Set up your homepage**: Go to Settings → Reading and set a static page as your homepage
2. **Configure menus**: Go to Appearance → Menus to create your navigation
3. **Install recommended plugins**: The theme will suggest essential plugins
4. **Import demo content**: Use the included XML file to import sample content
5. **Customize your site**: Use the WordPress Customizer to match your brand

## Support

For support and documentation, visit: https://aqualuxetheme.com/support
EOF

    # Create changelog
    cat > ${BUILD_DIR}/${THEME_NAME}/CHANGELOG.md << 'EOF'
# Changelog

## [1.0.0] - 2024-01-01

### Added
- Initial release
- Complete WooCommerce integration
- Dark mode support
- RTL language support
- Mobile-responsive design
- Performance optimizations
- Accessibility features
- SEO optimizations
- Custom post types
- Theme customizer options
- Advanced navigation system
- Contact form integration
- Newsletter subscription
- Social media integration

### Features
- Modern design with elegant animations
- Fully responsive layout
- WooCommerce ready with custom templates
- Dark/light theme toggle
- Multilingual and RTL support
- Performance optimized
- SEO friendly
- Accessibility compliant
- Security hardened
EOF

    print_success "Documentation created."
}

validate_theme() {
    print_status "Validating theme..."
    
    cd ${BUILD_DIR}/${THEME_NAME}
    
    # Check required files
    required_files=("style.css" "index.php" "functions.php" "screenshot.png")
    for file in "${required_files[@]}"; do
        if [ ! -f "$file" ]; then
            print_error "Required file missing: $file"
            exit 1
        fi
    done
    
    # Check style.css header
    if ! grep -q "Theme Name:" style.css; then
        print_error "Invalid style.css header"
        exit 1
    fi
    
    cd - > /dev/null
    
    print_success "Theme validation passed."
}

create_package() {
    print_status "Creating distribution package..."
    
    cd ${BUILD_DIR}
    
    # Create zip file
    zip -r ${PACKAGE_NAME}.zip ${THEME_NAME}/
    
    # Move to dist directory
    mkdir -p ../${DIST_DIR}
    mv ${PACKAGE_NAME}.zip ../${DIST_DIR}/
    
    cd - > /dev/null
    
    print_success "Package created: ${DIST_DIR}/${PACKAGE_NAME}.zip"
}

calculate_package_info() {
    print_status "Calculating package information..."
    
    if [ -f "${DIST_DIR}/${PACKAGE_NAME}.zip" ]; then
        size=$(du -h "${DIST_DIR}/${PACKAGE_NAME}.zip" | cut -f1)
        files=$(unzip -l "${DIST_DIR}/${PACKAGE_NAME}.zip" | tail -1 | awk '{print $2}')
        
        echo ""
        echo "📦 Package Information:"
        echo "   Name: ${PACKAGE_NAME}.zip"
        echo "   Size: ${size}"
        echo "   Files: ${files}"
        echo "   Location: ${DIST_DIR}/${PACKAGE_NAME}.zip"
        echo ""
    fi
}

cleanup() {
    print_status "Cleaning up temporary files..."
    
    rm -rf ${BUILD_DIR}
    
    print_success "Cleanup completed."
}

main() {
    echo ""
    echo "🌊 AquaLuxe Theme Deployment"
    echo "=============================="
    echo ""
    
    check_requirements
    install_dependencies
    build_assets
    run_tests
    optimize_images
    generate_pot_file
    clean_development_files
    create_documentation
    validate_theme
    create_package
    calculate_package_info
    cleanup
    
    echo ""
    print_success "🎉 Deployment completed successfully!"
    echo ""
    echo "Your theme package is ready for distribution:"
    echo "📁 ${DIST_DIR}/${PACKAGE_NAME}.zip"
    echo ""
    echo "Next steps:"
    echo "1. Test the package in a fresh WordPress installation"
    echo "2. Upload to WordPress.org repository or marketplace"
    echo "3. Create release notes and documentation"
    echo ""
}

# Run the deployment
main "$@"
