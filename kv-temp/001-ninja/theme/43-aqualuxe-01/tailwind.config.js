module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class',
  theme: {
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
        accent: {
          DEFAULT: '#00a0d2',
          50: '#e6f6fb',
          100: '#ccedf7',
          200: '#99dbef',
          300: '#66c9e7',
          400: '#33b7df',
          500: '#00a0d2',
          600: '#0080a8',
          700: '#00607e',
          800: '#004054',
          900: '#00202a',
        },
        dark: {
          DEFAULT: '#111111',
          50: '#e8e8e8',
          100: '#d1d1d1',
          200: '#a3a3a3',
          300: '#757575',
          400: '#474747',
          500: '#111111',
          600: '#0e0e0e',
          700: '#0a0a0a',
          800: '#070707',
          900: '#030303',
        },
        light: {
          DEFAULT: '#f8f9fa',
          50: '#fefefe',
          100: '#fdfdfd',
          200: '#fbfbfc',
          300: '#f9f9fa',
          400: '#f6f7f9',
          500: '#f8f9fa',
          600: '#c6c7c8',
          700: '#959596',
          800: '#636364',
          900: '#313132',
        },
      },
      fontFamily: {
        sans: ['-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Oxygen-Sans', 'Ubuntu', 'Cantarell', '"Helvetica Neue"', 'sans-serif'],
        serif: ['"Playfair Display"', 'serif'],
        heading: ['"Montserrat"', 'sans-serif'],
      },
      spacing: {
        '128': '32rem',
      },
      maxWidth: {
        'xxs': '16rem',
      },
      minHeight: {
        '10': '2.5rem',
        '20': '5rem',
        '80': '20rem',
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
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.dark.500'),
            a: {
              color: theme('colors.primary.500'),
              '&:hover': {
                color: theme('colors.primary.700'),
              },
            },
            h1: {
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h2: {
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h3: {
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h4: {
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h5: {
              fontFamily: theme('fontFamily.heading').join(', '),
            },
            h6: {
              fontFamily: theme('fontFamily.heading').join(', '),
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.light.500'),
            a: {
              color: theme('colors.primary.400'),
              '&:hover': {
                color: theme('colors.primary.300'),
              },
            },
            h1: {
              color: theme('colors.light.500'),
            },
            h2: {
              color: theme('colors.light.500'),
            },
            h3: {
              color: theme('colors.light.500'),
            },
            h4: {
              color: theme('colors.light.500'),
            },
            h5: {
              color: theme('colors.light.500'),
            },
            h6: {
              color: theme('colors.light.500'),
            },
            strong: {
              color: theme('colors.light.500'),
            },
            blockquote: {
              color: theme('colors.light.300'),
              borderLeftColor: theme('colors.dark.600'),
            },
            code: {
              color: theme('colors.light.500'),
            },
            figcaption: {
              color: theme('colors.light.400'),
            },
            hr: {
              borderColor: theme('colors.dark.600'),
            },
          },
        },
      }),
    },
  },
  variants: {
    extend: {
      backgroundColor: ['dark'],
      textColor: ['dark'],
      borderColor: ['dark'],
      typography: ['dark'],
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ],
  corePlugins: {
    preflight: true,
  },
  important: false,
  purge: {
    enabled: process.env.NODE_ENV === 'production',
    content: [
      './**/*.php',
      './assets/src/js/**/*.js',
    ],
    options: {
      safelist: [
        /^woocommerce-/,
        /^wp-block-/,
        /^has-/,
        /^is-/,
        /^alignfull/,
        /^alignwide/,
      ],
    },
  },
};