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
    'page',
    'search',
    'admin-bar',
    'logged-in',
    'woocommerce',
    'woocommerce-page',
    'woocommerce-active',
    'woocommerce-no-js',
    'dark-mode',
    'light-mode'
  ],
  darkMode: 'class', // or 'media' for media-query based dark mode
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0B6E99',
          50: '#E6F3F8',
          100: '#CCE7F1',
          200: '#99CFE3',
          300: '#66B7D5',
          400: '#339FC7',
          500: '#0B6E99',
          600: '#09587A',
          700: '#07425C',
          800: '#042C3D',
          900: '#02161F',
        },
        secondary: {
          DEFAULT: '#1A3C40',
          50: '#E8EEEE',
          100: '#D1DDDE',
          200: '#A3BBBD',
          300: '#75999C',
          400: '#47777B',
          500: '#1A3C40',
          600: '#153033',
          700: '#102426',
          800: '#0A181A',
          900: '#050C0D',
        },
        accent: {
          DEFAULT: '#D4AF37',
          50: '#FCF8EC',
          100: '#F9F1D9',
          200: '#F3E3B3',
          300: '#EDD58D',
          400: '#E7C767',
          500: '#D4AF37',
          600: '#A98C2C',
          700: '#7F6921',
          800: '#544616',
          900: '#2A230B',
        },
        success: '#10B981',
        warning: '#F59E0B',
        danger: '#EF4444',
        info: '#3B82F6',
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        mono: ['JetBrains Mono', 'monospace'],
      },
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'sm': '0.125rem',
        DEFAULT: '0.25rem',
        'md': '0.375rem',
        'lg': '0.5rem',
        'xl': '0.75rem',
        '2xl': '1rem',
        '3xl': '1.5rem',
        'full': '9999px',
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'medium': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
        'hard': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      zIndex: {
        '-10': '-10',
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
      typography: {
        DEFAULT: {
          css: {
            maxWidth: '65ch',
            color: 'inherit',
            a: {
              color: '#0B6E99',
              '&:hover': {
                color: '#09587A',
              },
            },
            strong: {
              color: 'inherit',
            },
            h1: {
              color: 'inherit',
            },
            h2: {
              color: 'inherit',
            },
            h3: {
              color: 'inherit',
            },
            h4: {
              color: 'inherit',
            },
            code: {
              color: 'inherit',
            },
          },
        },
      },
    },
  },
  plugins: [],
}