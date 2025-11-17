/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './template-parts/**/*.php',
    './assets/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#e6f3f8',
          100: '#cce7f1',
          200: '#99cfe3',
          300: '#66b7d5',
          400: '#339fc7',
          500: '#0077b6', // Primary color
          600: '#006da4',
          700: '#005c8a',
          800: '#004c71',
          900: '#003b58',
        },
        secondary: {
          50: '#e6f9fd',
          100: '#ccf3fa',
          200: '#99e7f5',
          300: '#66dbf0',
          400: '#33cfeb',
          500: '#00b4d8', // Secondary color
          600: '#00a2c2',
          700: '#0088a3',
          800: '#006e84',
          900: '#005465',
        },
        accent: {
          50: '#f0f9f9',
          100: '#e0f2f3',
          200: '#c1e5e7',
          300: '#a3d9db',
          400: '#84cccf',
          500: '#48cae4', // Accent color
          600: '#41b6cd',
          700: '#3798ab',
          800: '#2d7a89',
          900: '#235c67',
        },
        dark: {
          50: '#e6e8ea',
          100: '#ccd1d5',
          200: '#99a3ab',
          300: '#667582',
          400: '#334758',
          500: '#001a2e', // Dark color
          600: '#001729',
          700: '#001422',
          800: '#00101c',
          900: '#000c15',
        },
        light: '#f8f9fa',
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['"Playfair Display"', 'serif'],
      },
      spacing: {
        '128': '32rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 6px -1px rgba(0, 119, 182, 0.1), 0 2px 4px -1px rgba(0, 119, 182, 0.06)',
        'medium': '0 10px 15px -3px rgba(0, 119, 182, 0.1), 0 4px 6px -2px rgba(0, 119, 182, 0.05)',
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.dark.500'),
            a: {
              color: theme('colors.primary.500'),
              '&:hover': {
                color: theme('colors.primary.600'),
              },
            },
            h1: {
              color: theme('colors.dark.500'),
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h2: {
              color: theme('colors.dark.500'),
              fontFamily: theme('fontFamily.serif').join(', '),
            },
            h3: {
              color: theme('colors.dark.500'),
            },
            h4: {
              color: theme('colors.dark.500'),
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