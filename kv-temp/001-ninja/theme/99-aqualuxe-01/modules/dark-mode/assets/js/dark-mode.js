// Dark mode toggle functionality
const darkModeToggle = document.getElementById("dark-mode-toggle");
const html = document.documentElement;

// Check for saved theme preference or use system preference
const theme =
  localStorage.getItem("theme") ||
  (window.matchMedia("(prefers-color-scheme: dark)").matches
    ? "dark"
    : "light");

if (theme === "dark") {
  html.classList.add("dark");
}

darkModeToggle.addEventListener("click", () => {
  html.classList.toggle("dark");
  if (html.classList.contains("dark")) {
    localStorage.setItem("theme", "dark");
  } else {
    localStorage.setItem("theme", "light");
  }
});
