/**
 * Admin JavaScript file for the AquaLuxe theme
 * 
 * This file handles the admin functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin functionality
    initAdminFunctionality();
});

/**
 * Initialize admin functionality
 */
function initAdminFunctionality() {
    // Meta box functionality
    initMetaBoxes();
    
    // Admin notices
    initAdminNotices();
}

/**
 * Initialize meta boxes
 */
function initMetaBoxes() {
    // Page settings meta box
    const pageSettingsMetaBox = document.getElementById('aqualuxe-page-settings');
    
    if (pageSettingsMetaBox) {
        // Header style selection
        const headerStyleSelect = document.getElementById('aqualuxe-header-style');
        const headerStylePreview = document.getElementById('aqualuxe-header-style-preview');
        
        if (headerStyleSelect && headerStylePreview) {
            headerStyleSelect.addEventListener('change', function() {
                const selectedStyle = this.value;
                const previewImage = headerStylePreview.querySelector('img');
                
                if (previewImage) {
                    previewImage.src = aqualuxeAdmin.themeUri + '/assets/dist/images/admin/header-' + selectedStyle + '.jpg';
                }
            });
        }
        
        // Footer style selection
        const footerStyleSelect = document.getElementById('aqualuxe-footer-style');
        const footerStylePreview = document.getElementById('aqualuxe-footer-style-preview');
        
        if (footerStyleSelect && footerStylePreview) {
            footerStyleSelect.addEventListener('change', function() {
                const selectedStyle = this.value;
                const previewImage = footerStylePreview.querySelector('img');
                
                if (previewImage) {
                    previewImage.src = aqualuxeAdmin.themeUri + '/assets/dist/images/admin/footer-' + selectedStyle + '.jpg';
                }
            });
        }
        
        // Page layout selection
        const pageLayoutRadios = document.querySelectorAll('input[name="aqualuxe_page_layout"]');
        const pageLayoutPreview = document.getElementById('aqualuxe-page-layout-preview');
        
        if (pageLayoutRadios.length > 0 && pageLayoutPreview) {
            pageLayoutRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        const selectedLayout = this.value;
                        const previewImage = pageLayoutPreview.querySelector('img');
                        
                        if (previewImage) {
                            previewImage.src = aqualuxeAdmin.themeUri + '/assets/dist/images/admin/layout-' + selectedLayout + '.jpg';
                        }
                    }
                });
            });
        }
    }
}

/**
 * Initialize admin notices
 */
function initAdminNotices() {
    // Make admin notices dismissible
    const dismissibleNotices = document.querySelectorAll('.aqualuxe-admin-notice.is-dismissible');
    
    dismissibleNotices.forEach(function(notice) {
        const dismissButton = notice.querySelector('.notice-dismiss');
        
        if (dismissButton) {
            dismissButton.addEventListener('click', function() {
                const noticeId = notice.dataset.noticeId;
                
                // Send AJAX request to dismiss notice
                const xhr = new XMLHttpRequest();
                xhr.open('POST', ajaxurl);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('action=aqualuxe_dismiss_admin_notice&notice_id=' + noticeId + '&nonce=' + aqualuxeAdmin.nonce);
                
                // Hide notice
                notice.style.display = 'none';
            });
        }
    });
}