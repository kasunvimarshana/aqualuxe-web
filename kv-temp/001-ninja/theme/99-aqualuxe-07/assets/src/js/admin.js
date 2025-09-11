/**
 * Admin JavaScript
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

class AquaLuxeAdmin {
    constructor() {
        this.init();
    }

    init() {
        this.initDemoImporter();
        this.initModuleToggle();
        this.initThemeOptions();
        this.initImageUploader();
        this.initColorPicker();
        this.initTabs();
    }

    initDemoImporter() {
        const importButton = document.querySelector('.demo-import-btn');
        const flushButton = document.querySelector('.demo-flush-btn');
        
        if (importButton) {
            importButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.startDemoImport();
            });
        }

        if (flushButton) {
            flushButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.startDemoFlush();
            });
        }
    }

    startDemoImport() {
        if (!confirm('This will import demo content. Continue?')) return;

        const button = document.querySelector('.demo-import-btn');
        const progressContainer = document.querySelector('.import-progress');
        const progressBar = document.querySelector('.progress-bar');
        const statusText = document.querySelector('.import-status');

        button.disabled = true;
        button.textContent = 'Importing...';
        progressContainer.style.display = 'block';

        this.runImportStep('prepare', progressBar, statusText);
    }

    runImportStep(step, progressBar, statusText) {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_demo_import');
        formData.append('step', step);
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const { progress, status, next_step } = data.data;
                
                progressBar.style.width = `${progress}%`;
                statusText.textContent = status;

                if (next_step) {
                    setTimeout(() => {
                        this.runImportStep(next_step, progressBar, statusText);
                    }, 500);
                } else {
                    this.completeImport();
                }
            } else {
                this.showImportError(data.data?.message || 'Import failed');
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            this.showImportError('Import failed due to network error');
        });
    }

    completeImport() {
        const button = document.querySelector('.demo-import-btn');
        const statusText = document.querySelector('.import-status');
        
        button.disabled = false;
        button.textContent = 'Import Demo Content';
        statusText.textContent = 'Import completed successfully!';
        
        setTimeout(() => {
            document.querySelector('.import-progress').style.display = 'none';
        }, 3000);
    }

    showImportError(message) {
        const button = document.querySelector('.demo-import-btn');
        const statusText = document.querySelector('.import-status');
        
        button.disabled = false;
        button.textContent = 'Import Demo Content';
        statusText.textContent = `Error: ${message}`;
        statusText.style.color = '#ef4444';
    }

    startDemoFlush() {
        if (!confirm('This will delete all demo content and reset the site. This action cannot be undone. Continue?')) return;

        const button = document.querySelector('.demo-flush-btn');
        button.disabled = true;
        button.textContent = 'Flushing...';

        const formData = new FormData();
        formData.append('action', 'aqualuxe_demo_flush');
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Demo content flushed successfully!');
                location.reload();
            } else {
                alert(`Flush failed: ${data.data?.message || 'Unknown error'}`);
            }
        })
        .catch(error => {
            console.error('Flush error:', error);
            alert('Flush failed due to network error');
        })
        .finally(() => {
            button.disabled = false;
            button.textContent = 'Flush Demo Content';
        });
    }

    initModuleToggle() {
        const moduleToggles = document.querySelectorAll('.module-toggle');
        
        moduleToggles.forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                const module = e.target.dataset.module;
                const enabled = e.target.checked;
                
                this.toggleModule(module, enabled);
            });
        });
    }

    toggleModule(module, enabled) {
        const formData = new FormData();
        formData.append('action', 'aqualuxe_toggle_module');
        formData.append('module', module);
        formData.append('enabled', enabled ? '1' : '0');
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotice(`Module ${module} ${enabled ? 'enabled' : 'disabled'} successfully`);
            } else {
                this.showNotice(`Failed to toggle module: ${data.data?.message || 'Unknown error'}`, 'error');
            }
        })
        .catch(error => {
            console.error('Module toggle error:', error);
            this.showNotice('Failed to toggle module due to network error', 'error');
        });
    }

    initThemeOptions() {
        const saveButton = document.querySelector('.save-theme-options');
        
        if (saveButton) {
            saveButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveThemeOptions();
            });
        }

        // Auto-save on input change
        const inputs = document.querySelectorAll('.theme-option-input');
        inputs.forEach(input => {
            input.addEventListener('change', () => {
                this.autoSaveOptions();
            });
        });
    }

    saveThemeOptions() {
        const form = document.querySelector('.theme-options-form');
        if (!form) return;

        const formData = new FormData(form);
        formData.append('action', 'aqualuxe_save_theme_options');
        formData.append('nonce', window.AQUALUXE?.nonce || '');

        const saveButton = document.querySelector('.save-theme-options');
        saveButton.disabled = true;
        saveButton.textContent = 'Saving...';

        fetch(ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotice('Theme options saved successfully');
            } else {
                this.showNotice(`Save failed: ${data.data?.message || 'Unknown error'}`, 'error');
            }
        })
        .catch(error => {
            console.error('Save error:', error);
            this.showNotice('Save failed due to network error', 'error');
        })
        .finally(() => {
            saveButton.disabled = false;
            saveButton.textContent = 'Save Options';
        });
    }

    autoSaveOptions() {
        // Debounced auto-save
        clearTimeout(this.autoSaveTimeout);
        this.autoSaveTimeout = setTimeout(() => {
            this.saveThemeOptions();
        }, 2000);
    }

    initImageUploader() {
        const uploadButtons = document.querySelectorAll('.image-upload-btn');
        
        uploadButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.openMediaUploader(button);
            });
        });

        const removeButtons = document.querySelectorAll('.image-remove-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.removeImage(button);
            });
        });
    }

    openMediaUploader(button) {
        const targetInput = button.dataset.target;
        const previewContainer = button.dataset.preview;

        if (typeof wp !== 'undefined' && wp.media) {
            const mediaUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use This Image'
                },
                multiple: false
            });

            mediaUploader.on('select', () => {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                
                // Update input value
                const input = document.getElementById(targetInput);
                if (input) {
                    input.value = attachment.id;
                }

                // Update preview
                const preview = document.getElementById(previewContainer);
                if (preview) {
                    preview.innerHTML = `<img src="${attachment.sizes.medium?.url || attachment.url}" alt="" style="max-width: 200px; height: auto;">`;
                }
            });

            mediaUploader.open();
        }
    }

    removeImage(button) {
        const targetInput = button.dataset.target;
        const previewContainer = button.dataset.preview;

        // Clear input value
        const input = document.getElementById(targetInput);
        if (input) {
            input.value = '';
        }

        // Clear preview
        const preview = document.getElementById(previewContainer);
        if (preview) {
            preview.innerHTML = '';
        }
    }

    initColorPicker() {
        const colorInputs = document.querySelectorAll('.color-picker-input');
        
        colorInputs.forEach(input => {
            // Initialize color picker if wp.colorPicker is available
            if (typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
                jQuery(input).wpColorPicker({
                    change: () => {
                        this.autoSaveOptions();
                    }
                });
            }
        });
    }

    initTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanels = document.querySelectorAll('.tab-panel');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const targetTab = button.dataset.tab;
                
                // Update active button
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Show target panel
                tabPanels.forEach(panel => {
                    if (panel.dataset.tab === targetTab) {
                        panel.classList.remove('hidden');
                    } else {
                        panel.classList.add('hidden');
                    }
                });
                
                // Store active tab
                localStorage.setItem('aqualuxe-admin-active-tab', targetTab);
            });
        });

        // Restore active tab
        const activeTab = localStorage.getItem('aqualuxe-admin-active-tab');
        if (activeTab) {
            const activeButton = document.querySelector(`[data-tab="${activeTab}"]`);
            if (activeButton) {
                activeButton.click();
            }
        }
    }

    showNotice(message, type = 'success') {
        const notice = document.createElement('div');
        notice.className = `notice notice-${type} is-dismissible`;
        notice.innerHTML = `
            <p>${message}</p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
        `;

        const container = document.querySelector('.wrap') || document.body;
        container.insertBefore(notice, container.firstChild);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            notice.remove();
        }, 5000);

        // Handle manual dismiss
        notice.querySelector('.notice-dismiss').addEventListener('click', () => {
            notice.remove();
        });
    }

    // Utility method for AJAX requests
    async makeAjaxRequest(action, data = {}) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('nonce', window.AQUALUXE?.nonce || '');
        
        Object.entries(data).forEach(([key, value]) => {
            formData.append(key, value);
        });

        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formData
            });
            
            return await response.json();
        } catch (error) {
            console.error('AJAX request error:', error);
            throw error;
        }
    }
}

// Initialize admin functionality
document.addEventListener('DOMContentLoaded', () => {
    new AquaLuxeAdmin();
});