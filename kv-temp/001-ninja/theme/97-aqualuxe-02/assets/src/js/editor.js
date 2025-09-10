/**
 * AquaLuxe Theme - Block Editor JavaScript
 *
 * Enhances the WordPress block editor (Gutenberg) with
 * theme-specific functionality, custom blocks, and
 * improved editing experience for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @subpackage Assets/JavaScript
 * @since 2.0.0
 */

import '../scss/editor.scss';

/**
 * AquaLuxe Block Editor Module
 *
 * Provides block editor enhancements and custom functionality
 * for improved content creation experience.
 */
class AquaLuxeEditor {
  constructor() {
    this.init();
  }

  /**
   * Initialize block editor functionality
   */
  init() {
    this.setupEditorStyles();
    this.registerBlockVariations();
    this.addCustomFormats();
    this.enhanceMediaLibrary();
    this.setupEditorEnhancements();

    console.log('AquaLuxe Editor: Initialized successfully');
  }

  /**
   * Setup editor styles and theme integration
   */
  setupEditorStyles() {
    if (typeof wp === 'undefined' || !wp.domReady) return;

    wp.domReady(() => {
      // Add theme classes to editor
      const editorCanvas = document.querySelector('.editor-styles-wrapper');
      if (editorCanvas) {
        editorCanvas.classList.add('aqualuxe-editor', 'theme-aqualuxe');
      }

      // Apply theme color palette
      this.applyColorPalette();

      // Apply theme typography
      this.applyTypography();
    });
  }

  /**
   * Register custom block variations
   */
  registerBlockVariations() {
    if (typeof wp === 'undefined' || !wp.blocks) return;

    const { registerBlockVariation } = wp.blocks;

    // Hero Section Block Variation
    registerBlockVariation('core/group', {
      name: 'aqualuxe-hero',
      title: 'AquaLuxe Hero Section',
      description: 'A hero section optimized for aquatic businesses',
      category: 'aqualuxe',
      icon: 'format-image',
      attributes: {
        className: 'aqualuxe-hero-section',
        style: {
          spacing: {
            padding: {
              top: '4rem',
              bottom: '4rem',
            },
          },
        },
      },
      innerBlocks: [
        [
          'core/heading',
          {
            level: 1,
            placeholder: 'Your Aquatic Journey Begins Here...',
            className: 'hero-title',
          },
        ],
        [
          'core/paragraph',
          {
            placeholder: 'Discover premium aquatic solutions for your home or business.',
            className: 'hero-description',
          },
        ],
        [
          'core/buttons',
          {
            className: 'hero-actions',
          },
        ],
      ],
      scope: ['inserter'],
    });

    // Product Showcase Block Variation
    registerBlockVariation('core/columns', {
      name: 'aqualuxe-product-showcase',
      title: 'Product Showcase',
      description: 'Showcase your aquatic products beautifully',
      category: 'aqualuxe',
      icon: 'products',
      attributes: {
        className: 'aqualuxe-product-showcase',
      },
      innerBlocks: [
        [
          'core/column',
          {},
          [
            [
              'core/image',
              {
                className: 'product-image',
              },
            ],
            [
              'core/heading',
              {
                level: 3,
                placeholder: 'Product Name',
              },
            ],
            [
              'core/paragraph',
              {
                placeholder: 'Product description...',
              },
            ],
          ],
        ],
        [
          'core/column',
          {},
          [
            [
              'core/image',
              {
                className: 'product-image',
              },
            ],
            [
              'core/heading',
              {
                level: 3,
                placeholder: 'Product Name',
              },
            ],
            [
              'core/paragraph',
              {
                placeholder: 'Product description...',
              },
            ],
          ],
        ],
        [
          'core/column',
          {},
          [
            [
              'core/image',
              {
                className: 'product-image',
              },
            ],
            [
              'core/heading',
              {
                level: 3,
                placeholder: 'Product Name',
              },
            ],
            [
              'core/paragraph',
              {
                placeholder: 'Product description...',
              },
            ],
          ],
        ],
      ],
      scope: ['inserter'],
    });

    // Service Card Block Variation
    registerBlockVariation('core/group', {
      name: 'aqualuxe-service-card',
      title: 'Service Card',
      description: 'Highlight your aquatic services',
      category: 'aqualuxe',
      icon: 'admin-tools',
      attributes: {
        className: 'aqualuxe-service-card',
        style: {
          border: {
            width: '1px',
            style: 'solid',
            color: '#e2e8f0',
          },
          spacing: {
            padding: '2rem',
          },
        },
      },
      innerBlocks: [
        [
          'core/image',
          {
            className: 'service-icon',
            width: 64,
            height: 64,
          },
        ],
        [
          'core/heading',
          {
            level: 3,
            placeholder: 'Service Title',
            className: 'service-title',
          },
        ],
        [
          'core/paragraph',
          {
            placeholder: 'Describe your service here...',
            className: 'service-description',
          },
        ],
        [
          'core/button',
          {
            text: 'Learn More',
            className: 'service-button',
          },
        ],
      ],
      scope: ['inserter'],
    });
  }

