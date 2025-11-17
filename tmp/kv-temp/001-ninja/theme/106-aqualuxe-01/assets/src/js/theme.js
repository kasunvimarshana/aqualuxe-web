import "./dark-mode";

// Progressive enhancement helpers.
(function () {
	// Accessible skip link focus fix.
	const hash = window.location.hash;
	if (hash && document.getElementById(hash.substring(1))) {
		document
			.getElementById(hash.substring(1))
			.setAttribute("tabindex", "-1");
	}
})();
