/**
 * AquaLuxe Multilingual Module Admin Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Language Tabs
     */
    var LanguageTabs = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initTabs();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('click', '.aqualuxe-language-tabs a', this.handleTabClick);
        },
        
        /**
         * Initialize tabs
         */
        initTabs: function() {
            // Show first tab by default
            $('.aqualuxe-language-tabs li:first-child').addClass('active');
            $('.aqualuxe-language-tab-content:first-child').addClass('active');
        },
        
        /**
         * Handle tab click
         *
         * @param {Event} e Event
         */
        handleTabClick: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $tab = $this.parent();
            var $tabs = $tab.parent();
            var $tabContents = $tabs.next('.aqualuxe-language-tab-contents');
            var target = $this.attr('href');
            
            // Remove active class from all tabs
            $tabs.find('li').removeClass('active');
            
            // Add active class to current tab
            $tab.addClass('active');
            
            // Hide all tab contents
            $tabContents.find('.aqualuxe-language-tab-content').removeClass('active');
            
            // Show target tab content
            $(target).addClass('active');
            
            // Save active tab in localStorage
            if (typeof Storage !== 'undefined') {
                localStorage.setItem('aqualuxeLanguageActiveTab', target);
            }
        },
        
        /**
         * Restore active tab from localStorage
         */
        restoreActiveTab: function() {
            if (typeof Storage !== 'undefined') {
                var activeTab = localStorage.getItem('aqualuxeLanguageActiveTab');
                
                if (activeTab) {
                    var $tab = $('.aqualuxe-language-tabs a[href="' + activeTab + '"]');
                    
                    if ($tab.length) {
                        $tab.trigger('click');
                    }
                }
            }
        }
    };
    
    /**
     * Language Settings
     */
    var LanguageSettings = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initTooltips();
            this.initColorPickers();
            this.initSortable();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Toggle settings sections
            $(document).on('click', '.aqualuxe-language-settings-section-toggle', this.toggleSection);
            
            // Add language
            $(document).on('click', '.aqualuxe-add-language', this.addLanguage);
            
            // Remove language
            $(document).on('click', '.aqualuxe-remove-language', this.removeLanguage);
            
            // Toggle language status
            $(document).on('change', '.aqualuxe-language-status-toggle', this.toggleLanguageStatus);
            
            // Show/hide settings based on switcher style
            $(document).on('change', '.aqualuxe-language-switcher-style', this.toggleSwitcherSettings);
        },
        
        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('.aqualuxe-tooltip').tipTip({
                attribute: 'data-tip',
                fadeIn: 50,
                fadeOut: 50,
                delay: 200
            });
        },
        
        /**
         * Initialize color pickers
         */
        initColorPickers: function() {
            $('.aqualuxe-color-picker').wpColorPicker();
        },
        
        /**
         * Initialize sortable
         */
        initSortable: function() {
            $('.aqualuxe-sortable').sortable({
                items: '.aqualuxe-sortable-item',
                handle: '.aqualuxe-sortable-handle',
                axis: 'y',
                update: function() {
                    // Update order
                    $('.aqualuxe-sortable-item').each(function(index) {
                        $(this).find('.aqualuxe-language-order').val(index);
                    });
                }
            });
        },
        
        /**
         * Toggle settings section
         *
         * @param {Event} e Event
         */
        toggleSection: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $section = $this.closest('.aqualuxe-language-settings-section');
            var $content = $section.find('.aqualuxe-language-settings-section-content');
            
            $content.slideToggle(200);
            $this.toggleClass('open');
        },
        
        /**
         * Add language
         *
         * @param {Event} e Event
         */
        addLanguage: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $container = $this.closest('.aqualuxe-language-settings-field');
            var $template = $container.find('.aqualuxe-language-template');
            var $languages = $container.find('.aqualuxe-languages');
            var index = $languages.find('.aqualuxe-language-item').length;
            
            // Clone template
            var $newLanguage = $template.clone();
            $newLanguage.removeClass('aqualuxe-language-template').addClass('aqualuxe-language-item');
            
            // Update IDs and names
            $newLanguage.find('[name]').each(function() {
                var name = $(this).attr('name');
                $(this).attr('name', name.replace('__index__', index));
            });
            
            $newLanguage.find('[id]').each(function() {
                var id = $(this).attr('id');
                $(this).attr('id', id.replace('__index__', index));
            });
            
            $newLanguage.find('[for]').each(function() {
                var forAttr = $(this).attr('for');
                $(this).attr('for', forAttr.replace('__index__', index));
            });
            
            // Add to languages container
            $languages.append($newLanguage);
            
            // Show the new language
            $newLanguage.show();
            
            // Initialize color picker
            $newLanguage.find('.aqualuxe-color-picker').wpColorPicker();
            
            // Focus on language code field
            $newLanguage.find('.aqualuxe-language-code').focus();
        },
        
        /**
         * Remove language
         *
         * @param {Event} e Event
         */
        removeLanguage: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var $language = $this.closest('.aqualuxe-language-item');
            
            // Confirm removal
            if (confirm(aqualuxeMultilingualAdmin.confirmRemoveLanguage)) {
                $language.remove();
            }
        },
        
        /**
         * Toggle language status
         *
         * @param {Event} e Event
         */
        toggleLanguageStatus: function() {
            var $this = $(this);
            var $language = $this.closest('.aqualuxe-language-item');
            var isActive = $this.is(':checked');
            
            if (isActive) {
                $language.removeClass('inactive').addClass('active');
            } else {
                $language.removeClass('active').addClass('inactive');
            }
        },
        
        /**
         * Toggle switcher settings
         */
        toggleSwitcherSettings: function() {
            var $this = $(this);
            var style = $this.val();
            var $container = $this.closest('.aqualuxe-language-settings-section');
            
            // Hide all style-specific settings
            $container.find('.aqualuxe-language-switcher-style-settings').hide();
            
            // Show settings for current style
            $container.find('.aqualuxe-language-switcher-style-' + style + '-settings').show();
        }
    };
    
    /**
     * Menu Item Language
     */
    var MenuItemLanguage = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },
        
        /**
         * Bind events
         */
        bindEvents: function() {
            // Update language field when adding new menu items
            $(document).on('menu-item-added', this.initLanguageField);
        },
        
        /**
         * Initialize language field
         *
         * @param {Event} e Event
         * @param {number} itemId Menu item ID
         */
        initLanguageField: function(e, itemId) {
            var $item = $('#menu-item-' + itemId);
            var $languageField = $item.find('.field-language');
            
            // If language field doesn't exist, add it
            if (!$languageField.length) {
                var $settingsFields = $item.find('.menu-item-settings .field-move').before(
                    '<p class="field-language description description-wide">' +
                    '<label for="edit-menu-item-language-' + itemId + '">' +
                    aqualuxeMultilingualAdmin.languageLabel + '<br />' +
                    '<select name="menu-item-language[' + itemId + ']" id="edit-menu-item-language-' + itemId + '">' +
                    '<option value="">' + aqualuxeMultilingualAdmin.allLanguages + '</option>' +
                    MenuItemLanguage.getLanguageOptions() +
                    '</select>' +
                    '</label>' +
                    '</p>'
                );
            }
        },
        
        /**
         * Get language options
         *
         * @return {string}
         */
        getLanguageOptions: function() {
            var options = '';
            
            if (aqualuxeMultilingualAdmin.languages) {
                for (var code in aqualuxeMultilingualAdmin.languages) {
                    options += '<option value="' + code + '">' + aqualuxeMultilingualAdmin.languages[code].name + '</option>';
                }
            }
            
            return options;
        }
    };
    
    /**
     * Document ready
     */
    $(document).ready(function() {
        LanguageTabs.init();
        LanguageSettings.init();
        MenuItemLanguage.init();
        
        // Restore active tab
        LanguageTabs.restoreActiveTab();
    });
    
})(jQuery);