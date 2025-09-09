#!/bin/bash

# Exit on any error
set -e

# Theme slug
THEME_SLUG="aqualuxe"

# Destination folder
DEST_DIR="dist"

# Create destination folder if it doesn't exist
mkdir -p $DEST_DIR

# Install composer dependencies
if [ -f "composer.json" ]; then
    echo "Installing composer dependencies..."
    composer install --no-dev --optimize-autoloader
fi

# Install npm dependencies
echo "Installing npm dependencies..."
npm install

# Build assets for production
echo "Building assets for production..."
npm run prod

# Create a zip file of the theme
echo "Creating theme zip file..."
zip -r "${DEST_DIR}/${THEME_SLUG}.zip" . -x ".*" -x "node_modules/*" -x "dist/*" -x "deploy.sh"

echo "Deployment script finished. The theme is ready in the '${DEST_DIR}' directory."
