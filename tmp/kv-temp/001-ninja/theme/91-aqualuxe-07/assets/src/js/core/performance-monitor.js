/**
 * AquaLuxe Performance Monitor
 *
 * Performance monitoring and optimization system
 * Tracks metrics, lazy loading, and resource optimization
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Performance Monitor Class
 * 
 * Handles performance tracking and optimization
 */
export class PerformanceMonitor {
    /**
     * Constructor
     * 
     * @param {Object} config Performance monitor configuration
     */
    constructor(config = {}) {
        this.config = {
            debug: false,
            trackResources: true,
            trackUserTiming: true,
            trackLCP: true,
            trackFID: true,
            trackCLS: true,
            thresholds: {
                lcp: 2500, // ms
                fid: 100,  // ms
                cls: 0.1   // score
            },
            ...config
        };
        
        this.metrics = new Map();
        this.observers = new Map();
        this.marks = new Map();
        this.isSupported = this.checkSupport();
        
        if (this.isSupported) {
            this.init();
        }
    }

    /**
     * Check browser support
     * 
     * @return {boolean} True if supported
     */
    checkSupport() {
        return !!(
            window.performance &&
            window.performance.now &&
            window.performance.mark &&
            window.performance.measure
        );
    }

    /**
     * Initialize performance monitoring
     */
    init() {
        // Track initial page load metrics
        this.trackPageLoad();
        
        // Set up Core Web Vitals tracking
        if (this.config.trackLCP) this.trackLCP();
        if (this.config.trackFID) this.trackFID();
        if (this.config.trackCLS) this.trackCLS();
        
        // Track resource loading
        if (this.config.trackResources) this.trackResources();
        
        // Set up custom performance marks
        this.setupCustomMarks();
        
        if (this.config.debug) {
            console.log('📊 Performance Monitor initialized');
        }
    }

    /**
     * Track page load metrics
     */
    trackPageLoad() {
        if (document.readyState === 'complete') {
            this.recordPageLoadMetrics();
        } else {
            window.addEventListener('load', () => {
                this.recordPageLoadMetrics();
            });
        }
    }

    /**
     * Record page load metrics
     */
    recordPageLoadMetrics() {
        const navigation = performance.getEntriesByType('navigation')[0];
        
        if (navigation) {
            const metrics = {
                domContentLoaded: navigation.domContentLoadedEventEnd - navigation.fetchStart,
                loadComplete: navigation.loadEventEnd - navigation.fetchStart,
                firstByte: navigation.responseStart - navigation.fetchStart,
                domInteractive: navigation.domInteractive - navigation.fetchStart,
                redirectTime: navigation.redirectEnd - navigation.redirectStart,
                dnsTime: navigation.domainLookupEnd - navigation.domainLookupStart,
                connectTime: navigation.connectEnd - navigation.connectStart,
                serverTime: navigation.responseEnd - navigation.requestStart
            };
            
            this.metrics.set('pageLoad', metrics);
            
            if (this.config.debug) {
                console.log('📊 Page Load Metrics:', metrics);
            }
        }
    }

