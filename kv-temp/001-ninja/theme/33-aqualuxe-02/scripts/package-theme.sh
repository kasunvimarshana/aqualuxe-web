#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script creates a distributable ZIP file of the AquaLuxe WordPress theme

# Set variables
THEME_NAME="aqualuxe"
VERSION=$(grep -m 1 "Version:" style.css | awk -F': ' '{print $2}' | tr -d '\r')
CURRENT_DIR=$(pwd)
BUILD_DIR="$CURRENT_DIR/build"
DIST_DIR="$CURRENT_DIR/dist"
PACKAGE_NAME="$THEME_NAME-$VERSION"

# Display info
echo "========================================"
echo "Packaging AquaLuxe Theme version $VERSION"
echo "========================================"

# Create build and dist directories if they don't exist
mkdir -p "$BUILD_DIR"
mkdir -p "$DIST_DIR"

# Clean build directory
echo "Cleaning build directory..."
rm -rf "$BUILD_DIR"/*

# Run production build
echo "Running production build..."
npm run prod

# Copy theme files to build directory
echo "Copying theme files to build directory..."
rsync -av --exclude-from='.distignore' ./ "$BUILD_DIR/$PACKAGE_NAME/"

# Remove development files from build
echo "Removing development files..."
cd "$BUILD_DIR/$PACKAGE_NAME" || exit
rm -rf node_modules
rm -rf .git
rm -rf .github
rm -rf .vscode
rm -rf tests
rm -rf scripts
rm -f .gitignore
rm -f .editorconfig
rm -f .eslintrc.json
rm -f .stylelintrc.json
rm -f .distignore
rm -f .travis.yml
rm -f package-lock.json
rm -f composer.lock
rm -f phpcs.xml.dist
rm -f phpunit.xml.dist
rm -f webpack.mix.js
rm -f critical-css-simple.js
rm -f imagemin.js
rm -f svg-sprite.js
rm -f package-theme.sh

# Create distributable ZIP file
echo "Creating distributable ZIP file..."
cd "$BUILD_DIR" || exit
zip -r "$DIST_DIR/$PACKAGE_NAME.zip" "$PACKAGE_NAME"

# Clean up build directory
echo "Cleaning up..."
rm -rf "$BUILD_DIR"

# Done
echo "========================================"
echo "Package created: $DIST_DIR/$PACKAGE_NAME.zip"
echo "========================================"