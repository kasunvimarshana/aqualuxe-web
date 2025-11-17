module.exports = {
  darkMode: 'class',
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
    './assets/src/scss/**/*.scss'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: 'var(--aqlx-primary)'
        },
        accent: {
          DEFAULT: 'var(--aqlx-accent)'
        }
      },
      fontFamily: {
        sans: ['var(--aqlx-font)']
      },
      container: { center: true, padding: '1rem' }
    }
  },
  plugins: [],
};
