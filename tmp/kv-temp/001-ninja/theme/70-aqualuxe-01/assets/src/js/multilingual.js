/**
 * Multilingual JavaScript
 * @package AquaLuxe
 */

class MultilingualManager {
  constructor () {
    this.init()
  }

  init () {
    this.initLanguageSwitcher()
    this.initRTLSupport()
    this.loadStoredLanguage()
  }

  initLanguageSwitcher () {
    const switchers = document.querySelectorAll('.language-switcher')
    
    switchers.forEach(switcher => {
      const toggle = switcher.querySelector('.lang-toggle')
      const menu = switcher.querySelector('.lang-menu')
      const links = switcher.querySelectorAll('.lang-option')
      
      if (toggle && menu) {
        toggle.addEventListener('click', (e) => {
          e.preventDefault()
          menu.classList.toggle('show')
        })
        
        // Close on outside click
        document.addEventListener('click', (e) => {
          if (!switcher.contains(e.target)) {
            menu.classList.remove('show')
          }
        })
      }
      
      links.forEach(link => {
        link.addEventListener('click', (e) => {
          const lang = link.dataset.lang
          if (lang) {
            this.switchLanguage(lang)
          }
        })
      })
    })
  }

  initRTLSupport () {
    const rtlLanguages = ['ar', 'he', 'fa', 'ur']
    const currentLang = document.documentElement.lang
    
    if (rtlLanguages.includes(currentLang)) {
      document.documentElement.setAttribute('dir', 'rtl')
      document.body.classList.add('rtl')
    }
  }

  switchLanguage (lang) {
    // Store language preference
    localStorage.setItem('aqualuxe_language', lang)
    
    // Update HTML lang attribute
    document.documentElement.lang = lang
    
    // Handle RTL
    const rtlLanguages = ['ar', 'he', 'fa', 'ur']
    if (rtlLanguages.includes(lang)) {
      document.documentElement.setAttribute('dir', 'rtl')
      document.body.classList.add('rtl')
    } else {
      document.documentElement.setAttribute('dir', 'ltr')
      document.body.classList.remove('rtl')
    }
    
    // Emit language change event
    window.dispatchEvent(new CustomEvent('languageChanged', {
      detail: { language: lang }
    }))
    
    // If using WPML or similar, redirect to translated page
    if (window.aqualuxe_multilingual && window.aqualuxe_multilingual.urls[lang]) {
      window.location.href = window.aqualuxe_multilingual.urls[lang]
    }
  }

  loadStoredLanguage () {
    const storedLang = localStorage.getItem('aqualuxe_language')
    if (storedLang && storedLang !== document.documentElement.lang) {
      this.switchLanguage(storedLang)
    }
  }

  getCurrentLanguage () {
    return document.documentElement.lang
  }

  getAvailableLanguages () {
    return window.aqualuxe_multilingual ? window.aqualuxe_multilingual.languages : []
  }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
  window.multilingualManager = new MultilingualManager()
})
