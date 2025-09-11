/**
 * AquaLuxe Admin JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Admin Theme Class
class AquaLuxeAdmin {
    constructor() {
        this.init();
    }

    /**
     * Initialize admin functionality
     */
    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.onReady());
        } else {
            this.onReady();
        }
    }

    /**
     * DOM ready handler
     */
    onReady() {
        this.setupDemoImporter();
        this.setupCustomizer();
        this.setupMetaBoxes();
        
        console.log('🌊 AquaLuxe Admin loaded successfully');
    }

    /**
     * Setup demo importer
     */
    setupDemoImporter() {
        const importerForm = document.querySelector('#demo-importer-form');
        if (!importerForm) return;

        const importButton = importerForm.querySelector('.import-demo-btn');
        const progressBar = document.querySelector('.import-progress');
        const statusMessage = document.querySelector('.import-status');

        if (importButton) {
            importButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.runDemoImport();
            });
        }
    }

    /**
     * Run demo import
     */
    runDemoImport() {
        const progressBar = document.querySelector('.import-progress');
        const statusMessage = document.querySelector('.import-status');
        const importButton = document.querySelector('.import-demo-btn');

        // Show progress
        if (progressBar) progressBar.style.display = 'block';
        if (importButton) importButton.disabled = true;

        // AJAX request
        const formData = new FormData();
        formData.append('action', 'aqualuxe_import_demo');
        formData.append('nonce', aqualuxe_admin.nonce);

        fetch(aqualuxe_admin.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (statusMessage) {
                    statusMessage.innerHTML = '<div class="notice notice-success"><p>' + data.data.message + '</p></div>';
                }
            } else {
                if (statusMessage) {
                    statusMessage.innerHTML = '<div class="notice notice-error"><p>' + data.data.message + '</p></div>';
                }
            }
        })
        .catch(error => {
            console.error('Import error:', error);
            if (statusMessage) {
                statusMessage.innerHTML = '<div class="notice notice-error"><p>Import failed. Please try again.</p></div>';
            }
        })
        .finally(() => {
            if (progressBar) progressBar.style.display = 'none';
            if (importButton) importButton.disabled = false;
        });
    }

    /**
     * Setup customizer functionality
     */
    setupCustomizer() {
        // Color picker initialization
        const colorPickers = document.querySelectorAll('.color-picker');
        colorPickers.forEach(picker => {
            if (typeof wp !== 'undefined' && wp.customize && wp.customize.ColorControl) {
                // WordPress color picker integration
            }
        });
    }

    /**
     * Setup meta boxes
     */
    setupMetaBoxes() {
        // Page template selector
        const templateSelector = document.querySelector('#page_template');
        if (templateSelector) {
            templateSelector.addEventListener('change', (e) => {
                this.toggleTemplateOptions(e.target.value);
            });
        }

        // Product options for WooCommerce
        const productTypeSelector = document.querySelector('#product-type');
        if (productTypeSelector) {
            productTypeSelector.addEventListener('change', (e) => {
                this.toggleProductOptions(e.target.value);
            });
        }
    }

    /**
     * Toggle template options
     */
    toggleTemplateOptions(template) {
        const optionBoxes = document.querySelectorAll('.template-options');
        optionBoxes.forEach(box => {
            box.style.display = 'none';
        });

        const targetBox = document.querySelector('.template-options-' + template);
        if (targetBox) {
            targetBox.style.display = 'block';
        }
    }

    /**
     * Toggle product options
     */
    toggleProductOptions(type) {
        const optionBoxes = document.querySelectorAll('.product-type-options');
        optionBoxes.forEach(box => {
            box.style.display = 'none';
        });

        const targetBox = document.querySelector('.product-type-options-' + type);
        if (targetBox) {
            targetBox.style.display = 'block';
        }
    }
}

// Initialize admin when script loads
new AquaLuxeAdmin();