#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script creates a zip file of the theme for distribution

# Set variables
THEME_NAME="aqualuxe"
VERSION=$(grep "Version:" style.css | sed 's/.*Version: \(.*\)/\1/')
TIMESTAMP=$(date +"%Y%m%d%H%M%S")
OUTPUT_DIR="dist"
TEMP_DIR="temp_${THEME_NAME}_${TIMESTAMP}"

# Create output directory if it doesn't exist
mkdir -p $OUTPUT_DIR

# Create temporary directory
mkdir -p $TEMP_DIR

# Copy theme files to temporary directory
echo "Copying theme files..."
cp -r assets inc page-templates template-parts *.php style.css screenshot.png LICENSE.txt README.md $TEMP_DIR/

# Remove development files
echo "Removing development files..."
find $TEMP_DIR -name "*.map" -type f -delete
find $TEMP_DIR -name ".DS_Store" -type f -delete
find $TEMP_DIR -name "*.log" -type f -delete
find $TEMP_DIR -name ".git*" -type f -delete

# Create zip file
echo "Creating zip file..."
cd $TEMP_DIR
zip -r "../${OUTPUT_DIR}/${THEME_NAME}-${VERSION}.zip" .
cd ..

# Create child theme zip
echo "Creating child theme zip..."
mkdir -p "${TEMP_DIR}_child"
cp -r documentation/aqualuxe-child/* "${TEMP_DIR}_child/"
cd "${TEMP_DIR}_child"
zip -r "../${OUTPUT_DIR}/${THEME_NAME}-child-${VERSION}.zip" .
cd ..

# Clean up
echo "Cleaning up..."
rm -rf $TEMP_DIR
rm -rf "${TEMP_DIR}_child"

echo "Done! Theme packages created in ${OUTPUT_DIR}/ directory:"
echo "- ${THEME_NAME}-${VERSION}.zip (Main theme)"
echo "- ${THEME_NAME}-child-${VERSION}.zip (Child theme)"