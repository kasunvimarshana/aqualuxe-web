/**
 * AquaLuxe Admin JavaScript
 * 
 * Handles functionality specific to the WordPress admin area
 */

document.addEventListener('DOMContentLoaded', () => {
  // Theme options tabs
  const optionsTabs = document.querySelectorAll('.aqualuxe-options-tab');
  const optionsPanels = document.querySelectorAll('.aqualuxe-options-panel');
  
  if (optionsTabs.length && optionsPanels.length) {
    optionsTabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Remove active class from all tabs
        optionsTabs.forEach(t => t.classList.remove('nav-tab-active'));
        
        // Add active class to clicked tab
        tab.classList.add('nav-tab-active');
        
        // Hide all panels
        optionsPanels.forEach(panel => {
          panel.style.display = 'none';
        });
        
        // Show the corresponding panel
        const panelId = tab.getAttribute('href').substring(1);
        const panel = document.getElementById(panelId);
        if (panel) {
          panel.style.display = 'block';
        }
        
        // Save the active tab in localStorage
        localStorage.setItem('aqualuxe_active_tab', panelId);
      });
    });
    
    // Check for saved active tab
    const activeTab = localStorage.getItem('aqualuxe_active_tab');
    if (activeTab) {
      const tab = document.querySelector(`.aqualuxe-options-tab[href="#${activeTab}"]`);
      if (tab) {
        tab.click();
      } else {
        // Default to first tab
        optionsTabs[0].click();
      }
    } else {
      // Default to first tab
      optionsTabs[0].click();
    }
  }
  
  // Media uploader for image fields
  const mediaUploadButtons = document.querySelectorAll('.aqualuxe-media-upload');
  
  if (mediaUploadButtons.length && typeof wp !== 'undefined' && wp.media) {
    mediaUploadButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        
        const inputField = document.getElementById(button.dataset.input);
        const previewImage = document.getElementById(button.dataset.preview);
        
        // Create media uploader instance
        const mediaUploader = wp.media({
          title: 'Select Image',
          button: {
            text: 'Use this image'
          },
          multiple: false
        });
        
        // When image is selected
        mediaUploader.on('select', function() {
          const attachment = mediaUploader.state().get('selection').first().toJSON();
          
          // Update input field with image ID
          if (inputField) {
            inputField.value = attachment.id;
          }
          
          // Update preview image
          if (previewImage) {
            previewImage.src = attachment.url;
            previewImage.style.display = 'block';
          }
        });
        
        // Open media uploader
        mediaUploader.open();
      });
    });
    
    // Remove image buttons
    const mediaRemoveButtons = document.querySelectorAll('.aqualuxe-media-remove');
    
    mediaRemoveButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        
        const inputField = document.getElementById(button.dataset.input);
        const previewImage = document.getElementById(button.dataset.preview);
        
        // Clear input field
        if (inputField) {
          inputField.value = '';
        }
        
        // Hide preview image
        if (previewImage) {
          previewImage.src = '';
          previewImage.style.display = 'none';
        }
      });
    });
  }
  
  // Color picker initialization
  const colorPickers = document.querySelectorAll('.aqualuxe-color-picker');
  
  if (colorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
    colorPickers.forEach(picker => {
      jQuery(picker).wpColorPicker();
    });
  }
  
  // Repeater fields
  const repeaterAddButtons = document.querySelectorAll('.aqualuxe-repeater-add');
  
  if (repeaterAddButtons.length) {
    repeaterAddButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        
        const repeaterContainer = document.getElementById(button.dataset.container);
        const repeaterTemplate = document.getElementById(button.dataset.template);
        
        if (repeaterContainer && repeaterTemplate) {
          // Clone template
          const newItem = repeaterTemplate.content.cloneNode(true);
          
          // Update indices
          const index = repeaterContainer.querySelectorAll('.aqualuxe-repeater-item').length;
          const nameRegex = /\[(\d+)\]/g;
          const idRegex = /_(\d+)_/g;
          
          newItem.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace(nameRegex, `[${index}]`);
          });
          
          newItem.querySelectorAll('[id]').forEach(el => {
            el.id = el.id.replace(idRegex, `_${index}_`);
          });
          
          // Add to container
          repeaterContainer.appendChild(newItem);
          
          // Initialize new color pickers if any
          const newColorPickers = repeaterContainer.querySelectorAll(`.aqualuxe-repeater-item:nth-child(${index + 1}) .aqualuxe-color-picker`);
          
          if (newColorPickers.length && typeof jQuery !== 'undefined' && jQuery.fn.wpColorPicker) {
            newColorPickers.forEach(picker => {
              jQuery(picker).wpColorPicker();
            });
          }
          
          // Setup remove button
          const removeButton = repeaterContainer.querySelector(`.aqualuxe-repeater-item:nth-child(${index + 1}) .aqualuxe-repeater-remove`);
          
          if (removeButton) {
            removeButton.addEventListener('click', (e) => {
              e.preventDefault();
              e.target.closest('.aqualuxe-repeater-item').remove();
            });
          }
        }
      });
    });
    
    // Setup existing remove buttons
    document.querySelectorAll('.aqualuxe-repeater-remove').forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        e.target.closest('.aqualuxe-repeater-item').remove();
      });
    });
  }
  
  // Sortable fields
  const sortableContainers = document.querySelectorAll('.aqualuxe-sortable');
  
  if (sortableContainers.length && typeof jQuery !== 'undefined' && jQuery.fn.sortable) {
    sortableContainers.forEach(container => {
      jQuery(container).sortable({
        handle: '.aqualuxe-sortable-handle',
        update: function() {
          // Update order input fields if needed
          const items = container.querySelectorAll('.aqualuxe-sortable-item');
          items.forEach((item, index) => {
            const orderInput = item.querySelector('.aqualuxe-sortable-order');
            if (orderInput) {
              orderInput.value = index;
            }
          });
        }
      });
    });
  }
  
  // Toggle fields
  const toggleFields = document.querySelectorAll('.aqualuxe-toggle-field');
  
  if (toggleFields.length) {
    toggleFields.forEach(field => {
      const toggleInput = field.querySelector('input[type="checkbox"]');
      const dependentFields = document.querySelectorAll(`.aqualuxe-dependent-field[data-depends-on="${toggleInput.id}"]`);
      
      // Initial state
      updateDependentFields(toggleInput, dependentFields);
      
      // On change
      toggleInput.addEventListener('change', () => {
        updateDependentFields(toggleInput, dependentFields);
      });
    });
  }
  
  function updateDependentFields(toggleInput, dependentFields) {
    dependentFields.forEach(field => {
      if (toggleInput.checked) {
        field.style.display = 'block';
      } else {
        field.style.display = 'none';
      }
    });
  }
  
  // Import demo content
  const importDemoButton = document.getElementById('aqualuxe-import-demo');
  
  if (importDemoButton) {
    importDemoButton.addEventListener('click', (e) => {
      e.preventDefault();
      
      if (!confirm('Are you sure you want to import demo content? This may overwrite existing content.')) {
        return;
      }
      
      // Disable button and show loading state
      importDemoButton.disabled = true;
      importDemoButton.innerHTML = '<span class="spinner is-active"></span> Importing...';
      
      // Send AJAX request
      const xhr = new XMLHttpRequest();
      xhr.open('POST', ajaxurl);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          try {
            const response = JSON.parse(xhr.responseText);
            
            // Reset button
            importDemoButton.disabled = false;
            importDemoButton.textContent = 'Import Demo Content';
            
            // Show response message
            alert(response.data.message);
            
            // Reload page if successful
            if (response.success) {
              window.location.reload();
            }
          } catch (error) {
            console.error('Error parsing response:', error);
            importDemoButton.disabled = false;
            importDemoButton.textContent = 'Import Demo Content';
            alert('An error occurred. Please try again.');
          }
        } else {
          console.error('Request failed:', xhr.status);
          importDemoButton.disabled = false;
          importDemoButton.textContent = 'Import Demo Content';
          alert('An error occurred. Please try again.');
        }
      };
      xhr.onerror = function() {
        console.error('Request failed');
        importDemoButton.disabled = false;
        importDemoButton.textContent = 'Import Demo Content';
        alert('An error occurred. Please try again.');
      };
      xhr.send('action=aqualuxe_import_demo&nonce=' + aqualuxeAdminData.nonce);
    });
  }
});