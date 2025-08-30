#!/bin/bash

# AquaLuxe Theme Build Script
# This script runs the complete build process for the AquaLuxe WordPress theme

echo "Starting AquaLuxe theme build process..."

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "Error: npm is not installed. Please install Node.js and npm first."
    exit 1
fi

# Install dependencies
echo "Installing dependencies..."
npm install

# Clean previous build
echo "Cleaning previous build..."
npm run clean

# Run production build
echo "Building production assets..."
npm run production

# Generate critical CSS
echo "Generating critical CSS..."
npm run critical

# Optimize images
echo "Optimizing images..."
npm run imagemin

# Generate SVG sprite
echo "Generating SVG sprite..."
npm run svg-sprite

echo "Build process completed successfully!"
echo "The AquaLuxe theme is now ready for deployment."