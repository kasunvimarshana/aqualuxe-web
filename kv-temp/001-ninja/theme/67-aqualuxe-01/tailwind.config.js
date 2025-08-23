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
          light: 'var(--aqualuxe-primary-color-light)',
          dark: 'var(--aqualuxe-primary-color-dark)',
        },
        secondary: {
          DEFAULT: 'var(--aqualuxe-secondary-color)',
          light: 'var(--aqualuxe-secondary-color-light)',
          dark: 'var(--aqualuxe-secondary-color-dark)',
        },
        accent: {
          DEFAULT: 'var(--aqualuxe-accent-color)',
          light: 'var(--aqualuxe-accent-color-light)',
          dark: 'var(--aqualuxe-accent-color-dark)',
        },
        dark: {
          DEFAULT: 'var(--aqualuxe-dark-background-color)',
          light: 'var(--aqualuxe-dark-background-color-light)',
          text: 'var(--aqualuxe-dark-text-color)',
        },
      },
      fontFamily: {
        body: ['var(--aqualuxe-body-font-family)'],
        heading: ['var(--aqualuxe-heading-font-family)'],
      },
      fontSize: {
        base: 'var(--aqualuxe-body-font-size)',
      },
      lineHeight: {
        body: 'var(--aqualuxe-line-height)',
        heading: 'var(--aqualuxe-heading-line-height)',
      },
      maxWidth: {
        container: 'var(--aqualuxe-container-width)',
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'medium': '0 6px 30px rgba(0, 0, 0, 0.1)',
        'hard': '0 8px 40px rgba(0, 0, 0, 0.15)',
      },
      transitionDuration: {
        '400': '400ms',
      },
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
      gridTemplateColumns: {
        'auto-fill-100': 'repeat(auto-fill, minmax(100px, 1fr))',
        'auto-fill-200': 'repeat(auto-fill, minmax(200px, 1fr))',
        'auto-fill-300': 'repeat(auto-fill, minmax(300px, 1fr))',
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
      backgroundColor: ['dark', 'group-hover', 'focus-within', 'hover'],
      textColor: ['dark', 'group-hover', 'focus-within', 'hover'],
      borderColor: ['dark', 'group-hover', 'focus-within', 'hover'],
      opacity: ['dark', 'group-hover'],
      boxShadow: ['dark', 'hover', 'focus'],
      display: ['dark', 'group-hover'],
      transform: ['hover', 'focus', 'group-hover'],
      scale: ['hover', 'focus', 'group-hover'],
      translate: ['hover', 'focus', 'group-hover'],
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false, // Disable Tailwind's base styles
  },
};