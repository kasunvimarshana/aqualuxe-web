/*
 * Admin JavaScript for AquaLuxe Theme
 */

(function($) {
	// Document ready
	$(document).ready(function() {
		// Demo import button click event
		$('.aqualuxe-demo-import-button').on('click', function() {
			const button = $(this);
			const demoName = button.data('demo-name');
			
			// Disable button and show loading text
			button.prop('disabled', true).text('Importing...');
			
			// Send AJAX request to import demo
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'aqualuxe_import_demo',
					demo_name: demoName,
					nonce: aqualuxe_ajax.nonce
				},
				success: function(response) {
					if (response.success) {
						button.text('Imported Successfully');
						alert('Demo imported successfully!');
					} else {
						button.prop('disabled', false).text('Import Failed');
						alert('Demo import failed: ' + response.data);
					}
				},
				error: function() {
					button.prop('disabled', false).text('Import Failed');
					alert('Demo import failed: Unknown error');
				}
			});
		});
		
		// Color picker initialization
		if ($('.color-picker').length > 0) {
			$('.color-picker').wpColorPicker();
		}
		
		// Media upload button click event
		$(document).on('click', '.upload-button', function(e) {
			e.preventDefault();
			
			const button = $(this);
			const inputField = button.siblings('input[type="text"]');
			
			// Create media frame
			const mediaFrame = wp.media({
				title: 'Select Image',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});
			
			// When image is selected
			mediaFrame.on('select', function() {
				const attachment = mediaFrame.state().get('selection').first().toJSON();
				inputField.val(attachment.url);
			});
			
			// Open media frame
			mediaFrame.open();
		});
		
		// Range slider change event
		$(document).on('input', '.slider-input', function() {
			const slider = $(this);
			const valueDisplay = slider.siblings('.slider-value');
			valueDisplay.text(slider.val());
		});
		
		// Toggle switch change event
		$(document).on('change', '.toggle-switch input', function() {
			const toggle = $(this);
			const toggleContainer = toggle.closest('.toggle-switch');
			
			if (toggle.is(':checked')) {
				toggleContainer.addClass('active');
			} else {
				toggleContainer.removeClass('active');
			}
		});
		
		// Repeater field add button click event
		$(document).on('click', '.repeater-add', function() {
			const addButton = $(this);
			const repeaterContainer = addButton.closest('.repeater-container');
			const repeaterFields = repeaterContainer.find('.repeater-fields');
			const fieldTemplate = repeaterContainer.find('.repeater-field-template');
			
			// Clone template and append to fields
			const newField = fieldTemplate.clone();
			newField.removeClass('repeater-field-template').addClass('repeater-field');
			newField.find('input, textarea, select').each(function() {
				const input = $(this);
				const name = input.attr('name');
				if (name) {
					const index = repeaterFields.children().length;
					input.attr('name', name.replace('[]', '[' + index + ']'));
				}
			});
			
			repeaterFields.append(newField);
		});
		
		// Repeater field remove button click event
		$(document).on('click', '.repeater-field-remove', function() {
			const removeButton = $(this);
			const repeaterField = removeButton.closest('.repeater-field');
			
			repeaterField.remove();
		});
		
		// Repeater field toggle click event
		$(document).on('click', '.repeater-field-toggle', function() {
			const toggleButton = $(this);
			const repeaterField = toggleButton.closest('.repeater-field');
			const content = repeaterField.find('.repeater-field-content');
			
			content.toggleClass('active');
		});
		
		// Sortable list initialization
		if ($('.sortable-list').length > 0) {
			$('.sortable-list').sortable({
				handle: '.dashicons-move',
				placeholder: 'sortable-placeholder',
				forcePlaceholderSize: true
			});
		}
		
		// Icon picker click event
		$(document).on('click', '.icon-option', function() {
			const iconOption = $(this);
			const iconPicker = iconOption.closest('.icon-picker');
			const inputField = iconPicker.siblings('input[type="hidden"]');
			
			// Remove selected class from all options
			iconPicker.find('.icon-option').removeClass('selected');
			
			// Add selected class to clicked option
			iconOption.addClass('selected');
			
			// Update input field with selected icon
			inputField.val(iconOption.data('icon'));
		});
		
		// Initialize icon picker selection
		$('.icon-picker').each(function() {
			const iconPicker = $(this);
			const inputField = iconPicker.siblings('input[type="hidden"]');
			const selectedIcon = inputField.val();
			
			if (selectedIcon) {
				iconPicker.find('.icon-option[data-icon="' + selectedIcon + '"]').addClass('selected');
			}
		});
		
		// Customizer control dependencies
		function handleControlDependencies() {
			// Show/hide controls based on other control values
			$('.dependency-control').each(function() {
				const control = $(this);
				const dependency = control.data('dependency');
				const dependencyValue = control.data('dependency-value');
				
				if (dependency && dependencyValue) {
					const dependencyControl = $('#customize-control-' + dependency);
					const currentValue = dependencyControl.find('input:checked').val() || dependencyControl.find('select').val();
					
					if (currentValue == dependencyValue) {
						control.show();
					} else {
						control.hide();
					}
				}
			});
		}
		
		// Handle control dependencies on page load
		handleControlDependencies();
		
		// Handle control dependencies when controls change
		$(document).on('change', '.dependency-trigger', function() {
			handleControlDependencies();
		});
		
		// Customizer image upload
		$(document).on('click', '.customizer-image-upload', function(e) {
			e.preventDefault();
			
			const button = $(this);
			const inputField = button.siblings('input[type="text"]');
			
			// Create media frame
			const mediaFrame = wp.media({
				title: 'Select Image',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});
			
			// When image is selected
			mediaFrame.on('select', function() {
				const attachment = mediaFrame.state().get('selection').first().toJSON();
				inputField.val(attachment.url);
				
				// Update preview image
				const preview = button.siblings('.customizer-image-preview');
				if (preview.length > 0) {
					preview.attr('src', attachment.url);
				}
			});
			
			// Open media frame
			mediaFrame.open();
		});
		
		// Customizer image remove
		$(document).on('click', '.customizer-image-remove', function(e) {
			e.preventDefault();
			
			const button = $(this);
			const inputField = button.siblings('input[type="text"]');
			const preview = button.siblings('.customizer-image-preview');
			
			inputField.val('');
			preview.attr('src', '');
		});
		
		// Customizer color scheme presets
		$(document).on('click', '.color-scheme-preset', function() {
			const preset = $(this);
			const scheme = preset.data('scheme');
			
			// Apply color scheme to relevant controls
			switch(scheme) {
				case 'blue':
					$('#customize-control-header_background_color input').val('#007bff').trigger('change');
					$('#customize-control-link_color input').val('#0056b3').trigger('change');
					break;
				case 'green':
					$('#customize-control-header_background_color input').val('#28a745').trigger('change');
					$('#customize-control-link_color input').val('#1e7e34').trigger('change');
					break;
				case 'purple':
					$('#customize-control-header_background_color input').val('#6f42c1').trigger('change');
					$('#customize-control-link_color input').val('#5a32a3').trigger('change');
					break;
			}
		});
	});
	
	// Window load
	$(window).on('load', function() {
		// Initialize any components that need to wait for all resources to load
	});
	
	// Customizer control initialization
	$(document).on('click', '.customize-control-title', function() {
		const title = $(this);
		const section = title.closest('.control-section');
		
		// Toggle section visibility
		section.toggleClass('open');
	});
	
	// Customizer accordion initialization
	$('.customize-accordion').each(function() {
		const accordion = $(this);
		const headers = accordion.find('.accordion-header');
		
		headers.on('click', function() {
			const header = $(this);
			const content = header.siblings('.accordion-content');
			
			// Close other accordion items
			accordion.find('.accordion-content').not(content).slideUp();
			accordion.find('.accordion-header').not(header).removeClass('active');
			
			// Toggle current accordion item
			content.slideToggle();
			header.toggleClass('active');
		});
	});
	
	// Customizer tabbed interface
	$('.customize-tabs').each(function() {
		const tabs = $(this);
		const tabHeaders = tabs.find('.tab-header');
		const tabContents = tabs.find('.tab-content');
		
		tabHeaders.on('click', function() {
			const tabHeader = $(this);
			const tabId = tabHeader.data('tab');
			
			// Remove active class from all headers and contents
			tabHeaders.removeClass('active');
			tabContents.removeClass('active');
			
			// Add active class to clicked header and corresponding content
			tabHeader.addClass('active');
			tabs.find('.tab-content[data-tab="' + tabId + '"]').addClass('active');
		});
	});
	
	// Customizer multi-select initialization
	$('.customize-multiselect').each(function() {
		const multiselect = $(this);
		const options = multiselect.find('option');
		const selectedValues = multiselect.val() || [];
		
		// Create checkbox list
		const checkboxList = $('<div class="multiselect-checkboxes"></div>');
		
		options.each(function() {
			const option = $(this);
			const value = option.val();
			const label = option.text();
			const checked = selectedValues.includes(value) ? 'checked' : '';
			
			const checkbox = $('<label><input type="checkbox" value="' + value + '" ' + checked + '> ' + label + '</label>');
			checkboxList.append(checkbox);
		});
		
		// Replace select with checkbox list
		multiselect.hide().after(checkboxList);
		
		// Handle checkbox changes
		checkboxList.on('change', 'input[type="checkbox"]', function() {
			const checkboxes = checkboxList.find('input[type="checkbox"]:checked');
			const values = [];
			
			checkboxes.each(function() {
				values.push($(this).val());
			});
			
			multiselect.val(values).trigger('change');
		});
	});
	
	// Customizer date picker initialization
	if ($('.customize-datepicker').length > 0) {
		$('.customize-datepicker').datepicker({
			dateFormat: 'yy-mm-dd'
		});
	}
	
	// Customizer time picker initialization
	if ($('.customize-timepicker').length > 0) {
		$('.customize-timepicker').timepicker({
			timeFormat: 'HH:mm'
		});
	}
	
	// Customizer font selector
	$(document).on('change', '.font-selector', function() {
		const selector = $(this);
		const fontFamily = selector.val();
		
		// Update preview with selected font
		const preview = selector.siblings('.font-preview');
		if (preview.length > 0) {
			preview.css('font-family', fontFamily);
		}
		
		// Load Google Font if needed
		if (fontFamily && fontFamily.includes('Google:')) {
			const fontName = fontFamily.replace('Google:', '');
			const linkId = 'google-font-' + fontName.replace(/\s+/g, '-').toLowerCase();
			
			// Remove existing link if present
			$('#' + linkId).remove();
			
			// Add Google Font link
			const link = $('<link>');
			link.attr({
				id: linkId,
				rel: 'stylesheet',
				type: 'text/css',
				href: 'https://fonts.googleapis.com/css2?family=' + fontName.replace(/\s+/g, '+') + ':wght@400;500;600;700&display=swap'
			});
			
			$('head').append(link);
		}
	});
	
	// Customizer background pattern selector
	$(document).on('click', '.pattern-option', function() {
		const option = $(this);
		const pattern = option.data('pattern');
		const inputField = option.siblings('input[type="hidden"]');
		
		// Remove selected class from all options
		option.siblings('.pattern-option').removeClass('selected');
		
		// Add selected class to clicked option
		option.addClass('selected');
		
		// Update input field with selected pattern
		inputField.val(pattern);
		
		// Update preview
		const preview = option.siblings('.pattern-preview');
		if (preview.length > 0) {
			preview.css('background-image', 'url(' + pattern + ')');
		}
	});
	
	// Customizer social media repeater
	$(document).on('click', '.social-media-add', function() {
		const addButton = $(this);
		const repeaterContainer = addButton.closest('.social-media-repeater');
		const repeaterFields = repeaterContainer.find('.social-media-fields');
		
		// Create new field
		const newField = $('<div class="social-media-field">' +
			'<input type="text" name="social_media_icon[]" placeholder="Icon">' +
			'<input type="text" name="social_media_url[]" placeholder="URL">' +
			'<button type="button" class="social-media-remove">Remove</button>' +
		'</div>');
		
		repeaterFields.append(newField);
	});
	
	// Customizer social media remove
	$(document).on('click', '.social-media-remove', function() {
		const removeButton = $(this);
		const field = removeButton.closest('.social-media-field');
		
		field.remove();
	});
})(jQuery);