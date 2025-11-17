/**
 * AquaLuxe Theme Main JavaScript
 *
 * This is the main JavaScript file that imports all other JS modules.
 */

// Import Alpine.js and plugins
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import persist from '@alpinejs/persist';

// Import custom modules
import './utils/helpers';
import './components/dropdown';
import './components/modal';
import './components/tabs';
import './components/accordion';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.plugin(focus);
Alpine.plugin(persist);

// Global theme data
Alpine.store('theme', {
    init() {
        this.darkMode = Alpine.$persist(
            window.matchMedia('(prefers-color-scheme: dark)').matches
        ).as('aqualuxe_dark_mode');
        
        this.applyTheme();
        
        // Listen for OS theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('aqualuxe_dark_mode_manual')) {
                this.darkMode = e.matches;
                this.applyTheme();
            }
        });
    },
    
    darkMode: false,
    
    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('aqualuxe_dark_mode_manual', 'true');
        this.applyTheme();
    },
    
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});

// Initialize Alpine.js
Alpine.start();

// Document ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any non-Alpine components
    console.log('AquaLuxe theme initialized');
});