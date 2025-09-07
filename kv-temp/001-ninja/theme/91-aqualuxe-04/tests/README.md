# Testing

This directory contains the testing setup for the AquaLuxe theme.

## Unit Tests

Unit tests are located in the `tests/unit/` directory and are designed to be run with [PHPUnit](https://phpunit.de/).

### Setup

1.  **Install PHPUnit:** Follow the official PHPUnit installation guide.
2.  **Configure `phpunit.xml`:** A sample `phpunit.xml.dist` is provided. Copy it to `phpunit.xml` and configure it for your environment.
3.  **Install WP-CLI Scaffolding:** You may need to scaffold the WordPress testing library.
    ```bash
    bash tests/bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version] [skip-database-creation]
    ```

### Running Tests

From the theme's root directory, run:
```bash
phpunit
```

## End-to-End (E2E) Tests

E2E tests are located in the `tests/e2e/` directory and can be run with a framework like [Cypress](https://www.cypress.io/) or [Playwright](https://playwright.dev/).

### Setup (Example with Cypress)

1.  **Install Cypress:** `npm install cypress --save-dev`
2.  **Configure Cypress:** Create a `cypress.json` file in the theme root to configure your test environment (e.g., `baseUrl`).
3.  **Write Tests:** Add your test spec files to the `tests/e2e/` directory.

### Running Tests

```bash
npx cypress open
```
