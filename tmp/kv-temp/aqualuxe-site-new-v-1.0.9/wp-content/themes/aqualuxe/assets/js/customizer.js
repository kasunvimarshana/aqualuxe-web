/**
 * AquaLuxe Customizer JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
  'use strict';
  
  // Site title and description updates
  wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.site-title a').text(to);
    });
  });
  
  wp.customize('blogdescription', function(value) {
    value.bind(function(to) {
      $('.site-description').text(to);
    });
  });
  
  // Primary color updates
  wp.customize('aqualuxe_primary_color', function(value) {
    value.bind(function(to) {
      $(':root').css('--primary-color', to);
    });
  });
  
  // Secondary color updates
  wp.customize('aqualuxe_secondary_color', function(value) {
    value.bind(function(to) {
      $(':root').css('--secondary-color', to);
    });
  });
  
  // Body font updates
  wp.customize('aqualuxe_body_font', function(value) {
    value.bind(function(to) {
      $('body').css('font-family', to);
    });
  });
  
  // Heading font updates
  wp.customize('aqualuxe_heading_font', function(value) {
    value.bind(function(to) {
      $('h1, h2, h3, h4, h5, h6').css('font-family', to);
    });
  });
  
})(jQuery);