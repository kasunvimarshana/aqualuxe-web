#!/bin/bash

# AquaLuxe Theme Demo Site Setup Script
# This script sets up a demo site for the AquaLuxe WordPress theme

# Set variables
SITE_TITLE="AquaLuxe Demo"
ADMIN_USER="admin"
ADMIN_PASSWORD="demo_password" # In a real scenario, use a secure password
ADMIN_EMAIL="admin@aqualuxe.example.com"
WP_VERSION="latest"
THEME_NAME="aqualuxe"
SITE_URL="http://localhost:8000"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "Docker is required but not installed. Please install Docker first."
    exit 1
fi

# Create a directory for the demo site
echo "Creating demo site directory..."
mkdir -p demo-site
cd demo-site || exit

# Create docker-compose.yml file
echo "Creating docker-compose.yml file..."
cat > docker-compose.yml << EOL
version: '3'

services:
  db:
    image: mysql:5.7
    volumes:
      - db_data:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: wordpress
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress

  wordpress:
    depends_on:
      - db
    image: wordpress:${WP_VERSION}
    ports:
      - "8000:80"
    restart: always
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: wordpress
    volumes:
      - wordpress_data:/var/www/html
      - ../:/var/www/html/wp-content/themes/${THEME_NAME}

volumes:
  db_data:
  wordpress_data:
EOL

# Start the containers
echo "Starting Docker containers..."
docker-compose up -d

# Wait for WordPress to be ready
echo "Waiting for WordPress to be ready..."
sleep 30

# Install WP-CLI
echo "Installing WP-CLI..."
docker-compose exec wordpress curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
docker-compose exec wordpress chmod +x wp-cli.phar
docker-compose exec wordpress mv wp-cli.phar /usr/local/bin/wp

# Configure WordPress
echo "Configuring WordPress..."
docker-compose exec wordpress wp core install --url="${SITE_URL}" --title="${SITE_TITLE}" --admin_user="${ADMIN_USER}" --admin_password="${ADMIN_PASSWORD}" --admin_email="${ADMIN_EMAIL}" --skip-email

# Activate the theme
echo "Activating the theme..."
docker-compose exec wordpress wp theme activate ${THEME_NAME}

# Install and activate required plugins
echo "Installing and activating required plugins..."
docker-compose exec wordpress wp plugin install woocommerce --activate
docker-compose exec wordpress wp plugin install contact-form-7 --activate
docker-compose exec wordpress wp plugin install wordpress-seo --activate

# Import demo content
echo "Importing demo content..."
docker-compose exec wordpress wp import /var/www/html/wp-content/themes/${THEME_NAME}/demo/aqualuxe-demo-content.xml --authors=create

# Set up homepage
echo "Setting up homepage..."
docker-compose exec wordpress wp option update show_on_front "page"
docker-compose exec wordpress wp option update page_on_front "$(docker-compose exec wordpress wp post list --post_type=page --name=home --field=ID --format=ids)"

# Set up menus
echo "Setting up menus..."
# Create primary menu
docker-compose exec wordpress wp menu create "Primary Menu"
# Add pages to menu
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=home --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=about --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=services --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=blog --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=contact --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post primary-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=faq --field=ID --format=ids)"
# Assign menu to location
docker-compose exec wordpress wp menu location assign primary-menu primary

# Create footer menu
docker-compose exec wordpress wp menu create "Footer Menu"
# Add pages to menu
docker-compose exec wordpress wp menu item add-post footer-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=home --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post footer-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=about --field=ID --format=ids)"
docker-compose exec wordpress wp menu item add-post footer-menu "$(docker-compose exec wordpress wp post list --post_type=page --name=contact --field=ID --format=ids)"
# Assign menu to location
docker-compose exec wordpress wp menu location assign footer-menu footer

# Configure WooCommerce
echo "Configuring WooCommerce..."
docker-compose exec wordpress wp option update woocommerce_store_address "123 Aquarium Street"
docker-compose exec wordpress wp option update woocommerce_store_address_2 ""
docker-compose exec wordpress wp option update woocommerce_store_city "Ocean City"
docker-compose exec wordpress wp option update woocommerce_default_country "US:CA"
docker-compose exec wordpress wp option update woocommerce_store_postcode "90210"
docker-compose exec wordpress wp option update woocommerce_currency "USD"
docker-compose exec wordpress wp option update woocommerce_product_type "both"
docker-compose exec wordpress wp option update woocommerce_allow_tracking "no"

# Create WooCommerce pages
echo "Creating WooCommerce pages..."
docker-compose exec wordpress wp wc --user=admin tool run create_pages

# Final message
echo "Demo site setup complete!"
echo "You can access the demo site at: ${SITE_URL}"
echo "Admin login: ${ADMIN_USER} / ${ADMIN_PASSWORD}"