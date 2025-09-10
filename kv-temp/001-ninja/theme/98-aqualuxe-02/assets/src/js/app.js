import "../scss/app.scss";

// Mobile nav toggle
const navToggle = document.getElementById("navToggle");
const mobileNav = document.getElementById("mobileNav");
if (navToggle && mobileNav) {
  navToggle.addEventListener("click", () => {
    const expanded = navToggle.getAttribute("aria-expanded") === "true";
    navToggle.setAttribute("aria-expanded", expanded ? "false" : "true");
    mobileNav.hidden = expanded;
  });
}

// Reduce motion preference
if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
  document.documentElement.classList.add("reduce-motion");
}

// Placeholder: Example dynamic import for heavy modules
// if (document.querySelector('[data-3d-hero]')) {
//   import('./modules/hero-3d').then(m => m.init());
// }
