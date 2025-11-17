/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './templates/**/*.php',
    './inc/**/*.php',
    './woocommerce/**/*.php',
    './modules/**/*.php',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
  theme: {
    extend: {
      colors: {
        // Primary brand colors
        primary: {
          DEFAULT: '#0077B6', // Deep blue
          50: '#E6F3FB',
          100: '#CCE7F7',
          200: '#99CFEF',
          300: '#66B7E7',
          400: '#339FDF',
          500: '#0077B6', // Base primary
          600: '#006092',
          700: '#00486D',
          800: '#003049',
          900: '#001824',
        },
        // Secondary brand colors
        secondary: {
          DEFAULT: '#00B4D8', // Bright blue
          50: '#E6FAFF',
          100: '#CCF5FF',
          200: '#99EBFF',
          300: '#66E0FF',
          400: '#33D6FF',
          500: '#00B4D8', // Base secondary
          600: '#0090AD',
          700: '#006C82',
          800: '#004857',
          900: '#00242B',
        },
        // Accent colors
        accent: {
          DEFAULT: '#90E0EF', // Light blue
          50: '#F5FCFE',
          100: '#EBF9FD',
          200: '#D7F3FB',
          300: '#C3EDF9',
          400: '#AFE7F7',
          500: '#90E0EF', // Base accent
          600: '#5CD3E8',
          700: '#28C6E1',
          800: '#1A9CB2',
          900: '#13727F',
        },
        // Neutral colors
        neutral: {
          DEFAULT: '#CAF0F8', // Very light blue
          50: '#FAFEFF',
          100: '#F5FDFF',
          200: '#EBFBFF',
          300: '#E0F9FF',
          400: '#D6F7FF',
          500: '#CAF0F8', // Base neutral
          600: '#97E1EF',
          700: '#64D2E6',
          800: '#31C3DD',
          900: '#1A9CB2',
        },
        // Dark mode colors
        dark: {
          DEFAULT: '#023E8A', // Dark blue
          50: '#E6EDF7',
          100: '#CCDBEF',
          200: '#99B7DF',
          300: '#6693CF',
          400: '#336FBF',
          500: '#023E8A', // Base dark
          600: '#01326E',
          700: '#012553',
          800: '#001937',
          900: '#000C1C',
        },
        // Success, warning, error colors
        success: '#2CB67D',
        warning: '#FF9F1C',
        error: '#E63946',
      },
      fontFamily: {
        sans: ['Montserrat', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        serif: ['Playfair Display', 'ui-serif', 'Georgia', 'serif'],
        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
      },
      fontSize: {
        xs: ['0.75rem', { lineHeight: '1rem' }],
        sm: ['0.875rem', { lineHeight: '1.25rem' }],
        base: ['1rem', { lineHeight: '1.5rem' }],
        lg: ['1.125rem', { lineHeight: '1.75rem' }],
        xl: ['1.25rem', { lineHeight: '1.75rem' }],
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
        '4xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 119, 182, 0.1), 0 2px 4px -1px rgba(0, 119, 182, 0.06)',
        'medium': '0 10px 15px -3px rgba(0, 119, 182, 0.1), 0 4px 6px -2px rgba(0, 119, 182, 0.05)',
        'hard': '0 20px 25px -5px rgba(0, 119, 182, 0.1), 0 10px 10px -5px rgba(0, 119, 182, 0.04)',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      animation: {
        'ripple': 'ripple 1s linear infinite',
        'float': 'float 3s ease-in-out infinite',
        'wave': 'wave 2s linear infinite',
      },
      keyframes: {
        ripple: {
          '0%': { transform: 'scale(0.8)', opacity: '1' },
          '100%': { transform: 'scale(2.4)', opacity: '0' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        wave: {
          '0%': { transform: 'rotate(0deg)' },
          '50%': { transform: 'rotate(10deg)' },
          '100%': { transform: 'rotate(0deg)' },
        },
      },
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'water-pattern': "url('/assets/dist/images/water-pattern.svg')",
      },
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark', 'dark-hover', 'dark-group-hover'],
      borderColor: ['dark', 'dark-focus', 'dark-focus-within'],
      textColor: ['dark', 'dark-hover', 'dark-active'],
      opacity: ['dark'],
      display: ['group-hover'],
    },
  },
  plugins: [
    // Add custom Tailwind plugins here if needed
  ],
};