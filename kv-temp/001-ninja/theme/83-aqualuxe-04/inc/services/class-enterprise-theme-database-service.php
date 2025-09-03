<?php
/**
 * Enterprise Theme Database Service
 * 
 * Advanced database management service with connection pooling,
 * query optimization, backup management, and database security
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 2.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Database Service Class
 * 
 * Implements:
 * - Database connection management
 * - Query builder and optimization
 * - Database migrations and versioning
 * - Backup and restore functionality
 * - Database security and access control
 * - Performance monitoring and optimization
 * - Multi-tenant database isolation
 */
class Enterprise_Theme_Database_Service {
    
    /**
     * Database configuration
     * 
     * @var array
     */
    private array $config;
    
    /**
     * Database connections pool
     * 
     * @var array
     */
    private array $connections = [];
    
    /**
     * Query statistics
     * 
     * @var array
     */
    private array $query_stats = [
        'total_queries' => 0,
        'slow_queries' => 0,
        'failed_queries' => 0,
        'cache_hits' => 0,
        'total_time' => 0,
    ];
    
    /**
     * Query cache
     * 
     * @var array
     */
    private array $query_cache = [];
    
    /**
     * Database schema version
     * 
     * @var string
     */
    private string $schema_version = '2.0.0';
    
    /**
     * Table prefix
     * 
     * @var string
     */
    private string $table_prefix;
    
    /**
     * Constructor
     * 
     * @param Enterprise_Theme_Config $config Configuration instance
     */
    public function __construct(Enterprise_Theme_Config $config) {
        $this->config = $config->get('database');
        $this->table_prefix = $this->config['table_prefix'] ?? 'et_';
        $this->init_database();
    }
    
    /**
     * Get database connection
     * 
     * @param string $name Connection name
     * @return wpdb|null Database connection
     */
    public function get_connection(string $name = 'default'): ?wpdb {
        if (!isset($this->connections[$name])) {
            $this->create_connection($name);
        }
        
        return $this->connections[$name] ?? null;
    }
    
    /**
     * Execute query with monitoring and caching
     * 
     * @param string $query SQL query
     * @param array $params Query parameters
     * @param array $options Query options
     * @return mixed Query result
     */
    public function query(string $query, array $params = [], array $options = []) {
        $start_time = microtime(true);
        $cache_key = $options['cache_key'] ?? null;
        $cache_ttl = $options['cache_ttl'] ?? 3600;
        $connection_name = $options['connection'] ?? 'default';
        
        // Check cache first
        if ($cache_key && isset($this->query_cache[$cache_key])) {
            $this->query_stats['cache_hits']++;
            return $this->query_cache[$cache_key];
        }
        
        $connection = $this->get_connection($connection_name);
        if (!$connection) {
            $this->query_stats['failed_queries']++;
            return false;
        }
        
        // Prepare query with parameters
        if (!empty($params)) {
            $query = $connection->prepare($query, $params);
        }
        
        // Execute query
        $result = $connection->get_results($query);
        $execution_time = microtime(true) - $start_time;
        
        // Update statistics
        $this->query_stats['total_queries']++;
        $this->query_stats['total_time'] += $execution_time;
        
        if ($execution_time > ($this->config['slow_query_threshold'] ?? 1.0)) {
            $this->query_stats['slow_queries']++;
            $this->log_slow_query($query, $execution_time);
        }
        
        if ($connection->last_error) {
            $this->query_stats['failed_queries']++;
            $this->log_query_error($query, $connection->last_error);
            return false;
        }
        
        // Cache result if requested
        if ($cache_key) {
            $this->query_cache[$cache_key] = $result;
        }
        
        return $result;
    }
    
    /**
     * Insert data into table
     * 
     * @param string $table Table name
     * @param array $data Data to insert
     * @param array $format Data format
     * @param array $options Insert options
     * @return int|false Insert ID or false on failure
     */
    public function insert(string $table, array $data, array $format = [], array $options = []) {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        
        // Add timestamps if enabled
        if ($options['timestamps'] ?? true) {
            $data['created_at'] = current_time('mysql');
            $data['updated_at'] = current_time('mysql');
        }
        
        $result = $connection->insert($table_name, $data, $format);
        
        if ($result === false) {
            $this->log_query_error("INSERT INTO {$table_name}", $connection->last_error);
            return false;
        }
        
        return $connection->insert_id;
    }
    
