// Dark mode toggle with persistent preference
(function () {
  const btn = document.getElementById("alx-dark-toggle");
  const root = document.documentElement;
  const LS_KEY = "alx-theme";

  function setMode(mode) {
    if (mode === "dark") {
      root.classList.add("dark");
    } else {
      root.classList.remove("dark");
    }
    localStorage.setItem(LS_KEY, mode);
    if (btn) {
      btn.setAttribute("aria-pressed", String(mode === "dark"));
    }
  }

  const saved = localStorage.getItem(LS_KEY);
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  setMode(saved || (prefersDark ? "dark" : "light"));

  if (btn) {
    btn.addEventListener("click", () => {
      const next = root.classList.contains("dark") ? "light" : "dark";
      setMode(next);
    });
  }
})();
