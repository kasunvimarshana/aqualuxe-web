(function () {
    const darkModeSwitch = document.getElementById("dark-mode-switch");
    if (darkModeSwitch) {
        darkModeSwitch.addEventListener("change", function (event) {
            if (event.target.checked) {
                document.body.classList.add("dark-mode");
                localStorage.setItem("darkMode", "enabled");
            } else {
                document.body.classList.remove("dark-mode");
                localStorage.setItem("darkMode", "disabled");
            }
        });

        const darkMode = localStorage.getItem("darkMode");
        if (darkMode === "enabled") {
            document.body.classList.add("dark-mode");
            darkModeSwitch.checked = true;
        }
    }
})();
