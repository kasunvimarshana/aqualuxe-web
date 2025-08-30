-- AquaLuxe Database Initialization
CREATE DATABASE IF NOT EXISTS aqualuxe_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE aqualuxe_db;

-- Create additional user for application
CREATE USER IF NOT EXISTS 'aqualuxe_app'@'%' IDENTIFIED BY 'aqualuxe_app_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON aqualuxe_db.* TO 'aqualuxe_app'@'%';

-- Optimize MySQL settings for WordPress
SET GLOBAL innodb_buffer_pool_size = 128M;
SET GLOBAL innodb_log_file_size = 64M;
SET GLOBAL max_connections = 200;
SET GLOBAL query_cache_size = 32M;
SET GLOBAL query_cache_type = 1;

FLUSH PRIVILEGES;
