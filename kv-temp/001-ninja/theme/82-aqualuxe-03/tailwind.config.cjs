/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './assets/src/scss/**/*.pcss'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: '#0ea5e9',
        dark: '#021d2e'
      }
    },
  },
  plugins: [],
}
