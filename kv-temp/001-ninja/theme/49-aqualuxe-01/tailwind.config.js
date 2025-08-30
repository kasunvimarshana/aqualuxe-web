/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './templates/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // or 'media' or 'class'
  theme: {
    container: {
      center: true,
      padding: '1rem',
    },
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0073aa',
          50: '#e6f3f8',
          100: '#cce7f1',
          200: '#99cfe3',
          300: '#66b7d5',
          400: '#339fc7',
          500: '#0073aa',
          600: '#005c88',
          700: '#004566',
          800: '#002e44',
          900: '#001722',
        },
        secondary: {
          DEFAULT: '#005177',
          50: '#e6eef2',
          100: '#ccdde5',
          200: '#99bbcb',
          300: '#6699b1',
          400: '#337797',
          500: '#005177',
          600: '#00415f',
          700: '#003147',
          800: '#00202f',
          900: '#001017',
        },
        'dark-blue': {
          DEFAULT: '#1e3a8a',
          50: '#e9edf5',
          100: '#d3dbeb',
          200: '#a7b7d7',
          300: '#7b93c3',
          400: '#4f6faf',
          500: '#1e3a8a',
          600: '#182e6e',
          700: '#122353',
          800: '#0c1737',
          900: '#060c1c',
        },
        'light-blue': {
          DEFAULT: '#bfdbfe',
          50: '#f8fbff',
          100: '#f1f7fe',
          200: '#e3effd',
          300: '#d5e7fc',
          400: '#c7dffb',
          500: '#bfdbfe',
          600: '#99afcb',
          700: '#738398',
          800: '#4c5866',
          900: '#262c33',
        },
        dark: {
          DEFAULT: '#111827',
          50: '#e8e9ec',
          100: '#d1d3d9',
          200: '#a3a7b3',
          300: '#757b8d',
          400: '#474f67',
          500: '#111827',
          600: '#0e131f',
          700: '#0a0e17',
          800: '#07090f',
          900: '#030508',
        },
        light: {
          DEFAULT: '#f9fafb',
          50: '#fefefe',
          100: '#fcfdfe',
          200: '#f9fbfc',
          300: '#f7f9fa',
          400: '#f4f7f9',
          500: '#f9fafb',
          600: '#c7c8c9',
          700: '#959697',
          800: '#636464',
          900: '#313232',
        },
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
        serif: ['Playfair Display', 'ui-serif', 'Georgia', 'Cambria', 'Times New Roman', 'Times', 'serif'],
        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
      },
      spacing: {
        '72': '18rem',
        '84': '21rem',
        '96': '24rem',
        '128': '32rem',
      },
      maxWidth: {
        'xxs': '16rem',
        '8xl': '88rem',
        '9xl': '96rem',
      },
      zIndex: {
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'inner-lg': 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.06)',
        'inner-xl': 'inset 0 4px 6px 0 rgba(0, 0, 0, 0.1)',
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.dark.DEFAULT'),
            a: {
              color: theme('colors.primary.DEFAULT'),
              '&:hover': {
                color: theme('colors.primary.600'),
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
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h4: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.serif').join(', '),
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.light.DEFAULT'),
            a: {
              color: theme('colors.light-blue.400'),
              '&:hover': {
                color: theme('colors.light-blue.300'),
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
            h4: {
              color: theme('colors.light.DEFAULT'),
            },
            strong: {
              color: theme('colors.light.DEFAULT'),
            },
            blockquote: {
              color: theme('colors.light.300'),
              borderLeftColor: theme('colors.dark.700'),
            },
            code: {
              color: theme('colors.light.DEFAULT'),
            },
          },
        },
      }),
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark', 'dark-hover', 'dark-group-hover', 'dark-even', 'dark-odd'],
      borderColor: ['dark', 'dark-focus', 'dark-focus-within'],
      textColor: ['dark', 'dark-hover', 'dark-active'],
      typography: ['dark'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
  ],
};