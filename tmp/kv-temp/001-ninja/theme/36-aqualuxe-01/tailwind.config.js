/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './templates/**/*.php',
    './inc/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
  theme: {
    extend: {
      colors: {
        // AquaLuxe color palette
        'aqua': {
          50: '#f0fdfa',
          100: '#ccfbf1',
          200: '#99f6e4',
          300: '#5eead4',
          400: '#2dd4bf',
          500: '#14b8a6',
          600: '#0d9488',
          700: '#0f766e',
          800: '#115e59',
          900: '#134e4a',
          950: '#042f2e',
        },
        'luxe': {
          50: '#fdf8f6',
          100: '#f2e8e5',
          200: '#eaddd7',
          300: '#e0cec7',
          400: '#d2bab0',
          500: '#bfa094',
          600: '#a18072',
          700: '#977669',
          800: '#846358',
          900: '#43302b',
          950: '#24190f',
        },
      },
      fontFamily: {
        'sans': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        'serif': ['Playfair Display', 'ui-serif', 'Georgia', 'Cambria', 'Times New Roman', 'Times', 'serif'],
        'mono': ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'luxe': '0 10px 25px -5px rgba(191, 160, 148, 0.25), 0 8px 10px -6px rgba(191, 160, 148, 0.1)',
        'aqua': '0 10px 25px -5px rgba(20, 184, 166, 0.25), 0 8px 10px -6px rgba(20, 184, 166, 0.1)',
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.gray.800'),
            a: {
              color: theme('colors.aqua.600'),
              '&:hover': {
                color: theme('colors.aqua.800'),
              },
            },
            h1: {
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.gray.900'),
            },
            h2: {
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.gray.900'),
            },
            h3: {
              fontFamily: theme('fontFamily.serif').join(', '),
              color: theme('colors.gray.900'),
            },
            h4: {
              color: theme('colors.gray.900'),
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.gray.300'),
            a: {
              color: theme('colors.aqua.400'),
              '&:hover': {
                color: theme('colors.aqua.300'),
              },
            },
            h1: {
              color: theme('colors.gray.100'),
            },
            h2: {
              color: theme('colors.gray.100'),
            },
            h3: {
              color: theme('colors.gray.100'),
            },
            h4: {
              color: theme('colors.gray.100'),
            },
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
}