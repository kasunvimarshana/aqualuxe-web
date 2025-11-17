/**
 * Admin JavaScript for AquaLuxe Theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

/**
 * AquaLuxe Admin Class
 */
class AquaLuxeAdmin {
    constructor() {
        this.init();
    }

    /**
     * Initialize admin functionality
     */
    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }
    }

    /**
     * Execute when DOM is ready
     */
    onReady() {
        this.initTabs();
        this.initDemoImporter();
        this.initFormValidation();
        this.initColorPickers();
        this.initMediaUploaders();
        this.initSortables();
        
        console.log('AquaLuxe Admin initialized');
    }

    /**
     * Initialize admin tabs
     */
    initTabs() {
        const tabButtons = document.querySelectorAll('.nav-tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const targetTab = button.dataset.tab;
                
                // Remove active class from all tabs and contents
                tabButtons.forEach(btn => btn.classList.remove('nav-tab-active'));
                tabContents.forEach(content => content.style.display = 'none');
                
                // Add active class to clicked tab
                button.classList.add('nav-tab-active');
                
                // Show target content
                const targetContent = document.getElementById(targetTab);
                if (targetContent) {
                    targetContent.style.display = 'block';
                }
            });
        });
    }

    /**
     * Initialize demo content importer
     */
    initDemoImporter() {
        const importButtons = document.querySelectorAll('.import-demo-content');
        
        importButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const demoType = button.dataset.demo;
                this.importDemoContent(demoType, button);
            });
        });

        // Initialize reset functionality
        const resetButton = document.querySelector('.reset-demo-content');
        if (resetButton) {
            resetButton.addEventListener('click', (e) => {
                e.preventDefault();
                
                if (confirm('Are you sure you want to reset all demo content? This action cannot be undone.')) {
                    this.resetDemoContent(resetButton);
                }
            });
        }
    }

    /**
     * Import demo content
     */
    importDemoContent(demoType, button) {
        const originalText = button.textContent;
        button.textContent = 'Importing...';
        button.disabled = true;

        // Create or update progress bar
        let progressBar = document.querySelector('.import-progress');
        if (!progressBar) {
            progressBar = document.createElement('div');
            progressBar.className = 'import-progress progress-bar';
            progressBar.innerHTML = '<div class="progress-fill" style="width: 0%"></div>';
            button.parentNode.appendChild(progressBar);
        }

        // Start import process
        this.performImport(demoType, progressBar)
            .then(() => {
                button.textContent = 'Import Complete!';
                button.style.backgroundColor = '#00a32a';
                
                // Show success message
                this.showNotice('Demo content imported successfully!', 'success');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.backgroundColor = '';
                    progressBar.remove();
                }, 3000);
            })
            .catch((error) => {
                console.error('Import error:', error);
                
                button.textContent = 'Import Failed';
                button.style.backgroundColor = '#d63638';
                
                this.showNotice('Failed to import demo content. Please try again.', 'error');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.backgroundColor = '';
                }, 3000);
            });
    }

    /**
     * Perform import via AJAX
     */
    async performImport(demoType, progressBar) {
        const steps = ['posts', 'pages', 'products', 'media', 'settings'];
        const progressFill = progressBar.querySelector('.progress-fill');
        
        for (let i = 0; i < steps.length; i++) {
            const step = steps[i];
            const progress = ((i + 1) / steps.length) * 100;
            
            await this.importStep(demoType, step);
            
            progressFill.style.width = progress + '%';
            
            // Small delay to show progress
            await new Promise(resolve => setTimeout(resolve, 500));
        }
    }

    /**
     * Import individual step
     */
    importStep(demoType, step) {
        return new Promise((resolve, reject) => {
            if (typeof ajaxurl !== 'undefined') {
                const formData = new FormData();
                formData.append('action', 'aqualuxe_import_demo');
                formData.append('demo_type', demoType);
                formData.append('step', step);
                formData.append('nonce', aqualuxe_admin.nonce);

                fetch(ajaxurl, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resolve(data.data);
                    } else {
                        reject(new Error(data.data.message || 'Import failed'));
                    }
                })
                .catch(error => reject(error));
            } else {
                reject(new Error('AJAX URL not available'));
            }
        });
    }

    /**
     * Reset demo content
     */
    resetDemoContent(button) {
        const originalText = button.textContent;
        button.textContent = 'Resetting...';
        button.disabled = true;

        if (typeof ajaxurl !== 'undefined') {
            const formData = new FormData();
            formData.append('action', 'aqualuxe_reset_demo');
            formData.append('nonce', aqualuxe_admin.nonce);

            fetch(ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = 'Reset Complete!';
                    button.style.backgroundColor = '#00a32a';
                    
                    this.showNotice('Demo content reset successfully!', 'success');
                } else {
                    button.textContent = 'Reset Failed';
                    button.style.backgroundColor = '#d63638';
                    
                    this.showNotice('Failed to reset demo content.', 'error');
                }
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.backgroundColor = '';
                }, 3000);
            })
            .catch(error => {
                console.error('Reset error:', error);
                
                button.textContent = 'Reset Failed';
                button.style.backgroundColor = '#d63638';
                
                this.showNotice('An error occurred during reset.', 'error');
                
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.backgroundColor = '';
                }, 3000);
            });
        }
    }

    /**
     * Initialize form validation
     */
    initFormValidation() {
        const forms = document.querySelectorAll('.aqualuxe-admin form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }

    /**
     * Validate form
     */
    validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    /**
     * Show field error
     */
    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.style.borderColor = '#d63638';
        
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.style.color = '#d63638';
        errorElement.style.fontSize = '12px';
        errorElement.style.marginTop = '5px';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    /**
     * Clear field error
     */
    clearFieldError(field) {
        field.style.borderColor = '';
        
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    /**
     * Initialize color pickers
     */
    initColorPickers() {
        const colorInputs = document.querySelectorAll('input[type="color"]');
        
        colorInputs.forEach(input => {
            // Add color preview
            const preview = document.createElement('div');
            preview.className = 'color-preview';
            preview.style.cssText = `
                width: 30px;
                height: 30px;
                border: 1px solid #ddd;
                border-radius: 3px;
                margin-left: 10px;
                display: inline-block;
                vertical-align: middle;
                background-color: ${input.value};
            `;
            
            input.parentNode.appendChild(preview);
            
            input.addEventListener('change', () => {
                preview.style.backgroundColor = input.value;
            });
        });
    }

    /**
     * Initialize media uploaders
     */
    initMediaUploaders() {
        const uploadButtons = document.querySelectorAll('.upload-media-button');
        
        uploadButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const targetInput = document.getElementById(button.dataset.target);
                const preview = document.getElementById(button.dataset.preview);
                
                if (typeof wp !== 'undefined' && wp.media) {
                    const mediaUploader = wp.media({
                        title: 'Select Media',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', () => {
                        const attachment = mediaUploader.state().get('selection').first().toJSON();
                        
                        if (targetInput) {
                            targetInput.value = attachment.url;
                        }
                        
                        if (preview) {
                            if (attachment.type === 'image') {
                                preview.innerHTML = `<img src="${attachment.url}" style="max-width: 200px; height: auto;">`;
                            } else {
                                preview.innerHTML = `<p>${attachment.filename}</p>`;
                            }
                        }
                    });
                    
                    mediaUploader.open();
                }
            });
        });
    }

    /**
     * Initialize sortable lists
     */
    initSortables() {
        const sortableLists = document.querySelectorAll('.sortable-list');
        
        sortableLists.forEach(list => {
            // Simple drag and drop implementation
            let draggedElement = null;
            
            const items = list.querySelectorAll('.sortable-item');
            
            items.forEach(item => {
                item.draggable = true;
                
                item.addEventListener('dragstart', (e) => {
                    draggedElement = item;
                    item.style.opacity = '0.5';
                });
                
                item.addEventListener('dragend', () => {
                    item.style.opacity = '';
                    draggedElement = null;
                });
                
                item.addEventListener('dragover', (e) => {
                    e.preventDefault();
                });
                
                item.addEventListener('drop', (e) => {
                    e.preventDefault();
                    
                    if (draggedElement && draggedElement !== item) {
                        const rect = item.getBoundingClientRect();
                        const midpoint = rect.top + rect.height / 2;
                        
                        if (e.clientY < midpoint) {
                            list.insertBefore(draggedElement, item);
                        } else {
                            list.insertBefore(draggedElement, item.nextSibling);
                        }
                    }
                });
            });
        });
    }

    /**
     * Show admin notice
     */
    showNotice(message, type = 'info') {
        const notice = document.createElement('div');
        notice.className = `notice notice-${type} is-dismissible`;
        notice.innerHTML = `
            <p>${message}</p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        `;
        
        // Insert after admin header
        const adminHeader = document.querySelector('.aqualuxe-admin .admin-header');
        if (adminHeader) {
            adminHeader.parentNode.insertBefore(notice, adminHeader.nextSibling);
        } else {
            document.querySelector('.aqualuxe-admin').prepend(notice);
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            notice.remove();
        }, 5000);
        
        // Manual dismiss
        const dismissButton = notice.querySelector('.notice-dismiss');
        if (dismissButton) {
            dismissButton.addEventListener('click', () => {
                notice.remove();
            });
        }
    }
}

// Initialize admin when DOM is ready
new AquaLuxeAdmin();