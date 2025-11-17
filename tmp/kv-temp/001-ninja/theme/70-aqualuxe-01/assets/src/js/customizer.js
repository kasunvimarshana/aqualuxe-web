/**
 * Customizer JavaScript
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

/* global wp */

document.addEventListener('DOMContentLoaded', function () {
  // Initialize customizer controls
  initCustomizerControls()
  
  // Handle dependency management
  initDependencyManagement()
  
  // Initialize range sliders
  initRangeSliders()
  
  // Initialize toggle controls
  initToggleControls()
})

/**
 * Initialize customizer controls
 */
function initCustomizerControls () {
  const controls = document.querySelectorAll('.customize-control')
  
  controls.forEach(control => {
    const controlType = control.dataset.type
    
    switch (controlType) {
      case 'range':
        initRangeControl(control)
        break
      case 'color':
        initColorControl(control)
        break
      case 'typography':
        initTypographyControl(control)
        break
      case 'dimensions':
        initDimensionsControl(control)
        break
    }
  })
}

/**
 * Initialize range control
 */
function initRangeControl (control) {
  const slider = control.querySelector('input[type="range"]')
  const input = control.querySelector('input[type="number"]')
  
  if (slider && input) {
    slider.addEventListener('input', function () {
      input.value = this.value
      triggerChange(input)
    })
    
    input.addEventListener('input', function () {
      slider.value = this.value
      triggerChange(this)
    })
  }
}

/**
 * Initialize color control
 */
function initColorControl (control) {
  const colorInput = control.querySelector('.color-picker-hex')
  
  if (colorInput && typeof wp !== 'undefined' && wp.customize) {
    wp.customize.ColorControl.prototype.initWPColorPicker.call({
      container: control,
      setting: function () {
        return {
          set: function (value) {
            triggerChange(colorInput, value)
          }
        }
      }
    })
  }
}

/**
 * Initialize typography control
 */
function initTypographyControl (control) {
  const selects = control.querySelectorAll('select')
  const inputs = control.querySelectorAll('input')
  
  selects.forEach(select => {
    select.addEventListener('change', function () {
      updateTypographyValue(control)
    })
  })
  
  inputs.forEach(input => {
    input.addEventListener('input', function () {
      updateTypographyValue(control)
    })
  })
}

/**
 * Update typography control value
 */
function updateTypographyValue (control) {
  const hiddenInput = control.querySelector('.typography-hidden-value')
  if (!hiddenInput) return
  
  const values = {}
  const inputs = control.querySelectorAll('select, input[type="number"], input[type="text"]')
  
  inputs.forEach(input => {
    if (input !== hiddenInput) {
      values[input.name] = input.value
    }
  })
  
  hiddenInput.value = JSON.stringify(values)
  triggerChange(hiddenInput)
}

/**
 * Initialize dimensions control
 */
function initDimensionsControl (control) {
  const inputs = control.querySelectorAll('.dimension-input')
  const linkedBtn = control.querySelector('.dimension-linked')
  let isLinked = linkedBtn ? linkedBtn.classList.contains('linked') : false
  
  if (linkedBtn) {
    linkedBtn.addEventListener('click', function () {
      isLinked = !isLinked
      this.classList.toggle('linked', isLinked)
      updateDimensionsValue(control)
    })
  }
  
  inputs.forEach(input => {
    input.addEventListener('input', function () {
      if (isLinked) {
        inputs.forEach(otherInput => {
          if (otherInput !== this) {
            otherInput.value = this.value
          }
        })
      }
      updateDimensionsValue(control)
    })
  })
}

/**
 * Update dimensions control value
 */
function updateDimensionsValue (control) {
  const hiddenInput = control.querySelector('.dimensions-hidden-value')
  if (!hiddenInput) return
  
  const values = {}
  const inputs = control.querySelectorAll('.dimension-input')
  
  inputs.forEach(input => {
    values[input.dataset.dimension] = input.value
  })
  
  hiddenInput.value = JSON.stringify(values)
  triggerChange(hiddenInput)
}

/**
 * Initialize dependency management
 */
function initDependencyManagement () {
  const controls = document.querySelectorAll('[data-dependency]')
  
  controls.forEach(control => {
    const dependency = JSON.parse(control.dataset.dependency)
    const dependentControl = document.querySelector(`[data-customize-setting-link="${dependency.setting}"]`)
    
    if (dependentControl) {
      const checkDependency = () => {
        const value = dependentControl.value
        const show = dependency.operator === '==' ? value === dependency.value : value !== dependency.value
        control.style.display = show ? 'block' : 'none'
      }
      
      dependentControl.addEventListener('change', checkDependency)
      checkDependency() // Initial check
    }
  })
}

/**
 * Initialize range sliders
 */
function initRangeSliders () {
  const ranges = document.querySelectorAll('.range-slider')
  
  ranges.forEach(range => {
    const input = range.querySelector('input[type="range"]')
    const output = range.querySelector('.range-value')
    
    if (input && output) {
      const updateOutput = () => {
        output.textContent = input.value + (input.dataset.unit || '')
      }
      
      input.addEventListener('input', updateOutput)
      updateOutput() // Initial update
    }
  })
}

/**
 * Initialize toggle controls
 */
function initToggleControls () {
  const toggles = document.querySelectorAll('.toggle-control')
  
  toggles.forEach(toggle => {
    const input = toggle.querySelector('input[type="checkbox"]')
    const slider = toggle.querySelector('.toggle-slider')
    
    if (input && slider) {
      slider.addEventListener('click', function () {
        input.checked = !input.checked
        triggerChange(input)
      })
    }
  })
}

/**
 * Trigger change event
 */
function triggerChange (element, value) {
  if (value !== undefined) {
    element.value = value
  }
  
  const event = new Event('change', { bubbles: true })
  element.dispatchEvent(event)
}
