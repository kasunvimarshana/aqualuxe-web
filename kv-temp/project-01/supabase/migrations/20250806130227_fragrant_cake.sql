-- AquaLuxe Database Initialization
-- Create additional database if needed

-- Set character set
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create performance schema user if needed
-- CREATE USER IF NOT EXISTS 'performance_schema'@'localhost';

-- Create database for testing if needed
-- CREATE DATABASE IF NOT EXISTS aqualuxe_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- GRANT ALL PRIVILEGES ON aqualuxe_test.* TO 'aqualuxe_user'@'%';

-- Optimize settings for WordPress
SET GLOBAL innodb_buffer_pool_size = 536870912; -- 512MB
SET GLOBAL max_connections = 200;

-- Flush privileges
FLUSH PRIVILEGES;

-- Create some useful views for monitoring
CREATE OR REPLACE VIEW performance_summary AS
SELECT 
    SCHEMA_NAME as 'Database',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME NOT IN ('information_schema', 'performance_schema', 'mysql', 'sys');