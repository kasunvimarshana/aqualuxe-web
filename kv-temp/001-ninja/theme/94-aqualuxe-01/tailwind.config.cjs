module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: 'var(--al-primary)',
        accent: 'var(--al-accent)'
      },
      container: { center: true }
    }
  },
  plugins: [require('@tailwindcss/typography')]
};
