/**
 * WooCommerce JavaScript
 * @package AquaLuxe
 */

class WooCommerceManager {
  constructor () {
    this.init()
  }

  init () {
    this.initProductGallery()
    this.initQuantityButtons()
    this.initAddToCart()
    this.initQuickView()
    this.initWishlist()
    this.initProductFilters()
    this.initCartDrawer()
    this.initProductTabs()
  }

  initProductGallery () {
    const galleries = document.querySelectorAll('.woocommerce-product-gallery')
    
    galleries.forEach(gallery => {
      const thumbs = gallery.querySelectorAll('.flex-control-thumbs img')
      const mainImage = gallery.querySelector('.woocommerce-product-gallery__image img')
      
      thumbs.forEach(thumb => {
        thumb.addEventListener('click', (e) => {
          e.preventDefault()
          const fullSrc = thumb.dataset.large || thumb.src
          if (mainImage) {
            mainImage.src = fullSrc
          }
          
          // Update active thumb
          thumbs.forEach(t => t.parentNode.classList.remove('flex-active-slide'))
          thumb.parentNode.classList.add('flex-active-slide')
        })
      })
    })
  }

  initQuantityButtons () {
    const quantityInputs = document.querySelectorAll('.quantity')
    
    quantityInputs.forEach(wrapper => {
      const input = wrapper.querySelector('input[type="number"]')
      const plusBtn = wrapper.querySelector('.plus')
      const minusBtn = wrapper.querySelector('.minus')
      
      if (input && plusBtn && minusBtn) {
        plusBtn.addEventListener('click', () => {
          const max = parseFloat(input.getAttribute('max'))
          const current = parseFloat(input.value) || 0
          if (!max || current < max) {
            input.value = current + 1
            input.dispatchEvent(new Event('change'))
          }
        })
        
        minusBtn.addEventListener('click', () => {
          const min = parseFloat(input.getAttribute('min')) || 1
          const current = parseFloat(input.value) || 0
          if (current > min) {
            input.value = current - 1
            input.dispatchEvent(new Event('change'))
          }
        })
      }
    })
  }

  initAddToCart () {
    const buttons = document.querySelectorAll('.add_to_cart_button:not(.product_type_variable)')
    
    buttons.forEach(button => {
      button.addEventListener('click', (e) => {
        if (!button.classList.contains('ajax_add_to_cart')) {
          return
        }
        
        e.preventDefault()
        
        const productId = button.dataset.product_id
        const quantity = button.dataset.quantity || 1
        
        this.addToCart(productId, quantity, button)
      })
    })
  }

  async addToCart (productId, quantity, button) {
    const originalText = button.textContent
    button.textContent = 'Adding...'
    button.disabled = true
    
    try {
      const formData = new FormData()
      formData.append('action', 'woocommerce_add_to_cart')
      formData.append('product_id', productId)
      formData.append('quantity', quantity)
      
      const response = await fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'), {
        method: 'POST',
        body: formData
      })
      
      const result = await response.text()
      