  /**
   * Add custom text formats
   */
  addCustomFormats() {
    if (typeof wp === 'undefined' || !wp.richText) return;

    const { registerFormatType } = wp.richText;
    const { RichTextToolbarButton } = wp.blockEditor;

    // Highlight format
    registerFormatType('aqualuxe/highlight', {
      title: 'Highlight',
      tagName: 'span',
      className: 'aqualuxe-highlight',
      edit: ({ isActive, onChange, value }) => {
        return wp.element.createElement(RichTextToolbarButton, {
          icon: 'marker',
          title: 'Highlight',
          onClick: () => {
            onChange(
              wp.richText.toggleFormat(value, {
                type: 'aqualuxe/highlight',
              })
            );
          },
          isActive: isActive,
        });
      },
    });

    // Aqua accent format
    registerFormatType('aqualuxe/aqua-accent', {
      title: 'Aqua Accent',
      tagName: 'span',
      className: 'aqualuxe-aqua-accent',
      edit: ({ isActive, onChange, value }) => {
        return wp.element.createElement(RichTextToolbarButton, {
          icon: 'admin-appearance',
          title: 'Aqua Accent',
          onClick: () => {
            onChange(
              wp.richText.toggleFormat(value, {
                type: 'aqualuxe/aqua-accent',
              })
            );
          },
          isActive: isActive,
        });
      },
    });
  }

  /**
   * Enhance media library
   */
  enhanceMediaLibrary() {
    if (typeof wp === 'undefined' || !wp.media) return;

    // Add custom media library tabs
    wp.media.controller.Library = wp.media.controller.Library.extend({
      defaults: Object.assign({}, wp.media.controller.Library.prototype.defaults, {
        filterable: 'uploaded',
        displaySettings: true,
        priority: 80,
        syncSelection: false,
      }),
    });

    // Add aquatic image filter
    wp.media.view.AttachmentFilters.Uploaded = wp.media.view.AttachmentFilters.Uploaded.extend({
      createFilters: function () {
        const filters = {};

        filters.all = {
          text: wp.media.view.l10n.allMediaItems,
          props: {
            uploadedTo: null,
            orderby: 'date',
            order: 'DESC',
          },
          priority: 10,
        };

        filters.aquatic = {
          text: 'Aquatic Images',
          props: {
            tag: 'aquatic,fish,aquarium,marine,water',
            orderby: 'date',
            order: 'DESC',
          },
          priority: 20,
        };

        filters.products = {
          text: 'Product Images',
          props: {
            tag: 'product,equipment,supplies',
            orderby: 'date',
            order: 'DESC',
          },
          priority: 30,
        };

        return filters;
      },
    });
  }

  /**
   * Setup additional editor enhancements
   */
  setupEditorEnhancements() {
    this.addBlockPatterns();
    this.enhanceImageBlocks();
    this.setupEditorShortcuts();
    this.addCustomSidebar();
  }

