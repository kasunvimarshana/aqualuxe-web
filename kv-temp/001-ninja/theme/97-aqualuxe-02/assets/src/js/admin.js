/**
 * AquaLuxe Theme - Admin JavaScript
 *
 * Handles WordPress admin-specific functionality including
 * dashboard enhancements, settings management, and admin UI
 * improvements for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @subpackage Assets/JavaScript
 * @since 2.0.0
 */

// Import necessary modules and dependencies
import '../css/app.css';
import '../scss/admin.scss';

/**
 * AquaLuxe Admin Module
 *
 * Provides admin-specific functionality for theme management
 * and enhanced WordPress dashboard experience.
 */
class AquaLuxeAdmin {
  constructor() {
    this.init();
  }

  /**
   * Initialize admin functionality
   */
  init() {
    this.bindEvents();
    this.initializeComponents();
    this.setupEnhancements();

    console.log('AquaLuxe Admin: Initialized successfully');
  }

  /**
   * Bind admin event handlers
   */
  bindEvents() {
    // Module toggle handlers
    document.addEventListener('change', e => {
      if (e.target.classList.contains('aqualuxe-module-toggle')) {
        this.handleModuleToggle(e.target);
      }
    });

    // Settings form handlers
    document.addEventListener('submit', e => {
      if (e.target.classList.contains('aqualuxe-settings-form')) {
        this.handleSettingsSubmit(e);
      }
    });

    // Demo content import handlers
    document.addEventListener('click', e => {
      if (e.target.classList.contains('aqualuxe-import-demo')) {
        this.handleDemoImport(e);
      }
    });
  }

  /**
   * Initialize admin components
   */
  initializeComponents() {
    this.initTabs();
    this.initTooltips();
    this.initColorPickers();
    this.initMediaUploaders();
  }

  /**
   * Setup admin UI enhancements
   */
  setupEnhancements() {
    this.enhanceMetaboxes();
    this.improveFormValidation();
    this.addLoadingStates();
    this.setupNotifications();
  }

  /**
   * Handle module toggle changes
   */
  handleModuleToggle(toggle) {
    const moduleKey = toggle.dataset.module;
    const isEnabled = toggle.checked;

    // Show/hide related settings
    const relatedSettings = document.querySelectorAll(`[data-depends="${moduleKey}"]`);
    relatedSettings.forEach(setting => {
      setting.style.display = isEnabled ? 'block' : 'none';
    });

    // Show confirmation if disabling critical modules
    if (!isEnabled && this.isCriticalModule(moduleKey)) {
      this.showModuleWarning(moduleKey);
    }
  }

  /**
   * Handle settings form submission
   */
  handleSettingsSubmit(e) {
    const form = e.target;
    const submitButton = form.querySelector('[type="submit"]');

    // Add loading state
    this.setLoadingState(submitButton, true);

    // Validate form data
    if (!this.validateForm(form)) {
      e.preventDefault();
      this.setLoadingState(submitButton, false);
      return;
    }

    // Show success notification on successful submission
    setTimeout(() => {
      this.showNotification('Settings saved successfully!', 'success');
      this.setLoadingState(submitButton, false);
    }, 500);
  }

  /**
   * Handle demo content import
   */
  handleDemoImport(e) {
    e.preventDefault();

    const button = e.target;
    const importType = button.dataset.import;

    if (!confirm('This will import demo content. Continue?')) {
      return;
    }

    this.setLoadingState(button, true);

    // Simulate import process (replace with actual AJAX call)
    setTimeout(() => {
      this.showNotification('Demo content imported successfully!', 'success');
      this.setLoadingState(button, false);
    }, 3000);
  }

  /**
   * Initialize admin tabs
   */
  initTabs() {
    const tabContainers = document.querySelectorAll('.aqualuxe-admin-tabs');

    tabContainers.forEach(container => {
      const tabs = container.querySelectorAll('.tab-button');
      const panels = container.querySelectorAll('.tab-panel');

      tabs.forEach(tab => {
        tab.addEventListener('click', e => {
          e.preventDefault();
          const targetPanel = tab.dataset.tab;

          // Update active states
          tabs.forEach(t => t.classList.remove('active'));
          panels.forEach(p => p.classList.remove('active'));

          tab.classList.add('active');
          const panel = container.querySelector(`#${targetPanel}`);
          if (panel) panel.classList.add('active');
        });
      });
    });
  }

  /**
   * Initialize tooltips
   */
  initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');

