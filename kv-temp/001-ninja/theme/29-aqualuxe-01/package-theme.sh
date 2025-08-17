#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script packages the AquaLuxe WordPress theme for distribution

# Set variables
THEME_NAME="aqualuxe"
VERSION=$(grep "Version:" style.css | awk -F': ' '{print $2}')
PACKAGE_NAME="${THEME_NAME}-${VERSION}"
TEMP_DIR="../${PACKAGE_NAME}-temp"
DIST_DIR="../dist"

# Create temporary directory
echo "Creating temporary directory..."
mkdir -p "$TEMP_DIR"

# Create distribution directory if it doesn't exist
mkdir -p "$DIST_DIR"

# Copy theme files to temporary directory
echo "Copying theme files..."
cp -R ./* "$TEMP_DIR"

# Remove unnecessary files and directories
echo "Removing unnecessary files and directories..."
rm -rf "$TEMP_DIR/node_modules"
rm -rf "$TEMP_DIR/.git"
rm -rf "$TEMP_DIR/.github"
rm -rf "$TEMP_DIR/.vscode"
rm -rf "$TEMP_DIR/package-theme.sh"
rm -f "$TEMP_DIR/.gitignore"
rm -f "$TEMP_DIR/.editorconfig"
rm -f "$TEMP_DIR/package-lock.json"
rm -f "$TEMP_DIR/composer.lock"
rm -f "$TEMP_DIR/screenshot.txt"
rm -f "$TEMP_DIR/README.md"
rm -f "$TEMP_DIR/LICENSE.md"
rm -f "$TEMP_DIR/.DS_Store"
rm -f "$TEMP_DIR/Thumbs.db"

# Create a real screenshot.png placeholder if it doesn't exist
if [ ! -f "$TEMP_DIR/screenshot.png" ]; then
    echo "Creating placeholder screenshot.png..."
    # This would normally create a real image, but we'll just create a text file for this example
    echo "This is a placeholder for screenshot.png. In a real theme, this would be a 1200x900 PNG image." > "$TEMP_DIR/screenshot.png"
fi

# Optimize images
echo "Optimizing images..."
find "$TEMP_DIR" -type f -name "*.jpg" -o -name "*.jpeg" -o -name "*.png" -o -name "*.gif" | while read -r img; do
    echo "Optimizing $img..."
    # In a real script, you would use tools like optipng, jpegoptim, or imagemin
    # For this example, we'll just echo the command
    echo "optimize $img"
done

# Minify CSS and JS files
echo "Minifying CSS and JS files..."
find "$TEMP_DIR" -type f -name "*.css" | while read -r css; do
    echo "Minifying $css..."
    # In a real script, you would use tools like cssnano or clean-css
    # For this example, we'll just echo the command
    echo "minify $css"
done

find "$TEMP_DIR" -type f -name "*.js" | while read -r js; do
    echo "Minifying $js..."
    # In a real script, you would use tools like terser or uglify-js
    # For this example, we'll just echo the command
    echo "minify $js"
done

# Create zip file
echo "Creating zip file..."
cd "$TEMP_DIR" || exit
zip -r "../dist/${PACKAGE_NAME}.zip" ./*
cd - || exit

# Clean up
echo "Cleaning up..."
rm -rf "$TEMP_DIR"

echo "Package created: dist/${PACKAGE_NAME}.zip"
echo "Done!"