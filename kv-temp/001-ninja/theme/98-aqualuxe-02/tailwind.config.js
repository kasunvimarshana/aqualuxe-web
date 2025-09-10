module.exports = {
  darkMode: "class",
  content: [
    "./**/*.php",
    "./assets/src/js/**/*.js",
    "./assets/src/scss/**/*.scss",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: "#0ea5e9",
          dark: "#0b79a7",
        },
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
