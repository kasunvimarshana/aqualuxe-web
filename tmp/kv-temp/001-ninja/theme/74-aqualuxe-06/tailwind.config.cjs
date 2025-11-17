module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './assets/src/scss/**/*.scss',
    './templates/**/*.php',
    './modules/**/*.php'
  ],
  theme: {
    extend: {
      colors: {
        aqua: {
          50: '#effbfd',
          100: '#d8f4fb',
          200: '#b6e9f7',
          300: '#86d9f1',
          400: '#4bc3e8',
          500: '#14a5d1',
          600: '#0d83ad',
          700: '#0f6a8e',
          800: '#125772',
          900: '#12495f'
        },
        luxe: '#0e1a23'
      },
      fontFamily: {
        display: ['ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
        body: ['ui-sans-serif', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'sans-serif']
      }
    }
  },
  darkMode: 'class',
  plugins: [require('@tailwindcss/typography')]
};