  /**
   * Apply theme color palette
   */
  applyColorPalette() {
    const colors = [
      { name: 'Primary Aqua', slug: 'primary-aqua', color: '#0ea5e9' },
      { name: 'Deep Ocean', slug: 'deep-ocean', color: '#0c4a6e' },
      { name: 'Coral Accent', slug: 'coral-accent', color: '#f97316' },
      { name: 'Pearl White', slug: 'pearl-white', color: '#f8fafc' },
      { name: 'Seaweed Green', slug: 'seaweed-green', color: '#059669' },
      { name: 'Midnight Blue', slug: 'midnight-blue', color: '#1e293b' },
    ];

    // Apply colors to editor
    const editorCanvas = document.querySelector('.editor-styles-wrapper');
    if (editorCanvas) {
      colors.forEach(color => {
        editorCanvas.style.setProperty(`--wp--preset--color--${color.slug}`, color.color);
      });
    }
  }

  /**
   * Apply theme typography
   */
  applyTypography() {
    const fontSizes = [
      { name: 'Small', slug: 'small', size: '14px' },
      { name: 'Normal', slug: 'normal', size: '16px' },
      { name: 'Medium', slug: 'medium', size: '20px' },
      { name: 'Large', slug: 'large', size: '24px' },
      { name: 'Extra Large', slug: 'extra-large', size: '32px' },
      { name: 'Huge', slug: 'huge', size: '48px' },
    ];

    // Apply font sizes to editor
    const editorCanvas = document.querySelector('.editor-styles-wrapper');
    if (editorCanvas) {
      fontSizes.forEach(size => {
        editorCanvas.style.setProperty(`--wp--preset--font-size--${size.slug}`, size.size);
      });
    }
  }