    tooltipElements.forEach(element => {
      element.addEventListener('mouseenter', this.showTooltip);
      element.addEventListener('mouseleave', this.hideTooltip);
    });
  }

  /**
   * Initialize color pickers
   */
  initColorPickers() {
    const colorInputs = document.querySelectorAll('input[type="color"].aqualuxe-color-picker');

    colorInputs.forEach(input => {
      // Add color preview
      const preview = document.createElement('div');
      preview.className = 'color-preview';
      preview.style.backgroundColor = input.value;

      input.parentNode.insertBefore(preview, input.nextSibling);

      input.addEventListener('change', () => {
        preview.style.backgroundColor = input.value;
      });
    });
  }

  /**
   * Initialize media uploaders
   */
  initMediaUploaders() {
    const uploaderButtons = document.querySelectorAll('.aqualuxe-media-uploader');

    uploaderButtons.forEach(button => {
      button.addEventListener('click', e => {
        e.preventDefault();

        if (typeof wp !== 'undefined' && wp.media) {
          const mediaUploader = wp.media({
            title: 'Select Image',
            button: {
              text: 'Use this image',
            },
            multiple: false,
          });

          mediaUploader.on('select', () => {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            const targetInput = button.dataset.target;

            if (targetInput) {
              const input = document.getElementById(targetInput);
              if (input) input.value = attachment.url;

              // Update preview if exists
              const preview = document.querySelector(`[data-preview="${targetInput}"]`);
              if (preview) {
                preview.src = attachment.url;
                preview.style.display = 'block';
              }
            }
          });

          mediaUploader.open();
        }
      });
    });
  }

  /**
   * Check if module is critical
   */
  isCriticalModule(moduleKey) {
    const criticalModules = ['security', 'performance', 'assets'];
    return criticalModules.includes(moduleKey);
  }

  /**
   * Show module warning
   */
  showModuleWarning(moduleKey) {
    const message = `Disabling the ${moduleKey} module may affect theme functionality. Are you sure?`;
    if (!confirm(message)) {
      const toggle = document.querySelector(`[data-module="${moduleKey}"]`);
      if (toggle) toggle.checked = true;
    }
  }

  /**
   * Set loading state for elements
   */
  setLoadingState(element, loading) {
    if (loading) {
      element.classList.add('loading');
      element.disabled = true;
      element.setAttribute('data-original-text', element.textContent);
      element.textContent = 'Loading...';
    } else {
      element.classList.remove('loading');
      element.disabled = false;
      element.textContent = element.getAttribute('data-original-text') || element.textContent;
    }
  }

  /**
   * Validate form data
   */
  validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        this.highlightError(field);
        isValid = false;
      } else {
        this.clearError(field);
      }
    });

    return isValid;
  }

  /**
   * Highlight form errors
   */
  highlightError(field) {
    field.classList.add('error');

    let errorMessage = field.parentNode.querySelector('.error-message');
    if (!errorMessage) {
      errorMessage = document.createElement('div');
      errorMessage.className = 'error-message';
      errorMessage.textContent = 'This field is required';
      field.parentNode.appendChild(errorMessage);
    }
  }

  /**
   * Clear form errors
   */
  clearError(field) {
    field.classList.remove('error');
    const errorMessage = field.parentNode.querySelector('.error-message');
    if (errorMessage) {
      errorMessage.remove();
    }
  }

  /**
   * Show notifications
   */
  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `aqualuxe-notification ${type}`;
    notification.innerHTML = `
            <span class="message">${message}</span>
            <button class="close">&times;</button>
        `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
      notification.remove();
    }, 5000);

    // Remove on click
    notification.querySelector('.close').addEventListener('click', () => {
      notification.remove();
    });
  }

  /**
   * Show tooltips
   */
  showTooltip(e) {
    const element = e.target;
    const tooltipText = element.dataset.tooltip;

    if (!tooltipText) return;

    const tooltip = document.createElement('div');
    tooltip.className = 'aqualuxe-tooltip';
    tooltip.textContent = tooltipText;

    document.body.appendChild(tooltip);

    const rect = element.getBoundingClientRect();
    tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;

    element.tooltip = tooltip;
  }

  /**
   * Hide tooltips
   */
  hideTooltip(e) {
    const element = e.target;
    if (element.tooltip) {
      element.tooltip.remove();
      delete element.tooltip;
    }
  }

  /**
   * Enhance metaboxes
   */
  enhanceMetaboxes() {
    const metaboxes = document.querySelectorAll('.postbox');

    metaboxes.forEach(metabox => {
      // Add loading states to metabox actions
      const actions = metabox.querySelectorAll('button, input[type="submit"]');
      actions.forEach(action => {
        action.addEventListener('click', () => {
          this.setLoadingState(action, true);

          // Reset after a delay (replace with actual completion handler)
          setTimeout(() => {
            this.setLoadingState(action, false);
          }, 1000);
        });
      });
    });
  }

  /**
   * Improve form validation
   */
  improveFormValidation() {
    const forms = document.querySelectorAll('form.aqualuxe-form');

    forms.forEach(form => {
      const inputs = form.querySelectorAll('input, select, textarea');

      inputs.forEach(input => {
        input.addEventListener('blur', () => {
          this.validateField(input);
        });

        input.addEventListener('input', () => {
          if (input.classList.contains('error')) {
            this.validateField(input);
          }
        });
      });
    });
  }

  /**
   * Validate individual field
   */
  validateField(field) {
    if (field.hasAttribute('required') && !field.value.trim()) {
      this.highlightError(field);
      return false;
    } else {
      this.clearError(field);
      return true;
    }
  }

  /**
   * Add loading states
   */
  addLoadingStates() {
    // Add loading overlay for AJAX operations
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'aqualuxe-loading-overlay';
    loadingOverlay.className = 'loading-overlay hidden';
    loadingOverlay.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(loadingOverlay);
  }

  /**
   * Setup notifications
   */
  setupNotifications() {
    // Listen for WordPress admin notices
    const adminNotices = document.querySelectorAll('.notice');

    adminNotices.forEach(notice => {
      // Add dismiss functionality if not present
      if (!notice.querySelector('.notice-dismiss')) {
        const dismissButton = document.createElement('button');
        dismissButton.className = 'notice-dismiss';
        dismissButton.innerHTML = '<span class="screen-reader-text">Dismiss this notice.</span>';
        dismissButton.addEventListener('click', () => {
          notice.remove();
        });
        notice.appendChild(dismissButton);
      }
    });
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  new AquaLuxeAdmin();
});

// Export for use in other modules
window.AquaLuxeAdmin = AquaLuxeAdmin;
