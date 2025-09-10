/**
 * AquaLuxe Theme - Customizer JavaScript
 *
 * Handles WordPress Customizer functionality including
 * live preview updates, control interactions, and
 * selective refresh partials for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @subpackage Assets/JavaScript
 * @since 2.0.0
 */

import '../scss/customizer.scss';

/**
 * AquaLuxe Customizer Module
 *
 * Provides enhanced WordPress Customizer functionality
 * with live preview updates and interactive controls.
 */
class AquaLuxeCustomizer {
  constructor() {
    this.api = wp.customize;
    this.init();
  }

  /**
   * Initialize customizer functionality
   */
  init() {
    this.bindLivePreview();
    this.setupControls();
    this.initializeEnhancements();

    console.log('AquaLuxe Customizer: Initialized successfully');
  }

  /**
   * Bind live preview updates
   */
  bindLivePreview() {
    // Site identity updates
    this.api('blogname', value => {
      value.bind(to => {
        document.querySelector('.site-title').textContent = to;
      });
    });

    this.api('blogdescription', value => {
      value.bind(to => {
        document.querySelector('.site-description').textContent = to;
      });
    });

    // Theme colors
    this.api('aqualuxe_primary_color', value => {
      value.bind(to => {
        this.updateCSSProperty('--color-primary', to);
      });
    });

    this.api('aqualuxe_secondary_color', value => {
      value.bind(to => {
        this.updateCSSProperty('--color-secondary', to);
      });
    });

    this.api('aqualuxe_accent_color', value => {
      value.bind(to => {
        this.updateCSSProperty('--color-accent', to);
      });
    });

    // Typography updates
    this.api('aqualuxe_heading_font', value => {
      value.bind(to => {
        this.updateFontFamily('headings', to);
      });
    });

    this.api('aqualuxe_body_font', value => {
      value.bind(to => {
        this.updateFontFamily('body', to);
      });
    });

    // Layout updates
    this.api('aqualuxe_container_width', value => {
      value.bind(to => {
        this.updateCSSProperty('--container-max-width', to + 'px');
      });
    });

    this.api('aqualuxe_sidebar_width', value => {
      value.bind(to => {
        this.updateCSSProperty('--sidebar-width', to + '%');
      });
    });

    // Header updates
    this.api('aqualuxe_header_layout', value => {
      value.bind(to => {
        this.updateHeaderLayout(to);
      });
    });

    this.api('aqualuxe_header_sticky', value => {
      value.bind(to => {
        this.toggleStickyHeader(to);
      });
    });

    // Footer updates
    this.api('aqualuxe_footer_widgets', value => {
      value.bind(to => {
        this.updateFooterColumns(to);
      });
    });

    // Performance options
    this.api('aqualuxe_lazy_loading', value => {
      value.bind(to => {
        this.toggleLazyLoading(to);
      });
    });
  }

  /**
   * Setup customizer controls
   */
  setupControls() {
    // Enhanced color picker
    this.setupColorPickers();

    // Font preview
    this.setupFontPreviews();

    // Layout previews
    this.setupLayoutPreviews();

    // Import/Export functionality
    this.setupImportExport();
  }

  /**
   * Initialize customizer enhancements
   */
  initializeEnhancements() {
    this.addControlTooltips();
    this.improveNavigation();
    this.addPreviewModes();
    this.setupKeyboardShortcuts();
  }

  /**
   * Update CSS custom property
   */
  updateCSSProperty(property, value) {
    document.documentElement.style.setProperty(property, value);
  }

  /**
   * Update font family
   */
  updateFontFamily(target, fontFamily) {
    const elements = {
      headings: 'h1, h2, h3, h4, h5, h6, .heading',
      body: 'body, .body-text',
    };

    if (elements[target]) {
      const targetElements = document.querySelectorAll(elements[target]);
      targetElements.forEach(element => {
        element.style.fontFamily = fontFamily;
      });
    }
  }

  /**
   * Update header layout
   */
  updateHeaderLayout(layout) {
    const header = document.querySelector('.site-header');
    if (!header) return;

    // Remove existing layout classes
    header.classList.remove('layout-default', 'layout-centered', 'layout-minimal');

    // Add new layout class
    header.classList.add(`layout-${layout}`);
  }

