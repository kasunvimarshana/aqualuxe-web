module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based approach
  theme: {
    extend: {
      colors: {
        // AquaLuxe brand colors
        primary: {
          DEFAULT: '#0077B6', // Deep aqua blue
          light: '#90E0EF',
          dark: '#03045E',
        },
        secondary: {
          DEFAULT: '#CAF0F8', // Light aqua
          light: '#E0FBFC',
          dark: '#90E0EF',
        },
        accent: {
          DEFAULT: '#FFD700', // Gold for luxury accent
          light: '#FFF8E1',
          dark: '#FFC107',
        },
        dark: {
          DEFAULT: '#023E8A', // Dark blue for dark mode
          light: '#0077B6',
          dark: '#03045E',
        },
        light: {
          DEFAULT: '#F8F9FA',
          dark: '#E9ECEF',
        },
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        mono: ['Roboto Mono', 'monospace'],
      },
      spacing: {
        '128': '32rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
        'glow': '0 0 15px rgba(144, 224, 239, 0.5)',
      },
      typography: {
        DEFAULT: {
          css: {
            color: '#333',
            a: {
              color: '#0077B6',
              '&:hover': {
                color: '#03045E',
              },
            },
          },
        },
      },
      animation: {
        'ripple': 'ripple 1s linear infinite',
        'float': 'float 3s ease-in-out infinite',
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
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
};