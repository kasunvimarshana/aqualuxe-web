/**
 * AquaLuxe Admin JavaScript
 * 
 * Admin interface functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🐠 AquaLuxe Admin JS loaded');
    
    // Initialize color pickers
    if (typeof jQuery !== 'undefined' && jQuery.wp && jQuery.wp.wpColorPicker) {
        jQuery('.color-picker').wpColorPicker();
    }
    
    // Initialize media uploader
    initMediaUploader();
    
    // Initialize admin tabs
    initAdminTabs();
});

function initMediaUploader() {
    const mediaButtons = document.querySelectorAll('.media-upload-button');
    
    mediaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const frame = wp.media({
                title: 'Select Media',
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                const input = button.nextElementSibling;
                
                if (input) {
                    input.value = attachment.url;
                    
                    // Trigger change event
                    input.dispatchEvent(new Event('change'));
                }
            });
            
            frame.open();
        });
    });
}

function initAdminTabs() {
    const tabButtons = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabButtons.forEach(btn => btn.classList.remove('nav-tab-active'));
            tabContents.forEach(content => content.style.display = 'none');
            
            // Add active class to clicked tab
            button.classList.add('nav-tab-active');
            
            // Show corresponding content
            const targetId = button.getAttribute('href').substring(1);
            const targetContent = document.getElementById(targetId);
            
            if (targetContent) {
                targetContent.style.display = 'block';
            }
        });
    });
}