/**
 * Dark Mode JavaScript
 * @package AquaLuxe
 */

class DarkModeManager {
  constructor () {
    this.init()
  }

  init () {
    this.loadTheme()
    this.initToggle()
    this.initSystemPreference()
  }

  loadTheme () {
    const savedTheme = localStorage.getItem('aqualuxe_theme')
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches
    const theme = savedTheme || (prefersDark ? 'dark' : 'light')
    
    this.setTheme(theme)
  }

  initToggle () {
    const toggles = document.querySelectorAll('.dark-mode-toggle')
    
    toggles.forEach(toggle => {
      toggle.addEventListener('click', () => {
        this.toggleTheme()
      })
    })
  }

  initSystemPreference () {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
    
    mediaQuery.addEventListener('change', (e) => {
      if (!localStorage.getItem('aqualuxe_theme')) {
        this.setTheme(e.matches ? 'dark' : 'light')
      }
    })
  }

  setTheme (theme) {
    document.documentElement.setAttribute('data-theme', theme)
    this.updateToggleState(theme)
    
    // Emit custom event
    window.dispatchEvent(new CustomEvent('themeChanged', {
      detail: { theme }
    }))
  }

  toggleTheme () {
    const currentTheme = document.documentElement.getAttribute('data-theme')
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark'
    
    this.setTheme(newTheme)
    localStorage.setItem('aqualuxe_theme', newTheme)
  }

  updateToggleState (theme) {
    const toggles = document.querySelectorAll('.dark-mode-toggle')
    
    toggles.forEach(toggle => {
      toggle.setAttribute('aria-pressed', theme === 'dark')
      toggle.classList.toggle('active', theme === 'dark')
    })
  }

  getCurrentTheme () {
    return document.documentElement.getAttribute('data-theme')
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.darkModeManager = new DarkModeManager()
})
