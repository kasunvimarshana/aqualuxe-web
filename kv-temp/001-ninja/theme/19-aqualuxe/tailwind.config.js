/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
    './woocommerce/**/*.php',
    './assets/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0077B6', // Deep blue
          light: '#90E0EF',   // Light blue
          dark: '#03045E',    // Dark blue
        },
        secondary: {
          DEFAULT: '#00B4D8', // Bright blue
          light: '#CAF0F8',   // Very light blue
          dark: '#0096C7',    // Medium blue
        },
        accent: {
          DEFAULT: '#FFD166', // Gold
          light: '#FFECB3',   // Light gold
          dark: '#E6B800',    // Dark gold
        },
        dark: {
          DEFAULT: '#023E8A', // Dark blue for dark mode
          light: '#0077B6',   // Medium blue for dark mode
          lighter: '#0096C7', // Lighter blue for dark mode
          bg: '#0A192F',      // Dark background
          card: '#112240',    // Card background in dark mode
        },
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        mono: ['Roboto Mono', 'monospace'],
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
        'soft': '0 4px 6px -1px rgba(0, 119, 182, 0.1), 0 2px 4px -1px rgba(0, 119, 182, 0.06)',
        'water': '0 4px 20px rgba(0, 180, 216, 0.3)',
      },
      animation: {
        'water-ripple': 'ripple 3s linear infinite',
        'float': 'float 6s ease-in-out infinite',
      },
      keyframes: {
        ripple: {
          '0%': { transform: 'scale(0.8)', opacity: 1 },
          '100%': { transform: 'scale(2)', opacity: 0 },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },
      backgroundImage: {
        'water-pattern': "url('../images/water-pattern.svg')",
        'bubble-pattern': "url('../images/bubble-pattern.svg')",
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
}