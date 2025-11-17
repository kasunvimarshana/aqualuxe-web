#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script packages the AquaLuxe WordPress theme for distribution

# Set variables
THEME_NAME="AquaLuxe"
THEME_SLUG="aqualuxe"
VERSION=$(grep "Version:" ${THEME_SLUG}/style.css | awk -F': ' '{print $2}' | tr -d '\r')
DIST_DIR="dist"
PACKAGE_NAME="${THEME_SLUG}-${VERSION}"
TEMP_DIR="temp_${PACKAGE_NAME}"

# Print header
echo "========================================"
echo "  ${THEME_NAME} Theme Packaging Script  "
echo "  Version: ${VERSION}                   "
echo "========================================"
echo ""

# Check if dist directory exists, create if not
if [ ! -d "$DIST_DIR" ]; then
  echo "Creating distribution directory..."
  mkdir -p "$DIST_DIR"
fi

# Create temporary directory
echo "Creating temporary directory..."
mkdir -p "$TEMP_DIR"

# Copy theme files to temporary directory
echo "Copying theme files..."
cp -r "$THEME_SLUG"/* "$TEMP_DIR/"

# Remove development and unnecessary files
echo "Removing development and unnecessary files..."
rm -rf "$TEMP_DIR/node_modules"
rm -rf "$TEMP_DIR/.git"
rm -rf "$TEMP_DIR/.github"
rm -rf "$TEMP_DIR/.vscode"
rm -f "$TEMP_DIR/.gitignore"
rm -f "$TEMP_DIR/.editorconfig"
rm -f "$TEMP_DIR/.eslintrc"
rm -f "$TEMP_DIR/.stylelintrc"
rm -f "$TEMP_DIR/package-lock.json"
rm -f "$TEMP_DIR/composer.lock"
rm -f "$TEMP_DIR/phpcs.xml"
rm -f "$TEMP_DIR/README.md"
rm -f "$TEMP_DIR/CHANGELOG.md"
rm -f "$TEMP_DIR/CONTRIBUTING.md"
rm -f "$TEMP_DIR/package-theme.sh"

# Build assets for production if npm is available
if command -v npm &> /dev/null; then
  echo "Building assets for production..."
  cd "$THEME_SLUG" && npm run build && cd ..
  
  # Copy compiled assets to temp directory
  echo "Copying compiled assets..."
  cp -r "$THEME_SLUG/assets/dist" "$TEMP_DIR/assets/"
  
  # Remove source files
  echo "Removing source files..."
  rm -rf "$TEMP_DIR/assets/src"
fi

# Create zip file
echo "Creating zip file..."
cd "$TEMP_DIR" && zip -r "../$DIST_DIR/$PACKAGE_NAME.zip" . -x "*.DS_Store" && cd ..

# Clean up
echo "Cleaning up..."
rm -rf "$TEMP_DIR"

# Create a checksum file
echo "Creating checksum file..."
cd "$DIST_DIR" && md5sum "$PACKAGE_NAME.zip" > "$PACKAGE_NAME.md5" && cd ..

# Output success message
echo ""
echo "========================================"
echo "  Packaging complete!                   "
echo "  Package: $DIST_DIR/$PACKAGE_NAME.zip  "
echo "  Size: $(du -h "$DIST_DIR/$PACKAGE_NAME.zip" | cut -f1)"
echo "========================================"