module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    container: {
      center: true,
      padding: '1.5rem',
    },
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--color-primary)',
          light: 'var(--color-primary-light)',
          dark: 'var(--color-primary-dark)',
        },
        secondary: {
          DEFAULT: 'var(--color-secondary)',
          light: 'var(--color-secondary-light)',
          dark: 'var(--color-secondary-dark)',
        },
        accent: {
          DEFAULT: 'var(--color-accent)',
          light: 'var(--color-accent-light)',
          dark: 'var(--color-accent-dark)',
        },
        dark: {
          DEFAULT: 'var(--color-dark-background)',
          light: 'var(--color-dark-background-light)',
          text: 'var(--color-dark-text)',
        },
      },
      fontFamily: {
        body: ['var(--font-body)'],
        heading: ['var(--font-heading)'],
      },
      fontSize: {
        'base': 'var(--font-size-base)',
      },
      lineHeight: {
        'base': 'var(--line-height-base)',
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'sm': '0.25rem',
        DEFAULT: '0.375rem',
        'md': '0.5rem',
        'lg': '1rem',
        'xl': '1.5rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 20px 0 rgba(0, 0, 0, 0.05)',
        'medium': '0 6px 30px 0 rgba(0, 0, 0, 0.1)',
        'hard': '0 8px 40px 0 rgba(0, 0, 0, 0.15)',
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
        'auto-fill-150': 'repeat(auto-fill, minmax(150px, 1fr))',
        'auto-fill-200': 'repeat(auto-fill, minmax(200px, 1fr))',
        'auto-fill-250': 'repeat(auto-fill, minmax(250px, 1fr))',
        'auto-fill-300': 'repeat(auto-fill, minmax(300px, 1fr))',
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark', 'group-hover', 'focus-within', 'hover', 'focus', 'active'],
      textColor: ['dark', 'group-hover', 'focus-within', 'hover', 'focus', 'active'],
      borderColor: ['dark', 'group-hover', 'focus-within', 'hover', 'focus', 'active'],
      opacity: ['dark', 'group-hover', 'focus-within', 'hover', 'focus', 'active', 'disabled'],
      boxShadow: ['dark', 'hover', 'focus', 'active'],
      scale: ['group-hover', 'hover', 'focus', 'active'],
      transform: ['group-hover', 'hover', 'focus', 'active'],
      translate: ['group-hover', 'hover', 'focus', 'active'],
      display: ['dark', 'group-hover', 'responsive'],
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false, // Disable Tailwind's reset to avoid conflicts with WordPress styles
  },
}