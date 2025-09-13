/**
 * Franchise Module JavaScript
 *
 * Handles franchise and partner portal functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

class FranchiseModule {
  constructor() {
    this.form = null;
    this.portalSections = null;
    this.init();
  }

  init() {
    this.bindEvents();
    this.initPartnerPortal();
  }

  bindEvents() {
    document.addEventListener('DOMContentLoaded', () => {
      // Franchise inquiry form
      this.form = document.getElementById('franchise-inquiry-form');
      if (this.form) {
        this.form.addEventListener(
          'submit',
          this.handleInquirySubmit.bind(this)
        );
      }

      // Performance report form
      const performanceForm = document.getElementById(
        'performance-report-form'
      );
      if (performanceForm) {
        performanceForm.addEventListener(
          'submit',
          this.handlePerformanceSubmit.bind(this)
        );
      }

      // Partner portal navigation
      const portalNavLinks = document.querySelectorAll('.portal-nav-link');
      portalNavLinks.forEach(link => {
        link.addEventListener('click', this.handlePortalNavigation.bind(this));
      });

      // Initialize tooltips and help text
      this.initHelpTooltips();
    });
  }

  handleInquirySubmit(event) {
    event.preventDefault();

    if (!this.validateInquiryForm()) {
      return;
    }

    const submitButton = this.form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = aqualuxeFranchise.strings.processing;
    this.form.classList.add('aqualuxe-loading');

    const formData = new FormData(this.form);
    formData.append('action', 'aqualuxe_franchise_request');
    formData.append('franchise_action', 'submit_inquiry');
    formData.append('nonce', aqualuxeFranchise.nonce);

    fetch(aqualuxeFranchise.ajaxurl, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.showNotification(data.data.message, 'success');
          this.form.reset();

          // Show success state
          this.showInquirySuccess(data.data.inquiry_id);
        } else {
          this.showNotification(
            data.data || aqualuxeFranchise.strings.error,
            'error'
          );
        }
      })
      .catch(() => {
        // Error handled through notification
        this.showNotification(aqualuxeFranchise.strings.error, 'error');
      })
      .finally(() => {
        // Reset form state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        this.form.classList.remove('aqualuxe-loading');
      });
  }

  handlePerformanceSubmit(event) {
    event.preventDefault();

    const form = event.target;
    if (!this.validatePerformanceForm(form)) {
      return;
    }

    const submitButton = form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;

    // Show loading state
    submitButton.disabled = true;
    submitButton.textContent = aqualuxeFranchise.strings.processing;
    form.classList.add('aqualuxe-loading');

    const formData = new FormData(form);
    formData.append('action', 'aqualuxe_franchise_request');
    formData.append('franchise_action', 'submit_performance_report');
    formData.append('nonce', aqualuxeFranchise.nonce);

    fetch(aqualuxeFranchise.ajaxurl, {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.showNotification(data.data.message, 'success');
          form.reset();
        } else {
          this.showNotification(
            data.data || aqualuxeFranchise.strings.error,
            'error'
          );
        }
      })
      .catch(() => {
        // Error handled through notification
        this.showNotification(aqualuxeFranchise.strings.error, 'error');
      })
      .finally(() => {
        // Reset form state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
        form.classList.remove('aqualuxe-loading');
      });
  }

  handlePortalNavigation(event) {
    event.preventDefault();

    const link = event.target;
    const targetSection = link.dataset.section;

    // Update active states
    document
      .querySelectorAll('.portal-nav-link')
      .forEach(l => l.classList.remove('active'));
    document
      .querySelectorAll('.portal-section')
      .forEach(s => s.classList.remove('active'));

    link.classList.add('active');
    document.getElementById(`${targetSection}-section`).classList.add('active');

    // Update URL hash
    window.location.hash = targetSection;

    // Load section content if needed
    this.loadPortalSection(targetSection);
  }

  validateInquiryForm() {
    const requiredFields = this.form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        this.showFieldError(field, 'This field is required');
        isValid = false;
      } else {
        this.clearFieldError(field);
      }
    });

    // Validate email
    const emailField = this.form.querySelector('[name="email"]');
    if (
      emailField &&
      emailField.value &&
      !this.isValidEmail(emailField.value)
    ) {
      this.showFieldError(emailField, 'Please enter a valid email address');
      isValid = false;
    }

    // Validate phone if provided
    const phoneField = this.form.querySelector('[name="phone"]');
    if (
      phoneField &&
      phoneField.value &&
      !this.isValidPhone(phoneField.value)
    ) {
      this.showFieldError(phoneField, 'Please enter a valid phone number');
      isValid = false;
    }

    // Validate name length
    const nameField = this.form.querySelector('[name="contact_name"]');
    if (nameField && nameField.value.length < 2) {
      this.showFieldError(nameField, 'Name must be at least 2 characters');
      isValid = false;
    }

    return isValid;
  }

  validatePerformanceForm(form) {
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

    // Validate revenue is positive number
    const revenueField = form.querySelector('[name="revenue"]');
    if (
      revenueField &&
      revenueField.value &&
      parseFloat(revenueField.value) < 0
    ) {
      this.showFieldError(revenueField, 'Revenue must be a positive number');
      isValid = false;
    }

    // Validate customers is positive integer
    const customersField = form.querySelector('[name="customers"]');
    if (
      customersField &&
      customersField.value &&
      parseInt(customersField.value) < 0
    ) {
      this.showFieldError(
        customersField,
        'Number of customers must be positive'
      );
      isValid = false;
    }

    return isValid;
  }

  initPartnerPortal() {
    const portal = document.querySelector('.aqualuxe-partner-portal');
    if (!portal) {
      return;
    }

    // Check URL hash on load
    const hash = window.location.hash.substring(1);
    if (hash) {
      const link = document.querySelector(`[data-section="${hash}"]`);
      if (link) {
        link.click();
      }
    }

    // Initialize dashboard widgets
    this.initDashboardWidgets();

    // Initialize resource filters
    this.initResourceFilters();
  }

  initDashboardWidgets() {
    const dashboard = document.getElementById('dashboard-section');
    if (!dashboard) {
      return;
    }

    // Load recent activity
    this.loadRecentActivity();

    // Load announcements
    this.loadAnnouncements();

    // Load quick stats
    this.loadQuickStats();
  }

  initResourceFilters() {
    const resourcesSection = document.getElementById('resources-section');
    if (!resourcesSection) {
      return;
    }

    // Add search functionality
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.placeholder = 'Search resources...';
    searchInput.className = 'resource-search';

    const resourcesGrid = resourcesSection.querySelector('.resources-grid');
    if (resourcesGrid) {
      resourcesGrid.parentNode.insertBefore(searchInput, resourcesGrid);

      searchInput.addEventListener('input', this.filterResources.bind(this));
    }
  }

  initHelpTooltips() {
    // Add help tooltips to form fields
    const helpTexts = {
      investment_range:
        'Select the amount you are prepared to invest in the franchise',
      franchise_type: 'Choose the type of franchise that interests you most',
      experience: 'Describe any relevant business or industry experience',
      report_period: 'Select the month you are reporting performance for',
      revenue: 'Enter your total revenue for the reporting period',
    };

    Object.keys(helpTexts).forEach(fieldName => {
      const field = document.querySelector(`[name="${fieldName}"]`);
      if (field) {
        const helpIcon = document.createElement('span');
        helpIcon.className = 'help-icon';
        helpIcon.innerHTML = '?';
        helpIcon.title = helpTexts[fieldName];

        field.parentNode.appendChild(helpIcon);
      }
    });
  }

  loadPortalSection(section) {
    switch (section) {
      case 'dashboard':
        this.loadDashboardData();
        break;
      case 'resources':
        this.loadResourcesData();
        break;
      case 'performance':
        this.loadPerformanceData();
        break;
      case 'support':
        this.loadSupportData();
        break;
    }
  }

  loadDashboardData() {
    // Load dashboard data via AJAX if needed
    // Dashboard data loading
  }

  loadResourcesData() {
    // Load resources data via AJAX if needed
    // Resources data loading
  }

  loadPerformanceData() {
    // Load performance data via AJAX if needed
    // Performance data loading
  }

  loadSupportData() {
    // Load support data via AJAX if needed
    // Support data loading
  }

  loadRecentActivity() {
    // Placeholder for loading recent activity
    const activityList = document.querySelector('.activity-list');
    if (activityList) {
      // This would typically load from AJAX
    }
  }

  loadAnnouncements() {
    // Placeholder for loading announcements
    const announcementsList = document.querySelector('.announcements-list');
    if (announcementsList) {
      // This would typically load from AJAX
    }
  }

  loadQuickStats() {
    // Placeholder for loading quick stats
    const statValues = document.querySelectorAll('.stat-value');
    if (statValues.length > 0) {
      // This would typically load from AJAX
    }
  }

  filterResources(event) {
    const searchTerm = event.target.value.toLowerCase();
    const resourceItems = document.querySelectorAll('.resource-item');

    resourceItems.forEach(item => {
      const title = item.querySelector('h3').textContent.toLowerCase();
      const excerpt = item
        .querySelector('.resource-excerpt')
        .textContent.toLowerCase();

      if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
        item.style.display = 'block';
      } else {
        item.style.display = 'none';
      }
    });
  }

  showInquirySuccess(inquiryId) {
    const successMessage = document.createElement('div');
    successMessage.className = 'inquiry-success';
    successMessage.innerHTML = `
            <h3>Thank You!</h3>
            <p>Your franchise inquiry has been submitted successfully.</p>
            <p><strong>Reference ID:</strong> ${inquiryId}</p>
            <p>We will review your application and contact you within 5-7 business days.</p>
        `;

    this.form.parentNode.replaceChild(successMessage, this.form);
  }

  showFieldError(field, message) {
    this.clearFieldError(field);

    field.classList.add('error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
  }

  clearFieldError(field) {
    field.classList.remove('error');
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
      existingError.remove();
    }
  }

  showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `aqualuxe-notification aqualuxe-notification-${type}`;
    notification.innerHTML = `
            <span class="notification-message">${this.escapeHtml(message)}</span>
            <button type="button" class="notification-close" aria-label="Close">&times;</button>
        `;

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
      notification.classList.add('show');
    }, 100);

    // Auto-hide after delay
    const hideTimeout = setTimeout(
      () => {
        this.hideNotification(notification);
      },
      type === 'error' ? 8000 : 5000
    );

    // Close button
    notification
      .querySelector('.notification-close')
      .addEventListener('click', () => {
        clearTimeout(hideTimeout);
        this.hideNotification(notification);
      });
  }

  hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
      if (notification.parentNode) {
        notification.parentNode.removeChild(notification);
      }
    }, 300);
  }

  isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
  }

  isValidPhone(phone) {
    // Basic phone validation - accepts various formats
    const phoneRegex = /^[+]?[1-9][\d]{0,15}$/;
    const cleanPhone = phone.replace(/[\s\-().]/g, '');
    return phoneRegex.test(cleanPhone);
  }

  escapeHtml(text) {
    const map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;',
    };
    return text.replace(/[&<>"']/g, function (m) {
      return map[m];
    });
  }

  // Public API methods
  getCurrentSection() {
    const activeLink = document.querySelector('.portal-nav-link.active');
    return activeLink ? activeLink.dataset.section : 'dashboard';
  }

  switchToSection(section) {
    const link = document.querySelector(`[data-section="${section}"]`);
    if (link) {
      link.click();
    }
  }

  refreshDashboard() {
    this.loadDashboardData();
  }
}

// Initialize the franchise module
const aqualuxeFranchise = new FranchiseModule();

// Make it globally available
window.AquaLuxeFranchise = aqualuxeFranchise;

// Export for module systems (safely check for Node.js environment)
if (
  typeof window === 'undefined' &&
  typeof module !== 'undefined' &&
  module.exports
) {
  module.exports = FranchiseModule;
}