  /**
   * Add block patterns
   */
  addBlockPatterns() {
    if (typeof wp === 'undefined' || !wp.blocks) return;

    const { registerBlockPattern } = wp.blocks;

    // Hero with CTA pattern
    registerBlockPattern('aqualuxe/hero-cta', {
      title: 'Hero with Call to Action',
      description: 'Large hero section with heading, description, and call-to-action buttons',
      categories: ['aqualuxe-patterns'],
      content: `
        <!-- wp:group {"className":"aqualuxe-hero bg-gradient-to-r from-blue-600 to-cyan-500 text-white","style":{"spacing":{"padding":{"top":"4rem","bottom":"4rem"}}}} -->
        <div class="wp-block-group aqualuxe-hero bg-gradient-to-r from-blue-600 to-cyan-500 text-white" style="padding-top:4rem;padding-bottom:4rem">
          <!-- wp:heading {"textAlign":"center","level":1,"className":"hero-title"} -->
          <h1 class="wp-block-heading has-text-align-center hero-title">Premium Aquatic Solutions</h1>
          <!-- /wp:heading -->
          
          <!-- wp:paragraph {"align":"center","className":"hero-description text-xl"} -->
          <p class="has-text-align-center hero-description text-xl">Transform your space with our expertly curated collection of marine life, premium equipment, and professional services.</p>
          <!-- /wp:paragraph -->
          
          <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"className":"hero-actions mt-8"} -->
          <div class="wp-block-buttons hero-actions mt-8">
            <!-- wp:button {"backgroundColor":"coral-accent","className":"is-style-fill"} -->
            <div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-coral-accent-background-color has-background wp-element-button">Shop Now</a></div>
            <!-- /wp:button -->
            
            <!-- wp:button {"className":"is-style-outline"} -->
            <div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">Our Services</a></div>
            <!-- /wp:button -->
          </div>
          <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->`,
    });

    // Services grid pattern
    registerBlockPattern('aqualuxe/services-grid', {
      title: 'Services Grid',
      description: 'Three-column grid showcasing services with icons and descriptions',
      categories: ['aqualuxe-patterns'],
      content: `
        <!-- wp:group {"className":"aqualuxe-services-grid","style":{"spacing":{"padding":{"top":"3rem","bottom":"3rem"}}}} -->
        <div class="wp-block-group aqualuxe-services-grid" style="padding-top:3rem;padding-bottom:3rem">
          <!-- wp:heading {"textAlign":"center","level":2} -->
          <h2 class="wp-block-heading has-text-align-center">Our Services</h2>
          <!-- /wp:heading -->
          
          <!-- wp:columns {"className":"services-grid"} -->
          <div class="wp-block-columns services-grid">
            <!-- wp:column {"className":"service-card"} -->
            <div class="wp-block-column service-card">
              <!-- wp:image {"width":64,"height":64,"className":"service-icon mx-auto"} -->
              <figure class="wp-block-image is-resized service-icon mx-auto"><img alt="" width="64" height="64"/></figure>
              <!-- /wp:image -->
              
              <!-- wp:heading {"textAlign":"center","level":3} -->
              <h3 class="wp-block-heading has-text-align-center">Aquarium Design</h3>
              <!-- /wp:heading -->
              
              <!-- wp:paragraph {"align":"center"} -->
              <p class="has-text-align-center">Custom aquarium design and installation for homes, offices, and commercial spaces.</p>
              <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"service-card"} -->
            <div class="wp-block-column service-card">
              <!-- wp:image {"width":64,"height":64,"className":"service-icon mx-auto"} -->
              <figure class="wp-block-image is-resized service-icon mx-auto"><img alt="" width="64" height="64"/></figure>
              <!-- /wp:image -->
              
              <!-- wp:heading {"textAlign":"center","level":3} -->
              <h3 class="wp-block-heading has-text-align-center">Maintenance</h3>
              <!-- /wp:heading -->
              
              <!-- wp:paragraph {"align":"center"} -->
              <p class="has-text-align-center">Professional maintenance services to keep your aquatic environment healthy and beautiful.</p>
              <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
            
            <!-- wp:column {"className":"service-card"} -->
            <div class="wp-block-column service-card">
              <!-- wp:image {"width":64,"height":64,"className":"service-icon mx-auto"} -->
              <figure class="wp-block-image is-resized service-icon mx-auto"><img alt="" width="64" height="64"/></figure>
              <!-- /wp:image -->
              
              <!-- wp:heading {"textAlign":"center","level":3} -->
              <h3 class="wp-block-heading has-text-align-center">Consultation</h3>
              <!-- /wp:heading -->
              
              <!-- wp:paragraph {"align":"center"} -->
              <p class="has-text-align-center">Expert consultation for aquaculture projects, breeding programs, and system optimization.</p>
              <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->
          </div>
          <!-- /wp:columns -->
        </div>
        <!-- /wp:group -->`,
    });
  }

  /**
   * Enhance image blocks
   */
  enhanceImageBlocks() {
    if (typeof wp === 'undefined' || !wp.hooks) return;

    // Add lazy loading to images
    wp.hooks.addFilter(
      'blocks.getSaveElement',
      'aqualuxe/add-lazy-loading',
      (element, blockType) => {
        if (blockType.name === 'core/image') {
          const img = element.props.children.find(child => child && child.type === 'img');

          if (img && !img.props.loading) {
            img.props.loading = 'lazy';
          }
        }

        return element;
      }
    );

    // Add aquatic-specific alt text suggestions
    wp.hooks.addFilter('editor.MediaUpload', 'aqualuxe/alt-text-suggestions', MediaUpload => {
      return props => {
        const originalOnSelect = props.onSelect;

        props.onSelect = media => {
          // Suggest alt text for aquatic images
          if (!media.alt && media.filename) {
            const aquaticKeywords = ['fish', 'aquarium', 'tank', 'coral', 'marine', 'water'];
            const filename = media.filename.toLowerCase();

            const matchedKeyword = aquaticKeywords.find(keyword => filename.includes(keyword));

            if (matchedKeyword) {
              media.alt = `Beautiful ${matchedKeyword} in aquatic environment`;
            }
          }

          originalOnSelect(media);
        };

        return wp.element.createElement(MediaUpload, props);
      };
    });
  }

