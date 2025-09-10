/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  // Limit scanning to theme directories to avoid node_modules performance issues
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
    './woocommerce/**/*.php',
    './modules/**/*.php',
    './assets/src/js/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/typography')],
};
