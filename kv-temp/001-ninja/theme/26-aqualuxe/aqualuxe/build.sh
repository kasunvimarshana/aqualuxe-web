#!/bin/bash

# AquaLuxe Theme Build Script
echo "Starting AquaLuxe theme build process..."

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "Node.js is not installed. Please install Node.js to continue."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "npm is not installed. Please install npm to continue."
    exit 1
fi

# Install dependencies if node_modules doesn't exist
if [ ! -d "node_modules" ]; then
    echo "Installing dependencies..."
    npm install
fi

# Build for development
if [ "$1" == "dev" ]; then
    echo "Building for development..."
    npx mix
    echo "Development build completed!"
    exit 0
fi

# Build for production
if [ "$1" == "prod" ] || [ "$1" == "" ]; then
    echo "Building for production..."
    npx mix --production
    echo "Production build completed!"
    exit 0
fi

# Watch for changes
if [ "$1" == "watch" ]; then
    echo "Watching for changes..."
    npx mix watch
    exit 0
fi

# Show help if invalid argument
echo "Invalid argument: $1"
echo "Usage: ./build.sh [dev|prod|watch]"
echo "  dev   - Build for development"
echo "  prod  - Build for production (default)"
echo "  watch - Watch for changes and rebuild"
exit 1