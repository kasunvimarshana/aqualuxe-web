// ESLint configuration for AquaLuxe theme
export default [
  {
    files: ["assets/src/js/**/*.js"],
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: "module",
      globals: {
        window: "readonly",
        document: "readonly",
        jQuery: "readonly",
        $: "readonly",
        wp: "readonly",
        ajaxurl: "readonly",
        AquaLuxe: "writable",
        aqualuxe_vars: "readonly",
        aqualuxe_admin_vars: "readonly",
        localStorage: "readonly",
        console: "readonly",
        alert: "readonly",
        confirm: "readonly",
        setTimeout: "readonly",
        clearTimeout: "readonly",
        setInterval: "readonly",
        clearInterval: "readonly",
        IntersectionObserver: "readonly",
        CustomEvent: "readonly",
        FormData: "readonly"
      }
    },
    rules: {
      "no-console": "warn",
      "no-unused-vars": "error",
      "no-undef": "error",
      "prefer-const": "error",
      "no-var": "error"
    }
  }
];