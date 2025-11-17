/**
 * AquaLuxe Theme Admin JavaScript
 * 
 * JavaScript functionality for the WordPress admin area.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin functionality
    initMediaUploader();
    initColorPickers();
    initSortableLists();
    initAdminTabs();
});

/**
 * Initialize WordPress Media Uploader for custom fields
 */
function initMediaUploader() {
    // Check if we're on an admin page
    if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
        return;
    }
    
    // Handle all media upload buttons
    const mediaButtons = document.querySelectorAll('.aqualuxe-media-upload');
    
    if (mediaButtons.length) {
        mediaButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetInput = document.getElementById(this.dataset.target);
                const targetPreview = document.getElementById(this.dataset.preview);
                
                // Create media frame
                const mediaFrame = wp.media({
                    title: 'Select or Upload Media',
                    button: {
                        text: 'Use this media'
                    },
                    multiple: false
                });
                
                // When image selected, run callback
                mediaFrame.on('select', function() {
                    const attachment = mediaFrame.state().get('selection').first().toJSON();
                    
                    if (targetInput) {
                        targetInput.value = attachment.id;
                    }
                    
                    if (targetPreview) {
                        if (attachment.type === 'image') {
                            targetPreview.innerHTML = `<img src="${attachment.url}" alt="Preview" style="max-width: 100%; height: auto;">`;
                        } else {
                            targetPreview.innerHTML = `<div class="file-preview">${attachment.filename}</div>`;
                        }
                    }
                });
                
                // Open media frame
                mediaFrame.open();
            });
        });
        
        // Handle remove buttons
        const removeButtons = document.querySelectorAll('.aqualuxe-media-remove');
        
        if (removeButtons.length) {
            removeButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetInput = document.getElementById(this.dataset.target);
                    const targetPreview = document.getElementById(this.dataset.preview);
                    
                    if (targetInput) {
                        targetInput.value = '';
                    }
                    
                    if (targetPreview) {
                        targetPreview.innerHTML = '';
                    }
                });
            });
        }
    }
}

/**
 * Initialize WordPress Color Pickers
 */
function initColorPickers() {
    // Check if we're on an admin page with color picker support
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.wpColorPicker === 'undefined') {
        return;
    }
    
    // Initialize all color pickers
    jQuery('.aqualuxe-color-picker').wpColorPicker();
}

/**
 * Initialize Sortable Lists for admin interfaces
 */
function initSortableLists() {
    // Check if we're on an admin page with sortable support
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.sortable === 'undefined') {
        return;
    }
    
    // Initialize sortable lists
    jQuery('.aqualuxe-sortable').sortable({
        update: function(event, ui) {
            // Update order in hidden field
            const items = jQuery(this).sortable('toArray', { attribute: 'data-id' });
            const orderInput = document.getElementById(jQuery(this).data('order-input'));
            
            if (orderInput) {
                orderInput.value = items.join(',');
            }
        }
    });
}

/**
 * Initialize Admin Tabs
 */
function initAdminTabs() {
    const tabContainers = document.querySelectorAll('.aqualuxe-admin-tabs');
    
    if (tabContainers.length) {
        tabContainers.forEach(container => {
            const tabs = container.querySelectorAll('.aqualuxe-tab');
            const tabContents = container.querySelectorAll('.aqualuxe-tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Hide all tab contents
                    tabContents.forEach(content => content.classList.remove('active'));
                    
                    // Add active class to current tab
                    this.classList.add('active');
                    
                    // Show current tab content
                    const targetContent = container.querySelector(this.dataset.target);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                    
                    // Save active tab to localStorage if needed
                    if (container.dataset.remember) {
                        localStorage.setItem(`aqualuxe-active-tab-${container.dataset.remember}`, this.dataset.target);
                    }
                });
            });
            
            // Check for remembered tab
            if (container.dataset.remember) {
                const rememberedTab = localStorage.getItem(`aqualuxe-active-tab-${container.dataset.remember}`);
                
                if (rememberedTab) {
                    const activeTab = container.querySelector(`[data-target="${rememberedTab}"]`);
                    
                    if (activeTab) {
                        activeTab.click();
                        return;
                    }
                }
            }
            
            // Activate first tab by default
            if (tabs.length) {
                tabs[0].click();
            }
        });
    }
}