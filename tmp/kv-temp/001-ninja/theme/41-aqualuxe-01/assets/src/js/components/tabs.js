/**
 * AquaLuxe Theme Tabs Component
 *
 * Handles tab functionality for content organization.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all tabs that don't use Alpine.js
    initTabs();
});

/**
 * Initialize all tab components on the page
 */
function initTabs() {
    const tabContainers = document.querySelectorAll('.tabs-container:not([x-data])');
    
    tabContainers.forEach(container => {
        const tabList = container.querySelector('[role="tablist"]');
        const tabs = container.querySelectorAll('[role="tab"]');
        const panels = container.querySelectorAll('[role="tabpanel"]');
        
        if (!tabList || !tabs.length || !panels.length) return;
        
        // Set initial ARIA attributes
        tabs.forEach((tab, index) => {
            const panel = panels[index];
            
            if (!panel) return;
            
            const tabId = tab.id || `tab-${Math.floor(Math.random() * 1000)}-${index}`;
            const panelId = panel.id || `panel-${Math.floor(Math.random() * 1000)}-${index}`;
            
            tab.id = tabId;
            panel.id = panelId;
            
            tab.setAttribute('aria-controls', panelId);
            panel.setAttribute('aria-labelledby', tabId);
            
            // Set initial state (first tab active)
            if (index === 0) {
                tab.setAttribute('aria-selected', 'true');
                tab.classList.add('active');
                panel.classList.remove('hidden');
            } else {
                tab.setAttribute('aria-selected', 'false');
                tab.classList.remove('active');
                panel.classList.add('hidden');
            }
            
            // Add click event
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                activateTab(container, index);
            });
            
            // Add keyboard navigation
            tab.addEventListener('keydown', (e) => {
                handleTabKeydown(e, container, index, tabs.length);
            });
        });
    });
}

/**
 * Activate a specific tab
 * 
 * @param {HTMLElement} container - The tab container element
 * @param {number} index - The index of the tab to activate
 */
function activateTab(container, index) {
    const tabs = container.querySelectorAll('[role="tab"]');
    const panels = container.querySelectorAll('[role="tabpanel"]');
    
    if (!tabs[index] || !panels[index]) return;
    
    // Deactivate all tabs
    tabs.forEach(tab => {
        tab.setAttribute('aria-selected', 'false');
        tab.classList.remove('active');
    });
    
    // Hide all panels
    panels.forEach(panel => {
        panel.classList.add('hidden');
    });
    
    // Activate the selected tab
    tabs[index].setAttribute('aria-selected', 'true');
    tabs[index].classList.add('active');
    panels[index].classList.remove('hidden');
    
    // Trigger custom event
    container.dispatchEvent(new CustomEvent('tab:changed', {
        detail: { index, tabId: tabs[index].id, panelId: panels[index].id }
    }));
}

/**
 * Handle keyboard navigation for tabs
 * 
 * @param {Event} e - The keyboard event
 * @param {HTMLElement} container - The tab container element
 * @param {number} currentIndex - The index of the current tab
 * @param {number} tabCount - The total number of tabs
 */
function handleTabKeydown(e, container, currentIndex, tabCount) {
    const tabs = container.querySelectorAll('[role="tab"]');
    
    // Left/Up arrow: move to previous tab
    if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
        e.preventDefault();
        const prevIndex = currentIndex > 0 ? currentIndex - 1 : tabCount - 1;
        tabs[prevIndex].focus();
        activateTab(container, prevIndex);
    }
    
    // Right/Down arrow: move to next tab
    if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        e.preventDefault();
        const nextIndex = currentIndex < tabCount - 1 ? currentIndex + 1 : 0;
        tabs[nextIndex].focus();
        activateTab(container, nextIndex);
    }
    
    // Home: move to first tab
    if (e.key === 'Home') {
        e.preventDefault();
        tabs[0].focus();
        activateTab(container, 0);
    }
    
    // End: move to last tab
    if (e.key === 'End') {
        e.preventDefault();
        tabs[tabCount - 1].focus();
        activateTab(container, tabCount - 1);
    }
}

// Export for use in other modules
export { initTabs, activateTab };