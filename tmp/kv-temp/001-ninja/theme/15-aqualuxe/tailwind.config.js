/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
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
          DEFAULT: '#FFD700', // Gold accent for luxury feel
          light: '#FFF1AA',
          dark: '#B8860B',
        },
        dark: {
          DEFAULT: '#0A1128', // Dark blue for dark mode
          light: '#1A2A52',
          lighter: '#2A3A72',
        },
        light: {
          DEFAULT: '#F8F9FA',
          dark: '#E9ECEF',
          darker: '#DEE2E6',
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
        'soft': '0 4px 20px 0 rgba(0, 0, 0, 0.05)',
        'elegant': '0 10px 30px 0 rgba(0, 0, 0, 0.1)',
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
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.dark.DEFAULT'),
            },
            h2: {
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.dark.DEFAULT'),
            },
            h3: {
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.dark.DEFAULT'),
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.light.DEFAULT'),
            a: {
              color: theme('colors.secondary.light'),
              '&:hover': {
                color: theme('colors.secondary.DEFAULT'),
              },
            },
            h1: {
              color: theme('colors.light.DEFAULT'),
            },
            h2: {
              color: theme('colors.light.DEFAULT'),
            },
            h3: {
              color: theme('colors.light.DEFAULT'),
            },
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
}