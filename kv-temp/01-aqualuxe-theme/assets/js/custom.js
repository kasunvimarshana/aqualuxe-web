/**
 * AquaLuxe Custom JavaScript
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Declare AquaLuxe variable
window.AquaLuxe = {}
;(($) => {
  /**
   * Document Ready
   */
  $(document).ready(() => {
    // Initialize components
    AquaLuxe.init()
  })

  /**
   * Main AquaLuxe Object
   */
  window.AquaLuxe = {
    /**
     * Initialize all components
     */
    init: function () {
      this.mobileMenu()
      this.smoothScroll()
      this.productQuickView()
      this.cartUpdate()
      this.imageZoom()
      this.lazyLoading()
      this.searchToggle()
    },

    /**
     * Mobile Menu Toggle
     */
    mobileMenu: () => {
      $(".menu-toggle").on("click", function (e) {
        e.preventDefault()

        const $this = $(this)
        const $menu = $("#primary-menu")

        $this.toggleClass("active")
        $menu.slideToggle(300)

        // Update aria-expanded
        const expanded = $this.attr("aria-expanded") === "true"
        $this.attr("aria-expanded", !expanded)
      })

      // Close menu on window resize
      $(window).on("resize", () => {
        if ($(window).width() > 768) {
          $("#primary-menu").removeAttr("style")
          $(".menu-toggle").removeClass("active").attr("aria-expanded", "false")
        }
      })
    },

    /**
     * Smooth Scroll for Anchor Links
     */
    smoothScroll: () => {
      $('a[href*="#"]:not([href="#"])').on("click", function (e) {
        const target = $(this.hash)

        if (target.length) {
          e.preventDefault()

          $("html, body").animate(
            {
              scrollTop: target.offset().top - 80,
            },
            800,
            "swing",
          )
        }
      })
    },

    /**
     * Product Quick View
     */
    productQuickView: () => {
      $(document).on("click", ".quick-view-btn", function (e) {
        e.preventDefault()

        const productId = $(this).data("product-id")
        const $button = $(this)

        // Show loading
        $button.addClass("loading").text("Loading...")

        // Declare aqualuxe_ajax variable
        const aqualuxe_ajax = {
          ajax_url: "your_ajax_url_here",
          nonce: "your_nonce_here",
        }

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
      const modal = `
                <div class="quick-view-modal">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <button class="modal-close">&times;</button>
                        <div class="product-image">
                            <img src="${data.image}" alt="${data.title}">
                        </div>
                        <div class="product-info">
                            <h3>${data.title}</h3>
                            <div class="price">${data.price}</div>
                            <div class="description">${data.description}</div>
                            <a href="#" class="button add-to-cart">Add to Cart</a>
                        </div>
                    </div>
                </div>
            `

      $("body").append(modal)
      $(".quick-view-modal").fadeIn(300)

      // Close modal events
      $(".modal-close, .modal-overlay").on("click", () => {
        $(".quick-view-modal").fadeOut(300, function () {
          $(this).remove()
        })
      })
    },

    /**
     * Cart Update Animation
     */
    cartUpdate: () => {
      $(document.body).on("added_to_cart", () => {
        $(".cart-count").addClass("bounce")

        setTimeout(() => {
          $(".cart-count").removeClass("bounce")
        }, 600)
      })
    },

    /**
     * Image Zoom on Hover
     */
    imageZoom: () => {
      $(".product img")
        .on("mouseenter", function () {
          $(this).addClass("zoomed")
        })
        .on("mouseleave", function () {
          $(this).removeClass("zoomed")
        })
    },

    /**
     * Lazy Loading Images
     */
    lazyLoading: () => {
      if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const img = entry.target
              img.src = img.dataset.src
              img.classList.remove("lazy")
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
     * Search Toggle
     */
    searchToggle: () => {
      $(".search-toggle").on("click", (e) => {
        e.preventDefault()
        $(".search-form").slideToggle(300)
      })
    },

    /**
     * Utility Functions
     */
    utils: {
      /**
       * Debounce function
       */
      debounce: (func, wait, immediate) => {
        let timeout
        return function () {
          
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

      /**
       * Throttle function
       */
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
   * Window Load Event
   */
  $(window).on("load", () => {
    // Remove loading class from body
    $("body").removeClass("loading")

    // Initialize animations
    AquaLuxe.initAnimations()
  })

  /**
   * Initialize Animations
   */
  AquaLuxe.initAnimations = () => {
    // Fade in elements on scroll
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("fade-in")
          }
        })
      },
      {
        threshold: 0.1,
      },
    )

    document.querySelectorAll(".animate-on-scroll").forEach((el) => {
      observer.observe(el)
    })
  }
})(window.jQuery)

/**
 * Vanilla JavaScript for performance-critical features
 */
document.addEventListener("DOMContentLoaded", () => {
  // Back to top button
  const backToTop = document.createElement("button")
  backToTop.innerHTML = "↑"
  backToTop.className = "back-to-top"
  backToTop.setAttribute("aria-label", "Back to top")
  document.body.appendChild(backToTop)

  window.addEventListener("scroll", () => {
    if (window.pageYOffset > 300) {
      backToTop.classList.add("show")
    } else {
      backToTop.classList.remove("show")
    }
  })

  backToTop.addEventListener("click", () => {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    })
  })
})
