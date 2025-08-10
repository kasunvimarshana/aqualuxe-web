#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script creates a distributable zip file of the theme

echo "AquaLuxe Theme Packaging Script"
echo "==============================="

# Set variables
THEME_NAME="aqualuxe"
BUILD_DIR="./build"
VERSION=$(grep "Version:" style.css | sed 's/.*Version: \(.*\)/\1/')

# Create build directory if it doesn't exist
if [ ! -d "$BUILD_DIR" ]; then
  mkdir -p "$BUILD_DIR"
  echo "Created build directory: $BUILD_DIR"
fi

# Optimize CSS if Tailwind CLI is available
if command -v npx &> /dev/null; then
  echo "Optimizing CSS..."
  if [ -f "./src/tailwind.css" ]; then
    npx tailwindcss -i ./src/tailwind.css -o ./assets/css/tailwind.css --minify
    echo "CSS optimized successfully!"
  else
    echo "Warning: src/tailwind.css not found, skipping CSS optimization"
  fi
else
  echo "Warning: npx not found, skipping CSS optimization"
fi

# Create a list of files to exclude
echo "Preparing file list..."
cat > .packageignore << EOF
.git
.github
node_modules
package-lock.json
build.js
package.sh
.packageignore
.gitignore
*.log
build
src
EOF

# Create the zip file
echo "Creating theme package..."
zip -r "$BUILD_DIR/${THEME_NAME}-${VERSION}.zip" . -x@.packageignore

# Clean up
rm .packageignore

echo "Theme package created: $BUILD_DIR/${THEME_NAME}-${VERSION}.zip"
echo "Done!"