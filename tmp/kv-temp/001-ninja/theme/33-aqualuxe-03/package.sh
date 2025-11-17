#!/bin/bash

# AquaLuxe Theme Packaging Script
# This script creates a clean, production-ready zip archive of the AquaLuxe WordPress theme

# Set variables
THEME_NAME="aqualuxe"
VERSION=$(grep "Version:" style.css | sed 's/.*Version: \(.*\)/\1/')
TIMESTAMP=$(date +"%Y%m%d%H%M%S")
OUTPUT_DIR="/workspace"
TEMP_DIR="/tmp/${THEME_NAME}-${VERSION}-${TIMESTAMP}"
ZIP_FILE="${OUTPUT_DIR}/${THEME_NAME}-${VERSION}.zip"

echo "Packaging AquaLuxe Theme v${VERSION}..."

# Create temporary directory
mkdir -p "${TEMP_DIR}"

# Run build process if not already done
if [ ! -d "assets/css" ] || [ ! -d "assets/js" ]; then
    echo "Assets not found. Running build process..."
    ./build.sh
fi

# Copy all files to temporary directory
echo "Copying files to temporary directory..."
rsync -a --exclude=node_modules \
       --exclude=.git \
       --exclude=.github \
       --exclude=.gitignore \
       --exclude=.DS_Store \
       --exclude=package-lock.json \
       --exclude=yarn.lock \
       --exclude=.vscode \
       --exclude=.idea \
       --exclude=*.log \
       --exclude=*.tmp \
       --exclude=*.temp \
       --exclude=*.swp \
       --exclude=*.swo \
       --exclude=.sass-cache \
       --exclude=.cache \
       --exclude=.eslintcache \
       --exclude=.stylelintcache \
       --exclude=package.sh \
       --exclude=build.sh \
       . "${TEMP_DIR}"

# Create empty directories for assets if they don't exist
mkdir -p "${TEMP_DIR}/assets/css"
mkdir -p "${TEMP_DIR}/assets/js"
mkdir -p "${TEMP_DIR}/assets/images"
mkdir -p "${TEMP_DIR}/assets/fonts"
mkdir -p "${TEMP_DIR}/assets/vendor"

# Create zip file
echo "Creating zip archive..."
cd "${TEMP_DIR}/.."
zip -r "${ZIP_FILE}" "$(basename "${TEMP_DIR}")" -x "*/\.*" -x "*/__MACOSX/*"

# Clean up
echo "Cleaning up temporary files..."
rm -rf "${TEMP_DIR}"

echo "Package created successfully: ${ZIP_FILE}"
echo "AquaLuxe Theme v${VERSION} is ready for distribution!"