  /**
   * Toggle sticky header
   */
  toggleStickyHeader(enabled) {
    const header = document.querySelector('.site-header');
    if (!header) return;

    if (enabled) {
      header.classList.add('sticky-header');
    } else {
      header.classList.remove('sticky-header');
    }
  }

  /**
   * Update footer columns
   */
  updateFooterColumns(columns) {
    const footer = document.querySelector('.site-footer .widget-area');
    if (!footer) return;

    footer.className = footer.className.replace(/columns-\d+/, `columns-${columns}`);
  }

  /**
   * Toggle lazy loading
   */
  toggleLazyLoading(enabled) {
    const images = document.querySelectorAll('img');

    images.forEach(img => {
      if (enabled) {
        img.setAttribute('loading', 'lazy');
      } else {
        img.removeAttribute('loading');
      }
    });
  }

  /**
   * Setup enhanced color pickers
   */
  setupColorPickers() {
    const colorControls = document.querySelectorAll('.customize-control-color');

    colorControls.forEach(control => {
      const input = control.querySelector('input[type="text"]');
      if (!input) return;

      // Add color preview
      const preview = document.createElement('div');
      preview.className = 'color-preview';
      preview.style.backgroundColor = input.value;

      input.parentNode.insertBefore(preview, input);

      input.addEventListener('input', () => {
        preview.style.backgroundColor = input.value;
      });

      // Add palette shortcuts
      const palette = this.createColorPalette();
      control.appendChild(palette);
    });
  }

  /**
   * Create color palette
   */
  createColorPalette() {
    const palette = document.createElement('div');
    palette.className = 'color-palette';

    const colors = [
      '#0073aa',
      '#005a87',
      '#1e73be',
      '#2ea2cc',
      '#82b440',
      '#46b450',
      '#00a86b',
      '#006799',
      '#f56e28',
      '#ff6900',
      '#ffb900',
      '#ff2d55',
    ];

    colors.forEach(color => {
      const swatch = document.createElement('button');
      swatch.className = 'color-swatch';
      swatch.style.backgroundColor = color;
      swatch.setAttribute('data-color', color);
      swatch.addEventListener('click', e => {
        e.preventDefault();
        const control = e.target.closest('.customize-control-color');
        const input = control.querySelector('input[type="text"]');
        if (input) {
          input.value = color;
          input.dispatchEvent(new Event('input'));
        }
      });
      palette.appendChild(swatch);
    });

    return palette;
  }

  /**
   * Setup font previews
   */
  setupFontPreviews() {
    const fontControls = document.querySelectorAll(
      '.customize-control select[data-customize-setting-link*="font"]'
    );

    fontControls.forEach(select => {
      select.addEventListener('change', () => {
        this.updateFontPreview(select);
      });

      // Initial preview
      this.updateFontPreview(select);
    });
  }

  /**
   * Update font preview
   */
  updateFontPreview(select) {
    const preview = select.parentNode.querySelector('.font-preview');
    const previewText = preview || this.createFontPreview(select);

    previewText.style.fontFamily = select.value;
  }

  /**
   * Create font preview
   */
  createFontPreview(select) {
    const preview = document.createElement('div');
    preview.className = 'font-preview';
    preview.textContent = 'The quick brown fox jumps over the lazy dog';
    preview.style.fontSize = '16px';
    preview.style.lineHeight = '1.5';
    preview.style.padding = '10px';
    preview.style.border = '1px solid #ddd';
    preview.style.borderRadius = '4px';
    preview.style.marginTop = '10px';

    select.parentNode.appendChild(preview);
    return preview;
  }

  /**
   * Setup layout previews
   */
  setupLayoutPreviews() {
    const layoutControls = document.querySelectorAll('.customize-control input[name*="layout"]');

    layoutControls.forEach(control => {
      control.addEventListener('change', () => {
        this.highlightLayoutElements(control.value);
      });
    });
  }

  /**
   * Highlight layout elements
   */
  highlightLayoutElements(layout) {
    // Remove existing highlights
    document.querySelectorAll('.layout-highlight').forEach(el => {
      el.classList.remove('layout-highlight');
    });

    // Add highlights based on layout
    const elementMap = {
      sidebar: ['.sidebar', '.widget-area'],
      header: ['.site-header'],
      footer: ['.site-footer'],
      content: ['.content-area', 'main'],
    };

    if (elementMap[layout]) {
      elementMap[layout].forEach(selector => {
        document.querySelectorAll(selector).forEach(el => {
          el.classList.add('layout-highlight');
        });
      });
    }
  }

