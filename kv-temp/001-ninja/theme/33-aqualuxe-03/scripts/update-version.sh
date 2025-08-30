#!/bin/bash

# AquaLuxe Theme Version Update Script
# This script updates the version number in all necessary files

# Check if version parameter is provided
if [ -z "$1" ]; then
    echo "Error: Version number is required"
    echo "Usage: ./update-version.sh 1.0.1"
    exit 1
fi

# Set variables
NEW_VERSION=$1
CURRENT_DIR=$(pwd)

# Display info
echo "========================================"
echo "Updating AquaLuxe Theme to version $NEW_VERSION"
echo "========================================"

# Update version in style.css
echo "Updating version in style.css..."
sed -i "s/Version: .*/Version: $NEW_VERSION/" style.css

# Update version in functions.php
echo "Updating version in functions.php..."
sed -i "s/define( 'AQUALUXE_VERSION', '.*' );/define( 'AQUALUXE_VERSION', '$NEW_VERSION' );/" functions.php

# Update version in package.json
echo "Updating version in package.json..."
sed -i "s/&quot;version&quot;: &quot;.*&quot;/&quot;version&quot;: &quot;$NEW_VERSION&quot;/" package.json

# Update version in readme.txt
if [ -f "readme.txt" ]; then
    echo "Updating version in readme.txt..."
    sed -i "s/Stable tag: .*/Stable tag: $NEW_VERSION/" readme.txt
fi

# Create a new section in release-notes.md
echo "Adding new version section to release-notes.md..."
DATE=$(date +"%B %d, %Y")
NEW_SECTION="## Version $NEW_VERSION ($DATE)\n\n### Bug Fixes\n- \n\n### Enhancements\n- \n\n"

# Insert new section after the first line of release-notes.md
sed -i "1a\\$NEW_SECTION" docs/release-notes.md

# Done
echo "========================================"
echo "Version updated to $NEW_VERSION"
echo "Don't forget to update the release notes with actual changes!"
echo "========================================"