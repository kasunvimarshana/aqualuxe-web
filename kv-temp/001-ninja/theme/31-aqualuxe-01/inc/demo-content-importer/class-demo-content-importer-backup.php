<?php
/**
 * Demo Content Importer Backup
 *
 * Handles database backup and restore.
 *
 * @package DemoContentImporter
 * @subpackage Backup
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Backup Class
 */
class Demo_Content_Importer_Backup {

    /**
     * Logger instance.
     *
     * @var Demo_Content_Importer_Logger
     */
    protected $logger;

    /**
     * Backup directory.
     *
     * @var string
     */
    protected $backup_dir;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->logger = new Demo_Content_Importer_Logger();
        $this->backup_dir = DCI_BACKUP_DIR;
    }

    /**
     * Backup database.
     *
     * @return string|WP_Error Backup file path on success, WP_Error on failure.
     */
    public function backup_database() {
        $this->logger->info('Starting database backup');
        
        // Check if backup directory exists
        if (!is_dir($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
        }
        
        // Generate backup file name
        $backup_file = $this->backup_dir . '/backup-' . date('Y-m-d-H-i-s') . '.sql';
        
        // Get database credentials
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_password = DB_PASSWORD;
        $db_host = DB_HOST;
        
        // Check if mysqldump is available
        $mysqldump_command = $this->get_mysqldump_command_path();
        
        if ($mysqldump_command) {
            // Use mysqldump
            $command = sprintf(
                '%s --opt -h"%s" -u"%s" -p"%s" "%s" > "%s"',
                $mysqldump_command,
                $db_host,
                $db_user,
                $db_password,
                $db_name,
                $backup_file
            );
            
            $this->logger->info('Using mysqldump for database backup');
            
            // Execute command
            exec($command, $output, $return_var);
            
            if ($return_var !== 0) {
                $this->logger->error('Failed to backup database using mysqldump');
                $this->logger->error('Falling back to PHP backup method');
            } else {
                $this->logger->success('Database backup completed successfully using mysqldump');
                return $backup_file;
            }
        }
        
        // Fallback to PHP backup method
        $this->logger->info('Using PHP for database backup');
        
        // Get database connection
        $db = $this->get_db_connection();
        
        if (is_wp_error($db)) {
            return $db;
        }
        
        // Get tables
        $tables = $this->get_tables($db);
        
        if (is_wp_error($tables)) {
            return $tables;
        }
        
        // Open backup file
        $handle = fopen($backup_file, 'w');
        
        if (!$handle) {
            $this->logger->error(sprintf('Failed to open backup file: %s', $backup_file));
            return new WP_Error(
                'backup_file_open_failed',
                sprintf(__('Failed to open backup file: %s', 'demo-content-importer'), $backup_file)
            );
        }
        
        // Add header
        fwrite($handle, "-- WordPress Database Backup\n");
        fwrite($handle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
        fwrite($handle, "-- Host: " . DB_HOST . "\n");
        fwrite($handle, "-- Database: " . DB_NAME . "\n\n");
        
        // Backup tables
        foreach ($tables as $table) {
            $this->backup_table($db, $table, $handle);
        }
        
        // Close backup file
        fclose($handle);
        
        $this->logger->success('Database backup completed successfully using PHP');
        
        return $backup_file;
    }

    /**
     * Restore database.
     *
     * @param string $backup_file Backup file path.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function restore_database($backup_file) {
        $this->logger->info('Starting database restore');
        
        // Check if backup file exists
        if (!file_exists($backup_file)) {
            $this->logger->error(sprintf('Backup file not found: %s', $backup_file));
            return new WP_Error(
                'backup_file_not_found',
                sprintf(__('Backup file not found: %s', 'demo-content-importer'), $backup_file)
            );
        }
        
        // Get database connection
        $db = $this->get_db_connection();
        
        if (is_wp_error($db)) {
            return $db;
        }
        
        // Get tables
        $tables = $this->get_tables($db);
        
        if (is_wp_error($tables)) {
            return $tables;
        }
        
        // Drop existing tables
        foreach ($tables as $table) {
            $query = "DROP TABLE IF EXISTS `$table`";
            $result = mysqli_query($db, $query);
            
            if (!$result) {
                $this->logger->error(sprintf('Failed to drop table: %s - %s', $table, mysqli_error($db)));
                return new WP_Error(
                    'drop_table_failed',
                    sprintf(__('Failed to drop table: %s - %s', 'demo-content-importer'), $table, mysqli_error($db))
                );
            }
        }
        
        // Read backup file
        $sql = file_get_contents($backup_file);
        
        if (!$sql) {
            $this->logger->error(sprintf('Failed to read backup file: %s', $backup_file));
            return new WP_Error(
                'backup_file_read_failed',
                sprintf(__('Failed to read backup file: %s', 'demo-content-importer'), $backup_file)
            );
        }
        
        // Split SQL into queries
        $queries = $this->split_sql_file($sql);
        
        // Execute queries
        foreach ($queries as $query) {
            $result = mysqli_query($db, $query);
            
            if (!$result) {
                $this->logger->error(sprintf('Failed to execute query: %s - %s', substr($query, 0, 50) . '...', mysqli_error($db)));
                return new WP_Error(
                    'query_execution_failed',
                    sprintf(__('Failed to execute query: %s - %s', 'demo-content-importer'), substr($query, 0, 50) . '...', mysqli_error($db))
                );
            }
        }
        
        $this->logger->success('Database restore completed successfully');
        
        return true;
    }

    /**
     * Get database connection.
     *
     * @return resource|WP_Error Database connection on success, WP_Error on failure.
     */
    private function get_db_connection() {
        // Get database credentials
        $db_name = DB_NAME;
        $db_user = DB_USER;
        $db_password = DB_PASSWORD;
        $db_host = DB_HOST;
        
        // Connect to database
        $db = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        
        if (!$db) {
            $this->logger->error('Failed to connect to database: ' . mysqli_connect_error());
            return new WP_Error(
                'db_connection_failed',
                sprintf(__('Failed to connect to database: %s', 'demo-content-importer'), mysqli_connect_error())
            );
        }
        
        return $db;
    }

    /**
     * Get tables.
     *
     * @param resource $db Database connection.
     * @return array|WP_Error Tables on success, WP_Error on failure.
     */
    private function get_tables($db) {
        // Get tables
        $result = mysqli_query($db, 'SHOW TABLES');
        
        if (!$result) {
            $this->logger->error('Failed to get tables: ' . mysqli_error($db));
            return new WP_Error(
                'get_tables_failed',
                sprintf(__('Failed to get tables: %s', 'demo-content-importer'), mysqli_error($db))
            );
        }
        
        $tables = array();
        
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        
        return $tables;
    }

    /**
     * Backup table.
     *
     * @param resource $db     Database connection.
     * @param string   $table  Table name.
     * @param resource $handle File handle.
     */
    private function backup_table($db, $table, $handle) {
        $this->logger->info(sprintf('Backing up table: %s', $table));
        
        // Get create table statement
        $result = mysqli_query($db, "SHOW CREATE TABLE `$table`");
        
        if (!$result) {
            $this->logger->error(sprintf('Failed to get create table statement: %s - %s', $table, mysqli_error($db)));
            return;
        }
        
        $row = mysqli_fetch_row($result);
        
        // Write create table statement
        fwrite($handle, "-- Table structure for table `$table`\n\n");
        fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
        fwrite($handle, $row[1] . ";\n\n");
        
        // Get table data
        $result = mysqli_query($db, "SELECT * FROM `$table`");
        
        if (!$result) {
            $this->logger->error(sprintf('Failed to get table data: %s - %s', $table, mysqli_error($db)));
            return;
        }
        
        // Check if table has data
        if (mysqli_num_rows($result) > 0) {
            fwrite($handle, "-- Dumping data for table `$table`\n\n");
            
            // Get column count
            $column_count = mysqli_num_fields($result);
            
            // Get column names
            $fields = array();
            for ($i = 0; $i < $column_count; $i++) {
                $field = mysqli_fetch_field_direct($result, $i);
                $fields[] = '`' . $field->name . '`';
            }
            
            // Write insert statements
            while ($row = mysqli_fetch_row($result)) {
                $values = array();
                
                for ($i = 0; $i < $column_count; $i++) {
                    if (is_null($row[$i])) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . mysqli_real_escape_string($db, $row[$i]) . "'";
                    }
                }
                
                fwrite($handle, "INSERT INTO `$table` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $values) . ");\n");
            }
            
            fwrite($handle, "\n");
        }
    }

    /**
     * Split SQL file into queries.
     *
     * @param string $sql SQL file content.
     * @return array Queries.
     */
    private function split_sql_file($sql) {
        $queries = array();
        $current_query = '';
        $in_string = false;
        $string_quote = '';
        
        // Split SQL into queries
        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            $next_char = isset($sql[$i + 1]) ? $sql[$i + 1] : '';
            
            // Handle strings
            if ($in_string) {
                $current_query .= $char;
                
                if ($char === $string_quote && $next_char !== $string_quote) {
                    $in_string = false;
                }
            } else {
                // Handle comments
                if ($char === '-' && $next_char === '-') {
                    // Skip to end of line
                    $current_query .= $char;
                    $i++;
                    $current_query .= $next_char;
                    
                    while (isset($sql[++$i]) && $sql[$i] !== "\n") {
                        $current_query .= $sql[$i];
                    }
                    
                    if (isset($sql[$i])) {
                        $current_query .= $sql[$i];
                    }
                } elseif ($char === '#') {
                    // Skip to end of line
                    $current_query .= $char;
                    
                    while (isset($sql[++$i]) && $sql[$i] !== "\n") {
                        $current_query .= $sql[$i];
                    }
                    
                    if (isset($sql[$i])) {
                        $current_query .= $sql[$i];
                    }
                } elseif ($char === '/' && $next_char === '*') {
                    // Skip to end of comment
                    $current_query .= $char;
                    $i++;
                    $current_query .= $next_char;
                    
                    while (isset($sql[++$i]) && ($sql[$i] !== '*' || !isset($sql[$i + 1]) || $sql[$i + 1] !== '/')) {
                        $current_query .= $sql[$i];
                    }
                    
                    if (isset($sql[$i])) {
                        $current_query .= $sql[$i];
                        
                        if (isset($sql[$i + 1])) {
                            $i++;
                            $current_query .= $sql[$i];
                        }
                    }
                } elseif ($char === "'" || $char === '"' || $char === '`') {
                    // Start of string
                    $in_string = true;
                    $string_quote = $char;
                    $current_query .= $char;
                } elseif ($char === ';') {
                    // End of query
                    $current_query .= $char;
                    $queries[] = $current_query;
                    $current_query = '';
                } else {
                    // Add character to current query
                    $current_query .= $char;
                }
            }
        }
        
        // Add last query if not empty
        if (!empty(trim($current_query))) {
            $queries[] = $current_query;
        }
        
        return $queries;
    }

    /**
     * Get mysqldump command path.
     *
     * @return string|false Command path or false if not found.
     */
    private function get_mysqldump_command_path() {
        // Check if exec is available
        if (!function_exists('exec')) {
            return false;
        }
        
        // Try to find mysqldump
        $output = array();
        $return_var = 0;
        
        exec('which mysqldump 2>&1', $output, $return_var);
        
        if ($return_var === 0 && !empty($output[0])) {
            return $output[0];
        }
        
        // Try common paths
        $common_paths = array(
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            '/usr/local/mysql/bin/mysqldump',
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp\\bin\\mysql\\mysql5.7.26\\bin\\mysqldump.exe',
        );
        
        foreach ($common_paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return false;
    }

    /**
     * Get backup files.
     *
     * @return array Backup files.
     */
    public function get_backup_files() {
        // Check if backup directory exists
        if (!is_dir($this->backup_dir)) {
            return array();
        }
        
        // Get backup files
        $files = glob($this->backup_dir . '/backup-*.sql');
        
        if (empty($files)) {
            return array();
        }
        
        // Sort files by date (newest first)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $backup_files = array();
        
        foreach ($files as $file) {
            $backup_files[] = array(
                'path' => $file,
                'name' => basename($file),
                'size' => size_format(filesize($file)),
                'date' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), filemtime($file)),
            );
        }
        
        return $backup_files;
    }

    /**
     * Delete backup file.
     *
     * @param string $file Backup file path.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    public function delete_backup_file($file) {
        $this->logger->info(sprintf('Deleting backup file: %s', basename($file)));
        
        // Check if file exists
        if (!file_exists($file)) {
            $this->logger->error(sprintf('Backup file not found: %s', $file));
            return new WP_Error(
                'backup_file_not_found',
                sprintf(__('Backup file not found: %s', 'demo-content-importer'), $file)
            );
        }
        
        // Delete file
        $result = unlink($file);
        
        if (!$result) {
            $this->logger->error(sprintf('Failed to delete backup file: %s', $file));
            return new WP_Error(
                'backup_file_delete_failed',
                sprintf(__('Failed to delete backup file: %s', 'demo-content-importer'), $file)
            );
        }
        
        $this->logger->success(sprintf('Backup file deleted: %s', basename($file)));
        
        return true;
    }
}