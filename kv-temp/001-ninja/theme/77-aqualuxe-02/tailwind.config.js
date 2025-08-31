/***** Tailwind Config *****/
module.exports = {
  darkMode: 'class',
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './assets/src/css/**/*.css'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0ea5e9'
      }
    }
  },
  plugins: [
    require('@tailwindcss/typography')
  ]
};
