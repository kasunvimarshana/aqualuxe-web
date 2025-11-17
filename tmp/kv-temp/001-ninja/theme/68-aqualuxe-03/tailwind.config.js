/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--aqualuxe-primary-color)',
          light: 'color-mix(in srgb, var(--aqualuxe-primary-color), white 30%)',
          dark: 'color-mix(in srgb, var(--aqualuxe-primary-color), black 20%)',
        },
        secondary: {
          DEFAULT: 'var(--aqualuxe-secondary-color)',
          light: 'color-mix(in srgb, var(--aqualuxe-secondary-color), white 30%)',
          dark: 'color-mix(in srgb, var(--aqualuxe-secondary-color), black 20%)',
        },
        accent: {
          DEFAULT: 'var(--aqualuxe-accent-color)',
          light: 'color-mix(in srgb, var(--aqualuxe-accent-color), white 30%)',
          dark: 'color-mix(in srgb, var(--aqualuxe-accent-color), black 20%)',
        },
        dark: {
          DEFAULT: 'var(--aqualuxe-dark-background-color)',
          light: 'color-mix(in srgb, var(--aqualuxe-dark-background-color), white 10%)',
          lighter: 'color-mix(in srgb, var(--aqualuxe-dark-background-color), white 20%)',
        },
      },
      fontFamily: {
        body: 'var(--aqualuxe-body-font)',
        heading: 'var(--aqualuxe-heading-font)',
      },
      fontSize: {
        base: 'var(--aqualuxe-font-size-base)',
      },
      lineHeight: {
        base: 'var(--aqualuxe-line-height)',
      },
      fontWeight: {
        base: 'var(--aqualuxe-font-weight)',
        heading: 'var(--aqualuxe-heading-font-weight)',
      },
      maxWidth: {
        container: 'var(--aqualuxe-container-width)',
      },
      spacing: {
        'sidebar': 'var(--aqualuxe-sidebar-width)',
        'content': 'var(--aqualuxe-content-width)',
      },
      boxShadow: {
        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
        'header': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      zIndex: {
        'header': 100,
        'modal': 200,
        'dropdown': 50,
      },
      gridTemplateColumns: {
        'footer-4': 'minmax(0, 2fr) repeat(3, minmax(0, 1fr))',
        'footer-3': 'minmax(0, 2fr) repeat(2, minmax(0, 1fr))',
        'footer-2': 'repeat(2, minmax(0, 1fr))',
      },
    },
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark', 'group-hover', 'focus-within'],
      textColor: ['dark', 'group-hover', 'focus-within'],
      borderColor: ['dark', 'group-hover', 'focus-within'],
      opacity: ['dark', 'group-hover'],
      boxShadow: ['dark', 'hover', 'focus'],
      display: ['dark', 'group-hover'],
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false, // Disable Tailwind's base styles
  },
}