/**
 * Customizer Preview JavaScript
 * @package AquaLuxe
 */

/* global wp */

(function ($) {
  'use strict'

  // Site title
  wp.customize('blogname', function (value) {
    value.bind(function (to) {
      $('.site-title a').text(to)
    })
  })

  // Site description
  wp.customize('blogdescription', function (value) {
    value.bind(function (to) {
      $('.site-description').text(to)
    })
  })

  // Header background color
  wp.customize('header_background_color', function (value) {
    value.bind(function (to) {
      $('.site-header').css('background-color', to)
    })
  })

  // Primary color
  wp.customize('primary_color', function (value) {
    value.bind(function (to) {
      const style = '<style id="primary-color-style">.btn-primary, .primary-bg { background-color: ' + to + ' !important; }</style>'
      $('#primary-color-style').remove()
      $('head').append(style)
    })
  })

  // Footer text
  wp.customize('footer_text', function (value) {
    value.bind(function (to) {
      $('.footer-text').text(to)
    })
  })
})(jQuery)
