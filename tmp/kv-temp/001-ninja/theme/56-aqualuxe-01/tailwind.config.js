/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.php',
    './modules/**/*.php',
    './inc/**/*.php',
    './woocommerce/**/*.php',
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
    'wp-embed-responsive',
    'woocommerce',
    'woocommerce-page',
    'woocommerce-js',
    'woocommerce-no-js',
  ],
  darkMode: 'class', // or 'media' based on user preference
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0072B5', // Deep aqua blue
          50: '#E6F3F9',
          100: '#CCE7F3',
          200: '#99CFE7',
          300: '#66B7DB',
          400: '#339FCF',
          500: '#0087C3',
          600: '#0072B5', // Primary brand color
          700: '#005A92',
          800: '#00436E',
          900: '#002C4B',
        },
        secondary: {
          DEFAULT: '#00A896', // Teal accent
          50: '#E6F7F5',
          100: '#CCEFEB',
          200: '#99DFD7',
          300: '#66CFC3',
          400: '#33BFAF',
          500: '#00AF9B',
          600: '#00A896', // Secondary brand color
          700: '#00867A',
          800: '#00645B',
          900: '#00423D',
        },
        accent: {
          DEFAULT: '#F8C630', // Gold accent
          50: '#FEF9E6',
          100: '#FEF3CC',
          200: '#FCE799',
          300: '#FADB66',
          400: '#F9CF33',
          500: '#F8C300',
          600: '#F8C630', // Accent brand color
          700: '#C69E26',
          800: '#95771D',
          900: '#634F13',
        },
        dark: {
          DEFAULT: '#0A1828', // Deep blue-black
          50: '#E6E8EA',
          100: '#CCD1D5',
          200: '#99A3AB',
          300: '#667581',
          400: '#334756',
          500: '#001A2C',
          600: '#0A1828', // Dark brand color
          700: '#081320',
          800: '#060E18',
          900: '#040A10',
        },
        light: {
          DEFAULT: '#F5F7F9', // Off-white
          50: '#FEFEFE',
          100: '#FCFDFD',
          200: '#F9FBFC',
          300: '#F7F9FA',
          400: '#F6F8F9',
          500: '#F5F7F9', // Light brand color
          600: '#DDE3E9',
          700: '#C5CFD9',
          800: '#ADBBCA',
          900: '#95A7BA',
        },
      },
      fontFamily: {
        sans: ['Montserrat', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        serif: ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
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
        '5xl': ['3rem', { lineHeight: '1.16' }],
        '6xl': ['3.75rem', { lineHeight: '1.1' }],
        '7xl': ['4.5rem', { lineHeight: '1.05' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }],
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
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'medium': '0 6px 30px rgba(0, 0, 0, 0.1)',
        'hard': '0 8px 40px rgba(0, 0, 0, 0.15)',
      },
      transitionDuration: {
        '400': '400ms',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.5s ease-in-out',
        'slide-down': 'slideDown 0.5s ease-in-out',
        'slide-left': 'slideLeft 0.5s ease-in-out',
        'slide-right': 'slideRight 0.5s ease-in-out',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideDown: {
          '0%': { transform: 'translateY(-20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        slideLeft: {
          '0%': { transform: 'translateX(20px)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
        slideRight: {
          '0%': { transform: 'translateX(-20px)', opacity: '0' },
          '100%': { transform: 'translateX(0)', opacity: '1' },
        },
      },
    },
  },
  plugins: [],
}