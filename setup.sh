#!/bin/bash

# AquaLuxe Setup Script
# This script sets up the complete AquaLuxe development environment

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}"
echo "╔══════════════════════════════════════════════╗"
echo "║           AquaLuxe Setup Script              ║"
echo "║     Premium WordPress Theme Development      ║"
echo "╚══════════════════════════════════════════════╝"
echo -e "${NC}"

# Function to print colored output
print_status() {
    echo -e "${GREEN}✓${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_error "Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    print_error "Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Set Docker Compose command
if command -v docker-compose &> /dev/null; then
    DOCKER_COMPOSE="docker-compose"
else
    DOCKER_COMPOSE="docker compose"
fi

print_info "Using: $DOCKER_COMPOSE"

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    print_warning "Node.js is not installed. Installing dependencies in Docker container..."
else
    print_status "Node.js found: $(node --version)"
    
    # Install npm dependencies
    print_info "Installing npm dependencies..."
    npm install
    
    # Build assets
    print_info "Building development assets..."
    npm run development
fi

# Start Docker services
print_info "Starting Docker services..."
$DOCKER_COMPOSE down -v 2>/dev/null || true
$DOCKER_COMPOSE up -d

# Wait for services to be ready
print_info "Waiting for services to be ready..."
sleep 10

# Check if MySQL is ready
print_info "Waiting for MySQL to be ready..."
timeout=60
while [ $timeout -gt 0 ]; do
    if $DOCKER_COMPOSE exec -T mysql mysqladmin ping -h localhost --silent; then
        print_status "MySQL is ready!"
        break
    fi
    sleep 2
    timeout=$((timeout-2))
done

if [ $timeout -le 0 ]; then
    print_error "MySQL failed to start within 60 seconds"
    exit 1
fi

# Copy theme files to wp-cli container for processing
print_info "Preparing theme files..."
$DOCKER_COMPOSE exec -T wp-cli rm -rf /tmp/theme/* 2>/dev/null || true
$DOCKER_COMPOSE cp . wp-cli:/tmp/theme/

# Run WordPress initialization
print_info "Initializing WordPress..."
$DOCKER_COMPOSE exec -T wp-cli bash /scripts/init-wordpress.sh

# Build assets in Docker if not built locally
if ! command -v node &> /dev/null; then
    print_info "Building assets in Docker..."
    $DOCKER_COMPOSE exec -T node sh -c "cd /tmp/theme && npm install && npm run development"
    $DOCKER_COMPOSE exec -T wp-cli cp -r /tmp/theme/assets/dist /var/www/html/wp-content/themes/aqualuxe/assets/
fi

print_status "Setup completed successfully!"
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════╗"
echo "║              Setup Complete!                 ║"
echo "╚══════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}📋 Access Information:${NC}"
echo -e "  🌐 Website: ${GREEN}http://localhost${NC}"
echo -e "  👤 Admin User: ${GREEN}admin${NC}"
echo -e "  🔑 Password: ${GREEN}admin${NC}"
echo -e "  📧 Email: ${GREEN}admin@aqualuxe.local${NC}"
echo -e "  🗄️ phpMyAdmin: ${GREEN}http://localhost:8080${NC} (development profile)"
echo ""
echo -e "${BLUE}🔧 Development Commands:${NC}"
echo -e "  Start services: ${YELLOW}$DOCKER_COMPOSE up -d${NC}"
echo -e "  Stop services: ${YELLOW}$DOCKER_COMPOSE down${NC}"
echo -e "  View logs: ${YELLOW}$DOCKER_COMPOSE logs -f${NC}"
echo -e "  Build assets: ${YELLOW}npm run development${NC}"
echo -e "  Watch assets: ${YELLOW}npm run watch${NC}"
echo ""
echo -e "${GREEN}🚀 Happy developing with AquaLuxe!${NC}"