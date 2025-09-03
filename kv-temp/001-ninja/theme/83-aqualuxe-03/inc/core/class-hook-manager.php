<?php
/**
 * Enterprise Theme Hook Manager
 * 
 * Advanced hook management system for modular theme architecture
 * Provides centralized hook registration, priority management, and debugging
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
 * Hook Manager Class
 * 
 * Implements:
 * - Centralized hook management
 * - Priority queue system
 * - Hook debugging and profiling
 * - Conditional hook registration
 * - Hook dependency management
 */
class Enterprise_Theme_Hook_Manager {
    
    /**
     * Registered hooks
     * 
     * @var array
     */
    private array $hooks = [];
    
    /**
     * Hook dependencies
     * 
     * @var array
     */
    private array $dependencies = [];
    
    /**
     * Conditional hooks
     * 
     * @var array
     */
    private array $conditional_hooks = [];
    
    /**
     * Hook execution times (for debugging)
     * 
     * @var array
     */
    private array $execution_times = [];
    
    /**
     * Debug mode
     * 
     * @var bool
     */
    private bool $debug_mode;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->debug_mode = defined('WP_DEBUG') && WP_DEBUG;
        
        if ($this->debug_mode) {
            $this->init_debugging();
        }
    }
    
    /**
     * Register a hook with advanced options
     * 
     * @param string $hook_name Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @param int $accepted_args Number of accepted arguments (default: 1)
     * @param array $options Additional options
     * @return string Hook ID for later reference
     */
    public function add_hook(
        string $hook_name,
        callable $callback,
        int $priority = 10,
        int $accepted_args = 1,
        array $options = []
    ): string {
        $hook_id = $this->generate_hook_id($hook_name, $callback, $priority);
        
        $hook_data = [
            'hook_name' => $hook_name,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
            'options' => array_merge([
                'once' => false,
                'condition' => null,
                'dependencies' => [],
                'group' => 'default',
                'description' => '',
            ], $options),
            'registered' => false,
            'executed_count' => 0,
        ];
        
        $this->hooks[$hook_id] = $hook_data;
        
        // Handle dependencies
        if (!empty($hook_data['options']['dependencies'])) {
            $this->dependencies[$hook_id] = $hook_data['options']['dependencies'];
        }
        
        // Handle conditional registration
        if ($hook_data['options']['condition']) {
            $this->conditional_hooks[$hook_id] = $hook_data['options']['condition'];
        } else {
            $this->register_hook($hook_id);
        }
        
        return $hook_id;
    }
    
    /**
     * Register action hook
     * 
     * @param string $hook_name Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @param int $accepted_args Number of accepted arguments (default: 1)
     * @param array $options Additional options
     * @return string Hook ID
     */
    public function add_action(
        string $hook_name,
        callable $callback,
        int $priority = 10,
        int $accepted_args = 1,
        array $options = []
    ): string {
        $options['type'] = 'action';
        return $this->add_hook($hook_name, $callback, $priority, $accepted_args, $options);
    }
    
    /**
     * Register filter hook
     * 
     * @param string $hook_name Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority (default: 10)
     * @param int $accepted_args Number of accepted arguments (default: 1)
     * @param array $options Additional options
     * @return string Hook ID
     */
    public function add_filter(
        string $hook_name,
        callable $callback,
        int $priority = 10,
        int $accepted_args = 1,
        array $options = []
    ): string {
        $options['type'] = 'filter';
        return $this->add_hook($hook_name, $callback, $priority, $accepted_args, $options);
    }
    
    /**
     * Remove a registered hook
     * 
     * @param string $hook_id Hook ID
     * @return bool Success status
     */
    public function remove_hook(string $hook_id): bool {
        if (!isset($this->hooks[$hook_id])) {
            return false;
        }
        
        $hook_data = $this->hooks[$hook_id];
        
        if ($hook_data['registered']) {
            $success = remove_filter(
                $hook_data['hook_name'],
                $hook_data['callback'],
                $hook_data['priority']
            );
            
            if (!$success) {
                return false;
            }
        }
        
        unset($this->hooks[$hook_id]);
        unset($this->dependencies[$hook_id]);
        unset($this->conditional_hooks[$hook_id]);
        
        return true;
    }
    
    /**
     * Register hooks based on conditions
     * 
     * @return void
     */
    public function process_conditional_hooks(): void {
        foreach ($this->conditional_hooks as $hook_id => $condition) {
            if ($this->evaluate_condition($condition)) {
                $this->register_hook($hook_id);
                unset($this->conditional_hooks[$hook_id]);
            }
        }
    }
    
    /**
     * Register hooks in a specific group
     * 
     * @param string $group Group name
     * @return void
     */
    public function register_group(string $group): void {
        foreach ($this->hooks as $hook_id => $hook_data) {
            if ($hook_data['options']['group'] === $group && !$hook_data['registered']) {
                $this->register_hook($hook_id);
            }
        }
    }
    
    /**
     * Disable hooks in a specific group
     * 
     * @param string $group Group name
     * @return void
     */
    public function disable_group(string $group): void {
        foreach ($this->hooks as $hook_id => $hook_data) {
            if ($hook_data['options']['group'] === $group && $hook_data['registered']) {
                $this->remove_hook($hook_id);
            }
        }
    }
    
    /**
     * Get hooks by group
     * 
     * @param string $group Group name
     * @return array Hooks in the group
     */
    public function get_group_hooks(string $group): array {
        $group_hooks = [];
        
        foreach ($this->hooks as $hook_id => $hook_data) {
            if ($hook_data['options']['group'] === $group) {
                $group_hooks[$hook_id] = $hook_data;
            }
        }
        
        return $group_hooks;
    }
    
    /**
     * Get hook statistics
     * 
     * @return array Hook statistics
     */
    public function get_statistics(): array {
        $stats = [
            'total_hooks' => count($this->hooks),
            'registered_hooks' => 0,
            'conditional_hooks' => count($this->conditional_hooks),
            'hooks_by_group' => [],
            'hooks_by_priority' => [],
            'execution_times' => $this->execution_times,
        ];
        
        foreach ($this->hooks as $hook_data) {
            if ($hook_data['registered']) {
                $stats['registered_hooks']++;
            }
            
            $group = $hook_data['options']['group'];
            if (!isset($stats['hooks_by_group'][$group])) {
                $stats['hooks_by_group'][$group] = 0;
            }
            $stats['hooks_by_group'][$group]++;
            
            $priority = $hook_data['priority'];
            if (!isset($stats['hooks_by_priority'][$priority])) {
                $stats['hooks_by_priority'][$priority] = 0;
            }
            $stats['hooks_by_priority'][$priority]++;
        }
        
        return $stats;
    }
    
    /**
     * Generate unique hook ID
     * 
     * @param string $hook_name Hook name
     * @param callable $callback Callback function
     * @param int $priority Priority
     * @return string Hook ID
     */
    private function generate_hook_id(string $hook_name, callable $callback, int $priority): string {
        $callback_string = $this->get_callback_string($callback);
        return md5($hook_name . $callback_string . $priority);
    }
    
    /**
     * Get string representation of callback
     * 
     * @param callable $callback Callback function
     * @return string Callback string
     */
    private function get_callback_string(callable $callback): string {
        if (is_string($callback)) {
            return $callback;
        }
        
        if (is_array($callback)) {
            if (is_object($callback[0])) {
                return get_class($callback[0]) . '::' . $callback[1];
            }
            return $callback[0] . '::' . $callback[1];
        }
        
        if ($callback instanceof Closure) {
            return 'Closure#' . spl_object_hash($callback);
        }
        
        return 'Unknown';
    }
    
    /**
     * Register a hook with WordPress
     * 
     * @param string $hook_id Hook ID
     * @return bool Success status
     */
    private function register_hook(string $hook_id): bool {
        if (!isset($this->hooks[$hook_id])) {
            return false;
        }
        
        $hook_data = &$this->hooks[$hook_id];
        
        // Check dependencies
        if (isset($this->dependencies[$hook_id])) {
            foreach ($this->dependencies[$hook_id] as $dependency) {
                if (!$this->is_dependency_satisfied($dependency)) {
                    return false;
                }
            }
        }
        
        // Wrap callback for debugging and execution counting
        $wrapped_callback = $this->wrap_callback($hook_id, $hook_data['callback'], $hook_data['options']);
        
        // Register with WordPress
        $success = add_filter(
            $hook_data['hook_name'],
            $wrapped_callback,
            $hook_data['priority'],
            $hook_data['accepted_args']
        );
        
        if ($success) {
            $hook_data['registered'] = true;
        }
        
        return $success;
    }
    
    /**
     * Wrap callback for additional functionality
     * 
     * @param string $hook_id Hook ID
     * @param callable $callback Original callback
     * @param array $options Hook options
     * @return callable Wrapped callback
     */
    private function wrap_callback(string $hook_id, callable $callback, array $options): callable {
        return function(...$args) use ($hook_id, $callback, $options) {
            $hook_data = &$this->hooks[$hook_id];
            
            // Check if this is a "once" hook that has already been executed
            if ($options['once'] && $hook_data['executed_count'] > 0) {
                return $args[0] ?? null;
            }
            
            // Start timing if in debug mode
            $start_time = $this->debug_mode ? microtime(true) : 0;
            
            // Execute callback
            try {
                $result = call_user_func_array($callback, $args);
                $hook_data['executed_count']++;
                
                // Record execution time
                if ($this->debug_mode) {
                    $execution_time = microtime(true) - $start_time;
                    $this->record_execution_time($hook_id, $execution_time);
                }
                
                // Remove hook if it's a "once" hook
                if ($options['once']) {
                    remove_filter(
                        $hook_data['hook_name'],
                        $this->hooks[$hook_id]['callback'],
                        $hook_data['priority']
                    );
                }
                
                return $result;
                
            } catch (Exception $e) {
                if ($this->debug_mode) {
                    error_log("Hook execution error in {$hook_id}: " . $e->getMessage());
                }
                
                // Return original value for filters
                return $args[0] ?? null;
            }
        };
    }
    
    /**
     * Evaluate a condition
     * 
     * @param mixed $condition Condition to evaluate
     * @return bool Condition result
     */
    private function evaluate_condition($condition): bool {
        if (is_callable($condition)) {
            return (bool) call_user_func($condition);
        }
        
        if (is_string($condition)) {
            // Check if it's a WordPress conditional function
            if (function_exists($condition)) {
                return (bool) call_user_func($condition);
            }
        }
        
        return (bool) $condition;
    }
    
    /**
     * Check if dependency is satisfied
     * 
     * @param string $dependency Dependency name
     * @return bool Dependency status
     */
    private function is_dependency_satisfied(string $dependency): bool {
        // Check if it's a function
        if (function_exists($dependency)) {
            return true;
        }
        
        // Check if it's a class
        if (class_exists($dependency)) {
            return true;
        }
        
        // Check if it's a WordPress hook that has run
        if (did_action($dependency) > 0) {
            return true;
        }
        
        // Check if it's a defined constant
        if (defined($dependency)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Record execution time for debugging
     * 
     * @param string $hook_id Hook ID
     * @param float $execution_time Execution time in seconds
     * @return void
     */
    private function record_execution_time(string $hook_id, float $execution_time): void {
        if (!isset($this->execution_times[$hook_id])) {
            $this->execution_times[$hook_id] = [
                'total_time' => 0,
                'average_time' => 0,
                'execution_count' => 0,
                'max_time' => 0,
                'min_time' => PHP_FLOAT_MAX,
            ];
        }
        
        $stats = &$this->execution_times[$hook_id];
        $stats['total_time'] += $execution_time;
        $stats['execution_count']++;
        $stats['average_time'] = $stats['total_time'] / $stats['execution_count'];
        $stats['max_time'] = max($stats['max_time'], $execution_time);
        $stats['min_time'] = min($stats['min_time'], $execution_time);
    }
    
    /**
     * Initialize debugging features
     * 
     * @return void
     */
    private function init_debugging(): void {
        // Add debug information to admin bar if user can manage options
        add_action('admin_bar_menu', [$this, 'add_debug_admin_bar_node'], 999);
        
        // Add debug information to footer in debug mode
        add_action('wp_footer', [$this, 'render_debug_info'], 999);
        add_action('admin_footer', [$this, 'render_debug_info'], 999);
    }
    
    /**
     * Add debug node to admin bar
     * 
     * @param WP_Admin_Bar $wp_admin_bar Admin bar instance
     * @return void
     */
    public function add_debug_admin_bar_node($wp_admin_bar): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $stats = $this->get_statistics();
        
        $wp_admin_bar->add_node([
            'id' => 'enterprise-theme-hooks',
            'title' => sprintf('Hooks: %d/%d', $stats['registered_hooks'], $stats['total_hooks']),
            'href' => '#',
            'meta' => [
                'title' => 'Enterprise Theme Hook Statistics',
            ],
        ]);
    }
    
    /**
     * Render debug information
     * 
     * @return void
     */
    public function render_debug_info(): void {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $stats = $this->get_statistics();
        
        echo '<!-- Enterprise Theme Hook Debug Info -->';
        echo '<!-- Total Hooks: ' . $stats['total_hooks'] . ' -->';
        echo '<!-- Registered Hooks: ' . $stats['registered_hooks'] . ' -->';
        echo '<!-- Conditional Hooks: ' . $stats['conditional_hooks'] . ' -->';
        
        if (!empty($stats['execution_times'])) {
            echo '<!-- Hook Execution Times: -->';
            foreach ($stats['execution_times'] as $hook_id => $times) {
                echo sprintf(
                    '<!-- %s: %d executions, avg: %.4fs, max: %.4fs -->',
                    $hook_id,
                    $times['execution_count'],
                    $times['average_time'],
                    $times['max_time']
                );
            }
        }
    }
}
