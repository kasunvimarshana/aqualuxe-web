/**
 * AquaLuxe Admin JavaScript
 *
 * Handles admin interface functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function ($) {
  'use strict';

  const AquaLuxeAdmin = {
    /**
     * Initialize admin functionality
     */
    init() {
      this.initSortables();
      this.initConfirmDialogs();
      this.initAjaxForms();
      this.initTabs();
      this.initTooltips();
      this.initColorPickers();
      this.initMediaUploader();
    },

    /**
     * Initialize sortable lists
     */
    initSortables() {
      $('.aqualuxe-sortable').sortable({
        items: '.sortable-item',
        cursor: 'move',
        opacity: 0.7,
        placeholder: 'sortable-placeholder',
        update: function () {
          const order = $(this).sortable('toArray', { attribute: 'data-id' });
          AquaLuxeAdmin.saveSortOrder($(this).data('type'), order);
        },
      });
    },

    /**
     * Save sort order via AJAX
     */
    saveSortOrder(type, order) {
      $.ajax({
        url: aqualuxe_admin.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_save_sort_order',
          nonce: aqualuxe_admin.nonce,
          type: type,
          order: order,
        },
        success: function (response) {
          if (response.success) {
            AquaLuxeAdmin.showNotice('success', aqualuxe_admin.strings.success);
          } else {
            AquaLuxeAdmin.showNotice(
              'error',
              response.data || aqualuxe_admin.strings.error
            );
          }
        },
        error: function () {
          AquaLuxeAdmin.showNotice('error', aqualuxe_admin.strings.error);
        },
      });
    },

    /**
     * Initialize confirm dialogs
     */
    initConfirmDialogs() {
      $(document).on('click', '[data-confirm]', function (e) {
        const message =
          $(this).data('confirm') || aqualuxe_admin.strings.confirm_delete;
        if (!confirm(message)) {
          e.preventDefault();
          return false;
        }
      });
    },

    /**
     * Initialize AJAX forms
     */
    initAjaxForms() {
      $(document).on('submit', '.aqualuxe-ajax-form', function (e) {
        e.preventDefault();

        const $form = $(this);
        const $submitBtn = $form.find('[type="submit"]');
        const originalText = $submitBtn.text();

        // Disable submit button and show loading state
        $submitBtn
          .prop('disabled', true)
          .text(aqualuxe_admin.strings.processing);

        $.ajax({
          url: aqualuxe_admin.ajax_url,
          type: $form.attr('method') || 'POST',
          data: $form.serialize(),
          success: function (response) {
            if (response.success) {
              AquaLuxeAdmin.showNotice(
                'success',
                response.data.message || aqualuxe_admin.strings.success
              );
              if (response.data.reload) {
                location.reload();
              }
              if (response.data.redirect) {
                window.location = response.data.redirect;
              }
            } else {
              AquaLuxeAdmin.showNotice(
                'error',
                response.data || aqualuxe_admin.strings.error
              );
            }
          },
          error: function () {
            AquaLuxeAdmin.showNotice('error', aqualuxe_admin.strings.error);
          },
          complete: function () {
            $submitBtn.prop('disabled', false).text(originalText);
          },
        });
      });
    },

    /**
     * Initialize admin tabs
     */
    initTabs() {
      $('.aqualuxe-tabs').each(function () {
        const $container = $(this);
        const $tabs = $container.find('.tab-nav a');
        const $panels = $container.find('.tab-panel');

        $tabs.on('click', function (e) {
          e.preventDefault();

          const target = $(this).attr('href');

          // Update tab states
          $tabs.removeClass('active');
          $(this).addClass('active');

          // Update panel states
          $panels.removeClass('active');
          $(target).addClass('active');

          // Save active tab to localStorage
          localStorage.setItem(
            'aqualuxe_active_tab_' + $container.attr('id'),
            target
          );
        });

        // Restore active tab from localStorage
        const savedTab = localStorage.getItem(
          'aqualuxe_active_tab_' + $container.attr('id')
        );
        if (savedTab && $(savedTab).length) {
          $tabs.filter('[href="' + savedTab + '"]').trigger('click');
        } else {
          $tabs.first().trigger('click');
        }
      });
    },

    /**
     * Initialize tooltips
     */
    initTooltips() {
      if ($.fn.tooltip) {
        $('.aqualuxe-tooltip').tooltip({
          position: {
            my: 'center bottom-20',
            at: 'center top',
            using: function (position, feedback) {
              $(this).css(position);
              $('<div>')
                .addClass('arrow')
                .addClass(feedback.vertical)
                .addClass(feedback.horizontal)
                .appendTo(this);
            },
          },
        });
      }
    },

    /**
     * Initialize color pickers
     */
    initColorPickers() {
      if ($.fn.wpColorPicker) {
        $('.aqualuxe-color-picker').wpColorPicker();
      }
    },

    /**
     * Initialize media uploader
     */
    initMediaUploader() {
      $(document).on('click', '.aqualuxe-media-upload', function (e) {
        e.preventDefault();

        const $button = $(this);
        const $input = $($button.data('input'));
        const $preview = $($button.data('preview'));
        const multiple = $button.data('multiple') || false;
        const type = $button.data('type') || 'image';

        const mediaUploader = wp.media({
          title: $button.data('title') || 'Select Media',
          button: {
            text: $button.data('button-text') || 'Use this media',
          },
          multiple: multiple,
          library: {
            type: type,
          },
        });

        mediaUploader.on('select', function () {
          if (multiple) {
            const selection = mediaUploader.state().get('selection');
            const ids = [];
            const urls = [];

            selection.each(function (attachment) {
              ids.push(attachment.get('id'));
              urls.push(attachment.get('url'));
            });

            $input.val(ids.join(','));

            if ($preview.length) {
              $preview.empty();
              urls.forEach(function (url) {
                $preview.append(
                  '<img src="' +
                    url +
                    '" style="max-width: 100px; margin: 5px;">'
                );
              });
            }
          } else {
            const attachment = mediaUploader
              .state()
              .get('selection')
              .first()
              .toJSON();
            $input.val(attachment.id);

            if ($preview.length) {
              if (type === 'image') {
                $preview.html(
                  '<img src="' + attachment.url + '" style="max-width: 200px;">'
                );
              } else {
                $preview.html(
                  '<a href="' +
                    attachment.url +
                    '">' +
                    attachment.filename +
                    '</a>'
                );
              }
            }
          }
        });

        mediaUploader.open();
      });

      // Remove media
      $(document).on('click', '.aqualuxe-media-remove', function (e) {
        e.preventDefault();

        const $button = $(this);
        const $input = $($button.data('input'));
        const $preview = $($button.data('preview'));

        $input.val('');
        $preview.empty();
      });
    },

    /**
     * Show admin notice
     */
    showNotice(type, message, dismissible = true) {
      const $notice = $(
        '<div class="notice notice-' +
          type +
          (dismissible ? ' is-dismissible' : '') +
          '"><p>' +
          message +
          '</p></div>'
      );

      $('.wrap > h1').after($notice);

      if (dismissible) {
        $notice.append(
          '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>'
        );

        $notice.on('click', '.notice-dismiss', function () {
          $notice.fadeOut();
        });
      }

      // Auto-hide success notices
      if (type === 'success') {
        setTimeout(function () {
          $notice.fadeOut();
        }, 5000);
      }
    },

    /**
     * Handle module toggle
     */
    toggleModule(moduleId, enabled) {
      $.ajax({
        url: aqualuxe_admin.ajax_url,
        type: 'POST',
        data: {
          action: 'aqualuxe_toggle_module',
          nonce: aqualuxe_admin.nonce,
          module_id: moduleId,
          enabled: enabled,
        },
        success: function (response) {
          if (response.success) {
            AquaLuxeAdmin.showNotice('success', response.data.message);
            if (response.data.reload) {
              location.reload();
            }
          } else {
            AquaLuxeAdmin.showNotice(
              'error',
              response.data || aqualuxe_admin.strings.error
            );
          }
        },
        error: function () {
          AquaLuxeAdmin.showNotice('error', aqualuxe_admin.strings.error);
        },
      });
    },
  };

  // Initialize when document is ready
  $(document).ready(function () {
    AquaLuxeAdmin.init();
  });

  // Make AquaLuxeAdmin globally available
  window.AquaLuxeAdmin = AquaLuxeAdmin;
})(jQuery);