  /**
   * Setup import/export functionality
   */
  setupImportExport() {
    // Add import/export section if not present
    this.api.section.add(
      new this.api.Section('aqualuxe_import_export', {
        title: 'Import/Export Settings',
        priority: 1000,
        panel: 'aqualuxe_panel',
      })
    );

    // Add export button
    const exportControl = new this.api.Control('aqualuxe_export', {
      section: 'aqualuxe_import_export',
      type: 'button',
      settings: {},
      label: 'Export Settings',
    });

    exportControl.renderContent = function () {
      const button = document.createElement('button');
      button.className = 'button button-secondary';
      button.textContent = 'Export Settings';
      button.addEventListener('click', () => {
        this.exportSettings();
      });
      this.container[0].appendChild(button);
    }.bind(this);

    this.api.control.add(exportControl);
  }

  /**
   * Export settings
   */
  exportSettings() {
    const settings = {};

    this.api.each((setting, id) => {
      if (id.startsWith('aqualuxe_')) {
        settings[id] = setting.get();
      }
    });

    const dataStr = JSON.stringify(settings, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });

    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = 'aqualuxe-settings.json';
    link.click();

    URL.revokeObjectURL(link.href);
  }

  /**
   * Add control tooltips
   */
  addControlTooltips() {
    const controls = document.querySelectorAll('.customize-control');

    controls.forEach(control => {
      const label = control.querySelector('.customize-control-title');
      const description = control.querySelector('.description');

      if (label && description) {
        label.setAttribute('data-tooltip', description.textContent);
        label.addEventListener('mouseenter', this.showTooltip);
        label.addEventListener('mouseleave', this.hideTooltip);
      }
    });
  }

  /**
   * Improve navigation
   */
  improveNavigation() {
    // Add search functionality
    this.addCustomizerSearch();

    // Add breadcrumbs
    this.addBreadcrumbs();

    // Enhance panel navigation
    this.enhancePanelNavigation();
  }

  /**
   * Add customizer search
   */
  addCustomizerSearch() {
    const searchContainer = document.createElement('div');
    searchContainer.className = 'customizer-search';
    searchContainer.innerHTML = `
      <input type="text" placeholder="Search settings..." />
      <button type="button" class="search-clear">&times;</button>
    `;

    const accordion = document.querySelector('#customize-theme-controls');
    if (accordion) {
      accordion.insertBefore(searchContainer, accordion.firstChild);

      const searchInput = searchContainer.querySelector('input');
      const clearButton = searchContainer.querySelector('.search-clear');

      searchInput.addEventListener('input', e => {
        this.filterControls(e.target.value);
      });

      clearButton.addEventListener('click', () => {
        searchInput.value = '';
        this.filterControls('');
      });
    }
  }

  /**
   * Filter controls based on search
   */
  filterControls(query) {
    const controls = document.querySelectorAll('.customize-control');

    controls.forEach(control => {
      const title = control.querySelector('.customize-control-title');
      const description = control.querySelector('.description');

      let text = '';
      if (title) text += title.textContent + ' ';
      if (description) text += description.textContent + ' ';

      const matches = text.toLowerCase().includes(query.toLowerCase());
      control.style.display = matches || !query ? 'block' : 'none';
    });
  }

  /**
   * Add breadcrumbs
   */
  addBreadcrumbs() {
    const breadcrumbs = document.createElement('div');
    breadcrumbs.className = 'customizer-breadcrumbs';

    const header = document.querySelector('.wp-full-overlay-header');
    if (header) {
      header.appendChild(breadcrumbs);

      this.api.state('expandedPanel').bind(panelId => {
        this.updateBreadcrumbs(panelId);
      });

      this.api.state('expandedSection').bind(sectionId => {
        this.updateBreadcrumbs(null, sectionId);
      });
    }
  }

  /**
   * Update breadcrumbs
   */
  updateBreadcrumbs(panelId, sectionId) {
    const breadcrumbs = document.querySelector('.customizer-breadcrumbs');
    if (!breadcrumbs) return;

    let html = '<a href="#" data-target="root">Customize</a>';

    if (panelId) {
      const panel = this.api.panel(panelId);
      if (panel) {
        html += ` > <a href="#" data-target="panel:${panelId}">${panel.params.title}</a>`;
      }
    }

    if (sectionId) {
      const section = this.api.section(sectionId);
      if (section) {
        html += ` > <span>${section.params.title}</span>`;
      }
    }

    breadcrumbs.innerHTML = html;

    // Bind navigation
    breadcrumbs.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        const target = e.target.dataset.target;

        if (target === 'root') {
          this.api.panel.each(panel => panel.collapse());
          this.api.section.each(section => section.collapse());
        } else if (target.startsWith('panel:')) {
          const panelId = target.replace('panel:', '');
          this.api.panel(panelId).focus();
        }
      });
    });
  }

  /**
   * Enhance panel navigation
   */
  enhancePanelNavigation() {
    // Add keyboard shortcuts info
    const shortcutsInfo = document.createElement('div');
    shortcutsInfo.className = 'keyboard-shortcuts-info';
    shortcutsInfo.innerHTML = `
      <strong>Keyboard Shortcuts:</strong><br>
      <kbd>Esc</kbd> - Go back<br>
      <kbd>Ctrl</kbd> + <kbd>S</kbd> - Save & Publish<br>
      <kbd>/</kbd> - Search settings
    `;

    const sidebar = document.querySelector('.wp-full-overlay-sidebar-content');
    if (sidebar) {
      sidebar.appendChild(shortcutsInfo);
    }
  }

  /**
   * Add preview modes
   */
  addPreviewModes() {
    const deviceButtons = document.querySelectorAll('.devices button');

    deviceButtons.forEach(button => {
      button.addEventListener('click', () => {
        const device = button.classList.contains('preview-desktop')
          ? 'desktop'
          : button.classList.contains('preview-tablet')
            ? 'tablet'
            : 'mobile';

        this.updatePreviewMode(device);
      });
    });
  }

  /**
   * Update preview mode
   */
  updatePreviewMode(device) {
    const preview = document.querySelector('#customize-preview');
    if (!preview) return;

    // Remove existing device classes
    preview.classList.remove('preview-desktop', 'preview-tablet', 'preview-mobile');

    // Add new device class
    preview.classList.add(`preview-${device}`);

    // Trigger resize event for responsive elements
    window.dispatchEvent(new Event('resize'));
  }

  /**
   * Setup keyboard shortcuts
   */
  setupKeyboardShortcuts() {
    document.addEventListener('keydown', e => {
      // Save shortcut (Ctrl+S)
      if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        this.api.previewer.save();
      }

      // Search shortcut (/)
      if (e.key === '/' && !e.target.matches('input, textarea')) {
        e.preventDefault();
        const searchInput = document.querySelector('.customizer-search input');
        if (searchInput) {
          searchInput.focus();
        }
      }

      // Escape to go back
      if (e.key === 'Escape') {
        const expandedSection = this.api.state('expandedSection').get();
        const expandedPanel = this.api.state('expandedPanel').get();

        if (expandedSection) {
          this.api.section(expandedSection).collapse();
        } else if (expandedPanel) {
          this.api.panel(expandedPanel).collapse();
        }
      }
    });
  }

  /**
   * Show tooltip
   */
  showTooltip(e) {
    const element = e.target;
    const tooltipText = element.dataset.tooltip;

    if (!tooltipText) return;

    const tooltip = document.createElement('div');
    tooltip.className = 'aqualuxe-tooltip';
    tooltip.textContent = tooltipText;

    document.body.appendChild(tooltip);

    const rect = element.getBoundingClientRect();
    tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
    tooltip.style.top = `${rect.top - tooltip.offsetHeight - 8}px`;

    element.tooltip = tooltip;
  }

  /**
   * Hide tooltip
   */
  hideTooltip(e) {
    const element = e.target;
    if (element.tooltip) {
      element.tooltip.remove();
      delete element.tooltip;
    }
  }
}

// Initialize when customizer API is ready
if (typeof wp !== 'undefined' && wp.customize) {
  wp.customize.bind('ready', () => {
    new AquaLuxeCustomizer();
  });
} else {
  console.warn('AquaLuxe Customizer: WordPress Customize API not available');
}

// Export for use in other modules
window.AquaLuxeCustomizer = AquaLuxeCustomizer;
