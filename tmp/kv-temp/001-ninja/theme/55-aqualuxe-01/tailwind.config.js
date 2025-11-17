/** @type {import('tailwindcss').Config} */
module.exports = {
  // Enable dark mode with class strategy for our toggle functionality
  darkMode: 'class',
  
  // Only process these files
  content: [
    './assets/src/js/**/*.js',
    './**/*.php',
    './templates/**/*.php',
    './modules/**/*.php',
    './core/**/*.php',
    './woocommerce/**/*.php',
  ],
  
  theme: {
    extend: {
      colors: {
        // Brand colors
        primary: {
          DEFAULT: '#0072B5', // Deep aqua blue
          light: '#0090E1',
          dark: '#005A8E',
          50: '#E6F3FA',
          100: '#CCE7F5',
          200: '#99CFEB',
          300: '#66B7E1',
          400: '#339FD7',
          500: '#0087CD',
          600: '#006CA4',
          700: '#00517B',
          800: '#003652',
          900: '#001B29',
        },
        secondary: {
          DEFAULT: '#00B2A9', // Teal accent
          light: '#00D6CC',
          dark: '#008E87',
          50: '#E6FAF9',
          100: '#CCF5F3',
          200: '#99EBE7',
          300: '#66E0DB',
          400: '#33D6CF',
          500: '#00CCC3',
          600: '#00A39C',
          700: '#007A75',
          800: '#00524E',
          900: '#002927',
        },
        accent: {
          DEFAULT: '#F8C630', // Gold accent
          light: '#FFDA6A',
          dark: '#D9A400',
          50: '#FEF9E6',
          100: '#FEF3CC',
          200: '#FCE799',
          300: '#FBDB66',
          400: '#F9CF33',
          500: '#F8C300',
          600: '#C69C00',
          700: '#957500',
          800: '#634E00',
          900: '#322700',
        },
        luxe: {
          DEFAULT: '#2C3E50', // Deep blue-gray
          light: '#3E5871',
          dark: '#1A2530',
          50: '#E9ECF0',
          100: '#D3D9E0',
          200: '#A7B3C2',
          300: '#7B8DA3',
          400: '#4F6785',
          500: '#2C3E50',
          600: '#233240',
          700: '#1A2530',
          800: '#111920',
          900: '#080C10',
        },
        // Utility colors
        success: '#10B981', // Green
        warning: '#F59E0B', // Amber
        danger: '#EF4444',  // Red
        info: '#3B82F6',    // Blue
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
        'soft': '0 2px 15px 0 rgba(0, 0, 0, 0.05)',
        'medium': '0 4px 20px 0 rgba(0, 0, 0, 0.1)',
        'hard': '0 10px 25px 0 rgba(0, 0, 0, 0.15)',
      },
      transitionDuration: {
        '400': '400ms',
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'slide-down': 'slideDown 0.5s ease-out',
        'slide-left': 'slideLeft 0.5s ease-out',
        'slide-right': 'slideRight 0.5s ease-out',
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
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
    },
  },
  plugins: [],
}