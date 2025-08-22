# AquaLuxe Theme Structure

```
aqualuxe/
├── assets/
│   ├── dist/           # Compiled assets
│   │   ├── css/
│   │   ├── js/
│   │   ├── fonts/
│   │   └── images/
│   └── src/            # Source assets
│       ├── css/
│       ├── js/
│       ├── fonts/
│       ├── images/
│       └── scss/
├── core/               # Core theme functionality
│   ├── classes/        # PHP classes for core functionality
│   ├── functions/      # Core function files
│   ├── setup/          # Theme setup files
│   └── customizer/     # Theme customizer files
├── inc/                # Include files
│   ├── helpers/        # Helper functions
│   ├── template-tags/  # Template tag functions
│   └── widgets/        # Custom widgets
├── languages/          # Translation files
├── modules/            # Modular features
│   ├── multilingual/
│   ├── dark-mode/
│   ├── auctions/
│   ├── bookings/
│   ├── events/
│   ├── wholesale/
│   ├── trade-in/
│   └── services/
├── templates/          # Page templates
│   ├── content/        # Content templates
│   ├── layouts/        # Layout templates
│   └── partials/       # Partial templates
├── woocommerce/        # WooCommerce template overrides
├── demo/               # Demo content
├── tests/              # Unit and e2e tests
├── .gitignore
├── functions.php       # Main functions file
├── index.php           # Main template file
├── style.css           # Theme stylesheet
├── screenshot.png      # Theme screenshot
├── package.json        # NPM package file
├── webpack.mix.js      # Laravel Mix config
├── tailwind.config.js  # Tailwind CSS config
├── README.md           # Theme documentation
└── LICENSE             # Theme license
```