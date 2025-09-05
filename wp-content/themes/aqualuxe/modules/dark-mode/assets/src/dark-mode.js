import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    Alpine.data('themeSwitcher', () => ({
        theme: localStorage.getItem('theme') || 'system',

        init() {
            this.applyTheme(this.getEffectiveTheme());

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (this.theme === 'system') {
                    this.applyTheme(e.matches ? 'dark' : 'light');
                }
            });

            document.getElementById('theme-toggle')?.addEventListener('click', () => {
                const themes = ['light', 'dark', 'system'];
                const currentIndex = themes.indexOf(this.theme);
                this.theme = themes[(currentIndex + 1) % themes.length];
                localStorage.setItem('theme', this.theme);
                this.applyTheme(this.getEffectiveTheme());
                this.savePreference(this.theme);
            });
        },

        getEffectiveTheme() {
            if (this.theme !== 'system') {
                return this.theme;
            }
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        },

        applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },

        async savePreference(theme) {
            if (window.AQUALUXE?.restUrl) {
                try {
                    await fetch(`${window.AQUALUXE.restUrl}/theme-mode`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': window.AQUALUXE.nonce,
                        },
                        body: JSON.stringify({ mode: theme }),
                    });
                } catch (error) {
                    console.error('Failed to save theme preference:', error);
                }
            }
        }
    }));
});

// Ensure AlpineJS is available, otherwise provide a fallback.
if (typeof Alpine === 'undefined') {
    console.warn('AlpineJS not found. Dark mode toggle will not be fully interactive.');
    // Add a simple fallback if Alpine isn't loaded
    const toggle = document.getElementById('theme-toggle');
    toggle?.addEventListener('click', () => {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    });
} else {
    Alpine.start();
}