  /**
   * Setup editor keyboard shortcuts
   */
  setupEditorShortcuts() {
    if (typeof wp === 'undefined' || !wp.data) return;

    wp.data.subscribe(() => {
      const isEditorReady = wp.data.select('core/editor');
      if (!isEditorReady) return;

      document.addEventListener('keydown', e => {
        // Quick save (Ctrl+S)
        if (e.ctrlKey && e.key === 's') {
          e.preventDefault();
          wp.data.dispatch('core/editor').savePost();
        }

        // Preview (Ctrl+Shift+P)
        if (e.ctrlKey && e.shiftKey && e.key === 'P') {
          e.preventDefault();
          const previewLink = document.querySelector('.editor-post-preview');
          if (previewLink) {
            previewLink.click();
          }
        }

        // Insert AquaLuxe block (Ctrl+Shift+A)
        if (e.ctrlKey && e.shiftKey && e.key === 'A') {
          e.preventDefault();
          // Open block inserter with AquaLuxe blocks
          const inserterToggle = document.querySelector(
            '.edit-post-header-toolbar__inserter-toggle'
          );
          if (inserterToggle) {
            inserterToggle.click();
            setTimeout(() => {
              const searchInput = document.querySelector('.block-editor-inserter__search-input');
              if (searchInput) {
                searchInput.value = 'aqualuxe';
                searchInput.dispatchEvent(new Event('input', { bubbles: true }));
              }
            }, 100);
          }
        }
      });
    });
  }

  /**
   * Add custom editor sidebar
   */
  addCustomSidebar() {
    if (typeof wp === 'undefined' || !wp.plugins) return;

    const { registerPlugin } = wp.plugins;
    const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
    const { PanelBody, TextControl, ToggleControl } = wp.components;

    const AquaLuxeSidebar = () => {
      const postMeta = wp.data.useSelect(select =>
        select('core/editor').getEditedPostAttribute('meta')
      );

      const { editPost } = wp.data.useDispatch('core/editor');

      return wp.element.createElement(
        PluginSidebar,
        {
          name: 'aqualuxe-sidebar',
          icon: 'admin-appearance',
          title: 'AquaLuxe Settings',
        },
        wp.element.createElement(
          PanelBody,
          { title: 'Aquatic Settings', initialOpen: true },
          wp.element.createElement(ToggleControl, {
            label: 'Featured Aquatic Content',
            checked: postMeta._aqualuxe_featured || false,
            onChange: value =>
              editPost({
                meta: { ...postMeta, _aqualuxe_featured: value },
              }),
          }),
          wp.element.createElement(TextControl, {
            label: 'Species Information',
            value: postMeta._aqualuxe_species || '',
            onChange: value =>
              editPost({
                meta: { ...postMeta, _aqualuxe_species: value },
              }),
            help: 'Add species information for aquatic content',
          }),
          wp.element.createElement(TextControl, {
            label: 'Care Level',
            value: postMeta._aqualuxe_care_level || '',
            onChange: value =>
              editPost({
                meta: { ...postMeta, _aqualuxe_care_level: value },
              }),
            help: 'Beginner, Intermediate, or Advanced',
          })
        )
      );
    };

    registerPlugin('aqualuxe-sidebar', {
      render: () => [
        wp.element.createElement(
          PluginSidebarMoreMenuItem,
          {
            target: 'aqualuxe-sidebar',
            icon: 'admin-appearance',
          },
          'AquaLuxe Settings'
        ),
        wp.element.createElement(AquaLuxeSidebar),
      ],
    });
  }
}

// Initialize when editor is ready
if (typeof wp !== 'undefined' && wp.domReady) {
  wp.domReady(() => {
    new AquaLuxeEditor();
  });
} else {
  console.warn('AquaLuxe Editor: WordPress Block Editor not available');
}

// Export for use in other modules
window.AquaLuxeEditor = AquaLuxeEditor;
