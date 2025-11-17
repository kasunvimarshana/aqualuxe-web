// Dark mode toggle persistence.
(function () {
	const STORAGE_KEY = "aqlx_dark_mode";
	const root = document.documentElement;
	function apply(mode) {
		if (mode === "dark") {
			root.classList.add("dark");
		} else {
			root.classList.remove("dark");
		}
	}
	const saved = localStorage.getItem(STORAGE_KEY);
	if (saved) {
		apply(saved);
	}
	window.addEventListener("DOMContentLoaded", () => {
		const btn = document.getElementById("aqualuxe-dark-mode");
		if (!btn) return;
		btn.addEventListener("click", () => {
			const isDark = root.classList.toggle("dark");
			localStorage.setItem(STORAGE_KEY, isDark ? "dark" : "light");
			btn.setAttribute("aria-pressed", String(isDark));
		});
	});
})();
