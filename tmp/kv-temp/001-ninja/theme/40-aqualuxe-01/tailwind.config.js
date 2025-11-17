/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // or 'media' if you want to respect system preferences
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--primary-color, #0073aa)',
          light: 'var(--primary-color-light, #00a0d2)',
          dark: 'var(--primary-color-dark, #005177)',
        },
        secondary: {
          DEFAULT: 'var(--secondary-color, #23282d)',
          light: 'var(--secondary-color-light, #40464d)',
          dark: 'var(--secondary-color-dark, #191e23)',
        },
        accent: {
          DEFAULT: 'var(--accent-color, #00a0d2)',
          light: 'var(--accent-color-light, #33b3db)',
          dark: 'var(--accent-color-dark, #007cad)',
        },
        aqua: {
          light: '#e6f7fb',
          DEFAULT: '#00a0d2',
          dark: '#005177',
        },
        luxe: {
          light: '#f7f5f0',
          DEFAULT: '#d4af37',
          dark: '#8c7223',
        },
      },
      fontFamily: {
        sans: ['var(--body-font, system-ui, sans-serif)'],
        heading: ['var(--heading-font, system-ui, sans-serif)'],
      },
      fontSize: {
        'base': 'var(--body-font-size, 16px)',
      },
      fontWeight: {
        heading: 'var(--heading-font-weight, 700)',
      },
      maxWidth: {
        'container': 'var(--container-width, 1200px)',
      },
      spacing: {
        'header': '80px',
        'header-sticky': '60px',
      },
      zIndex: {
        'header': '100',
        'modal': '200',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      boxShadow: {
        'header': '0 2px 10px rgba(0, 0, 0, 0.1)',
        'card': '0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1)',
        'card-hover': '0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05)',
      },
      gridTemplateColumns: {
        'footer-4': 'minmax(0, 2fr) repeat(3, minmax(0, 1fr))',
        'footer-3': 'repeat(3, minmax(0, 1fr))',
        'footer-2': 'repeat(2, minmax(0, 1fr))',
        'footer-1': 'minmax(0, 1fr)',
      },
    },
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
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
    },
  },
  variants: {
    extend: {
      opacity: ['disabled'],
      backgroundColor: ['dark', 'dark-hover'],
      textColor: ['dark', 'dark-hover'],
      borderColor: ['dark', 'dark-focus'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}