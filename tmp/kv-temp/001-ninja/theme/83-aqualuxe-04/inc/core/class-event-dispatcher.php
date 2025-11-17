<?php
/**
 * Enterprise Theme Event Dispatcher
 * 
 * Advanced event system for decoupled module communication
 * Implements observer pattern with priority handling and async support
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
 * Event Dispatcher Class
 * 
 * Implements:
 * - Observer Pattern
 * - Event priority system
 * - Asynchronous event handling
 * - Event filtering and modification
 * - Event logging and debugging
 */
class Enterprise_Theme_Event_Dispatcher {
    
    /**
     * Event listeners
     * 
     * @var array
     */
    private array $listeners = [];
    
    /**
     * Event history (for debugging)
     * 
     * @var array
     */
    private array $event_history = [];
    
    /**
     * Async event queue
     * 
     * @var array
     */
    private array $async_queue = [];
    
    /**
     * Event filters
     * 
     * @var array
     */
    private array $filters = [];
    
    /**
     * Debug mode
     * 
     * @var bool
     */
    private bool $debug_mode;
    
    /**
     * Maximum event history size
     * 
     * @var int
     */
    private int $max_history_size = 1000;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->debug_mode = defined('WP_DEBUG') && WP_DEBUG;
        
        // Process async events on shutdown
        add_action('shutdown', [$this, 'process_async_events']);
    }
    
    /**
     * Add event listener
     * 
     * @param string $event_name Event name
     * @param callable $listener Listener callback
     * @param int $priority Priority (lower = earlier execution)
     * @param array $options Additional options
     * @return string Listener ID
     */
    public function add_listener(
        string $event_name,
        callable $listener,
        int $priority = 10,
        array $options = []
    ): string {
        $listener_id = $this->generate_listener_id($event_name, $listener, $priority);
        
        $listener_data = [
            'event_name' => $event_name,
            'callback' => $listener,
            'priority' => $priority,
            'options' => array_merge([
                'once' => false,
                'async' => false,
                'condition' => null,
                'group' => 'default',
                'description' => '',
            ], $options),
            'executed_count' => 0,
            'last_executed' => null,
        ];
        
        if (!isset($this->listeners[$event_name])) {
            $this->listeners[$event_name] = [];
        }
        
        $this->listeners[$event_name][$listener_id] = $listener_data;
        
        // Sort by priority
        uasort($this->listeners[$event_name], function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
        
        return $listener_id;
    }
    
    /**
     * Remove event listener
     * 
     * @param string $event_name Event name
     * @param string $listener_id Listener ID
     * @return bool Success status
     */
    public function remove_listener(string $event_name, string $listener_id): bool {
        if (!isset($this->listeners[$event_name][$listener_id])) {
            return false;
        }
        
        unset($this->listeners[$event_name][$listener_id]);
        
        // Clean up empty event arrays
        if (empty($this->listeners[$event_name])) {
            unset($this->listeners[$event_name]);
        }
        
        return true;
    }
    
    /**
     * Remove all listeners for an event
     * 
     * @param string $event_name Event name
     * @return int Number of listeners removed
     */
    public function remove_all_listeners(string $event_name): int {
        if (!isset($this->listeners[$event_name])) {
            return 0;
        }
        
        $count = count($this->listeners[$event_name]);
        unset($this->listeners[$event_name]);
        
        return $count;
    }
    
    /**
     * Dispatch event to listeners
     * 
     * @param string $event_name Event name
     * @param array $event_data Event data
     * @param array $options Dispatch options
     * @return Enterprise_Theme_Event Event object
     */
    public function dispatch(string $event_name, array $event_data = [], array $options = []): Enterprise_Theme_Event {
        $event = new Enterprise_Theme_Event($event_name, $event_data, $options);
        
        // Apply filters to event data
        $event = $this->apply_event_filters($event);
        
        // Record event in history
        if ($this->debug_mode) {
            $this->record_event($event);
        }
        
        // Check if event should be dispatched asynchronously
        if ($options['async'] ?? false) {
            $this->queue_async_event($event);
            return $event;
        }
        
        // Dispatch to listeners
        $this->dispatch_to_listeners($event);
        
        return $event;
    }
    
    /**
     * Dispatch event asynchronously
     * 
     * @param string $event_name Event name
     * @param array $event_data Event data
     * @param array $options Dispatch options
     * @return void
     */
    public function dispatch_async(string $event_name, array $event_data = [], array $options = []): void {
        $options['async'] = true;
        $this->dispatch($event_name, $event_data, $options);
    }
    
    /**
     * Add event filter
     * 
     * @param string $event_name Event name (or * for all events)
     * @param callable $filter Filter callback
     * @param int $priority Priority
     * @return string Filter ID
     */
    public function add_filter(string $event_name, callable $filter, int $priority = 10): string {
        $filter_id = uniqid('filter_');
        
        if (!isset($this->filters[$event_name])) {
            $this->filters[$event_name] = [];
        }
        
        $this->filters[$event_name][$filter_id] = [
            'callback' => $filter,
            'priority' => $priority,
        ];
        
        // Sort by priority
        uasort($this->filters[$event_name], function($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });
        
        return $filter_id;
    }
    
    /**
     * Remove event filter
     * 
     * @param string $event_name Event name
     * @param string $filter_id Filter ID
     * @return bool Success status
     */
    public function remove_filter(string $event_name, string $filter_id): bool {
        if (!isset($this->filters[$event_name][$filter_id])) {
            return false;
        }
        
        unset($this->filters[$event_name][$filter_id]);
        
        if (empty($this->filters[$event_name])) {
            unset($this->filters[$event_name]);
        }
        
        return true;
    }
    
    /**
     * Check if event has listeners
     * 
     * @param string $event_name Event name
     * @return bool True if has listeners
     */
    public function has_listeners(string $event_name): bool {
        return isset($this->listeners[$event_name]) && !empty($this->listeners[$event_name]);
    }
    
    /**
     * Get listeners for event
     * 
     * @param string $event_name Event name
     * @return array Event listeners
     */
    public function get_listeners(string $event_name): array {
        return $this->listeners[$event_name] ?? [];
    }
    
    /**
     * Get event statistics
     * 
     * @return array Event statistics
     */
    public function get_statistics(): array {
        $stats = [
            'total_listeners' => 0,
            'total_events' => count($this->listeners),
            'event_history_count' => count($this->event_history),
            'async_queue_count' => count($this->async_queue),
            'listeners_by_event' => [],
            'listeners_by_group' => [],
        ];
        
        foreach ($this->listeners as $event_name => $listeners) {
            $stats['listeners_by_event'][$event_name] = count($listeners);
            $stats['total_listeners'] += count($listeners);
            
            foreach ($listeners as $listener_data) {
                $group = $listener_data['options']['group'];
                if (!isset($stats['listeners_by_group'][$group])) {
                    $stats['listeners_by_group'][$group] = 0;
                }
                $stats['listeners_by_group'][$group]++;
            }
        }
        
        return $stats;
    }
    
    /**
     * Get event history
     * 
     * @param int $limit Number of events to return
     * @return array Recent events
     */
    public function get_event_history(int $limit = 50): array {
        return array_slice($this->event_history, -$limit);
    }
    
    /**
     * Clear event history
     * 
     * @return void
     */
    public function clear_event_history(): void {
        $this->event_history = [];
    }
    
    /**
     * Process async events
     * 
     * @return void
     */
    public function process_async_events(): void {
        while (!empty($this->async_queue)) {
            $event = array_shift($this->async_queue);
            $this->dispatch_to_listeners($event);
        }
    }
    
    /**
     * Generate unique listener ID
     * 
     * @param string $event_name Event name
     * @param callable $listener Listener callback
     * @param int $priority Priority
     * @return string Listener ID
     */
    private function generate_listener_id(string $event_name, callable $listener, int $priority): string {
        $callback_string = $this->get_callback_string($listener);
        return md5($event_name . $callback_string . $priority . uniqid());
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
     * Apply event filters
     * 
     * @param Enterprise_Theme_Event $event Event object
     * @return Enterprise_Theme_Event Filtered event
     */
    private function apply_event_filters(Enterprise_Theme_Event $event): Enterprise_Theme_Event {
        $event_name = $event->get_name();
        
        // Apply global filters (*)
        if (isset($this->filters['*'])) {
            foreach ($this->filters['*'] as $filter_data) {
                $event = call_user_func($filter_data['callback'], $event);
            }
        }
        
        // Apply event-specific filters
        if (isset($this->filters[$event_name])) {
            foreach ($this->filters[$event_name] as $filter_data) {
                $event = call_user_func($filter_data['callback'], $event);
            }
        }
        
        return $event;
    }
    
    /**
     * Dispatch event to listeners
     * 
     * @param Enterprise_Theme_Event $event Event object
     * @return void
     */
    private function dispatch_to_listeners(Enterprise_Theme_Event $event): void {
        $event_name = $event->get_name();
        
        if (!isset($this->listeners[$event_name])) {
            return;
        }
        
        foreach ($this->listeners[$event_name] as $listener_id => $listener_data) {
            // Check condition if specified
            if ($listener_data['options']['condition'] && !$this->evaluate_condition($listener_data['options']['condition'])) {
                continue;
            }
            
            // Check if this is a "once" listener that has already been executed
            if ($listener_data['options']['once'] && $listener_data['executed_count'] > 0) {
                continue;
            }
            
            try {
                // Execute listener
                call_user_func($listener_data['callback'], $event);
                
                // Update execution data
                $this->listeners[$event_name][$listener_id]['executed_count']++;
                $this->listeners[$event_name][$listener_id]['last_executed'] = time();
                
                // Remove "once" listeners after execution
                if ($listener_data['options']['once']) {
                    unset($this->listeners[$event_name][$listener_id]);
                }
                
                // Stop propagation if requested
                if ($event->is_propagation_stopped()) {
                    break;
                }
                
            } catch (Exception $e) {
                if ($this->debug_mode) {
                    error_log("Event listener error for {$event_name}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Queue event for async processing
     * 
     * @param Enterprise_Theme_Event $event Event object
     * @return void
     */
    private function queue_async_event(Enterprise_Theme_Event $event): void {
        $this->async_queue[] = $event;
    }
    
    /**
     * Record event in history
     * 
     * @param Enterprise_Theme_Event $event Event object
     * @return void
     */
    private function record_event(Enterprise_Theme_Event $event): void {
        $this->event_history[] = [
            'name' => $event->get_name(),
            'data' => $event->get_data(),
            'timestamp' => microtime(true),
            'memory_usage' => memory_get_usage(),
        ];
        
        // Limit history size
        if (count($this->event_history) > $this->max_history_size) {
            array_shift($this->event_history);
        }
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
        
        return (bool) $condition;
    }
}

/**
 * Event Class
 * 
 * Represents a single event with data and metadata
 */
class Enterprise_Theme_Event {
    
    /**
     * Event name
     * 
     * @var string
     */
    private string $name;
    
    /**
     * Event data
     * 
     * @var array
     */
    private array $data;
    
    /**
     * Event options
     * 
     * @var array
     */
    private array $options;
    
    /**
     * Propagation stopped flag
     * 
     * @var bool
     */
    private bool $propagation_stopped = false;
    
    /**
     * Event timestamp
     * 
     * @var float
     */
    private float $timestamp;
    
    /**
     * Constructor
     * 
     * @param string $name Event name
     * @param array $data Event data
     * @param array $options Event options
     */
    public function __construct(string $name, array $data = [], array $options = []) {
        $this->name = $name;
        $this->data = $data;
        $this->options = $options;
        $this->timestamp = microtime(true);
    }
    
    /**
     * Get event name
     * 
     * @return string Event name
     */
    public function get_name(): string {
        return $this->name;
    }
    
    /**
     * Get event data
     * 
     * @param string|null $key Specific data key (optional)
     * @return mixed Event data
     */
    public function get_data(?string $key = null) {
        if ($key === null) {
            return $this->data;
        }
        
        return $this->data[$key] ?? null;
    }
    
    /**
     * Set event data
     * 
     * @param string $key Data key
     * @param mixed $value Data value
     * @return void
     */
    public function set_data(string $key, $value): void {
        $this->data[$key] = $value;
    }
    
    /**
     * Get event options
     * 
     * @return array Event options
     */
    public function get_options(): array {
        return $this->options;
    }
    
    /**
     * Get event timestamp
     * 
     * @return float Event timestamp
     */
    public function get_timestamp(): float {
        return $this->timestamp;
    }
    
    /**
     * Stop event propagation
     * 
     * @return void
     */
    public function stop_propagation(): void {
        $this->propagation_stopped = true;
    }
    
    /**
     * Check if propagation is stopped
     * 
     * @return bool Propagation status
     */
    public function is_propagation_stopped(): bool {
        return $this->propagation_stopped;
    }
}
