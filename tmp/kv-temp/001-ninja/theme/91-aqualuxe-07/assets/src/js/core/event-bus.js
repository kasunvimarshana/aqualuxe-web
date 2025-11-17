/**
 * AquaLuxe Event Bus
 *
 * Central event management system for the AquaLuxe theme
 * Provides decoupled communication between modules
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Event Bus Class
 * 
 * Handles event dispatching and listening across modules
 * Supports namespaced events and debugging
 */
export class EventBus {
    /**
     * Constructor
     * 
     * @param {Object} config Event bus configuration
     */
    constructor(config = {}) {
        this.config = {
            debug: false,
            namespace: 'aqualuxe',
            maxListeners: 50,
            ...config
        };
        
        this.listeners = new Map();
        this.onceListeners = new Map();
        this.eventHistory = [];
        this.isEnabled = true;
    }

    /**
     * Add event listener
     * 
     * @param {string} event Event name
     * @param {Function} callback Event callback
     * @param {Object} options Listener options
     * @return {Function} Unsubscribe function
     */
    on(event, callback, options = {}) {
        if (!this.isEnabled) return () => {};
        
        const eventName = this.namespaceEvent(event);
        
        if (!this.listeners.has(eventName)) {
            this.listeners.set(eventName, []);
        }
        
        const listeners = this.listeners.get(eventName);
        
        // Check max listeners limit
        if (listeners.length >= this.config.maxListeners) {
            console.warn(`⚠️ Max listeners (${this.config.maxListeners}) reached for event: ${eventName}`);
            return () => {};
        }
        
        const listener = {
            callback,
            priority: options.priority || 0,
            context: options.context || null,
            once: false
        };
        
        // Insert listener based on priority (higher priority first)
        const insertIndex = listeners.findIndex(l => l.priority < listener.priority);
        if (insertIndex === -1) {
            listeners.push(listener);
        } else {
            listeners.splice(insertIndex, 0, listener);
        }
        
        if (this.config.debug) {
            console.log(`📡 Listener added for: ${eventName}`);
        }
        
        // Return unsubscribe function
        return () => this.off(event, callback);
    }

    /**
     * Add one-time event listener
     * 
     * @param {string} event Event name
     * @param {Function} callback Event callback
     * @param {Object} options Listener options
     * @return {Function} Unsubscribe function
     */
    once(event, callback, options = {}) {
        if (!this.isEnabled) return () => {};
        
        const eventName = this.namespaceEvent(event);
        
        if (!this.onceListeners.has(eventName)) {
            this.onceListeners.set(eventName, []);
        }
        
        const listener = {
            callback,
            priority: options.priority || 0,
            context: options.context || null
        };
        
        this.onceListeners.get(eventName).push(listener);
        
        if (this.config.debug) {
            console.log(`📡 Once listener added for: ${eventName}`);
        }
        
        // Return unsubscribe function
        return () => this.offOnce(event, callback);
    }

    /**
     * Remove event listener
     * 
     * @param {string} event Event name
     * @param {Function} callback Event callback
     */
    off(event, callback) {
        if (!this.isEnabled) return;
        
        const eventName = this.namespaceEvent(event);
        
        if (this.listeners.has(eventName)) {
            const listeners = this.listeners.get(eventName);
            const index = listeners.findIndex(l => l.callback === callback);
            
            if (index !== -1) {
                listeners.splice(index, 1);
                
                if (this.config.debug) {
                    console.log(`📡 Listener removed for: ${eventName}`);
                }
                
                // Clean up empty arrays
                if (listeners.length === 0) {
                    this.listeners.delete(eventName);
                }
            }
        }
    }

    /**
     * Remove one-time event listener
     * 
     * @param {string} event Event name
     * @param {Function} callback Event callback
     */
    offOnce(event, callback) {
        if (!this.isEnabled) return;
        
        const eventName = this.namespaceEvent(event);
        
        if (this.onceListeners.has(eventName)) {
            const listeners = this.onceListeners.get(eventName);
            const index = listeners.findIndex(l => l.callback === callback);
            
            if (index !== -1) {
                listeners.splice(index, 1);
                
                if (this.config.debug) {
                    console.log(`📡 Once listener removed for: ${eventName}`);
                }
                
                // Clean up empty arrays
                if (listeners.length === 0) {
                    this.onceListeners.delete(eventName);
                }
            }
        }
    }

    /**
     * Emit event
     * 
     * @param {string} event Event name
     * @param {*} data Event data
     * @param {Object} options Emit options
     */
    emit(event, data = null, options = {}) {
        if (!this.isEnabled) return;
        
        const eventName = this.namespaceEvent(event);
        const eventData = {
            type: eventName,
            data,
            timestamp: Date.now(),
            target: options.target || null,
            bubbles: options.bubbles !== false,
            cancelable: options.cancelable !== false,
            defaultPrevented: false,
            stopPropagation: false
        };
        
        // Add to event history
        this.addToHistory(eventData);
        
        if (this.config.debug) {
            console.log(`📡 Event emitted: ${eventName}`, data);
        }
        
        // Process regular listeners
        this.processListeners(eventName, eventData);
        
        // Process one-time listeners
        this.processOnceListeners(eventName, eventData);
    }

