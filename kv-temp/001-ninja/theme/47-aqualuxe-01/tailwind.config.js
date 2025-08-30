/** @type {import('tailwindcss').Config} */
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
          DEFAULT: '#00B4D8', // Bright aqua
          light: '#CAF0F8',
          dark: '#0096C7',
        },
        accent: {
          DEFAULT: '#FFD700', // Gold for luxury accent
          light: '#FFF8E1',
          dark: '#FFC400',
        },
        dark: {
          DEFAULT: '#023E8A', // Deep blue for dark mode
          light: '#0077B6',
          darker: '#03045E',
        },
        light: {
          DEFAULT: '#F0F9FF', // Light blue tint
          dark: '#E1F5FE',
        }
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        mono: ['JetBrains Mono', 'monospace'],
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
        'soft': '0 4px 20px rgba(0, 119, 182, 0.1)',
        'glow': '0 0 15px rgba(0, 180, 216, 0.5)',
      },
      animation: {
        'ripple': 'ripple 1s linear infinite',
        'float': 'float 3s ease-in-out infinite',
      },
      keyframes: {
        ripple: {
          '0%': { transform: 'scale(0.8)', opacity: '1' },
          '100%': { transform: 'scale(2)', opacity: '0' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.dark.DEFAULT'),
            a: {
              color: theme('colors.primary.DEFAULT'),
              '&:hover': {
                color: theme('colors.primary.dark'),
              },
            },
            h1: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h2: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h3: {
              color: theme('colors.dark.DEFAULT'),
            },
          },
        },
      }),
    },
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}