#!/bin/bash

# AquaLuxe Docker Development Environment Startup Script
# This script sets up and starts the complete development environment

echo "🐠 AquaLuxe Development Environment Setup"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_color() {
    printf "${!1}%s${NC}\n" "$2"
}

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    print_color "RED" "❌ Docker is not installed. Please install Docker and try again."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    print_color "RED" "❌ Docker Compose is not installed. Please install Docker Compose and try again."
    exit 1
fi

print_color "GREEN" "✅ Docker and Docker Compose are installed"

# Create necessary directories
print_color "BLUE" "📁 Creating necessary directories..."
mkdir -p logs/nginx
mkdir -p mysql-init
mkdir -p nginx/ssl
mkdir -p uploads

# Set proper permissions
print_color "BLUE" "🔐 Setting proper permissions..."
chmod +x docker-start.sh
chmod 755 logs
chmod 755 uploads

# Generate SSL certificates for development (self-signed)
if [ ! -f "nginx/ssl/aqualuxe.crt" ]; then
    print_color "BLUE" "🔒 Generating self-signed SSL certificates..."
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout nginx/ssl/aqualuxe.key \
        -out nginx/ssl/aqualuxe.crt \
        -subj "/C=US/ST=State/L=City/O=AquaLuxe/CN=aqualuxe.local" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        print_color "GREEN" "✅ SSL certificates generated successfully"
    else
        print_color "YELLOW" "⚠️  SSL certificate generation failed (OpenSSL required)"
    fi
fi

# Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    print_color "BLUE" "⚙️  Creating environment configuration..."
    cat > .env << EOF
# AquaLuxe Development Environment
COMPOSE_PROJECT_NAME=aqualuxe

# Database Configuration
MYSQL_ROOT_PASSWORD=root_secure_pass_2024
MYSQL_DATABASE=aqualuxe_db
MYSQL_USER=aqualuxe_user
MYSQL_PASSWORD=aqualuxe_secure_pass_2024

# Redis Configuration
REDIS_PASSWORD=redis_secure_pass_2024

# WordPress Configuration
WP_DEBUG=true
WP_DEBUG_LOG=true
WP_DEBUG_DISPLAY=false

# Development URLs
WP_HOME=http://localhost:8080
WP_SITEURL=http://localhost:8080

# Email Configuration (MailHog)
SMTP_HOST=mailhog
SMTP_PORT=1025
EOF
    print_color "GREEN" "✅ Environment file created"
fi

# Stop any existing containers
print_color "BLUE" "🛑 Stopping existing containers..."
docker-compose down

# Pull latest images
print_color "BLUE" "📥 Pulling latest Docker images..."
docker-compose pull

# Start the services
print_color "BLUE" "🚀 Starting AquaLuxe development environment..."
docker-compose up -d

# Wait for services to start
print_color "BLUE" "⏳ Waiting for services to start..."
sleep 10

# Check service status
print_color "BLUE" "🔍 Checking service status..."

# WordPress
if curl -s http://localhost:8080 > /dev/null; then
    print_color "GREEN" "✅ WordPress is running at http://localhost:8080"
else
    print_color "RED" "❌ WordPress is not responding"
fi

# phpMyAdmin
if curl -s http://localhost:8081 > /dev/null; then
    print_color "GREEN" "✅ phpMyAdmin is running at http://localhost:8081"
else
    print_color "YELLOW" "⚠️  phpMyAdmin might still be starting..."
fi

# Redis Commander
if curl -s http://localhost:8082 > /dev/null; then
    print_color "GREEN" "✅ Redis Commander is running at http://localhost:8082"
else
    print_color "YELLOW" "⚠️  Redis Commander might still be starting..."
fi

# MailHog
if curl -s http://localhost:8025 > /dev/null; then
    print_color "GREEN" "✅ MailHog is running at http://localhost:8025"
else
    print_color "YELLOW" "⚠️  MailHog might still be starting..."
fi

print_color "GREEN" ""
print_color "GREEN" "🎉 AquaLuxe Development Environment is ready!"
print_color "GREEN" "=============================================="
echo ""
print_color "BLUE" "📱 Services Available:"
echo "   • WordPress:        http://localhost:8080"
echo "   • phpMyAdmin:       http://localhost:8081"
echo "   • Redis Commander:  http://localhost:8082"
echo "   • MailHog:          http://localhost:8025"
echo ""
print_color "BLUE" "🔑 Default Credentials:"
echo "   • WordPress Admin:  admin / admin"
echo "   • Database:         aqualuxe_user / aqualuxe_secure_pass_2024"
echo "   • phpMyAdmin:       aqualuxe_user / aqualuxe_secure_pass_2024"
echo "   • Redis Commander:  admin / admin_secure_pass_2024"
echo ""
print_color "BLUE" "🛠️  Useful Commands:"
echo "   • View logs:        docker-compose logs -f"
echo "   • Stop services:    docker-compose down"
echo "   • Restart service:  docker-compose restart wordpress"
echo "   • Access container: docker exec -it aqualuxe_wordpress bash"
echo ""
print_color "YELLOW" "📝 Note: It may take a few minutes for all services to be fully ready."
print_color "YELLOW" "     If WordPress shows a database connection error, wait a moment and refresh."
echo ""
print_color "GREEN" "Happy coding! 🐠✨"