    /**
     * Process regular listeners
     * 
     * @param {string} eventName Event name
     * @param {Object} eventData Event data
     */
    processListeners(eventName, eventData) {
        if (!this.listeners.has(eventName)) return;
        
        const listeners = [...this.listeners.get(eventName)]; // Copy array to avoid issues with modifications
        
        for (const listener of listeners) {
            if (eventData.stopPropagation) break;
            
            try {
                if (listener.context) {
                    listener.callback.call(listener.context, eventData);
                } else {
                    listener.callback(eventData);
                }
            } catch (error) {
                console.error(`❌ Error in event listener for ${eventName}:`, error);
            }
        }
    }

    /**
     * Process one-time listeners
     * 
     * @param {string} eventName Event name
     * @param {Object} eventData Event data
     */
    processOnceListeners(eventName, eventData) {
        if (!this.onceListeners.has(eventName)) return;
        
        const listeners = [...this.onceListeners.get(eventName)]; // Copy array
        
        // Clear the once listeners first
        this.onceListeners.delete(eventName);
        
        // Sort by priority
        listeners.sort((a, b) => b.priority - a.priority);
        
        for (const listener of listeners) {
            if (eventData.stopPropagation) break;
            
            try {
                if (listener.context) {
                    listener.callback.call(listener.context, eventData);
                } else {
                    listener.callback(eventData);
                }
            } catch (error) {
                console.error(`❌ Error in once event listener for ${eventName}:`, error);
            }
        }
    }

    /**
     * Add event to history
     * 
     * @param {Object} eventData Event data
     */
    addToHistory(eventData) {
        this.eventHistory.push(eventData);
        
        // Keep history limited
        if (this.eventHistory.length > 100) {
            this.eventHistory.shift();
        }
    }

    /**
     * Namespace event name
     * 
     * @param {string} event Event name
     * @return {string} Namespaced event name
     */
    namespaceEvent(event) {
        if (event.includes(':')) {
            return event; // Already namespaced
        }
        
        return `${this.config.namespace}:${event}`;
    }

    /**
     * Remove all listeners for an event
     * 
     * @param {string} event Event name
     */
    removeAllListeners(event = null) {
        if (event) {
            const eventName = this.namespaceEvent(event);
            this.listeners.delete(eventName);
            this.onceListeners.delete(eventName);
            
            if (this.config.debug) {
                console.log(`📡 All listeners removed for: ${eventName}`);
            }
        } else {
            this.listeners.clear();
            this.onceListeners.clear();
            
            if (this.config.debug) {
                console.log('📡 All listeners removed');
            }
        }
    }

    /**
     * Get listener count for an event
     * 
     * @param {string} event Event name
     * @return {number} Listener count
     */
    listenerCount(event) {
        const eventName = this.namespaceEvent(event);
        const regularCount = this.listeners.has(eventName) ? this.listeners.get(eventName).length : 0;
        const onceCount = this.onceListeners.has(eventName) ? this.onceListeners.get(eventName).length : 0;
        
        return regularCount + onceCount;
    }

    /**
     * Get all event names with listeners
     * 
     * @return {Array} Array of event names
     */
    eventNames() {
        const names = new Set([
            ...this.listeners.keys(),
            ...this.onceListeners.keys()
        ]);
        
        return Array.from(names);
    }

    /**
     * Get event history
     * 
     * @param {number} limit Number of events to return
     * @return {Array} Event history
     */
    getHistory(limit = 10) {
        return this.eventHistory.slice(-limit);
    }

    /**
     * Clear event history
     */
    clearHistory() {
        this.eventHistory = [];
        
        if (this.config.debug) {
            console.log('📡 Event history cleared');
        }
    }

    /**
     * Enable event bus
     */
    enable() {
        this.isEnabled = true;
        
        if (this.config.debug) {
            console.log('📡 Event bus enabled');
        }
    }

    /**
     * Disable event bus
     */
    disable() {
        this.isEnabled = false;
        
        if (this.config.debug) {
            console.log('📡 Event bus disabled');
        }
    }

    /**
     * Check if event bus is enabled
     * 
     * @return {boolean} True if enabled
     */
    isEventBusEnabled() {
        return this.isEnabled;
    }

    /**
     * Get debug information
     * 
     * @return {Object} Debug information
     */
    getDebugInfo() {
        return {
            enabled: this.isEnabled,
            config: this.config,
            listenerCount: this.listeners.size,
            onceListenerCount: this.onceListeners.size,
            historyLength: this.eventHistory.length,
            eventNames: this.eventNames()
        };
    }
}
