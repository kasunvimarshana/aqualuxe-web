/**
 * File dark-mode.js.
 *
 * Handles the dark mode toggle functionality.
 */
(function() {
    // Get theme toggle button
    const themeToggleBtn = document.getElementById('theme-toggle');
    const darkIcon = document.getElementById('theme-toggle-dark-icon');
    const lightIcon = document.getElementById('theme-toggle-light-icon');

    // Check for theme in localStorage
    function getThemePreference() {
        // Check if theme preference is stored in cookie
        const cookieName = aqualuxeDarkMode.cookieName || 'aqualuxe_color_scheme';
        const cookies = document.cookie.split(';');
        let storedTheme = null;

        // Look for our theme cookie
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.indexOf(cookieName + '=') === 0) {
                storedTheme = cookie.substring(cookieName.length + 1);
                break;
            }
        }

        // If theme preference exists in cookie, return it
        if (storedTheme) {
            return storedTheme;
        }

        // If no cookie, check for system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark';
        }

        // Default to theme setting or light
        return aqualuxeDarkMode.defaultScheme || 'light';
    }

    // Function to set theme
    function setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            if (darkIcon) darkIcon.classList.remove('hidden');
            if (lightIcon) lightIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            if (darkIcon) darkIcon.classList.add('hidden');
            if (lightIcon) lightIcon.classList.remove('hidden');
        }

        // Save theme preference to cookie
        const cookieName = aqualuxeDarkMode.cookieName || 'aqualuxe_color_scheme';
        const expiryDays = aqualuxeDarkMode.cookieExpiry || 365;
        const date = new Date();
        date.setTime(date.getTime() + (expiryDays * 24 * 60 * 60 * 1000));
        const expires = "; expires=" + date.toUTCString();
        document.cookie = cookieName + "=" + theme + expires + "; path=/; SameSite=Lax";
    }

    // Set initial theme
    const currentTheme = getThemePreference();
    setTheme(currentTheme);

    // Toggle theme when button is clicked
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            setTheme(isDark ? 'light' : 'dark');
        });
    }

    // Update icons based on current theme
    if (darkIcon && lightIcon) {
        if (document.documentElement.classList.contains('dark')) {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        } else {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }
    }

    // Listen for system theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            // Only change theme if user hasn't manually set a preference
            const cookieName = aqualuxeDarkMode.cookieName || 'aqualuxe_color_scheme';
            const cookies = document.cookie.split(';');
            let hasStoredPreference = false;

            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.indexOf(cookieName + '=') === 0) {
                    hasStoredPreference = true;
                    break;
                }
            }

            if (!hasStoredPreference) {
                setTheme(event.matches ? 'dark' : 'light');
            }
        });
    }
})();