#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script creates a distributable zip file of the AquaLuxe WordPress theme

# Set variables
THEME_NAME="aqualuxe"
VERSION=$(grep "Version:" style.css | sed 's/.*Version: \(.*\)/\1/')
CURRENT_DIR=$(pwd)
BUILD_DIR="../build"
DIST_DIR="../dist"
PACKAGE_NAME="${THEME_NAME}-${VERSION}.zip"

# Display info
echo "====================================="
echo "AquaLuxe Theme Packaging Script"
echo "====================================="
echo "Theme: ${THEME_NAME}"
echo "Version: ${VERSION}"
echo "Package: ${PACKAGE_NAME}"
echo "====================================="

# Create build and dist directories if they don't exist
mkdir -p ${BUILD_DIR}
mkdir -p ${DIST_DIR}

# Clean build directory
echo "Cleaning build directory..."
rm -rf ${BUILD_DIR}/${THEME_NAME}
mkdir -p ${BUILD_DIR}/${THEME_NAME}

# Copy theme files to build directory
echo "Copying theme files to build directory..."
rsync -av --exclude-from='.distignore' ./ ${BUILD_DIR}/${THEME_NAME}/

# Remove unnecessary files from build directory
echo "Removing unnecessary files from build directory..."
cd ${BUILD_DIR}/${THEME_NAME}
rm -rf .git .github .gitignore .distignore .editorconfig package-theme.sh node_modules package-lock.json

# Build assets if needed
if [ -f "package.json" ]; then
    echo "Building assets..."
    npm install
    npm run build
    # Remove development files
    rm -rf node_modules package-lock.json
fi

# Create zip file
echo "Creating zip file..."
cd ${BUILD_DIR}
zip -r ${DIST_DIR}/${PACKAGE_NAME} ${THEME_NAME}

# Return to original directory
cd ${CURRENT_DIR}

# Display completion message
echo "====================================="
echo "Package created successfully!"
echo "Location: ${DIST_DIR}/${PACKAGE_NAME}"
echo "====================================="