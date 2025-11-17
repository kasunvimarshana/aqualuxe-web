/**
 * Admin JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

;(($) => {
  $(document).ready(() => {
    // Add admin page class
    $(".wrap").addClass("aqualuxe-admin-page")

    // Confirmation for important settings
    $('input[name="aqualuxe_options[enable_animations]"]').on("change", function () {
      if (!$(this).is(":checked")) {
        if (!confirm("Disabling animations may affect user experience. Continue?")) {
          $(this).prop("checked", true)
        }
      }
    })
  })
})(window.jQuery)
