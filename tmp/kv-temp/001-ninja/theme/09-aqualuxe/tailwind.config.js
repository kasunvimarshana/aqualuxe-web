/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './src/**/*.js',
  ],
  safelist: [
    'bg-primary',
    'bg-secondary',
    'text-primary',
    'text-secondary',
    'border-primary',
    'border-secondary',
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--aqualuxe-primary-color)',
        secondary: 'var(--aqualuxe-secondary-color)',
        accent: 'var(--aqualuxe-accent-color)',
      },
      fontFamily: {
        heading: 'var(--aqualuxe-heading-font)',
        body: 'var(--aqualuxe-body-font)',
      },
      container: {
        center: true,
        padding: '1rem',
      },
      typography: {
        DEFAULT: {
          css: {
            color: '#333',
            a: {
              color: 'var(--aqualuxe-primary-color)',
              '&:hover': {
                color: 'var(--aqualuxe-secondary-color)',
              },
            },
            h1: {
              fontFamily: 'var(--aqualuxe-heading-font)',
              color: '#111',
            },
            h2: {
              fontFamily: 'var(--aqualuxe-heading-font)',
              color: '#111',
            },
            h3: {
              fontFamily: 'var(--aqualuxe-heading-font)',
              color: '#111',
            },
            h4: {
              fontFamily: 'var(--aqualuxe-heading-font)',
              color: '#111',
            },
          },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
  darkMode: 'class',
}