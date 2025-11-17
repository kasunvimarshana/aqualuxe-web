# Development Guide

Structure:
- core/: Framework (setup, assets, security, customizer, SEO, modules loader)
- modules/: Feature modules (self-contained)
- templates/, template-parts/: Theme templates
- woocommerce/: Select overrides
- assets/src: Authoring (JS/SCSS/images/fonts)
- assets/dist: Compiled (versioned)

Commands:
```cmd
npm ci
npm run dev   # single build
npm run watch # watch and HMR-like rebuilds
npm run build # production
```

Tailwind:
- Config in tailwind.config.js
- Use utility classes in PHP templates

Modules:
- Each module has module.json and module.php with a class implementing static init().
- Toggle by setting enabled: true/false in module.json.

Coding standards:
- Escape output, sanitize input, use nonces for forms.
- Use semantic HTML, ARIA roles, lazy loading for media.

Testing:
```cmd
npm run test
```

