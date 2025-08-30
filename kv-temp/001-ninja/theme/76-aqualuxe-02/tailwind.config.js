module.exports = {
  darkMode: 'class',
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1'
        },
        accent: '#14b8a6'
      }
    }
  },
  plugins: [require('@tailwindcss/typography')]
};
