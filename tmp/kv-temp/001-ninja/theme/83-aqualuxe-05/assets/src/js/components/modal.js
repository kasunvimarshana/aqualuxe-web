/**
 * Modal Component
 * 
 * Flexible modal system with accessibility, animations,
 * and multiple content types support.
 */

class Modal {
    constructor() {
        this.modals = new Map();
        this.activeModal = null;
        this.scrollPosition = 0;
        
        this.init();
    }

    init() {
        this.bindGlobalEvents();
        this.initExistingModals();
        console.log('Modal system initialized');
    }

    bindGlobalEvents() {
        // Global escape key handler
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModal) {
                this.close(this.activeModal);
            }
        });
        
        // Handle modal triggers
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-trigger]');
            if (trigger) {
                e.preventDefault();
                const modalId = trigger.dataset.modalTrigger;
                this.open(modalId, {
                    trigger: trigger,
                    content: trigger.dataset.modalContent,
                    title: trigger.dataset.modalTitle,
                    size: trigger.dataset.modalSize,
                    type: trigger.dataset.modalType
                });
            }
            
            // Close modal on backdrop click
            if (e.target.classList.contains('modal-backdrop')) {
                this.close(this.activeModal);
            }
            
            // Close button
            if (e.target.classList.contains('modal-close')) {
                e.preventDefault();
                const modal = e.target.closest('.modal');
                if (modal) {
                    this.close(modal.id);
                }
            }
        });
    }

    initExistingModals() {
        const existingModals = document.querySelectorAll('.modal');
        existingModals.forEach(modal => {
            this.register(modal.id, modal);
        });
    }

    register(id, element, options = {}) {
        if (this.modals.has(id)) {
            console.warn(`Modal with id "${id}" already exists`);
            return;
        }
        
        const modalData = {
            element: element,
            options: {
                closable: true,
                backdrop: true,
                keyboard: true,
                focus: true,
                size: 'medium',
                animation: 'fade',
                ...options
            }
        };
        
        this.modals.set(id, modalData);
        this.setupModalElement(element, modalData.options);
    }

    setupModalElement(element, options) {
        // Ensure proper structure
        if (!element.querySelector('.modal-dialog')) {
            this.wrapContent(element);
        }
        
        // Set ARIA attributes
        element.setAttribute('role', 'dialog');
        element.setAttribute('aria-modal', 'true');
        element.setAttribute('aria-hidden', 'true');
        element.classList.add('modal');
        
        // Add size class
        element.classList.add(`modal-${options.size}`);
        
        // Add animation class
        element.classList.add(`modal-${options.animation}`);
        
        // Ensure close button exists
        this.ensureCloseButton(element, options);
    }

    wrapContent(element) {
        const content = element.innerHTML;
        element.innerHTML = `
            <div class="modal-backdrop">
                <div class="modal-dialog">
                    <div class="modal-content">
                        ${content}
                    </div>
                </div>
            </div>
        `;
    }

    ensureCloseButton(element, options) {
        if (!options.closable) return;
        
        const closeBtn = element.querySelector('.modal-close');
        if (!closeBtn) {
            const modalContent = element.querySelector('.modal-content');
            const closeButton = document.createElement('button');
            closeButton.className = 'modal-close';
            closeButton.innerHTML = '&times;';
            closeButton.setAttribute('aria-label', 'Close modal');
            modalContent.appendChild(closeButton);
        }
    }

    create(id, options = {}) {
        if (this.modals.has(id)) {
            return this.modals.get(id).element;
        }
        
        const modal = document.createElement('div');
        modal.id = id;
        modal.style.display = 'none';
        document.body.appendChild(modal);
        
        this.register(id, modal, options);
        return modal;
    }

    open(id, data = {}) {
        const modalData = this.modals.get(id);
        if (!modalData) {
            console.error(`Modal with id "${id}" not found`);
            return;
        }
        
        const modal = modalData.element;
        const options = modalData.options;
        
        // Close any existing modal
        if (this.activeModal && this.activeModal !== id) {
            this.close(this.activeModal);
        }
        
        // Prepare modal content
        this.prepareContent(modal, data);
        
        // Store scroll position and prevent body scroll
        this.scrollPosition = window.pageYOffset;
        document.body.style.top = `-${this.scrollPosition}px`;
        document.body.classList.add('modal-open');
        
        // Show modal
        modal.style.display = 'block';
        modal.setAttribute('aria-hidden', 'false');
        
        // Trigger reflow for animation
        modal.offsetHeight;
        
        // Add active class for animation
        modal.classList.add('modal-active');
        
        // Focus management
        if (options.focus) {
            this.setFocus(modal, data.trigger);
        }
        
        // Store active modal
        this.activeModal = id;
        
        // Trigger event
        this.triggerEvent(modal, 'modal:opened', { data });
        
        return modal;
    }

    close(id) {
        if (!id || !this.modals.has(id)) return;
        
        const modalData = this.modals.get(id);
        const modal = modalData.element;
        const options = modalData.options;
        
        // Trigger event (cancelable)
        const closeEvent = this.triggerEvent(modal, 'modal:closing', { cancelable: true });
        if (closeEvent.defaultPrevented) return;
        
        // Start close animation
        modal.classList.remove('modal-active');
        
        // Wait for animation to complete
        const animationDuration = this.getAnimationDuration(modal);
        setTimeout(() => {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            
            // Restore body scroll
            document.body.classList.remove('modal-open');
            document.body.style.top = '';
            window.scrollTo(0, this.scrollPosition);
            
            // Return focus to trigger if available
            const trigger = modal.querySelector('[data-return-focus]');
            if (trigger) {
                trigger.focus();
            }
            
            this.activeModal = null;
            
            // Trigger closed event
            this.triggerEvent(modal, 'modal:closed');
            
        }, animationDuration);
    }

    prepareContent(modal, data) {
        const modalContent = modal.querySelector('.modal-content');
        if (!modalContent) return;
        
        // Set title if provided
        if (data.title) {
            let titleEl = modal.querySelector('.modal-title');
            if (!titleEl) {
                titleEl = document.createElement('h2');
                titleEl.className = 'modal-title';
                modalContent.insertBefore(titleEl, modalContent.firstChild);
            }
            titleEl.textContent = data.title;
            modal.setAttribute('aria-labelledby', titleEl.id || 'modal-title');
        }
        
        // Set content based on type
        const bodyEl = modal.querySelector('.modal-body') || modalContent;
        
        switch (data.type) {
            case 'ajax':
                this.loadAjaxContent(bodyEl, data.content);
                break;
            case 'iframe':
                this.loadIframeContent(bodyEl, data.content);
                break;
            case 'image':
                this.loadImageContent(bodyEl, data.content);
                break;
            case 'video':
                this.loadVideoContent(bodyEl, data.content);
                break;
            default:
                if (data.content) {
                    bodyEl.innerHTML = data.content;
                }
        }
        
        // Store return focus reference
        if (data.trigger) {
            data.trigger.setAttribute('data-return-focus', 'true');
        }
    }

    async loadAjaxContent(container, url) {
        container.innerHTML = '<div class="modal-loading">Loading...</div>';
        
        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const content = await response.text();
            container.innerHTML = content;
            
        } catch (error) {
            container.innerHTML = '<div class="modal-error">Failed to load content</div>';
            console.error('Ajax content loading failed:', error);
        }
    }

    loadIframeContent(container, url) {
        container.innerHTML = `
            <div class="modal-iframe-wrapper">
                <iframe src="${url}" frameborder="0" allowfullscreen></iframe>
            </div>
        `;
    }

    loadImageContent(container, src) {
        container.innerHTML = `
            <div class="modal-image-wrapper">
                <img src="${src}" alt="Modal image" />
            </div>
        `;
    }

    loadVideoContent(container, src) {
        const isYoutube = src.includes('youtube.com') || src.includes('youtu.be');
        const isVimeo = src.includes('vimeo.com');
        
        if (isYoutube) {
            const videoId = this.extractYouTubeId(src);
            container.innerHTML = `
                <div class="modal-video-wrapper">
                    <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                            frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        } else if (isVimeo) {
            const videoId = this.extractVimeoId(src);
            container.innerHTML = `
                <div class="modal-video-wrapper">
                    <iframe src="https://player.vimeo.com/video/${videoId}?autoplay=1" 
                            frameborder="0" allowfullscreen></iframe>
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="modal-video-wrapper">
                    <video controls autoplay>
                        <source src="${src}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            `;
        }
    }

    setFocus(modal, returnElement) {
        // Find first focusable element
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
        
        // Trap focus within modal
        this.trapFocus(modal, focusableElements);
    }

    trapFocus(modal, focusableElements) {
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        const trapHandler = (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    // Shift + Tab
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    // Tab
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        };
        
        modal.addEventListener('keydown', trapHandler);
        
        // Store handler for cleanup
        modal.setAttribute('data-focus-trap', 'true');
        modal._focusTrapHandler = trapHandler;
    }

    getAnimationDuration(modal) {
        const style = window.getComputedStyle(modal);
        const duration = style.transitionDuration || style.animationDuration || '0s';
        return parseFloat(duration) * 1000;
    }

    extractYouTubeId(url) {
        const match = url.match(/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/user\/\S+|\/ytscreeningroom\?v=|\/sandalsResorts#\w\/\w\/.*\/))([^\/&\n\?]{11})/);
        return match ? match[1] : null;
    }

    extractVimeoId(url) {
        const match = url.match(/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/);
        return match ? match[3] : null;
    }

    triggerEvent(element, eventName, options = {}) {
        const event = new CustomEvent(eventName, {
            bubbles: true,
            cancelable: options.cancelable || false,
            detail: options.detail || {}
        });
        
        element.dispatchEvent(event);
        return event;
    }

    // Public API methods
    isOpen(id) {
        return this.activeModal === id;
    }

    getActive() {
        return this.activeModal;
    }

    destroy(id) {
        if (!this.modals.has(id)) return;
        
        const modalData = this.modals.get(id);
        const modal = modalData.element;
        
        // Close if currently open
        if (this.activeModal === id) {
            this.close(id);
        }
        
        // Clean up focus trap
        if (modal._focusTrapHandler) {
            modal.removeEventListener('keydown', modal._focusTrapHandler);
        }
        
        // Remove from DOM if created dynamically
        if (modal.parentNode) {
            modal.parentNode.removeChild(modal);
        }
        
        // Remove from registry
        this.modals.delete(id);
    }

    destroyAll() {
        this.modals.forEach((_, id) => {
            this.destroy(id);
        });
    }
}

// Create global instance
const modalInstance = new Modal();

// Export both class and instance
export { Modal, modalInstance as default };
