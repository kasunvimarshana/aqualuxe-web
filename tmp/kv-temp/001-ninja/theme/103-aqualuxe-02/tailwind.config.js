/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './templates/**/*.php',
    './inc/**/*.php',
    './modules/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js',
  ],
  theme: {
    extend: {
      // AquaLuxe Brand Colors
      colors: {
        // Primary aqua/blue palette
        aqua: {
          50: '#f0fdfe',
          100: '#ccfbfe',
          200: '#99f6fd',
          300: '#60e9fa',
          400: '#22d1ee',
          500: '#06b6d4', // Primary aqua
          600: '#0894a7',
          700: '#0e7686',
          800: '#155e6b',
          900: '#164e5a',
        },
        // Luxury gold accents
        luxury: {
          50: '#fffef7',
          100: '#fffbd1',
          200: '#fff7a6',
          300: '#ffef70',
          400: '#ffe140',
          500: '#ffd700', // Luxury gold
          600: '#e5b800',
          700: '#c19100',
          800: '#a07000',
          900: '#7a5500',
        },
        // Deep ocean blues for sophistication
        ocean: {
          50: '#f4f9ff',
          100: '#e6f2ff',
          200: '#c7e4ff',
          300: '#9dd0ff',
          400: '#6bb3ff',
          500: '#3b82f6',
          600: '#1e40af',
          700: '#1e3a8a', // Deep ocean
          800: '#1e2563',
          900: '#0f172a',
        },
        // Coral/salmon for warmth
        coral: {
          50: '#fff5f5',
          100: '#ffe6e6',
          200: '#ffcccc',
          300: '#ffa6a6',
          400: '#ff7373',
          500: '#ff4d4d',
          600: '#f43f5e', // Coral accent
          700: '#e11d48',
          800: '#be185d',
          900: '#881337',
        },
      },
      // Custom font families
      fontFamily: {
        'sans': ['Inter', 'system-ui', 'sans-serif'],
        'serif': ['Playfair Display', 'Georgia', 'serif'],
        'mono': ['JetBrains Mono', 'monospace'],
        'heading': ['Playfair Display', 'Georgia', 'serif'],
      },
      // Custom spacing for aquatic-themed designs
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
        '128': '32rem',
      },
      // Custom border radius for modern feel
      borderRadius: {
        '4xl': '2rem',
        '5xl': '2.5rem',
      },
      // Custom shadows for depth
      boxShadow: {
        'aqua': '0 4px 14px 0 rgba(6, 182, 212, 0.39)',
        'luxury': '0 4px 14px 0 rgba(255, 215, 0, 0.39)',
        'ocean': '0 10px 25px 0 rgba(30, 58, 138, 0.3)',
        'coral': '0 4px 14px 0 rgba(244, 63, 94, 0.39)',
      },
      // Animation for micro-interactions
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'wave': 'wave 4s ease-in-out infinite',
        'bubble': 'bubble 8s ease-in-out infinite',
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.3s ease-out',
        'scale-in': 'scaleIn 0.2s ease-out',
      },
      // Custom keyframes
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        wave: {
          '0%, 100%': { transform: 'rotate(0deg)' },
          '25%': { transform: 'rotate(5deg)' },
          '75%': { transform: 'rotate(-5deg)' },
        },
        bubble: {
          '0%': { transform: 'translateY(0px) scale(1)' },
          '50%': { transform: 'translateY(-20px) scale(1.1)' },
          '100%': { transform: 'translateY(0px) scale(1)' },
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        slideUp: {
          '0%': { transform: 'translateY(100%)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' },
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' },
        },
      },
      // Custom typography scales
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
        '7xl': ['4.5rem', { lineHeight: '1' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }],
      },
      // Custom grid template columns
      gridTemplateColumns: {
        'auto-fit': 'repeat(auto-fit, minmax(250px, 1fr))',
        'auto-fill': 'repeat(auto-fill, minmax(250px, 1fr))',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    // Custom plugin for AquaLuxe utilities
    function({ addUtilities, addComponents, theme }) {
      // Custom utilities for aquatic effects
      const newUtilities = {
        '.text-gradient-aqua': {
          background: 'linear-gradient(135deg, #06b6d4, #3b82f6)',
          '-webkit-background-clip': 'text',
          '-webkit-text-fill-color': 'transparent',
          'background-clip': 'text',
        },
        '.text-gradient-luxury': {
          background: 'linear-gradient(135deg, #ffd700, #f59e0b)',
          '-webkit-background-clip': 'text',
          '-webkit-text-fill-color': 'transparent',
          'background-clip': 'text',
        },
        '.bg-gradient-aqua': {
          background: 'linear-gradient(135deg, #06b6d4, #3b82f6)',
        },
        '.bg-gradient-ocean': {
          background: 'linear-gradient(135deg, #1e3a8a, #0f172a)',
        },
        '.backdrop-blur-aqua': {
          'backdrop-filter': 'blur(8px)',
          'background-color': 'rgba(6, 182, 212, 0.1)',
        },
      };

      // Custom components for common patterns
      const newComponents = {
        '.btn-primary': {
          '@apply bg-aqua-500 hover:bg-aqua-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 shadow-aqua hover:shadow-lg': {},
        },
        '.btn-secondary': {
          '@apply bg-luxury-500 hover:bg-luxury-600 text-ocean-900 font-medium py-3 px-6 rounded-lg transition-colors duration-200 shadow-luxury hover:shadow-lg': {},
        },
        '.card-aqua': {
          '@apply bg-white rounded-2xl shadow-lg border border-aqua-100 overflow-hidden': {},
        },
        '.input-aqua': {
          '@apply border-aqua-200 focus:border-aqua-500 focus:ring-aqua-500 rounded-lg': {},
        },
      };

      addUtilities(newUtilities);
      addComponents(newComponents);
    },
  ],
  // Dark mode configuration
  darkMode: 'class',
}