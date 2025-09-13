/**
 * Product Gallery Enhancement
 * Handles product image galleries, zoom, lightbox, and carousel functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /**
   * Product Gallery Handler
   */
  class ProductGalleryHandler {
    constructor() {
      this.galleries = document.querySelectorAll('.product-gallery');
      this.init();
    }

    /**
     * Initialize gallery functionality
     */
    init() {
      this.galleries.forEach(gallery => {
        this.setupGallery(gallery);
      });

      this.setupZoomFunctionality();
      this.setupLightbox();
    }

    /**
     * Setup individual gallery
     * @param {HTMLElement} gallery
     */
    setupGallery(gallery) {
      const mainImage = gallery.querySelector('.main-product-image');
      const thumbnails = gallery.querySelectorAll('.product-thumbnail');

      if (!mainImage || !thumbnails.length) {
        return;
      }

      // Setup thumbnail clicks
      thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', e => {
          e.preventDefault();
          this.changeMainImage(mainImage, thumbnail, gallery);
          this.setActiveThumbnail(thumbnail, thumbnails);
        });

        // Setup keyboard navigation
        thumbnail.addEventListener('keydown', e => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            thumbnail.click();
          }
        });
      });

      // Setup carousel navigation if exists
      this.setupCarouselNavigation(gallery);
    }

    /**
     * Change main product image
     * @param {HTMLElement} mainImage
     * @param {HTMLElement} thumbnail
     * @param {HTMLElement} gallery
     */
    changeMainImage(mainImage, thumbnail, gallery) {
      const newSrc = thumbnail.dataset.fullImage || thumbnail.src;
      const newAlt = thumbnail.alt;

      // Add loading class
      gallery.classList.add('loading');

      // Preload new image
      const img = new Image();
      img.onload = () => {
        mainImage.src = newSrc;
        mainImage.alt = newAlt;
        gallery.classList.remove('loading');

        // Update zoom if enabled
        this.updateZoomImage(mainImage, newSrc);
      };

      img.onerror = () => {
        gallery.classList.remove('loading');
      };

      img.src = newSrc;
    }

    /**
     * Set active thumbnail
     * @param {HTMLElement} activeThumbnail
     * @param {NodeList} allThumbnails
     */
    setActiveThumbnail(activeThumbnail, allThumbnails) {
      allThumbnails.forEach(thumb => {
        thumb.classList.remove('active');
        thumb.setAttribute('aria-selected', 'false');
      });

      activeThumbnail.classList.add('active');
      activeThumbnail.setAttribute('aria-selected', 'true');
    }

    /**
     * Setup carousel navigation
     * @param {HTMLElement} gallery
     */
    setupCarouselNavigation(gallery) {
      const prevBtn = gallery.querySelector('.gallery-prev');
      const nextBtn = gallery.querySelector('.gallery-next');
      const thumbnails = gallery.querySelectorAll('.product-thumbnail');

      if (!prevBtn || !nextBtn || !thumbnails.length) {
        return;
      }

      let currentIndex = 0;

      prevBtn.addEventListener('click', () => {
        currentIndex =
          currentIndex > 0 ? currentIndex - 1 : thumbnails.length - 1;
        thumbnails[currentIndex].click();
      });

      nextBtn.addEventListener('click', () => {
        currentIndex =
          currentIndex < thumbnails.length - 1 ? currentIndex + 1 : 0;
        thumbnails[currentIndex].click();
      });

      // Keyboard navigation for carousel
      gallery.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft') {
          prevBtn.click();
        } else if (e.key === 'ArrowRight') {
          nextBtn.click();
        }
      });
    }

    /**
     * Setup zoom functionality
     */
    setupZoomFunctionality() {
      const zoomContainers = document.querySelectorAll('.zoom-container');

      zoomContainers.forEach(container => {
        const image = container.querySelector('img');
        if (!image) {
          return;
        }

        let isZoomed = false;

        // Mouse zoom
        container.addEventListener('mouseenter', () => {
          this.enableZoom(container, image);
          isZoomed = true;
        });

        container.addEventListener('mouseleave', () => {
          this.disableZoom(container);
          isZoomed = false;
        });

        container.addEventListener('mousemove', e => {
          if (isZoomed) {
            this.updateZoomPosition(e, container, image);
          }
        });

        // Touch zoom for mobile
        let touchStartDistance = 0;
        let initialScale = 1;

        container.addEventListener('touchstart', e => {
          if (e.touches.length === 2) {
            touchStartDistance = this.getTouchDistance(e.touches);
            initialScale = this.getCurrentScale(image);
          }
        });

        container.addEventListener('touchmove', e => {
          if (e.touches.length === 2) {
            e.preventDefault();
            const currentDistance = this.getTouchDistance(e.touches);
            const scale = (currentDistance / touchStartDistance) * initialScale;
            this.setImageScale(image, Math.min(Math.max(scale, 1), 3));
          }
        });
      });
    }

    /**
     * Enable zoom
     * @param {HTMLElement} container
     * @param {HTMLElement} image
     */
    enableZoom(container, image) {
      container.classList.add('zoomed');
      image.style.cursor = 'zoom-in';
    }

    /**
     * Disable zoom
     * @param {HTMLElement} container
     */
    disableZoom(container) {
      container.classList.remove('zoomed');
      const image = container.querySelector('img');
      if (image) {
        image.style.transform = '';
        image.style.cursor = '';
      }
    }

    /**
     * Update zoom position
     * @param {MouseEvent} e
     * @param {HTMLElement} container
     * @param {HTMLElement} image
     */
    updateZoomPosition(e, container, image) {
      const rect = container.getBoundingClientRect();
      const x = ((e.clientX - rect.left) / rect.width) * 100;
      const y = ((e.clientY - rect.top) / rect.height) * 100;

      image.style.transformOrigin = `${x}% ${y}%`;
      image.style.transform = 'scale(2)';
    }

    /**
     * Update zoom image source
     * @param {HTMLElement} image
     * @param {string} newSrc
     */
    updateZoomImage(image, newSrc) {
      if (image.dataset.zoomImage) {
        image.dataset.zoomImage = newSrc;
      }
    }

    /**
     * Setup lightbox functionality
     */
    setupLightbox() {
      const lightboxTriggers = document.querySelectorAll('.lightbox-trigger');

      lightboxTriggers.forEach(trigger => {
        trigger.addEventListener('click', e => {
          e.preventDefault();
          this.openLightbox(trigger);
        });
      });

      // Close lightbox on ESC key
      document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          this.closeLightbox();
        }
      });
    }

    /**
     * Open lightbox
     * @param {HTMLElement} trigger
     */
    openLightbox(trigger) {
      const imageSrc = trigger.dataset.lightboxImage || trigger.src;
      const imageAlt = trigger.alt || '';

      // Create lightbox if it doesn't exist
      let lightbox = document.querySelector('.product-lightbox');

      if (!lightbox) {
        lightbox = this.createLightbox();
      }

      const lightboxImage = lightbox.querySelector('.lightbox-image');
      lightboxImage.src = imageSrc;
      lightboxImage.alt = imageAlt;

      lightbox.style.display = 'flex';
      document.body.classList.add('lightbox-open');

      // Focus trap
      lightbox.focus();
    }

    /**
     * Close lightbox
     */
    closeLightbox() {
      const lightbox = document.querySelector('.product-lightbox');

      if (lightbox) {
        lightbox.style.display = 'none';
        document.body.classList.remove('lightbox-open');
      }
    }

    /**
     * Create lightbox element
     * @returns {HTMLElement}
     */
    createLightbox() {
      const lightbox = document.createElement('div');
      lightbox.className = 'product-lightbox';
      lightbox.tabIndex = -1;

      lightbox.innerHTML = `
        <div class="lightbox-overlay"></div>
        <div class="lightbox-content">
          <img class="lightbox-image" src="" alt="">
          <button class="lightbox-close" aria-label="Close lightbox">&times;</button>
        </div>
      `;

      document.body.appendChild(lightbox);

      // Setup close functionality
      const overlay = lightbox.querySelector('.lightbox-overlay');
      const closeBtn = lightbox.querySelector('.lightbox-close');

      overlay.addEventListener('click', () => this.closeLightbox());
      closeBtn.addEventListener('click', () => this.closeLightbox());

      return lightbox;
    }

    /**
     * Get distance between two touch points
     * @param {TouchList} touches
     * @returns {number}
     */
    getTouchDistance(touches) {
      const dx = touches[0].clientX - touches[1].clientX;
      const dy = touches[0].clientY - touches[1].clientY;
      return Math.sqrt(dx * dx + dy * dy);
    }

    /**
     * Get current scale of image
     * @param {HTMLElement} image
     * @returns {number}
     */
    getCurrentScale(image) {
      const transform = window.getComputedStyle(image).transform;
      if (transform === 'none') {
        return 1;
      }

      const matrix = transform.match(/matrix\((.+)\)/);
      if (matrix) {
        const values = matrix[1].split(', ');
        return parseFloat(values[0]);
      }

      return 1;
    }

    /**
     * Set image scale
     * @param {HTMLElement} image
     * @param {number} scale
     */
    setImageScale(image, scale) {
      image.style.transform = `scale(${scale})`;
    }
  }

  // Initialize product gallery handler
  new ProductGalleryHandler();
});
