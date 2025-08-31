module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './assets/src/css/**/*.css'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: 'var(--aqlx-primary)'
      },
      container: {
        center: true,
        padding: '1rem'
      }
    }
  },
  plugins: []
};
