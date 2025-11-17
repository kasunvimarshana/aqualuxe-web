module.exports = {
  content: ["./**/*.php", "./assets/src/js/**/*.js"],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        primary: "var(--alx-primary)",
      },
      container: {
        center: true,
        padding: "1rem",
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
