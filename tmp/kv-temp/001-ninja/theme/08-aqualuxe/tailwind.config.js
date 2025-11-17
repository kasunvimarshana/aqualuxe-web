/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0077B6', // Ocean blue
          light: '#90E0EF',
          dark: '#03045E',
        },
        secondary: {
          DEFAULT: '#48CAE4', // Light blue
          light: '#ADE8F4',
          dark: '#0096C7',
        },
        accent: {
          DEFAULT: '#FFB703', // Gold/Yellow
          light: '#FFD166',
          dark: '#FB8500',
        },
      },
    },
  },
  plugins: [],
}