/** @type {import('tailwindcss').Config} */
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
          DEFAULT: '#0077b6',
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0077b6',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49',
        },
        secondary: {
          DEFAULT: '#00b4d8',
          50: '#f0fdff',
          100: '#e0fafe',
          200: '#baf3fc',
          300: '#7ee7f9',
          400: '#3bd2f4',
          500: '#14b4e4',
          600: '#0092c3',
          700: '#00749e',
          800: '#006282',
          900: '#07516c',
          950: '#03344a',
        },
        accent: {
          DEFAULT: '#48cae4',
          50: '#f0fbfc',
          100: '#e0f7fa',
          200: '#b8edf3',
          300: '#83dfe9',
          400: '#48cae4',
          500: '#22b0d1',
          600: '#128cb1',
          700: '#117090',
          800: '#145b76',
          900: '#164c64',
          950: '#0b3242',
        },
        dark: {
          DEFAULT: '#03045e',
          50: '#eeeeff',
          100: '#e0e0ff',
          200: '#c7c7fe',
          300: '#a3a1fc',
          400: '#817af9',
          500: '#6a5cf2',
          600: '#5a3ee6',
          700: '#4c2ecc',
          800: '#3e28a5',
          900: '#03045e',
          950: '#1c1650',
        },
        light: {
          DEFAULT: '#caf0f8',
          50: '#f2fcff',
          100: '#e6f9fe',
          200: '#caf0f8',
          300: '#9de2f0',
          400: '#65cbe3',
          500: '#3aafd0',
          600: '#288eb1',
          700: '#237290',
          800: '#225e77',
          900: '#214f64',
          950: '#0f3342',
        },
        gold: {
          DEFAULT: '#d4af37',
          50: '#fbf8eb',
          100: '#f7f0d7',
          200: '#efdcad',
          300: '#e5c77d',
          400: '#d4af37',
          500: '#c79a2a',
          600: '#ab7a21',
          700: '#8a591e',
          800: '#734820',
          900: '#623d1f',
          950: '#391f0e',
        },
      },
      fontFamily: {
        heading: ['"Playfair Display"', 'serif'],
        body: ['"Montserrat"', 'sans-serif'],
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
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'medium': '0 8px 30px rgba(0, 0, 0, 0.1)',
        'hard': '0 12px 40px rgba(0, 0, 0, 0.15)',
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.black'),
            a: {
              color: theme('colors.primary.DEFAULT'),
              '&:hover': {
                color: theme('colors.primary.700'),
              },
            },
            h1: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.heading').join(', '),
            },
            h2: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.heading').join(', '),
            },
            h3: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.heading').join(', '),
            },
            h4: {
              color: theme('colors.dark.DEFAULT'),
              fontFamily: theme('fontFamily.heading').join(', '),
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.white'),
            a: {
              color: theme('colors.primary.400'),
              '&:hover': {
                color: theme('colors.primary.300'),
              },
            },
            h1: {
              color: theme('colors.white'),
            },
            h2: {
              color: theme('colors.white'),
            },
            h3: {
              color: theme('colors.white'),
            },
            h4: {
              color: theme('colors.white'),
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
  corePlugins: {
    container: false,
  },
};