/**
 * AquaLuxe Shop JavaScript
 */

;(($) => {
  // Declare AquaLuxeShop variable
  const AquaLuxeShop = (window.AquaLuxeShop = {
    /**
     * Initialize shop functions
     */
    init: function () {
      this.productFilters()
      this.productSorting()
      this.priceSlider()
      this.colorFilter()
      this.loadMoreProducts()
      this.productComparison()
    },

    /**
     * Product filters
     */
    productFilters: () => {
      $('.product-filters input[type="checkbox"]').on("change", () => {
        AquaLuxeShop.filterProducts()
      })

      $(".filter-reset").on("click", (e) => {
        e.preventDefault()
        $('.product-filters input[type="checkbox"]').prop("checked", false)
        $(".price-slider").slider("values", [0, 1000])
        AquaLuxeShop.filterProducts()
      })
    },

    /**
     * Product sorting
     */
    productSorting: () => {
      $(".product-sort select").on("change", () => {
        AquaLuxeShop.filterProducts()
      })
    },

    /**
     * Price range slider
     */
    priceSlider: () => {
      if ($(".price-slider").length) {
        $(".price-slider").slider({
          range: true,
          min: 0,
          max: 1000,
          values: [0, 1000],
          slide: (event, ui) => {
            $(".price-range-display").text("$" + ui.values[0] + " - $" + ui.values[1])
          },
          stop: (event, ui) => {
            AquaLuxeShop.filterProducts()
          },
        })
      }
    },

    /**
     * Color filter
     */
    colorFilter: () => {
      $(".color-filter .color-option").on("click", function () {
        $(this).toggleClass("selected")
        AquaLuxeShop.filterProducts()
      })
    },

    /**
     * Filter products via AJAX
     */
    filterProducts: () => {
      const $container = $(".products-container")
      const $loading = $(".products-loading")

      // Show loading
      $loading.show()
      $container.addClass("loading")

      // Collect filter data
      const filterData = {
        action: "aqualuxe_filter_products",
        nonce: window.aqualuxe_ajax.nonce,
        category: [],
        price_range: "",
        colors: [],
        sort_by: $(".product-sort select").val() || "default",
      }

      // Get selected categories
      $(".category-filter input:checked").each(function () {
        filterData.category.push($(this).val())
      })

      // Get price range
      if ($(".price-slider").length) {
        const values = $(".price-slider").slider("values")
        filterData.price_range = values[0] + "-" + values[1]
      }

      // Get selected colors
      $(".color-filter .color-option.selected").each(function () {
        filterData.colors.push($(this).data("color"))
      })

      // AJAX request
      $.ajax({
        url: window.aqualuxe_ajax.ajax_url,
        type: "POST",
        data: filterData,
        success: (response) => {
          if (response.success) {
            $container.html(response.data)
            AquaLuxeShop.updateProductCount()
          } else {
            $container.html('<p class="no-products">' + (response.data || "No products found") + "</p>")
          }
        },
        error: () => {
          window.AquaLuxe.showNotification("Error filtering products", "error")
        },
        complete: () => {
          $loading.hide()
          $container.removeClass("loading")

          // Reinitialize lazy loading for new images
          if (window.AquaLuxe && window.AquaLuxe.lazyLoading) {
            window.AquaLuxe.lazyLoading()
          }
        },
      })
    },

    /**
     * Load more products
     */
    loadMoreProducts: () => {
      let page = 2

      $(".load-more-products").on("click", function (e) {
        e.preventDefault()

        const $button = $(this)
        const $container = $(".products-container")

        $button.addClass("loading").text("Loading...")

        $.ajax({
          url: window.aqualuxe_ajax.ajax_url,
          type: "POST",
          data: {
            action: "aqualuxe_load_more_products",
            page: page,
            nonce: window.aqualuxe_ajax.nonce,
          },
          success: (response) => {
            if (response.success) {
              $container.append(response.data)
              page++

              if (!response.has_more) {
                $button.hide()
              }
            } else {
              $button.hide()
            }
          },
          error: () => {
            window.AquaLuxe.showNotification("Error loading more products", "error")
          },
          complete: () => {
            $button.removeClass("loading").text("Load More")
          },
        })
      })
    },

    /**
     * Product comparison
     */
    productComparison: () => {
      let compareList = JSON.parse(localStorage.getItem("aqualuxe_compare") || "[]")

      // Add to compare
      $(".add-to-compare").on("click", function (e) {
        e.preventDefault()

        const productId = $(this).data("product-id")
        const productName = $(this).data("product-name")

        if (compareList.length >= 3) {
          window.AquaLuxe.showNotification("You can compare up to 3 products only", "warning")
          return
        }

        if (compareList.find((item) => item.id === productId)) {
          window.AquaLuxe.showNotification("Product already in comparison list", "info")
          return
        }

        compareList.push({
          id: productId,
          name: productName,
        })

        localStorage.setItem("aqualuxe_compare", JSON.stringify(compareList))
        AquaLuxeShop.updateCompareCount()
        window.AquaLuxe.showNotification("Product added to comparison", "success")
      })

      // Remove from compare
      $(document).on("click", ".remove-from-compare", function (e) {
        e.preventDefault()

        const productId = $(this).data("product-id")
        compareList = compareList.filter((item) => item.id !== productId)

        localStorage.setItem("aqualuxe_compare", JSON.stringify(compareList))
        AquaLuxeShop.updateCompareCount()
        $(this).closest(".compare-item").remove()
      })

      // View comparison
      $(".view-comparison").on("click", (e) => {
        e.preventDefault()

        if (compareList.length < 2) {
          window.AquaLuxe.showNotification("Add at least 2 products to compare", "warning")
          return
        }

        AquaLuxeShop.showComparison()
      })

      // Initialize compare count
      AquaLuxeShop.updateCompareCount()
    },

    /**
     * Update compare count
     */
    updateCompareCount: () => {
      const compareList = JSON.parse(localStorage.getItem("aqualuxe_compare") || "[]")
      $(".compare-count").text(compareList.length)

      if (compareList.length > 0) {
        $(".compare-widget").addClass("has-items")
      } else {
        $(".compare-widget").removeClass("has-items")
      }
    },

    /**
     * Show product comparison
     */
    showComparison: () => {
      const compareList = JSON.parse(localStorage.getItem("aqualuxe_compare") || "[]")

      if (compareList.length === 0) {
        return
      }

      $.ajax({
        url: window.aqualuxe_ajax.ajax_url,
        type: "POST",
        data: {
          action: "aqualuxe_get_comparison",
          products: compareList.map((item) => item.id),
          nonce: window.aqualuxe_ajax.nonce,
        },
        success: (response) => {
          if (response.success) {
            window.AquaLuxe.openModal(response.data)
          }
        },
        error: () => {
          window.AquaLuxe.showNotification("Error loading comparison", "error")
        },
      })
    },

    /**
     * Update product count display
     */
    updateProductCount: () => {
      const productCount = $(".products-container .product").length
      $(".products-count").text(productCount + " products found")
    },
  })

  // Declare aqualuxe_ajax variable
  window.aqualuxe_ajax = {
    ajax_url: "your_ajax_url_here",
    nonce: "your_nonce_here",
  }

  // Declare AquaLuxe variable
  window.AquaLuxe = {
    showNotification: (message, type) => {
      console.log(message, type)
    },
    openModal: (content) => {
      console.log(content)
    },
    lazyLoading: () => {
      console.log("Lazy loading initialized")
    },
  }

  $(document).ready(() => {
    AquaLuxeShop.init()
  })
})(window.jQuery)
