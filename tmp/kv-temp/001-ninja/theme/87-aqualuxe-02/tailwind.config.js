module.exports = {
  darkMode: 'class',
  content: [
    './**/*.php',
    './assets/src/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        aqua: {
          500: '#0ea5e9'
        }
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms')
  ]
}