    /**
     * Track Largest Contentful Paint (LCP)
     */
    trackLCP() {
        if (!('PerformanceObserver' in window)) return;
        
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            const lastEntry = entries[entries.length - 1];
            
            this.metrics.set('lcp', {
                value: lastEntry.startTime,
                element: lastEntry.element,
                url: lastEntry.url
            });
            
            // Check threshold
            if (lastEntry.startTime > this.config.thresholds.lcp) {
                this.reportThresholdExceeded('lcp', lastEntry.startTime);
            }
            
            if (this.config.debug) {
                console.log('📊 LCP:', lastEntry.startTime);
            }
        });
        
        observer.observe({ entryTypes: ['largest-contentful-paint'] });
        this.observers.set('lcp', observer);
    }

    /**
     * Track First Input Delay (FID)
     */
    trackFID() {
        if (!('PerformanceObserver' in window)) return;
        
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            
            entries.forEach(entry => {
                this.metrics.set('fid', {
                    value: entry.processingStart - entry.startTime,
                    name: entry.name,
                    startTime: entry.startTime
                });
                
                const fid = entry.processingStart - entry.startTime;
                
                // Check threshold
                if (fid > this.config.thresholds.fid) {
                    this.reportThresholdExceeded('fid', fid);
                }
                
                if (this.config.debug) {
                    console.log('📊 FID:', fid);
                }
            });
        });
        
        observer.observe({ entryTypes: ['first-input'] });
        this.observers.set('fid', observer);
    }

    /**
     * Track Cumulative Layout Shift (CLS)
     */
    trackCLS() {
        if (!('PerformanceObserver' in window)) return;
        
        let clsValue = 0;
        let clsEntries = [];
        
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            
            entries.forEach(entry => {
                if (!entry.hadRecentInput) {
                    clsValue += entry.value;
                    clsEntries.push(entry);
                }
            });
            
            this.metrics.set('cls', {
                value: clsValue,
                entries: clsEntries
            });
            
            // Check threshold
            if (clsValue > this.config.thresholds.cls) {
                this.reportThresholdExceeded('cls', clsValue);
            }
            
            if (this.config.debug) {
                console.log('📊 CLS:', clsValue);
            }
        });
        
        observer.observe({ entryTypes: ['layout-shift'] });
        this.observers.set('cls', observer);
    }

    /**
     * Track resource loading
     */
    trackResources() {
        if (!('PerformanceObserver' in window)) return;
        
        const observer = new PerformanceObserver((list) => {
            const entries = list.getEntries();
            
            entries.forEach(entry => {
                this.recordResourceMetric(entry);
            });
        });
        
        observer.observe({ entryTypes: ['resource'] });
        this.observers.set('resource', observer);
    }

    /**
     * Record resource metric
     * 
     * @param {PerformanceResourceTiming} entry Resource timing entry
     */
    recordResourceMetric(entry) {
        const resourceMetrics = this.metrics.get('resources') || [];
        
        const metric = {
            name: entry.name,
            type: this.getResourceType(entry),
            duration: entry.duration,
            transferSize: entry.transferSize,
            encodedBodySize: entry.encodedBodySize,
            decodedBodySize: entry.decodedBodySize,
            initiatorType: entry.initiatorType,
            startTime: entry.startTime,
            domainLookupTime: entry.domainLookupEnd - entry.domainLookupStart,
            connectTime: entry.connectEnd - entry.connectStart,
            requestTime: entry.responseStart - entry.requestStart,
            responseTime: entry.responseEnd - entry.responseStart
        };
        
        resourceMetrics.push(metric);
        this.metrics.set('resources', resourceMetrics);
        
        // Track slow resources
        if (entry.duration > 1000) { // 1 second
            this.reportSlowResource(metric);
        }
    }

    /**
     * Get resource type
     * 
     * @param {PerformanceResourceTiming} entry Resource timing entry
     * @return {string} Resource type
     */
    getResourceType(entry) {
        const url = new URL(entry.name);
        const extension = url.pathname.split('.').pop().toLowerCase();
        
        const typeMap = {
            js: 'script',
            css: 'stylesheet',
            jpg: 'image',
            jpeg: 'image',
            png: 'image',
            gif: 'image',
            svg: 'image',
            webp: 'image',
            woff: 'font',
            woff2: 'font',
            ttf: 'font',
            otf: 'font'
        };
        
        return typeMap[extension] || entry.initiatorType || 'other';
    }

    /**
     * Set up custom performance marks
     */
    setupCustomMarks() {
        // Mark theme initialization
        this.mark('theme:init:start');
        
        // Mark when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.mark('theme:dom:ready');
            });
        } else {
            this.mark('theme:dom:ready');
        }
        
        // Mark when everything is loaded
        if (document.readyState === 'complete') {
            this.mark('theme:load:complete');
        } else {
            window.addEventListener('load', () => {
                this.mark('theme:load:complete');
            });
        }
    }

    /**
     * Create performance mark
     * 
     * @param {string} name Mark name
     * @param {Object} detail Additional details
     */
    mark(name, detail = {}) {
        if (!this.isSupported) return;
        
        try {
            performance.mark(name);
            
            this.marks.set(name, {
                timestamp: performance.now(),
                detail
            });
            
            if (this.config.debug) {
                console.log(`📊 Mark: ${name}`);
            }
        } catch (error) {
            console.error('❌ Performance mark error:', error);
        }
    }

    /**
     * Measure performance between marks
     * 
     * @param {string} name Measure name
     * @param {string} startMark Start mark name
     * @param {string} endMark End mark name
     */
    measure(name, startMark, endMark = null) {
        if (!this.isSupported) return;
        
        try {
            if (endMark) {
                performance.measure(name, startMark, endMark);
            } else {
                performance.measure(name, startMark);
            }
            
            const measure = performance.getEntriesByName(name, 'measure')[0];
            
            if (measure) {
                this.metrics.set(`measure:${name}`, {
                    duration: measure.duration,
                    startTime: measure.startTime
                });
                
                if (this.config.debug) {
                    console.log(`📊 Measure ${name}: ${measure.duration}ms`);
                }
            }
        } catch (error) {
            console.error('❌ Performance measure error:', error);
        }
    }

    /**
     * Report threshold exceeded
     * 
     * @param {string} metric Metric name
     * @param {number} value Metric value
     */
    reportThresholdExceeded(metric, value) {
        const threshold = this.config.thresholds[metric];
        
        console.warn(`⚠️ Performance threshold exceeded for ${metric}: ${value} (threshold: ${threshold})`);
        
        // Could send to analytics service here
        this.recordIssue(metric, 'threshold_exceeded', { value, threshold });
    }

    /**
     * Report slow resource
     * 
     * @param {Object} resource Resource metric
     */
    reportSlowResource(resource) {
        console.warn(`⚠️ Slow resource detected: ${resource.name} (${resource.duration}ms)`);
        
        this.recordIssue('resource', 'slow_loading', resource);
    }

    /**
     * Record performance issue
     * 
     * @param {string} category Issue category
     * @param {string} type Issue type
     * @param {Object} data Issue data
     */
    recordIssue(category, type, data) {
        const issues = this.metrics.get('issues') || [];
        
        issues.push({
            category,
            type,
            data,
            timestamp: Date.now(),
            url: window.location.href,
            userAgent: navigator.userAgent
        });
        
        this.metrics.set('issues', issues);
    }

    /**
     * Get all metrics
     * 
     * @return {Object} All collected metrics
     */
    getMetrics() {
        const metrics = Object.fromEntries(this.metrics);
        
        // Add browser performance entries
        if (this.isSupported) {
            metrics.performanceEntries = {
                navigation: performance.getEntriesByType('navigation'),
                resource: performance.getEntriesByType('resource'),
                measure: performance.getEntriesByType('measure'),
                mark: performance.getEntriesByType('mark')
            };
        }
        
        return metrics;
    }

    /**
     * Get metric by name
     * 
     * @param {string} name Metric name
     * @return {*} Metric value
     */
    getMetric(name) {
        return this.metrics.get(name);
    }

    /**
     * Clear all metrics
     */
    clearMetrics() {
        this.metrics.clear();
        this.marks.clear();
        
        if (this.isSupported) {
            performance.clearMarks();
            performance.clearMeasures();
        }
        
        if (this.config.debug) {
            console.log('📊 Metrics cleared');
        }
    }

    /**
     * Generate performance report
     * 
     * @return {Object} Performance report
     */
    generateReport() {
        const metrics = this.getMetrics();
        const report = {
            timestamp: Date.now(),
            url: window.location.href,
            userAgent: navigator.userAgent,
            viewport: {
                width: window.innerWidth,
                height: window.innerHeight
            },
            connection: this.getConnectionInfo(),
            coreWebVitals: {
                lcp: metrics.lcp?.value || null,
                fid: metrics.fid?.value || null,
                cls: metrics.cls?.value || null
            },
            pageLoad: metrics.pageLoad || null,
            resources: this.summarizeResources(metrics.resources || []),
            issues: metrics.issues || [],
            customMarks: Object.fromEntries(this.marks)
        };
        
        return report;
    }

    /**
     * Get connection information
     * 
     * @return {Object} Connection info
     */
    getConnectionInfo() {
        if (!navigator.connection) return null;
        
        return {
            effectiveType: navigator.connection.effectiveType,
            downlink: navigator.connection.downlink,
            rtt: navigator.connection.rtt,
            saveData: navigator.connection.saveData
        };
    }

    /**
     * Summarize resource metrics
     * 
     * @param {Array} resources Resource metrics
     * @return {Object} Resource summary
     */
    summarizeResources(resources) {
        const summary = {
            total: resources.length,
            totalSize: 0,
            totalDuration: 0,
            byType: {}
        };
        
        resources.forEach(resource => {
            summary.totalSize += resource.transferSize || 0;
            summary.totalDuration += resource.duration || 0;
            
            if (!summary.byType[resource.type]) {
                summary.byType[resource.type] = {
                    count: 0,
                    size: 0,
                    duration: 0
                };
            }
            
            summary.byType[resource.type].count++;
            summary.byType[resource.type].size += resource.transferSize || 0;
            summary.byType[resource.type].duration += resource.duration || 0;
        });
        
        return summary;
    }

    /**
     * Cleanup observers
     */
    cleanup() {
        this.observers.forEach(observer => {
            observer.disconnect();
        });
        
        this.observers.clear();
        
        if (this.config.debug) {
            console.log('📊 Performance Monitor cleaned up');
        }
    }
}
