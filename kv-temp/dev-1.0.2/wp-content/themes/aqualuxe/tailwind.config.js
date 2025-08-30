/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ['./**/*.php', './assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        primary: '#006994',
        secondary: '#00a8cc',
        accent: '#ffd166',
        light: '#f8f9fa',
        dark: '#343a40',
        success: '#28a745',
        warning: '#ffc107',
      },
      fontFamily: {
        sans: ['Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
      },
      spacing: {
        18: '4.5rem',
        88: '22rem',
      },
    },
  },
  plugins: [],
};
