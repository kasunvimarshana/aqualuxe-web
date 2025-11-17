-- Initialize AquaLuxe Database
CREATE DATABASE IF NOT EXISTS aqualuxe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user with proper permissions
CREATE USER IF NOT EXISTS 'aqualuxe_user'@'%' IDENTIFIED BY 'aqualuxe_pass';
GRANT ALL PRIVILEGES ON aqualuxe_db.* TO 'aqualuxe_user'@'%';

-- Create additional databases for multitenancy (if needed)
CREATE DATABASE IF NOT EXISTS aqualuxe_tenant1 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS aqualuxe_tenant2 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON aqualuxe_tenant1.* TO 'aqualuxe_user'@'%';
GRANT ALL PRIVILEGES ON aqualuxe_tenant2.* TO 'aqualuxe_user'@'%';

FLUSH PRIVILEGES;