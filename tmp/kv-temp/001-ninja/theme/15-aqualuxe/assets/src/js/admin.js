/**
 * AquaLuxe Admin JavaScript
 * 
 * Handles custom functionality in the WordPress admin area
 */

document.addEventListener('DOMContentLoaded', function() {
  // Theme options tabs functionality
  const optionsTabs = document.querySelectorAll('.aqualuxe-admin-tabs li a');
  const optionsPanels = document.querySelectorAll('.aqualuxe-admin-panel');
  
  if (optionsTabs.length && optionsPanels.length) {
    optionsTabs.forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs
        optionsTabs.forEach(t => t.classList.remove('nav-tab-active'));
        
        // Add active class to current tab
        this.classList.add('nav-tab-active');
        
        // Hide all panels
        optionsPanels.forEach(panel => panel.style.display = 'none');
        
        // Show the selected panel
        const targetPanel = document.querySelector(this.getAttribute('href'));
        if (targetPanel) {
          targetPanel.style.display = 'block';
        }
        
        // Save the active tab to localStorage
        localStorage.setItem('aqualuxeActiveTab', this.getAttribute('href'));
      });
    });
    
    // Check for saved tab in localStorage
    const savedTab = localStorage.getItem('aqualuxeActiveTab');
    if (savedTab) {
      const activeTab = document.querySelector(`a[href="${savedTab}"]`);
      if (activeTab) {
        activeTab.click();
      } else {
        // Default to first tab
        optionsTabs[0].click();
      }
    } else {
      // Default to first tab
      optionsTabs[0].click();
    }
  }
  
  // Media uploader for theme options
  const mediaButtons = document.querySelectorAll('.aqualuxe-media-upload');
  
  if (mediaButtons.length && typeof wp !== 'undefined' && wp.media) {
    mediaButtons.forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetInput = document.querySelector(this.dataset.input);
        const targetPreview = document.querySelector(this.dataset.preview);
        
        if (!targetInput) return;
        
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
              targetPreview.innerHTML = `<img src="${attachment.url}" alt="Preview" style="max-width: 100%; max-height: 200px;">`;
            } else {
              targetPreview.innerHTML = `<div class="media-preview-filename">${attachment.filename}</div>`;
            }
          }
          
          // Trigger change event
          targetInput.dispatchEvent(new Event('change', { bubbles: true }));
        });
        
        mediaUploader.open();
      });
    });
  }
  
  // Color picker initialization
  const colorPickers = document.querySelectorAll('.aqualuxe-color-picker');
  
  if (colorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
    colorPickers.forEach(picker => {
      jQuery(picker).wpColorPicker({
        change: function(event, ui) {
          // Trigger change event for custom handling
          setTimeout(() => {
            event.target.dispatchEvent(new Event('aqualuxe-color-change', { bubbles: true }));
          }, 100);
        }
      });
    });
  }
  
  // Custom meta boxes functionality
  const metaBoxToggles = document.querySelectorAll('.aqualuxe-meta-toggle');
  
  metaBoxToggles.forEach(toggle => {
    toggle.addEventListener('change', function() {
      const targetSection = document.querySelector(this.dataset.target);
      if (targetSection) {
        if (this.checked) {
          targetSection.style.display = 'block';
        } else {
          targetSection.style.display = 'none';
        }
      }
    });
    
    // Initialize state
    toggle.dispatchEvent(new Event('change'));
  });
  
  // Sortable fields for custom sections
  const sortableContainers = document.querySelectorAll('.aqualuxe-sortable');
  
  if (sortableContainers.length && typeof jQuery !== 'undefined' && jQuery.fn.sortable) {
    sortableContainers.forEach(container => {
      jQuery(container).sortable({
        items: '.aqualuxe-sortable-item',
        handle: '.aqualuxe-sortable-handle',
        update: function() {
          // Update order values
          const items = container.querySelectorAll('.aqualuxe-sortable-item');
          items.forEach((item, index) => {
            const orderInput = item.querySelector('.aqualuxe-sortable-order');
            if (orderInput) {
              orderInput.value = index;
            }
          });
          
          // Trigger change event
          container.dispatchEvent(new Event('aqualuxe-order-change', { bubbles: true }));
        }
      });
    });
  }
  
  // Repeater fields
  const repeaterAddButtons = document.querySelectorAll('.aqualuxe-repeater-add');
  
  repeaterAddButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      
      const container = document.querySelector(this.dataset.container);
      const template = document.querySelector(this.dataset.template);
      
      if (!container || !template) return;
      
      // Clone template
      const clone = template.content.cloneNode(true);
      
      // Update IDs and names with unique index
      const timestamp = new Date().getTime();
      const allElements = clone.querySelectorAll('[name], [id], [data-name], [data-id]');
      
      allElements.forEach(el => {
        if (el.hasAttribute('name')) {
          el.setAttribute('name', el.getAttribute('name').replace('{{index}}', timestamp));
        }
        if (el.hasAttribute('id')) {
          el.setAttribute('id', el.getAttribute('id').replace('{{index}}', timestamp));
        }
        if (el.hasAttribute('data-name')) {
          el.setAttribute('data-name', el.getAttribute('data-name').replace('{{index}}', timestamp));
        }
        if (el.hasAttribute('data-id')) {
          el.setAttribute('data-id', el.getAttribute('data-id').replace('{{index}}', timestamp));
        }
      });
      
      // Add to container
      container.appendChild(clone);
      
      // Initialize any special fields in the clone
      const newItem = container.lastElementChild;
      
      // Initialize color pickers
      const newColorPickers = newItem.querySelectorAll('.aqualuxe-color-picker');
      if (newColorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
        newColorPickers.forEach(picker => {
          jQuery(picker).wpColorPicker();
        });
      }
      
      // Trigger event for custom handling
      container.dispatchEvent(new CustomEvent('aqualuxe-repeater-added', { 
        bubbles: true,
        detail: { item: newItem }
      }));
    });
  });
  
  // Handle repeater item removal
  document.addEventListener('click', function(e) {
    if (e.target.matches('.aqualuxe-repeater-remove') || e.target.closest('.aqualuxe-repeater-remove')) {
      e.preventDefault();
      
      const button = e.target.matches('.aqualuxe-repeater-remove') ? 
                     e.target : 
                     e.target.closest('.aqualuxe-repeater-remove');
                     
      const item = button.closest('.aqualuxe-repeater-item');
      const container = item.parentNode;
      
      if (confirm('Are you sure you want to remove this item?')) {
        item.remove();
        
        // Trigger event for custom handling
        container.dispatchEvent(new CustomEvent('aqualuxe-repeater-removed', { 
          bubbles: true 
        }));
      }
    }
  });
});