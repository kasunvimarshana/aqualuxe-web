#!/bin/bash

# # 1) Make script executable:
# chmod +x backup-to-production.sh
# # 2) Run backup:
# source .env && ./backup-to-production.sh


# Configuration
BACKUP_DIR="./wordpress-backup"
TIMESTAMP=$(date +%Y%m%d-%H%M%S)
BACKUP_NAME="wp-backup-${TIMESTAMP}"
SQL_FILE="wordpress-db.sql"
CONFIG_FILE="wp-config-production.php"

# Create backup directory structure
mkdir -p "${BACKUP_DIR}/${BACKUP_NAME}/wp-content"
mkdir -p "${BACKUP_DIR}/${BACKUP_NAME}/database"

echo "➤ Backing up database..."
docker-compose exec -T db mysqldump -u root -p"${DB_ROOT_PW}" --single-transaction "${DB_NAME}" > "${BACKUP_DIR}/${BACKUP_NAME}/database/${SQL_FILE}"

echo "➤ Backing up wp-content..."
rsync -avz --exclude='cache/' --exclude='debug.log' ./wp-content/ "${BACKUP_DIR}/${BACKUP_NAME}/wp-content/"

echo "➤ Backing up must-use plugins..."
cp -r ./mu-plugins "${BACKUP_DIR}/${BACKUP_NAME}/"

echo "➤ Creating sanitized wp-config.php..."
cat > "${BACKUP_DIR}/${BACKUP_NAME}/${CONFIG_FILE}" <<EOL
<?php
define('DB_NAME', 'production_db_name');
define('DB_USER', 'production_db_user');
define('DB_PASSWORD', 'production_db_password');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

define('AUTH_KEY',         '${AUTH_KEY}');
define('SECURE_AUTH_KEY',  '${SECURE_AUTH_KEY}');
define('LOGGED_IN_KEY',    '${LOGGED_IN_KEY}');
define('NONCE_KEY',        '${NONCE_KEY}');
define('AUTH_SALT',        '${AUTH_SALT}');
define('SECURE_AUTH_SALT', '${SECURE_AUTH_SALT}');
define('LOGGED_IN_SALT',   '${LOGGED_IN_SALT}');
define('NONCE_SALT',       '${NONCE_SALT}');

\$table_prefix = '${WP_PREFIX}';

define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
EOL

echo "➤ Creating restore instructions..."
cat > "${BACKUP_DIR}/${BACKUP_NAME}/RESTORE_INSTRUCTIONS.txt" <<EOL
======== WordPress Production Migration ========

1. UPLOAD FILES:
   - Upload ALL contents to your web root (public_html or www)
   - Maintain directory structure:
        /wp-content
        /mu-plugins

2. IMPORT DATABASE:
   - Create a new MySQL database in cPanel
   - Import ${SQL_FILE} using phpMyAdmin or MySQL CLI

3. CONFIGURE:
   - Rename ${CONFIG_FILE} → wp-config.php
   - Edit wp-config.php with your production DB credentials
   - Update site URLs:
        wp search-replace 'http://localhost:8080' 'https://yourdomain.com'
        wp search-replace 'http://localhost:8080' 'https://yourdomain.com' --all-tables

4. FIX PERMISSIONS (SSH):
   cd /path/to/yourdomain.com
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   chmod 600 wp-config.php

5. ENABLE CACHING:
   - Install a caching plugin (WP Rocket, W3 Total Cache)
   - Configure object caching if available

6. SECURITY:
   - Remove unused plugins/themes
   - Install security plugin (Wordfence, Sucuri)
   - Change all admin passwords

7. VERIFY:
   - Visit https://yourdomain.com
   - Check Settings → Permalinks (re-save if needed)
   - Test all forms and functionality

======== Additional Notes ========
- Search/Replace MUST be done before site goes live
- Clear all caches after migration
- Test with browser incognito mode
EOL

echo "➤ Creating compressed archive..."
tar -czvf "${BACKUP_DIR}/${BACKUP_NAME}.tar.gz" -C "${BACKUP_DIR}" "${BACKUP_NAME}"

echo "➤ Cleaning up temporary files..."
rm -rf "${BACKUP_DIR}/${BACKUP_NAME}"

echo "✅ Backup completed: ${BACKUP_DIR}/${BACKUP_NAME}.tar.gz"