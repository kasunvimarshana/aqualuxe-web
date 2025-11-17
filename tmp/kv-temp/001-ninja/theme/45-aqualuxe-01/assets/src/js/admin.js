/**
 * AquaLuxe Theme Admin JavaScript
 * 
 * This file contains JavaScript functionality for the WordPress admin area.
 */

document.addEventListener('DOMContentLoaded', () => {
  // Custom media uploader for theme options
  const mediaUploadButtons = document.querySelectorAll('.aqualuxe-media-upload');
  
  if (mediaUploadButtons.length) {
    mediaUploadButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetInput = document.getElementById(this.dataset.target);
        const targetPreview = document.getElementById(this.dataset.preview);
        
        if (!targetInput) return;
        
        // If wp.media is available (we're in the WordPress admin)
        if (typeof wp !== 'undefined' && wp.media) {
          const mediaUploader = wp.media({
            title: 'Select or Upload Media',
            button: {
              text: 'Use this media'
            },
            multiple: false
          });
          
          mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            targetInput.value = attachment.url;
            
            if (targetPreview) {
              if (attachment.type === 'image') {
                targetPreview.innerHTML = `<img src="${attachment.url}" alt="Preview" style="max-width: 100%; height: auto;">`;
              } else {
                targetPreview.innerHTML = `<div class="file-preview">${attachment.filename}</div>`;
              }
            }
          });
          
          mediaUploader.open();
        }
      });
    });
  }
  
  // Color picker initialization
  const colorPickers = document.querySelectorAll('.aqualuxe-color-picker');
  
  if (colorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
    jQuery(colorPickers).wpColorPicker();
  }
  
  // Meta box toggle functionality
  const metaBoxToggles = document.querySelectorAll('.aqualuxe-meta-box-toggle');
  
  if (metaBoxToggles.length) {
    metaBoxToggles.forEach(toggle => {
      toggle.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.dataset.target;
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
          targetElement.classList.toggle('hidden');
          this.classList.toggle('toggled');
          
          // Update toggle icon
          const icon = this.querySelector('.toggle-icon');
          if (icon) {
            if (this.classList.contains('toggled')) {
              icon.textContent = '−'; // Minus sign
            } else {
              icon.textContent = '+'; // Plus sign
            }
          }
        }
      });
    });
  }
  
  // Sortable fields for custom options
  if (typeof jQuery !== 'undefined' && jQuery.fn.sortable) {
    jQuery('.aqualuxe-sortable').sortable({
      update: function(event, ui) {
        // Update the hidden input with the new order
        const container = jQuery(this);
        const inputField = container.siblings('input.sortable-value');
        
        if (inputField.length) {
          const newOrder = container.children().map(function() {
            return jQuery(this).data('id');
          }).get().join(',');
          
          inputField.val(newOrder);
        }
      }
    });
  }
  
  // Repeater fields
  const addRepeaterItemButtons = document.querySelectorAll('.add-repeater-item');
  
  if (addRepeaterItemButtons.length) {
    addRepeaterItemButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const repeaterContainer = document.getElementById(this.dataset.container);
        const template = document.getElementById(this.dataset.template);
        
        if (repeaterContainer && template) {
          const newItem = template.content.cloneNode(true);
          const index = repeaterContainer.children.length;
          
          // Replace placeholder index with actual index
          const html = newItem.firstElementChild.outerHTML.replace(/\{index\}/g, index);
          
          repeaterContainer.insertAdjacentHTML('beforeend', html);
          
          // Initialize any special fields in the new item
          initializeSpecialFields(repeaterContainer.lastElementChild);
        }
      });
    });
    
    // Remove repeater item
    document.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('remove-repeater-item')) {
        e.preventDefault();
        
        const item = e.target.closest('.repeater-item');
        if (item) {
          item.remove();
        }
      }
    });
  }
  
  // Initialize special fields (color pickers, etc.) in dynamically added content
  function initializeSpecialFields(container) {
    if (!container) return;
    
    // Initialize color pickers
    const colorPickers = container.querySelectorAll('.aqualuxe-color-picker');
    if (colorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
      jQuery(colorPickers).wpColorPicker();
    }
    
    // Initialize media uploaders
    const mediaButtons = container.querySelectorAll('.aqualuxe-media-upload');
    if (mediaButtons.length) {
      mediaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          
          const targetInput = document.getElementById(this.dataset.target);
          const targetPreview = document.getElementById(this.dataset.preview);
          
          if (!targetInput) return;
          
          // If wp.media is available (we're in the WordPress admin)
          if (typeof wp !== 'undefined' && wp.media) {
            const mediaUploader = wp.media({
              title: 'Select or Upload Media',
              button: {
                text: 'Use this media'
              },
              multiple: false
            });
            
            mediaUploader.on('select', function() {
              const attachment = mediaUploader.state().get('selection').first().toJSON();
              targetInput.value = attachment.url;
              
              if (targetPreview) {
                if (attachment.type === 'image') {
                  targetPreview.innerHTML = `<img src="${attachment.url}" alt="Preview" style="max-width: 100%; height: auto;">`;
                } else {
                  targetPreview.innerHTML = `<div class="file-preview">${attachment.filename}</div>`;
                }
              }
            });
            
            mediaUploader.open();
          }
        });
      });
    }
  }
});