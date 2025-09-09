/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",
  content: ["./**/*.php", "./assets/src/js/**/*.js"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Open Sans"', "sans-serif"],
        serif: ['"Merriweather"', "serif"],
      },
      colors: {
        primary: "#005f73",
        secondary: "#0a9396",
        accent: "#94d2bd",
        light: "#e9d8a6",
        dark: "#001219",
      },
    },
  },
  plugins: [require("@tailwindcss/typography")],
};
