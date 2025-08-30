/**
 * AquaLuxe Main JavaScript
 */

;(($) => {
  // DOM Ready
  $(document).ready(() => {
    window.AquaLuxe.init()
  })

  // Main AquaLuxe object
  window.AquaLuxe = {
    /**
     * Initialize all functions
     */
    init: function () {
      this.mobileMenu()
      this.smoothScroll()
      this.cartUpdate()
      this.productQuickView()
      this.lazyLoading()
      this.searchEnhancement()
      this.newsletterSignup()
    },

    /**
     * Mobile menu functionality
     */
    mobileMenu: () => {
      $(".menu-toggle").on("click", function (e) {
        e.preventDefault()
        $(this).toggleClass("active")
        $(".main-navigation").toggleClass("active")
        $("body").toggleClass("menu-open")
      })

      // Close menu when clicking outside
      $(document).on("click", (e) => {
        if (!$(e.target).closest(".main-navigation, .menu-toggle").length) {
          $(".menu-toggle").removeClass("active")
          $(".main-navigation").removeClass("active")
          $("body").removeClass("menu-open")
        }
      })
    },

    /**
     * Smooth scrolling for anchor links
     */
    smoothScroll: () => {
      $('a[href*="#"]:not([href="#"])').on("click", function (e) {
        if (
          location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") &&
          location.hostname === this.hostname
        ) {
          var target = $(this.hash)
          target = target.length ? target : $("[name=" + this.hash.slice(1) + "]")

          if (target.length) {
            e.preventDefault()
            $("html, body").animate(
              {
                scrollTop: target.offset().top - 80,
              },
              800,
            )
          }
        }
      })
    },

    /**
     * Cart update functionality
     */
    cartUpdate: () => {
      $(document.body).on("added_to_cart", (event, fragments, cart_hash, $button) => {
        // Update cart count
        $(".cart-count").text(fragments["div.widget_shopping_cart_content .cart-contents-count"] || "0")

        // Show success message
        window.AquaLuxe.showNotification("Product added to cart!", "success")

        // Animate cart icon
        $(".cart-contents").addClass("bounce")
        setTimeout(() => {
          $(".cart-contents").removeClass("bounce")
        }, 600)
      })
    },

    /**
     * Product quick view
     */
    productQuickView: () => {
      $(".quick-view-btn").on("click", function (e) {
        e.preventDefault()

        var productId = $(this).data("product-id")
        var $button = $(this)

        $button.addClass("loading")

        $.ajax({
          url: window.aqualuxe_ajax.ajax_url,
          type: "POST",
          data: {
            action: "aqualuxe_quick_view",
            product_id: productId,
            nonce: window.aqualuxe_ajax.nonce,
          },
          success: (response) => {
            if (response.success) {
              window.AquaLuxe.openModal(response.data)
            } else {
              window.AquaLuxe.showNotification("Error loading product details", "error")
            }
          },
          error: () => {
            window.AquaLuxe.showNotification("Error loading product details", "error")
          },
          complete: () => {
            $button.removeClass("loading")
          },
        })
      })
    },

    /**
     * Lazy loading for images
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
              imageObserver.unobserve(img)
            }
          })
        })

        document.querySelectorAll("img[data-src]").forEach((img) => {
          imageObserver.observe(img)
        })
      }
    },

    /**
     * Enhanced search functionality
     */
    searchEnhancement: () => {
      let searchTimeout

      $(".search-field").on("input", function () {
        const query = $(this).val()
        const $results = $(".search-results")

        clearTimeout(searchTimeout)

        if (query.length >= 3) {
          searchTimeout = setTimeout(() => {
            window.AquaLuxe.performSearch(query, $results)
          }, 300)
        } else {
          $results.hide()
        }
      })
    },

    /**
     * Perform AJAX search
     */
    performSearch: (query, $results) => {
      $.ajax({
        url: window.aqualuxe_ajax.ajax_url,
        type: "POST",
        data: {
          action: "aqualuxe_search",
          query: query,
          nonce: window.aqualuxe_ajax.nonce,
        },
        success: (response) => {
          if (response.success) {
            $results.html(response.data).show()
          }
        },
      })
    },

    /**
     * Newsletter signup
     */
    newsletterSignup: () => {
      $(".newsletter-form").on("submit", function (e) {
        e.preventDefault()

        const $form = $(this)
        const $button = $form.find('button[type="submit"]')
        const email = $form.find('input[type="email"]').val()

        $button.addClass("loading").prop("disabled", true)

        $.ajax({
          url: window.aqualuxe_ajax.ajax_url,
          type: "POST",
          data: {
            action: "aqualuxe_newsletter_signup",
            email: email,
            nonce: window.aqualuxe_ajax.nonce,
          },
          success: (response) => {
            if (response.success) {
              window.AquaLuxe.showNotification("Thank you for subscribing!", "success")
              $form[0].reset()
            } else {
              window.AquaLuxe.showNotification(response.data || "Subscription failed", "error")
            }
          },
          error: () => {
            window.AquaLuxe.showNotification("Subscription failed", "error")
          },
          complete: () => {
            $button.removeClass("loading").prop("disabled", false)
          },
        })
      })
    },

    /**
     * Show notification
     */
    showNotification: (message, type = "info") => {
      const notification = $(`
                <div class="aqualuxe-notification ${type}">
                    <span class="message">${message}</span>
                    <button class="close">&times;</button>
                </div>
            `)

      $("body").append(notification)

      setTimeout(() => {
        notification.addClass("show")
      }, 100)

      // Auto hide after 5 seconds
      setTimeout(() => {
        notification.removeClass("show")
        setTimeout(() => {
          notification.remove()
        }, 300)
      }, 5000)

      // Manual close
      notification.find(".close").on("click", () => {
        notification.removeClass("show")
        setTimeout(() => {
          notification.remove()
        }, 300)
      })
    },

    /**
     * Open modal
     */
    openModal: (content) => {
      const modal = $(`
                <div class="aqualuxe-modal">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <button class="modal-close">&times;</button>
                        <div class="modal-body">${content}</div>
                    </div>
                </div>
            `)

      $("body").append(modal).addClass("modal-open")

      setTimeout(() => {
        modal.addClass("show")
      }, 100)

      // Close modal
      modal.find(".modal-close, .modal-overlay").on("click", () => {
        window.AquaLuxe.closeModal(modal)
      })

      // Close on escape key
      $(document).on("keyup.modal", (e) => {
        if (e.keyCode === 27) {
          window.AquaLuxe.closeModal(modal)
        }
      })
    },

    /**
     * Close modal
     */
    closeModal: (modal) => {
      modal.removeClass("show")
      $("body").removeClass("modal-open")
      $(document).off("keyup.modal")

      setTimeout(() => {
        modal.remove()
      }, 300)
    },

    /**
     * Utility function to debounce
     */
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
  }
})(window.jQuery)
