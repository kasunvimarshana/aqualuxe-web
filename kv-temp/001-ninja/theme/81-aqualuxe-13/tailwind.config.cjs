/**** Tailwind config ****/
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: '#0ea5e9'
        }
      }
    }
  },
  plugins: [
    require('@tailwindcss/typography')
  ]
};
