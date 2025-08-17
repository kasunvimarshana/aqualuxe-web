/**
 * AquaLuxe Theme Accordion Component
 *
 * Handles accordion functionality for collapsible content sections.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all accordions that don't use Alpine.js
    initAccordions();
});

/**
 * Initialize all accordion components on the page
 */
function initAccordions() {
    const accordions = document.querySelectorAll('.accordion:not([x-data])');
    
    accordions.forEach(accordion => {
        const items = accordion.querySelectorAll('.accordion-item');
        const allowMultiple = accordion.hasAttribute('data-allow-multiple');
        
        items.forEach((item, index) => {
            const header = item.querySelector('.accordion-header');
            const button = item.querySelector('.accordion-button');
            const panel = item.querySelector('.accordion-panel');
            
            if (!header || !button || !panel) return;
            
            // Set unique IDs if not present
            const buttonId = button.id || `accordion-button-${Math.floor(Math.random() * 1000)}-${index}`;
            const panelId = panel.id || `accordion-panel-${Math.floor(Math.random() * 1000)}-${index}`;
            
            button.id = buttonId;
            panel.id = panelId;
            
            // Set ARIA attributes
            button.setAttribute('aria-controls', panelId);
            panel.setAttribute('aria-labelledby', buttonId);
            
            // Set initial state
            const isExpanded = button.getAttribute('aria-expanded') === 'true';
            
            if (isExpanded) {
                panel.classList.remove('hidden');
                item.classList.add('is-active');
            } else {
                button.setAttribute('aria-expanded', 'false');
                panel.classList.add('hidden');
                item.classList.remove('is-active');
            }
            
            // Add click event
            button.addEventListener('click', () => {
                toggleAccordionItem(item, accordion, allowMultiple);
            });
        });
    });
}

/**
 * Toggle an accordion item's expanded/collapsed state
 * 
 * @param {HTMLElement} item - The accordion item element
 * @param {HTMLElement} accordion - The parent accordion element
 * @param {boolean} allowMultiple - Whether multiple items can be expanded at once
 */
function toggleAccordionItem(item, accordion, allowMultiple) {
    const button = item.querySelector('.accordion-button');
    const panel = item.querySelector('.accordion-panel');
    
    if (!button || !panel) return;
    
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    
    // If not allowing multiple open items, close all other items
    if (!allowMultiple && !isExpanded) {
        const otherItems = accordion.querySelectorAll('.accordion-item');
        
        otherItems.forEach(otherItem => {
            if (otherItem !== item) {
                const otherButton = otherItem.querySelector('.accordion-button');
                const otherPanel = otherItem.querySelector('.accordion-panel');
                
                if (otherButton && otherPanel) {
                    otherButton.setAttribute('aria-expanded', 'false');
                    otherPanel.classList.add('hidden');
                    otherItem.classList.remove('is-active');
                }
            }
        });
    }
    
    // Toggle current item
    if (isExpanded) {
        button.setAttribute('aria-expanded', 'false');
        panel.classList.add('hidden');
        item.classList.remove('is-active');
    } else {
        button.setAttribute('aria-expanded', 'true');
        panel.classList.remove('hidden');
        item.classList.add('is-active');
    }
    
    // Trigger custom event
    accordion.dispatchEvent(new CustomEvent('accordion:toggled', {
        detail: { 
            item, 
            isExpanded: !isExpanded,
            buttonId: button.id,
            panelId: panel.id
        }
    }));
}

/**
 * Expand an accordion item
 * 
 * @param {HTMLElement} item - The accordion item element
 * @param {HTMLElement} accordion - The parent accordion element
 * @param {boolean} allowMultiple - Whether multiple items can be expanded at once
 */
function expandAccordionItem(item, accordion, allowMultiple) {
    const button = item.querySelector('.accordion-button');
    const panel = item.querySelector('.accordion-panel');
    
    if (!button || !panel || button.getAttribute('aria-expanded') === 'true') return;
    
    // If not allowing multiple open items, close all other items
    if (!allowMultiple) {
        const otherItems = accordion.querySelectorAll('.accordion-item');
        
        otherItems.forEach(otherItem => {
            if (otherItem !== item) {
                const otherButton = otherItem.querySelector('.accordion-button');
                const otherPanel = otherItem.querySelector('.accordion-panel');
                
                if (otherButton && otherPanel) {
                    otherButton.setAttribute('aria-expanded', 'false');
                    otherPanel.classList.add('hidden');
                    otherItem.classList.remove('is-active');
                }
            }
        });
    }
    
    // Expand current item
    button.setAttribute('aria-expanded', 'true');
    panel.classList.remove('hidden');
    item.classList.add('is-active');
    
    // Trigger custom event
    accordion.dispatchEvent(new CustomEvent('accordion:expanded', {
        detail: { 
            item,
            buttonId: button.id,
            panelId: panel.id
        }
    }));
}

/**
 * Collapse an accordion item
 * 
 * @param {HTMLElement} item - The accordion item element
 */
function collapseAccordionItem(item) {
    const button = item.querySelector('.accordion-button');
    const panel = item.querySelector('.accordion-panel');
    
    if (!button || !panel || button.getAttribute('aria-expanded') === 'false') return;
    
    // Collapse item
    button.setAttribute('aria-expanded', 'false');
    panel.classList.add('hidden');
    item.classList.remove('is-active');
    
    // Trigger custom event
    const accordion = item.closest('.accordion');
    if (accordion) {
        accordion.dispatchEvent(new CustomEvent('accordion:collapsed', {
            detail: { 
                item,
                buttonId: button.id,
                panelId: panel.id
            }
        }));
    }
}

// Export for use in other modules
export { initAccordions, toggleAccordionItem, expandAccordionItem, collapseAccordionItem };