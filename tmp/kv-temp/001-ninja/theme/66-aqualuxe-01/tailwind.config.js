/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  safelist: [
    'rtl',
    'home',
    'blog',
    'archive',
    'single',
    'search',
    'error404',
    'admin-bar',
    'logged-in',
    'wp-embed-responsive',
  ],
  darkMode: 'class',
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
          100: '#f7f7f7',
          200: '#e6e6e6',
          300: '#d5d5d5',
          400: '#b4b4b4',
          500: '#929292',
          600: '#717171',
          700: '#505050',
          800: '#2e2e2e',
          900: '#1a1a1a',
        },
      },
      fontFamily: {
        body: 'var(--aqualuxe-body-font)',
        heading: 'var(--aqualuxe-heading-font)',
      },
      fontSize: {
        'base': 'var(--aqualuxe-body-font-size)',
        'xs': 'calc(var(--aqualuxe-body-font-size) * 0.75)',
        'sm': 'calc(var(--aqualuxe-body-font-size) * 0.875)',
        'lg': 'calc(var(--aqualuxe-body-font-size) * 1.125)',
        'xl': 'calc(var(--aqualuxe-body-font-size) * 1.25)',
        '2xl': 'calc(var(--aqualuxe-body-font-size) * 1.5)',
        '3xl': 'calc(var(--aqualuxe-body-font-size) * 1.875)',
        '4xl': 'calc(var(--aqualuxe-body-font-size) * 2.25)',
        '5xl': 'calc(var(--aqualuxe-body-font-size) * 3)',
        '6xl': 'calc(var(--aqualuxe-body-font-size) * 4)',
      },
      lineHeight: {
        'body': 'var(--aqualuxe-line-height)',
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
          lg: '4rem',
          xl: '5rem',
          '2xl': '6rem',
        },
        screens: {
          sm: '640px',
          md: '768px',
          lg: '1024px',
          xl: '1280px',
          '2xl': 'var(--aqualuxe-container-width)',
        },
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      animation: {
        'fade-in': 'fadeIn 0.3s ease-in-out',
        'fade-out': 'fadeOut 0.3s ease-in-out',
        'slide-in': 'slideIn 0.3s ease-in-out',
        'slide-out': 'slideOut 0.3s ease-in-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeOut: {
          '0%': { opacity: '1' },
          '100%': { opacity: '0' },
        },
        slideIn: {
          '0%': { transform: 'translateY(-10px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideOut: {
          '0%': { transform: 'translateY(0)', opacity: '1' },
          '100%': { transform: 'translateY(-10px)', opacity: '0' },
        },
      },
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
    },
  },
  plugins: [
    function({ addComponents }) {
      addComponents({
        '.container': {
          maxWidth: '100%',
          '@screen sm': {
            maxWidth: '640px',
          },
          '@screen md': {
            maxWidth: '768px',
          },
          '@screen lg': {
            maxWidth: '1024px',
          },
          '@screen xl': {
            maxWidth: '1280px',
          },
          '@screen 2xl': {
            maxWidth: 'var(--aqualuxe-container-width)',
          },
        }
      })
    },
  ],
}