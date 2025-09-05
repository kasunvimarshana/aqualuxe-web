module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: '#0ea5e9',
          dark: '#0284c7',
        },
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
};
