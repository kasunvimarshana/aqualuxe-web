# AquaLuxe WordPress Site

## Docker Development Environment

This repository contains a complete Docker setup for the AquaLuxe ornamental fish business website.

### Features

- WordPress with WooCommerce
- MySQL database
- phpMyAdmin for database management
- Redis for caching
- Custom AquaLuxe child theme

## Quick Start

1. Clone this repository
2. Copy `.env.example` to `.env` (already provided)
3. Run `docker-compose up -d`
4. Access the site at http://localhost:8080
5. Access phpMyAdmin at http://localhost:8081

## Theme Development

The custom AquaLuxe theme is located in `wordpress/wp-content/themes/aqualuxe/`.

### Key Features

- Responsive design
- Water-themed color palette
- WooCommerce integration
- Modern product showcase
- Luxury branding elements

## Production Deployment

For production:

1. Update environment variables in `.env` with secure values
2. Use a reverse proxy (like Nginx) for SSL termination
3. Set up proper backups
4. Use a volume for persistent storage

## Docker Commands

- Start containers: `docker-compose up -d`
- Stop containers: `docker-compose down`
- View logs: `docker-compose logs -f`
- Access WordPress container: `docker-compose exec wordpress bash`
