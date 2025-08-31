<?php
/**
 * Generic modal component template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get modal parameters
$modal_id = $args['id'] ?? 'modal-' . wp_rand();
$modal_title = $args['title'] ?? '';
$modal_content = $args['content'] ?? '';
$modal_size = $args['size'] ?? 'medium'; // small, medium, large, xlarge
$modal_closable = $args['closable'] ?? true;
$modal_overlay_close = $args['overlay_close'] ?? true;
$modal_classes = $args['classes'] ?? '';
$modal_attributes = $args['attributes'] ?? [];

// Size classes
$size_classes = [
    'small' => 'max-w-md',
    'medium' => 'max-w-lg',
    'large' => 'max-w-2xl',
    'xlarge' => 'max-w-4xl',
    'full' => 'max-w-full mx-4',
];

$size_class = $size_classes[$modal_size] ?? $size_classes['medium'];

// Build attributes string
$attributes_string = '';
if (!empty($modal_attributes)) {
    foreach ($modal_attributes as $attr => $value) {
        $attributes_string .= ' ' . esc_attr($attr) . '="' . esc_attr($value) . '"';
    }
}
?>

<!-- Modal Overlay -->
<div id="<?php echo esc_attr($modal_id); ?>" 
     class="modal-overlay fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4" 
     role="dialog" 
     aria-modal="true"
     aria-labelledby="<?php echo esc_attr($modal_id); ?>-title"
     aria-hidden="true"
     <?php echo $modal_overlay_close ? 'data-overlay-close="true"' : ''; ?>
     <?php echo $attributes_string; ?>>
    
    <!-- Modal Container -->
    <div class="modal-container bg-white rounded-lg shadow-xl <?php echo esc_attr($size_class); ?> <?php echo esc_attr($modal_classes); ?> max-h-full overflow-hidden transform transition-all duration-300 scale-95 opacity-0"
         role="document">
        
        <!-- Modal Header -->
        <?php if ($modal_title || $modal_closable) : ?>
            <div class="modal-header flex items-center justify-between p-6 border-b border-gray-200">
                
                <?php if ($modal_title) : ?>
                    <h2 id="<?php echo esc_attr($modal_id); ?>-title" class="modal-title text-xl font-semibold text-gray-900">
                        <?php echo wp_kses_post($modal_title); ?>
                    </h2>
                <?php endif; ?>

                <?php if ($modal_closable) : ?>
                    <button type="button" 
                            class="modal-close text-gray-400 hover:text-gray-600 transition-colors p-2 -mr-2" 
                            aria-label="<?php esc_attr_e('Close modal', 'aqualuxe'); ?>"
                            data-modal-close="<?php echo esc_attr($modal_id); ?>">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <!-- Modal Body -->
        <div class="modal-body p-6 overflow-y-auto max-h-96">
            <?php if ($modal_content) : ?>
                <?php echo wp_kses_post($modal_content); ?>
            <?php else : ?>
                <!-- Content will be populated dynamically -->
                <div class="modal-content-placeholder">
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Modal Footer (optional, populated by JavaScript or passed content) -->
        <div class="modal-footer hidden p-6 border-t border-gray-200 bg-gray-50">
            <!-- Footer content goes here -->
        </div>

    </div>
</div>

<script>
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('<?php echo esc_js($modal_id); ?>');
    const modalContainer = modal?.querySelector('.modal-container');
    const closeButtons = modal?.querySelectorAll('[data-modal-close]');
    const overlayCloseEnabled = modal?.getAttribute('data-overlay-close') === 'true';
    
    // Modal open function
    window.openModal = function(modalId, options = {}) {
        const targetModal = document.getElementById(modalId || '<?php echo esc_js($modal_id); ?>');
        if (!targetModal) return;
        
        const container = targetModal.querySelector('.modal-container');
        const body = targetModal.querySelector('.modal-body');
        const footer = targetModal.querySelector('.modal-footer');
        const title = targetModal.querySelector('.modal-title');
        
        // Update content if provided
        if (options.title && title) {
            title.innerHTML = options.title;
        }
        
        if (options.content && body) {
            body.innerHTML = options.content;
        }
        
        if (options.footer && footer) {
            footer.innerHTML = options.footer;
            footer.classList.remove('hidden');
        }
        
        // Show modal
        targetModal.classList.remove('hidden');
        targetModal.classList.add('flex');
        targetModal.setAttribute('aria-hidden', 'false');
        
        // Animate in
        setTimeout(() => {
            if (container) {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
        
        // Focus management
        const firstFocusable = targetModal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
        
        // Dispatch custom event
        targetModal.dispatchEvent(new CustomEvent('modal:open', {
            detail: { modalId, options }
        }));
    };
    
    // Modal close function
    window.closeModal = function(modalId) {
        const targetModal = document.getElementById(modalId || '<?php echo esc_js($modal_id); ?>');
        if (!targetModal) return;
        
        const container = targetModal.querySelector('.modal-container');
        
        // Animate out
        if (container) {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
        }
        
        // Hide modal after animation
        setTimeout(() => {
            targetModal.classList.add('hidden');
            targetModal.classList.remove('flex');
            targetModal.setAttribute('aria-hidden', 'true');
            
            // Restore body scrolling
            document.body.style.overflow = '';
            
            // Reset footer if it was shown
            const footer = targetModal.querySelector('.modal-footer');
            if (footer) {
                footer.classList.add('hidden');
            }
            
            // Dispatch custom event
            targetModal.dispatchEvent(new CustomEvent('modal:close', {
                detail: { modalId }
            }));
        }, 300);
    };
    
    // Close button handlers
    if (closeButtons) {
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal-close');
                closeModal(modalId);
            });
        });
    }
    
    // Overlay click to close
    if (overlayCloseEnabled && modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal('<?php echo esc_js($modal_id); ?>');
            }
        });
    }
    
    // Keyboard handling
    if (modal) {
        modal.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal('<?php echo esc_js($modal_id); ?>');
            }
            
            // Trap focus within modal
            if (e.key === 'Tab') {
                const focusableElements = modal.querySelectorAll(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
                );
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        });
    }
});

// Global modal trigger handler
document.addEventListener('click', function(e) {
    const trigger = e.target.closest('[data-modal-open]');
    if (trigger) {
        e.preventDefault();
        const modalId = trigger.getAttribute('data-modal-open');
        const title = trigger.getAttribute('data-modal-title');
        const content = trigger.getAttribute('data-modal-content');
        const footer = trigger.getAttribute('data-modal-footer');
        
        openModal(modalId, {
            title: title,
            content: content,
            footer: footer
        });
    }
});
</script>

<style>
/* Modal specific styles */
.modal-overlay {
    backdrop-filter: blur(4px);
    animation: fadeIn 0.3s ease-out;
}

.modal-container {
    max-height: calc(100vh - 2rem);
}

.modal-body {
    max-height: calc(100vh - 12rem);
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .modal-container {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
    
    .modal-body {
        max-height: calc(100vh - 8rem);
    }
}

/* Loading animation */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .modal-container {
        background-color: #1f2937;
        color: #f9fafb;
    }
    
    .modal-header {
        border-color: #374151;
    }
    
    .modal-footer {
        background-color: #111827;
        border-color: #374151;
    }
}

/* RTL Support */
[dir="rtl"] .modal-close {
    margin-right: 0;
    margin-left: -0.5rem;
}
</style>
