/**
 * AquaLuxe Theme JavaScript
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

;(($) => {
  // Declare the aqualuxe_ajax variable
  const aqualuxe_ajax = {
    ajax_url: "your_ajax_url_here",
    nonce: "your_nonce_here",
  }

  /**
   * AquaLuxe Theme Object
   */
  const AquaLuxe = {
    /**
     * Initialize all functions
     */
    init: function () {
      this.mobileMenu()
      this.smoothScroll()
      this.lazyLoading()
      this.productQuickView()
      this.cartUpdates()
      this.animations()
      this.accessibility()
      this.performance()
    },

    /**
     * Mobile Menu Toggle
     */
    mobileMenu: () => {
      const menuToggle = $(".menu-toggle")
      const navigation = $(".main-navigation")

      menuToggle.on("click", function (e) {
        e.preventDefault()

        const isExpanded = $(this).attr("aria-expanded") === "true"
        $(this).attr("aria-expanded", !isExpanded)
        navigation.toggleClass("toggled")

        // Trap focus within menu when open
        if (!isExpanded) {
          navigation.find("a").first().focus()
        }
      })

      // Close menu on escape key
      $(document).on("keydown", (e) => {
        if (e.keyCode === 27 && navigation.hasClass("toggled")) {
          menuToggle.click()
          menuToggle.focus()
        }
      })
    },

    /**
     * Smooth Scrolling for Anchor Links
     */
    smoothScroll: () => {
      $('a[href*="#"]:not([href="#"])').on("click", function (e) {
        const target = $(this.hash)

        if (target.length) {
          e.preventDefault()

          $("html, body").animate(
            {
              scrollTop: target.offset().top - 100,
            },
            800,
            "easeInOutCubic",
          )

          // Update focus for accessibility
          target.focus()
          if (!target.is(":focus")) {
            target.attr("tabindex", "-1")
            target.focus()
          }
        }
      })
    },

    /**
     * Lazy Loading for Images
     */
    lazyLoading: () => {
      if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target
              img.src = img.dataset.src
              img.classList.remove("lazy")
              img.classList.add("loaded")
              observer.unobserve(img)
            }
          })
        })

        document.querySelectorAll("img[data-src]").forEach((img) => {
          imageObserver.observe(img)
        })
      }
    },

    /**
     * Product Quick View
     */
    productQuickView: () => {
      $(document).on("click", ".quick-view-btn", function (e) {
        e.preventDefault()

        const productId = $(this).data("product-id")
        const $button = $(this)

        // Show loading state
        $button.addClass("loading").text("Loading...")

        $.ajax({
          url: aqualuxe_ajax.ajax_url,
          type: "POST",
          data: {
            action: "aqualuxe_quick_view",
            product_id: productId,
            nonce: aqualuxe_ajax.nonce,
          },
          success: (response) => {
            if (response.success) {
              AquaLuxe.showQuickViewModal(response.data)
            }
          },
          error: () => {
            console.error("Quick view failed")
          },
          complete: () => {
            $button.removeClass("loading").text("Quick View")
          },
        })
      })
    },

    /**
     * Show Quick View Modal
     */
    showQuickViewModal: (data) => {
      const modal = $(`
                <div class="aqualuxe-modal" role="dialog" aria-labelledby="modal-title" aria-modal="true">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <button class="modal-close" aria-label="Close modal">&times;</button>
                        <div class="modal-body">
                            <div class="product-image">
                                <img src="${data.image}" alt="${data.title}">
                            </div>
                            <div class="product-details">
                                <h2 id="modal-title">${data.title}</h2>
                                <div class="price">${data.price}</div>
                                <div class="description">${data.description}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `)

      $("body").append(modal)
      modal.fadeIn(300)

      // Focus management
      modal.find(".modal-close").focus()

      // Close modal events
      modal.find(".modal-close, .modal-overlay").on("click", () => {
        AquaLuxe.closeModal(modal)
      })

      // Escape key to close
      $(document).on("keydown.modal", (e) => {
        if (e.keyCode === 27) {
          AquaLuxe.closeModal(modal)
        }
      })
    },

    /**
     * Close Modal
     */
    closeModal: (modal) => {
      modal.fadeOut(300, () => {
        modal.remove()
        $(document).off("keydown.modal")
      })
    },

    /**
     * Cart Updates
     */
    cartUpdates: () => {
      $(document.body).on("added_to_cart", (event, fragments, cart_hash, $button) => {
        // Update cart count
        $(".cart-count").text(fragments["div.widget_shopping_cart_content"].match(/\d+/)[0] || 0)

        // Show success message
        AquaLuxe.showNotification("Product added to cart!", "success")
      })

      $(document.body).on("removed_from_cart", () => {
        AquaLuxe.showNotification("Product removed from cart!", "info")
      })
    },

    /**
     * Show Notification
     */
    showNotification: (message, type = "info") => {
      const notification = $(`
                <div class="aqualuxe-notification ${type}" role="alert">
                    <span>${message}</span>
                    <button class="notification-close" aria-label="Close notification">&times;</button>
                </div>
            `)

      $("body").append(notification)

      // Auto remove after 5 seconds
      setTimeout(() => {
        notification.fadeOut(300, function () {
          $(this).remove()
        })
      }, 5000)

      // Manual close
      notification.find(".notification-close").on("click", () => {
        notification.fadeOut(300, function () {
          $(this).remove()
        })
      })
    },

    /**
     * Scroll Animations
     */
    animations: () => {
      if ("IntersectionObserver" in window) {
        const animationObserver = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("animate")
              }
            })
          },
          {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
          },
        )

        document.querySelectorAll(".fade-in-up, .fade-in-left, .fade-in-right").forEach((el) => {
          animationObserver.observe(el)
        })
      }
    },

    /**
     * Accessibility Enhancements
     */
    accessibility: () => {
      // Skip link functionality
      $(".skip-link").on("click", function (e) {
        const target = $($(this).attr("href"))
        if (target.length) {
          target.attr("tabindex", "-1").focus()
        }
      })

      // Keyboard navigation for dropdowns
      $(".menu-item-has-children > a").on("keydown", function (e) {
        if (e.keyCode === 13 || e.keyCode === 32) {
          // Enter or Space
          e.preventDefault()
          $(this).next(".sub-menu").toggle()
        }
      })

      // ARIA labels for dynamic content
      $(".cart-contents").attr("aria-label", function () {
        const count = $(this).find(".cart-count").text()
        return `Shopping cart with ${count} items`
      })
    },

    /**
     * Performance Optimizations
     */
    performance: () => {
      // Debounce scroll events
      let scrollTimer
      $(window).on("scroll", () => {
        clearTimeout(scrollTimer)
        scrollTimer = setTimeout(() => {
          AquaLuxe.handleScroll()
        }, 16) // ~60fps
      })

      // Preload critical images
      const criticalImages = $(".hero-section img, .featured-product img")
      criticalImages.each(function () {
        const img = new Image()
        img.src = $(this).attr("src")
      })
    },

    /**
     * Handle Scroll Events
     */
    handleScroll: () => {
      const scrollTop = $(window).scrollTop()
      const header = $(".site-header")

      // Sticky header
      if (scrollTop > 100) {
        header.addClass("scrolled")
      } else {
        header.removeClass("scrolled")
      }

      // Back to top button
      const backToTop = $(".back-to-top")
      if (scrollTop > 500) {
        backToTop.fadeIn()
      } else {
        backToTop.fadeOut()
      }
    },

    /**
     * Utility Functions
     */
    utils: {
      debounce: (func, wait, immediate) => {
        let timeout
        return function executedFunction() {
          
          const args = arguments
          const later = () => {
            timeout = null
            if (!immediate) func.apply(this, args)
          }
          const callNow = immediate && !timeout
          clearTimeout(timeout)
          timeout = setTimeout(later, wait)
          if (callNow) func.apply(this, args)
        }
      },

      throttle: (func, limit) => {
        let inThrottle
        return function () {
          const args = arguments
          
          if (!inThrottle) {
            func.apply(this, args)
            inThrottle = true
            setTimeout(() => (inThrottle = false), limit)
          }
        }
      },
    },
  }

  /**
   * Initialize when DOM is ready
   */
  $(document).ready(() => {
    AquaLuxe.init()
  })

  /**
   * Additional initialization after window load
   */
  $(window).on("load", () => {
    // Remove loading class from body
    $("body").removeClass("loading")

    // Initialize any plugins that need full page load
    if (typeof $.fn.magnificPopup !== "undefined") {
      $(".gallery-item a").magnificPopup({
        type: "image",
        gallery: {
          enabled: true,
        },
      })
    }
  })

  /**
   * Handle window resize
   */
  $(window).on(
    "resize",
    AquaLuxe.utils.debounce(() => {
      // Recalculate any size-dependent elements
      AquaLuxe.handleResize()
    }, 250),
  )

  /**
   * Handle Resize Events
   */
  AquaLuxe.handleResize = () => {
    // Close mobile menu on desktop
    if ($(window).width() > 768) {
      $(".main-navigation").removeClass("toggled")
      $(".menu-toggle").attr("aria-expanded", "false")
    }
  }

  /**
   * Expose AquaLuxe to global scope for extensibility
   */
  window.AquaLuxe = AquaLuxe
})(window.jQuery)

/**
 * Vanilla JavaScript for critical functionality
 * (No jQuery dependency)
 */
document.addEventListener("DOMContentLoaded", () => {
  // Critical CSS loading
  const criticalCSS = document.createElement("style")
  criticalCSS.textContent = `
        .loading { opacity: 0; }
        .loaded { opacity: 1; transition: opacity 0.3s ease; }
        .fade-in-up { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .fade-in-up.animate { opacity: 1; transform: translateY(0); }
    `
  document.head.appendChild(criticalCSS)

  // Service Worker registration (if available)
  if ("serviceWorker" in navigator) {
    window.addEventListener("load", () => {
      navigator.serviceWorker
        .register("/sw.js")
        .then((registration) => {
          console.log("SW registered: ", registration)
        })
        .catch((registrationError) => {
          console.log("SW registration failed: ", registrationError)
        })
    })
  }
})
