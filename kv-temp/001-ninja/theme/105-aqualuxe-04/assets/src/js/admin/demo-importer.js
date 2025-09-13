/**
 * AquaLuxe Demo Importer Admin JavaScript
 *
 * Handles demo content import/export functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Demo Importer Admin Object
   */
  const AquaLuxeDemoImporter = {
    /**
     * Initialize the admin
     */
    init: function () {
      this.bindEvents();
      this.initProgressTracking();
    },

    /**
     * Bind events
     */
    bindEvents: function () {
      $(document).on(
        'submit',
        '#demo-import-form',
        this.handleImportSubmit.bind(this)
      );
      $(document).on('click', '#reset-demo', this.handleResetClick.bind(this));
      $(document).on(
        'change',
        'input[name="import_type"]',
        this.handleImportTypeChange.bind(this)
      );
    },

    /**
     * Handle import type change
     */
    handleImportTypeChange: function (e) {
      const importType = $(e.target).val();
      const selectiveOptions = $('.selective-options');

      if (importType === 'selective') {
        selectiveOptions.show();
      } else {
        selectiveOptions.hide();
      }
    },

    /**
     * Handle import form submission
     */
    handleImportSubmit: function (e) {
      e.preventDefault();

      const form = $(e.target);
      const formData = new FormData(form[0]);
      const importType = form.find('input[name="import_type"]:checked').val();

      // Validate selective import options
      if (importType === 'selective') {
        const selectedOptions = form.find(
          'input[name="selective_options[]"]:checked'
        );
        if (selectedOptions.length === 0) {
          alert(
            'Please select at least one content type for selective import.'
          );
          return;
        }
      }

      // Confirm import action
      if (
        !confirm(
          'Are you sure you want to import demo content? This will add new content to your site.'
        )
      ) {
        return;
      }

      // Add AJAX data
      formData.append('action', 'aqualuxe_import_demo');
      formData.append('nonce', window.aqualuxeDemo.nonce);

      // Show progress
      this.showImportProgress();

      // Start progress tracking
      this.startProgressTracking();

      // Submit import request
      $.ajax({
        url: window.aqualuxeDemo.ajax_url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          AquaLuxeDemoImporter.handleImportResponse(response);
        },
        error: function (xhr, status, error) {
          AquaLuxeDemoImporter.handleImportError(error);
        },
      });
    },

    /**
     * Handle import response
     */
    handleImportResponse: function (response) {
      this.stopProgressTracking();

      if (response.success) {
        this.showImportComplete(response.data);
      } else {
        this.showImportError(response.data.message);
      }
    },

    /**
     * Handle import error
     */
    handleImportError: function (error) {
      this.stopProgressTracking();
      this.showImportError('Import failed: ' + error);
    },

    /**
     * Handle reset button click
     */
    handleResetClick: function (e) {
      e.preventDefault();

      if (!confirm(window.aqualuxeDemo.strings.confirm_reset)) {
        return;
      }

      const button = $(e.target);
      button.prop('disabled', true).text(window.aqualuxeDemo.strings.resetting);

      // Show reset progress
      this.showResetProgress();

      $.ajax({
        url: window.aqualuxeDemo.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_reset_demo',
          nonce: window.aqualuxeDemo.reset_nonce,
        },
        success: function (response) {
          AquaLuxeDemoImporter.handleResetResponse(response, button);
        },
        error: function () {
          AquaLuxeDemoImporter.handleResetError(button);
        },
      });
    },

    /**
     * Handle reset response
     */
    handleResetResponse: function (response, button) {
      button.prop('disabled', false).text('Reset Demo Content');

      if (response.success) {
        $('.reset-progress').hide();
        alert(window.aqualuxeDemo.strings.reset_complete);
        location.reload();
      } else {
        alert(
          window.aqualuxeDemo.strings.reset_error + ': ' + response.data.message
        );
      }
    },

    /**
     * Handle reset error
     */
    handleResetError: function (button) {
      button.prop('disabled', false).text('Reset Demo Content');
      alert(window.aqualuxeDemo.strings.reset_error);
    },

    /**
     * Show import progress
     */
    showImportProgress: function () {
      $('#demo-import-form').hide();
      $('.import-progress').show();
      $('.import-complete').hide();
    },

    /**
     * Show reset progress
     */
    showResetProgress: function () {
      $('.reset-progress').show();
      this.updateResetProgress(0, 'Starting reset...');
    },

    /**
     * Show import complete
     */
    showImportComplete: function (data) {
      $('.import-progress').hide();
      $('.import-complete').show();

      // Update completion message
      if (data.imported_items) {
        $('.import-complete')
          .find('p')
          .append(
            '<br><small>Imported ' +
              data.imported_items +
              ' items successfully.</small>'
          );
      }
    },

    /**
     * Show import error
     */
    showImportError: function (message) {
      $('.import-progress').hide();
      $('#demo-import-form').show();

      alert('Import failed: ' + message);
    },

    /**
     * Initialize progress tracking
     */
    initProgressTracking: function () {
      this.progressInterval = null;
    },

    /**
     * Start progress tracking
     */
    startProgressTracking: function () {
      this.progressInterval = setInterval(this.updateProgress.bind(this), 1000);
    },

    /**
     * Stop progress tracking
     */
    stopProgressTracking: function () {
      if (this.progressInterval) {
        clearInterval(this.progressInterval);
        this.progressInterval = null;
      }
    },

    /**
     * Update progress
     */
    updateProgress: function () {
      $.ajax({
        url: window.aqualuxeDemo.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_import_progress',
        },
        success: function (response) {
          if (response.success) {
            const progress = response.data;
            AquaLuxeDemoImporter.updateProgressBar(
              progress.percent,
              progress.message
            );

            // Stop tracking if complete
            if (progress.percent >= 100) {
              AquaLuxeDemoImporter.stopProgressTracking();
            }
          }
        },
      });
    },

    /**
     * Update progress bar
     */
    updateProgressBar: function (percent, message) {
      $('.progress-fill').css('width', percent + '%');
      $('.progress-message').text(message);

      // Add to log
      this.addToProgressLog(message);
    },

    /**
     * Update reset progress
     */
    updateResetProgress: function (percent, message) {
      $('.reset-progress .progress-fill').css('width', percent + '%');
      $('.reset-progress .progress-message').text(message);
    },

    /**
     * Add message to progress log
     */
    addToProgressLog: function (message) {
      const log = $('.progress-log');
      const timestamp = new Date().toLocaleTimeString();

      log.append('<div>[' + timestamp + '] ' + message + '</div>');
      log.scrollTop(log[0].scrollHeight);
    },
  };

  /**
   * Tab Management
   */
  const TabManager = {
    init: function () {
      this.bindEvents();
    },

    bindEvents: function () {
      $(document).on('click', '.nav-tab', this.handleTabClick.bind(this));
    },

    handleTabClick: function (e) {
      e.preventDefault();

      const tab = $(e.target);
      const targetId = tab.attr('href').substring(1);

      // Update tab states
      $('.nav-tab').removeClass('nav-tab-active');
      tab.addClass('nav-tab-active');

      // Update content visibility
      $('.tab-content').removeClass('active');
      $('#' + targetId).addClass('active');

      // Update URL hash
      window.location.hash = targetId;
    },
  };

  /**
   * Form Validation
   */
  const FormValidation = {
    init: function () {
      this.bindEvents();
    },

    bindEvents: function () {
      $(document).on(
        'change',
        'input[name="import_type"]',
        this.validateImportForm.bind(this)
      );
      $(document).on(
        'change',
        'input[name="selective_options[]"]',
        this.validateSelectiveOptions.bind(this)
      );
    },

    validateImportForm: function () {
      const importType = $('input[name="import_type"]:checked').val();
      const submitBtn = $('#start-import');

      if (importType === 'selective') {
        this.validateSelectiveOptions();
      } else {
        submitBtn.prop('disabled', false);
      }
    },

    validateSelectiveOptions: function () {
      const selectedOptions = $('input[name="selective_options[]"]:checked');
      const submitBtn = $('#start-import');

      submitBtn.prop('disabled', selectedOptions.length === 0);
    },
  };

  /**
   * Initialize when document is ready
   */
  $(document).ready(function () {
    AquaLuxeDemoImporter.init();
    TabManager.init();
    FormValidation.init();

    // Handle URL hash on load
    if (window.location.hash) {
      const targetTab = $('.nav-tab[href="' + window.location.hash + '"]');
      if (targetTab.length) {
        targetTab.click();
      }
    }
  });

  // Expose to global scope
  window.AquaLuxeDemoImporter = AquaLuxeDemoImporter;
})(jQuery);