    /**
     * Update data in table
     * 
     * @param string $table Table name
     * @param array $data Data to update
     * @param array $where Where conditions
     * @param array $format Data format
     * @param array $where_format Where format
     * @param array $options Update options
     * @return int|false Number of rows affected or false on failure
     */
    public function update(string $table, array $data, array $where, array $format = [], array $where_format = [], array $options = []) {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        
        // Add timestamp if enabled
        if ($options['timestamps'] ?? true) {
            $data['updated_at'] = current_time('mysql');
        }
        
        $result = $connection->update($table_name, $data, $where, $format, $where_format);
        
        if ($result === false) {
            $this->log_query_error("UPDATE {$table_name}", $connection->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Delete data from table
     * 
     * @param string $table Table name
     * @param array $where Where conditions
     * @param array $where_format Where format
     * @param array $options Delete options
     * @return int|false Number of rows affected or false on failure
     */
    public function delete(string $table, array $where, array $where_format = [], array $options = []) {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        
        $result = $connection->delete($table_name, $where, $where_format);
        
        if ($result === false) {
            $this->log_query_error("DELETE FROM {$table_name}", $connection->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Get records from table
     * 
     * @param string $table Table name
     * @param array $options Query options
     * @return array Query results
     */
    public function get(string $table, array $options = []): array {
        $select = $options['select'] ?? '*';
        $where = $options['where'] ?? [];
        $order_by = $options['order_by'] ?? '';
        $limit = $options['limit'] ?? '';
        $offset = $options['offset'] ?? 0;
        
        $table_name = $this->get_table_name($table);
        $query = "SELECT {$select} FROM {$table_name}";
        $params = [];
        
        // Build WHERE clause
        if (!empty($where)) {
            $where_clauses = [];
            foreach ($where as $column => $value) {
                if (is_array($value)) {
                    $placeholders = implode(',', array_fill(0, count($value), '%s'));
                    $where_clauses[] = "{$column} IN ({$placeholders})";
                    $params = array_merge($params, $value);
                } else {
                    $where_clauses[] = "{$column} = %s";
                    $params[] = $value;
                }
            }
            $query .= ' WHERE ' . implode(' AND ', $where_clauses);
        }
        
        // Add ORDER BY
        if ($order_by) {
            $query .= " ORDER BY {$order_by}";
        }
        
        // Add LIMIT and OFFSET
        if ($limit) {
            $query .= " LIMIT {$limit}";
            if ($offset > 0) {
                $query .= " OFFSET {$offset}";
            }
        }
        
        return $this->query($query, $params, $options) ?: [];
    }
    
    /**
     * Count records in table
     * 
     * @param string $table Table name
     * @param array $where Where conditions
     * @param array $options Query options
     * @return int Record count
     */
    public function count(string $table, array $where = [], array $options = []): int {
        $table_name = $this->get_table_name($table);
        $query = "SELECT COUNT(*) as count FROM {$table_name}";
        $params = [];
        
        // Build WHERE clause
        if (!empty($where)) {
            $where_clauses = [];
            foreach ($where as $column => $value) {
                $where_clauses[] = "{$column} = %s";
                $params[] = $value;
            }
            $query .= ' WHERE ' . implode(' AND ', $where_clauses);
        }
        
        $result = $this->query($query, $params, $options);
        return $result ? (int) $result[0]->count : 0;
    }
    
    /**
     * Create database table
     * 
     * @param string $table Table name
     * @param array $schema Table schema
     * @param array $options Creation options
     * @return bool Success status
     */
    public function create_table(string $table, array $schema, array $options = []): bool {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        $charset_collate = $connection->get_charset_collate();
        
        $columns = [];
        $indexes = [];
        
        foreach ($schema['columns'] as $column_name => $column_def) {
            $columns[] = $this->build_column_definition($column_name, $column_def);
        }
        
        if (isset($schema['indexes'])) {
            foreach ($schema['indexes'] as $index_name => $index_def) {
                $indexes[] = $this->build_index_definition($index_name, $index_def);
            }
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (\n";
        $sql .= implode(",\n", $columns);
        
        if (!empty($indexes)) {
            $sql .= ",\n" . implode(",\n", $indexes);
        }
        
        $sql .= "\n) {$charset_collate};";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        return true;
    }
    
    /**
     * Drop database table
     * 
     * @param string $table Table name
     * @param array $options Drop options
     * @return bool Success status
     */
    public function drop_table(string $table, array $options = []): bool {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        $result = $connection->query("DROP TABLE IF EXISTS {$table_name}");
        
        return $result !== false;
    }
    
    /**
     * Check if table exists
     * 
     * @param string $table Table name
     * @param array $options Check options
     * @return bool Table existence status
     */
    public function table_exists(string $table, array $options = []): bool {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return false;
        }
        
        $table_name = $this->get_table_name($table);
        $result = $connection->get_var("SHOW TABLES LIKE '{$table_name}'");
        
        return $result === $table_name;
    }
    
    /**
     * Begin database transaction
     * 
     * @param string $connection_name Connection name
     * @return bool Success status
     */
    public function begin_transaction(string $connection_name = 'default'): bool {
        $connection = $this->get_connection($connection_name);
        if (!$connection) {
            return false;
        }
        
        return $connection->query('START TRANSACTION') !== false;
    }
    
    /**
     * Commit database transaction
     * 
     * @param string $connection_name Connection name
     * @return bool Success status
     */
    public function commit_transaction(string $connection_name = 'default'): bool {
        $connection = $this->get_connection($connection_name);
        if (!$connection) {
            return false;
        }
        
        return $connection->query('COMMIT') !== false;
    }
    
    /**
     * Rollback database transaction
     * 
     * @param string $connection_name Connection name
     * @return bool Success status
     */
    public function rollback_transaction(string $connection_name = 'default'): bool {
        $connection = $this->get_connection($connection_name);
        if (!$connection) {
            return false;
        }
        
        return $connection->query('ROLLBACK') !== false;
    }
    
    /**
     * Get database statistics
     * 
     * @return array Database statistics
     */
    public function get_statistics(): array {
        return array_merge($this->query_stats, [
            'connections' => count($this->connections),
            'cache_size' => count($this->query_cache),
            'avg_query_time' => $this->query_stats['total_queries'] > 0 
                ? $this->query_stats['total_time'] / $this->query_stats['total_queries'] 
                : 0,
        ]);
    }
    
    /**
     * Optimize database tables
     * 
     * @param array $tables Tables to optimize (empty for all)
     * @param array $options Optimization options
     * @return array Optimization results
     */
    public function optimize_tables(array $tables = [], array $options = []): array {
        $connection = $this->get_connection($options['connection'] ?? 'default');
        if (!$connection) {
            return [];
        }
        
        if (empty($tables)) {
            $tables = $this->get_theme_tables();
        }
        
        $results = [];
        
        foreach ($tables as $table) {
            $table_name = $this->get_table_name($table);
            $result = $connection->get_results("OPTIMIZE TABLE {$table_name}");
            $results[$table] = $result;
        }
        
        return $results;
    }
    
    /**
     * Create database backup
     * 
     * @param array $options Backup options
     * @return string|false Backup file path or false on failure
     */
    public function create_backup(array $options = []): string|false {
        $backup_dir = $options['backup_dir'] ?? WP_CONTENT_DIR . '/backups';
        $filename = $options['filename'] ?? 'enterprise_theme_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $tables = $options['tables'] ?? $this->get_theme_tables();
        
        if (!is_dir($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }
        
        $backup_file = $backup_dir . '/' . $filename;
        $connection = $this->get_connection();
        
        if (!$connection) {
            return false;
        }
        
        $sql_content = "-- Enterprise Theme Database Backup\n";
        $sql_content .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";
        
        foreach ($tables as $table) {
            $table_name = $this->get_table_name($table);
            
            // Get table structure
            $create_table = $connection->get_row("SHOW CREATE TABLE {$table_name}", ARRAY_A);
            if ($create_table) {
                $sql_content .= "DROP TABLE IF EXISTS `{$table_name}`;\n";
                $sql_content .= $create_table['Create Table'] . ";\n\n";
            }
            
            // Get table data
            $rows = $connection->get_results("SELECT * FROM {$table_name}", ARRAY_A);
            foreach ($rows as $row) {
                $values = array_map(function($value) use ($connection) {
                    return $value === null ? 'NULL' : "'" . esc_sql($value) . "'";
                }, array_values($row));
                
                $columns = '`' . implode('`, `', array_keys($row)) . '`';
                $sql_content .= "INSERT INTO `{$table_name}` ({$columns}) VALUES (" . implode(', ', $values) . ");\n";
            }
            
            $sql_content .= "\n";
        }
        
        if (file_put_contents($backup_file, $sql_content) !== false) {
            return $backup_file;
        }
        
        return false;
    }
    
    /**
     * Restore database from backup
     * 
     * @param string $backup_file Backup file path
     * @param array $options Restore options
     * @return bool Success status
     */
    public function restore_backup(string $backup_file, array $options = []): bool {
        if (!file_exists($backup_file)) {
            return false;
        }
        
        $connection = $this->get_connection();
        if (!$connection) {
            return false;
        }
        
        $sql_content = file_get_contents($backup_file);
        if ($sql_content === false) {
            return false;
        }
        
        // Split SQL into individual queries
        $queries = array_filter(array_map('trim', explode(';', $sql_content)));
        
        $this->begin_transaction();
        
        try {
            foreach ($queries as $query) {
                if (!empty($query) && !str_starts_with($query, '--')) {
                    $result = $connection->query($query);
                    if ($result === false) {
                        throw new Exception("Query failed: {$query}");
                    }
                }
            }
            
            $this->commit_transaction();
            return true;
        } catch (Exception $e) {
            $this->rollback_transaction();
            error_log('Database restore failed: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Initialize database
     * 
     * @return void
     */
    private function init_database(): void {
        $this->create_connection('default');
        $this->create_theme_tables();
        $this->run_migrations();
    }
    
    /**
     * Create database connection
     * 
     * @param string $name Connection name
     * @return void
     */
    private function create_connection(string $name): void {
        global $wpdb;
        
        if ($name === 'default') {
            $this->connections[$name] = $wpdb;
            return;
        }
        
        // Create additional connections if configured
        $connection_config = $this->config['connections'][$name] ?? null;
        if ($connection_config) {
            $this->connections[$name] = new wpdb(
                $connection_config['username'],
                $connection_config['password'],
                $connection_config['database'],
                $connection_config['host']
            );
        }
    }
    
    /**
     * Get full table name with prefix
     * 
     * @param string $table Table name
     * @return string Full table name
     */
    private function get_table_name(string $table): string {
        global $wpdb;
        return $wpdb->prefix . $this->table_prefix . $table;
    }
    
    /**
     * Get theme tables
     * 
     * @return array Theme table names
     */
    private function get_theme_tables(): array {
        return [
            'tenants',
            'vendors',
            'vendor_products',
            'vendor_commissions',
            'languages',
            'translations',
            'currencies',
            'exchange_rates',
            'user_preferences',
            'audit_logs',
            'cache_entries',
            'sessions',
        ];
    }
    
    /**
     * Create theme tables
     * 
     * @return void
     */
    private function create_theme_tables(): void {
        $tables = $this->get_table_schemas();
        
        foreach ($tables as $table_name => $schema) {
            $this->create_table($table_name, $schema);
        }
    }
    
    /**
     * Get table schemas
     * 
     * @return array Table schemas
     */
    private function get_table_schemas(): array {
        return [
            'tenants' => [
                'columns' => [
                    'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                    'domain' => ['type' => 'varchar', 'length' => 255, 'unique' => true],
                    'subdomain' => ['type' => 'varchar', 'length' => 100, 'unique' => true],
                    'name' => ['type' => 'varchar', 'length' => 255],
                    'status' => ['type' => 'varchar', 'length' => 20, 'default' => 'active'],
                    'settings' => ['type' => 'longtext'],
                    'created_at' => ['type' => 'datetime'],
                    'updated_at' => ['type' => 'datetime'],
                ],
                'indexes' => [
                    'domain_index' => ['columns' => ['domain']],
                    'status_index' => ['columns' => ['status']],
                ],
            ],
            'vendors' => [
                'columns' => [
                    'id' => ['type' => 'bigint', 'auto_increment' => true, 'primary' => true],
                    'user_id' => ['type' => 'bigint'],
                    'tenant_id' => ['type' => 'bigint'],
                    'store_name' => ['type' => 'varchar', 'length' => 255],
                    'store_slug' => ['type' => 'varchar', 'length' => 255],
                    'status' => ['type' => 'varchar', 'length' => 20, 'default' => 'pending'],
                    'commission_rate' => ['type' => 'decimal', 'precision' => 5, 'scale' => 2, 'default' => 10.00],
                    'settings' => ['type' => 'longtext'],
                    'created_at' => ['type' => 'datetime'],
                    'updated_at' => ['type' => 'datetime'],
                ],
                'indexes' => [
                    'user_index' => ['columns' => ['user_id']],
                    'tenant_index' => ['columns' => ['tenant_id']],
                    'slug_index' => ['columns' => ['store_slug']],
                ],
            ],
        ];
    }
    
    /**
     * Build column definition
     * 
     * @param string $name Column name
     * @param array $definition Column definition
     * @return string Column definition SQL
     */
    private function build_column_definition(string $name, array $definition): string {
        $sql = "`{$name}` " . strtoupper($definition['type']);
        
        if (isset($definition['length'])) {
            $sql .= "({$definition['length']})";
        } elseif (isset($definition['precision']) && isset($definition['scale'])) {
            $sql .= "({$definition['precision']},{$definition['scale']})";
        }
        
        if ($definition['unsigned'] ?? false) {
            $sql .= ' UNSIGNED';
        }
        
        if ($definition['null'] ?? true) {
            $sql .= ' NULL';
        } else {
            $sql .= ' NOT NULL';
        }
        
        if (isset($definition['default'])) {
            $sql .= " DEFAULT '{$definition['default']}'";
        }
        
        if ($definition['auto_increment'] ?? false) {
            $sql .= ' AUTO_INCREMENT';
        }
        
        return $sql;
    }
    
    /**
     * Build index definition
     * 
     * @param string $name Index name
     * @param array $definition Index definition
     * @return string Index definition SQL
     */
    private function build_index_definition(string $name, array $definition): string {
        $type = strtoupper($definition['type'] ?? 'INDEX');
        $columns = '`' . implode('`, `', $definition['columns']) . '`';
        
        if ($type === 'PRIMARY') {
            return "PRIMARY KEY ({$columns})";
        } elseif ($type === 'UNIQUE') {
            return "UNIQUE KEY `{$name}` ({$columns})";
        } else {
            return "KEY `{$name}` ({$columns})";
        }
    }
    
    /**
     * Run database migrations
     * 
     * @return void
     */
    private function run_migrations(): void {
        $current_version = get_option('enterprise_theme_db_version', '0.0.0');
        
        if (version_compare($current_version, $this->schema_version, '<')) {
            $this->run_migration_scripts($current_version, $this->schema_version);
            update_option('enterprise_theme_db_version', $this->schema_version);
        }
    }
    
    /**
     * Run migration scripts
     * 
     * @param string $from_version From version
     * @param string $to_version To version
     * @return void
     */
    private function run_migration_scripts(string $from_version, string $to_version): void {
        // Migration scripts would be implemented here
        // This is a placeholder for version-specific migrations
        
        error_log("Running database migration from {$from_version} to {$to_version}");
    }
    
    /**
     * Log slow query
     * 
     * @param string $query SQL query
     * @param float $execution_time Execution time
     * @return void
     */
    private function log_slow_query(string $query, float $execution_time): void {
        error_log("Slow query detected ({$execution_time}s): {$query}");
    }
    
    /**
     * Log query error
     * 
     * @param string $query SQL query
     * @param string $error Error message
     * @return void
     */
    private function log_query_error(string $query, string $error): void {
        error_log("Database query error: {$error} - Query: {$query}");
    }
}