      if (result) {
        // Update cart count
        this.updateCartCount()
        
        // Show success message
        button.textContent = 'Added!'
        button.classList.add('added')
        
        setTimeout(() => {
          button.textContent = originalText
          button.classList.remove('added')
          button.disabled = false
        }, 2000)
        
        // Trigger cart update events
        document.body.dispatchEvent(new Event('added_to_cart'))
      }
    } catch (error) {
      button.textContent = 'Error'
      setTimeout(() => {
        button.textContent = originalText
        button.disabled = false
      }, 2000)
    }
  }

  initQuickView () {
    const quickViewBtns = document.querySelectorAll('.quick-view-btn')
    
    quickViewBtns.forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault()
        const productId = btn.dataset.product_id
        await this.showQuickView(productId)
      })
    })
  }

  async showQuickView (productId) {
    try {
      const response = await fetch(wc_add_to_cart_params.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'product_quick_view',
          product_id: productId
        })
      })
      
      const data = await response.json()
      
      if (data.success) {
        this.createQuickViewModal(data.data)
      }
    } catch (error) {
      console.error('Quick view error:', error)
    }
  }

  createQuickViewModal (content) {
    const modal = document.createElement('div')
    modal.className = 'quick-view-modal'
    modal.innerHTML = `
      <div class="modal-overlay"></div>
      <div class="modal-content">
        <button class="modal-close">&times;</button>
        <div class="modal-body">${content}</div>
      </div>
    `
    
    document.body.appendChild(modal)
    document.body.classList.add('modal-open')
    
    // Close functionality
    const closeBtn = modal.querySelector('.modal-close')
    const overlay = modal.querySelector('.modal-overlay')
    
    const closeModal = () => {
      modal.remove()
      document.body.classList.remove('modal-open')
    }
    
    closeBtn.addEventListener('click', closeModal)
    overlay.addEventListener('click', closeModal)
    
    // Initialize product functionality in modal
    this.initProductGallery()
    this.initQuantityButtons()
  }

  initWishlist () {
    const wishlistBtns = document.querySelectorAll('.wishlist-btn')
    
    wishlistBtns.forEach(btn => {
      btn.addEventListener('click', async (e) => {
        e.preventDefault()
        const productId = btn.dataset.product_id
        await this.toggleWishlist(productId, btn)
      })
    })
  }

  async toggleWishlist (productId, btn) {
    const isInWishlist = btn.classList.contains('in-wishlist')
    const action = isInWishlist ? 'remove' : 'add'
    
    try {
      const response = await fetch(wc_add_to_cart_params.ajax_url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'toggle_wishlist',
          product_id: productId,
          wishlist_action: action
        })
      })
      
      const data = await response.json()
      
      if (data.success) {
        btn.classList.toggle('in-wishlist')
        const icon = btn.querySelector('.wishlist-icon')
        if (icon) {
          icon.classList.toggle('filled')
        }
      }
    } catch (error) {
      console.error('Wishlist error:', error)
    }
  }

  initProductFilters () {
    const filterForms = document.querySelectorAll('.product-filters')
    
    filterForms.forEach(form => {
      const inputs = form.querySelectorAll('input, select')
      
      inputs.forEach(input => {
        input.addEventListener('change', () => {
          this.applyFilters(form)
        })
      })
    })
  }

  applyFilters (form) {
    const formData = new FormData(form)
    const params = new URLSearchParams(formData)
    const url = new URL(window.location)
    
    // Update URL with filter parameters
    for (const [key, value] of params) {
      if (value) {
        url.searchParams.set(key, value)
      } else {
        url.searchParams.delete(key)
      }
    }
    
    // Reload page with filters
    window.location.href = url.toString()
  }

  initCartDrawer () {
    const cartTriggers = document.querySelectorAll('.cart-trigger')
    const cartDrawer = document.querySelector('.cart-drawer')
    
    if (!cartDrawer) return
    
    cartTriggers.forEach(trigger => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault()
        cartDrawer.classList.add('open')
        document.body.classList.add('cart-drawer-open')
      })
    })
    
    const closeBtn = cartDrawer.querySelector('.cart-close')
    const overlay = cartDrawer.querySelector('.cart-overlay')
    
    const closeCart = () => {
      cartDrawer.classList.remove('open')
      document.body.classList.remove('cart-drawer-open')
    }
    
    if (closeBtn) closeBtn.addEventListener('click', closeCart)
    if (overlay) overlay.addEventListener('click', closeCart)
  }

  initProductTabs () {
    const tabContainers = document.querySelectorAll('.woocommerce-tabs')
    
    tabContainers.forEach(container => {
      const tabLinks = container.querySelectorAll('.tabs li a')
      const tabPanels = container.querySelectorAll('.panel')
      
      tabLinks.forEach(link => {
        link.addEventListener('click', (e) => {
          e.preventDefault()
          const targetId = link.getAttribute('href')
          
          // Remove active classes
          tabLinks.forEach(l => l.parentNode.classList.remove('active'))
          tabPanels.forEach(p => p.style.display = 'none')
          
          // Add active class to clicked tab
          link.parentNode.classList.add('active')
          
          // Show target panel
          const targetPanel = document.querySelector(targetId)
          if (targetPanel) {
            targetPanel.style.display = 'block'
          }
        })
      })
    })
  }

  updateCartCount () {
    const counters = document.querySelectorAll('.cart-count')
    
    if (typeof wc_add_to_cart_params !== 'undefined') {
      fetch(wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_cart_count'))
        .then(response => response.json())
        .then(data => {
          counters.forEach(counter => {
            counter.textContent = data.count || 0
          })
        })
    }
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.wooCommerceManager = new WooCommerceManager()
})
