#!/bin/bash

# AquaLuxe Backup Script

set -e

BACKUP_DIR="backups"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_NAME="aqualuxe_backup_$DATE"

echo "💾 Starting AquaLuxe backup..."

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
echo "🗄️ Backing up database..."
docker-compose exec mysql mysqldump -u root -paqualuxe_root_password aqualuxe_db > $BACKUP_DIR/${BACKUP_NAME}_database.sql

# Backup WordPress files
echo "📁 Backing up WordPress files..."
docker-compose exec wordpress tar -czf /tmp/wordpress_backup.tar.gz -C /var/www/html .
docker cp $(docker-compose ps -q wordpress):/tmp/wordpress_backup.tar.gz $BACKUP_DIR/${BACKUP_NAME}_files.tar.gz

# Backup uploads
echo "🖼️ Backing up uploads..."
tar -czf $BACKUP_DIR/${BACKUP_NAME}_uploads.tar.gz uploads/

# Create backup info file
echo "📝 Creating backup info..."
cat > $BACKUP_DIR/${BACKUP_NAME}_info.txt << EOF
AquaLuxe Backup Information
==========================
Backup Date: $(date)
Backup Name: $BACKUP_NAME
Database: ${BACKUP_NAME}_database.sql
Files: ${BACKUP_NAME}_files.tar.gz
Uploads: ${BACKUP_NAME}_uploads.tar.gz

Restore Instructions:
1. Stop containers: docker-compose down
2. Restore database: docker-compose exec mysql mysql -u root -paqualuxe_root_password aqualuxe_db < ${BACKUP_NAME}_database.sql
3. Extract files: tar -xzf ${BACKUP_NAME}_files.tar.gz
4. Extract uploads: tar -xzf ${BACKUP_NAME}_uploads.tar.gz
5. Start containers: docker-compose up -d
EOF

echo "✅ Backup completed successfully!"
echo "📦 Backup files saved in: $BACKUP_DIR/"
echo "   - Database: ${BACKUP_NAME}_database.sql"
echo "   - Files: ${BACKUP_NAME}_files.tar.gz"
echo "   - Uploads: ${BACKUP_NAME}_uploads.tar.gz"
echo "   - Info: ${BACKUP_NAME}_info.txt"
