module.exports = {
  content: [
    './templates/**/*.php',
    './core/**/*.php',
    './modules/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js'
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0e7490', // Aqua
        accent: '#b6e0fe',  // Luxe
        dark: '#0a192f',
        light: '#f8fafc'
      },
      fontFamily: {
        display: ['"Playfair Display"', 'serif'],
        body: ['"Inter"', 'sans-serif']
      }
    }
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ]
};
