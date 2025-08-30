/**
 * Admin JavaScript
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Media uploader functionality
    initMediaUploader();
    
    // Color picker
    initColorPicker();
    
    // Sortable lists
    initSortables();
    
    // Ajax forms
    initAjaxForms();
});

/**
 * Initialize media uploader
 */
function initMediaUploader() {
    const uploaders = document.querySelectorAll('.media-upload-btn');
    
    uploaders.forEach(uploader => {
        uploader.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetInput = document.querySelector(this.dataset.target);
            const targetPreview = document.querySelector(this.dataset.preview);
            
            if (typeof wp !== 'undefined' && wp.media) {
                const mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    if (targetInput) {
                        targetInput.value = attachment.url;
                    }
                    
                    if (targetPreview) {
                        targetPreview.src = attachment.url;
                        targetPreview.style.display = 'block';
                    }
                });
                
                mediaUploader.open();
            }
        });
    });
}

/**
 * Initialize color picker
 */
function initColorPicker() {
    if (typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
        jQuery('.color-picker').wpColorPicker();
    }
}

/**
 * Initialize sortable lists
 */
function initSortables() {
    const sortables = document.querySelectorAll('.sortable-list');
    
    if (typeof jQuery !== 'undefined' && jQuery.fn.sortable) {
        jQuery(sortables).sortable({
            placeholder: 'sortable-placeholder',
            update: function(event, ui) {
                // Handle order update
                const order = jQuery(this).sortable('toArray');
                // Save order via Ajax if needed
            }
        });
    }
}

/**
 * Initialize Ajax forms
 */
function initAjaxForms() {
    const forms = document.querySelectorAll('.ajax-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
            
            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotice('Settings saved successfully!', 'success');
                } else {
                    showNotice(data.data || 'Error saving settings', 'error');
                }
            })
            .catch(error => {
                showNotice('Network error occurred', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    });
}

/**
 * Show admin notice
 */
function showNotice(message, type = 'info') {
    const notice = document.createElement('div');
    notice.className = `notice notice-${type} is-dismissible`;
    notice.innerHTML = `
        <p>${message}</p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    `;
    
    const adminNotices = document.querySelector('.wrap h1');
    if (adminNotices) {
        adminNotices.parentNode.insertBefore(notice, adminNotices.nextSibling);
    }
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        notice.remove();
    }, 5000);
}
