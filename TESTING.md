# AquaLuxe Testing Guide

Comprehensive testing strategy for the AquaLuxe WordPress theme ensuring quality, performance, and accessibility.

## 🧪 Testing Strategy

### Testing Pyramid
1. **Unit Tests** (70%): Individual components and functions
2. **Integration Tests** (20%): Module interactions and API endpoints
3. **End-to-End Tests** (10%): Full user workflows

### Test Categories
- **Functionality Testing**: Core features and user interactions
- **Performance Testing**: Speed, load times, and resource usage
- **Security Testing**: Vulnerability scanning and penetration testing
- **Accessibility Testing**: WCAG 2.1 AA compliance verification
- **Cross-browser Testing**: Compatibility across different browsers
- **Mobile Testing**: Responsive design and touch interactions
- **SEO Testing**: Search engine optimization validation

## 🛠️ Test Setup

### Prerequisites

```bash
# Install testing dependencies
npm install --save-dev jest puppeteer axios lighthouse pa11y
npm install --save-dev @testing-library/jest-dom @testing-library/dom
npm install --save-dev cypress @cypress/code-coverage
```

### Test Configuration

`jest.config.js`:
```javascript
module.exports = {
  testEnvironment: 'jsdom',
  setupFilesAfterEnv: ['<rootDir>/tests/setup.js'],
  testMatch: [
    '<rootDir>/tests/unit/**/*.test.js',
    '<rootDir>/tests/integration/**/*.test.js'
  ],
  collectCoverageFrom: [
    'assets/src/js/**/*.js',
    '!assets/src/js/vendor/**',
    '!**/node_modules/**'
  ],
  coverageReporters: ['text', 'lcov', 'html'],
  coverageThreshold: {
    global: {
      branches: 80,
      functions: 80,
      lines: 80,
      statements: 80
    }
  }
};
```

`cypress.config.js`:
```javascript
module.exports = {
  e2e: {
    baseUrl: 'http://localhost:8080',
    supportFile: 'tests/e2e/support/index.js',
    specPattern: 'tests/e2e/specs/**/*.cy.js',
    video: true,
    screenshotOnRunFailure: true,
  },
  component: {
    devServer: {
      framework: 'wordpress',
      bundler: 'webpack'
    }
  }
};
```

## 🔬 Unit Tests

### JavaScript Unit Tests

`tests/unit/accessibility.test.js`:
```javascript
import { AquaLuxeAccessibility } from '../../assets/src/js/accessibility.js';

describe('AquaLuxe Accessibility', () => {
  beforeEach(() => {
    document.body.innerHTML = '';
  });

  test('should add skip links to page', () => {
    AquaLuxeAccessibility.setupSkipLinks();
    
    const skipLinks = document.querySelector('.skip-links');
    expect(skipLinks).toBeInTheDocument();
    
    const mainContentLink = document.querySelector('a[href="#main-content"]');
    expect(mainContentLink).toBeInTheDocument();
  });

  test('should handle keyboard navigation', () => {
    document.body.innerHTML = `
      <nav class="nav-menu">
        <a href="/home">Home</a>
        <a href="/about">About</a>
        <a href="/contact">Contact</a>
      </nav>
    `;
    
    AquaLuxeAccessibility.setupKeyboardNavigation();
    
    const firstLink = document.querySelector('a[href="/home"]');
    const secondLink = document.querySelector('a[href="/about"]');
    
    firstLink.focus();
    
    // Simulate ArrowDown key
    const event = new KeyboardEvent('keydown', { key: 'ArrowDown' });
    firstLink.dispatchEvent(event);
    
    expect(document.activeElement).toBe(secondLink);
  });

  test('should announce changes to screen readers', () => {
    AquaLuxeAccessibility.setupARIALabels();
    
    const liveRegion = document.getElementById('aria-live-polite');
    expect(liveRegion).toBeInTheDocument();
    
    AquaLuxeAccessibility.announceChange('Test message');
    expect(liveRegion.textContent).toBe('Test message');
  });
});
```

`tests/unit/security.test.js`:
```javascript
import { AquaLuxeSecurity } from '../../assets/src/js/security.js';

describe('AquaLuxe Security', () => {
  test('should validate form inputs', () => {
    document.body.innerHTML = `
      <form>
        <input type="text" value="<script>alert('xss')</script>" />
        <input type="email" value="user@example.com" />
      </form>
    `;
    
    const form = document.querySelector('form');
    const result = AquaLuxeSecurity.validateForm(form);
    
    expect(result).toBe(false);
    
    const textInput = document.querySelector('input[type="text"]');
    expect(textInput).toHaveClass('security-error');
  });

  test('should detect SQL injection attempts', () => {
    const maliciousInput = "'; DROP TABLE users; --";
    
    document.body.innerHTML = `
      <form>
        <input type="text" value="${maliciousInput}" />
      </form>
    `;
    
    const form = document.querySelector('form');
    const result = AquaLuxeSecurity.validateForm(form);
    
    expect(result).toBe(false);
  });

  test('should validate URL format', () => {
    const validURL = 'https://example.com';
    const invalidURL = 'javascript:alert(1)';
    
    expect(AquaLuxeSecurity.isValidURL(validURL)).toBe(true);
    expect(AquaLuxeSecurity.isValidURL(invalidURL)).toBe(false);
  });
});
```

### PHP Unit Tests

`tests/unit/SecurityModuleTest.php`:
```php
<?php
use PHPUnit\Framework\TestCase;
use AquaLuxe\Modules\Security\Module as SecurityModule;

class SecurityModuleTest extends TestCase
{
    private $security;

    protected function setUp(): void
    {
        $this->security = SecurityModule::get_instance();
    }

    public function testGetClientIP()
    {
        $_SERVER['REMOTE_ADDR'] = '192.168.1.100';
        
        $reflection = new ReflectionClass($this->security);
        $method = $reflection->getMethod('get_client_ip');
        $method->setAccessible(true);
        
        $ip = $method->invoke($this->security);
        $this->assertEquals('192.168.1.100', $ip);
    }

    public function testSecureFileUpload()
    {
        $file = [
            'name' => 'test.php',
            'size' => 1024,
            'tmp_name' => '/tmp/test'
        ];
        
        $result = $this->security->secure_file_upload($file);
        
        $this->assertArrayHasKey('error', $result);
        $this->assertStringContainsString('not allowed', $result['error']);
    }

    public function testRateLimiting()
    {
        // Simulate multiple requests
        for ($i = 0; $i < 65; $i++) {
            ob_start();
            $this->security->check_rate_limit();
            $output = ob_get_clean();
        }
        
        // Should be rate limited after 60 requests
        $this->expectException(WP_Error::class);
    }
}
```

## 🔗 Integration Tests

### API Integration Tests

`tests/integration/woocommerce.test.js`:
```javascript
import axios from 'axios';

describe('WooCommerce Integration', () => {
  const baseURL = 'http://localhost:8080/wp-json/wc/v3';
  
  test('should fetch products when WooCommerce is active', async () => {
    try {
      const response = await axios.get(`${baseURL}/products`);
      expect(response.status).toBe(200);
      expect(Array.isArray(response.data)).toBe(true);
    } catch (error) {
      // If WooCommerce not active, should gracefully fallback
      expect(error.response.status).toBe(404);
    }
  });

  test('should handle graceful fallback when WooCommerce inactive', async () => {
    const response = await axios.get('http://localhost:8080/shop');
    expect(response.status).toBe(200);
    
    // Should contain fallback content
    expect(response.data).toContain('fallback-shop');
  });
});
```

### Module Integration Tests

`tests/integration/modules.test.js`:
```javascript
describe('Module Integration', () => {
  test('should load all enabled modules', () => {
    const enabledModules = [
      'Security',
      'Performance', 
      'SEO',
      'Accessibility',
      'Multilingual',
      'Dark Mode'
    ];
    
    enabledModules.forEach(module => {
      const moduleInstance = window[`AquaLuxe${module}`];
      expect(moduleInstance).toBeDefined();
      expect(typeof moduleInstance.init).toBe('function');
    });
  });

  test('should handle module dependencies', () => {
    // Test that Performance module properly integrates with Security
    const performanceMetrics = window.AquaLuxePerformance.getMetrics();
    const securityHeaders = window.AquaLuxeSecurity.getHeaders();
    
    expect(performanceMetrics).toBeDefined();
    expect(securityHeaders).toBeDefined();
  });
});
```

## 🎭 End-to-End Tests

### Cypress E2E Tests

`tests/e2e/specs/user-journey.cy.js`:
```javascript
describe('User Journey', () => {
  beforeEach(() => {
    cy.visit('/');
  });

  it('should complete full shopping journey', () => {
    // Navigate to shop
    cy.get('[data-cy="nav-shop"]').click();
    cy.url().should('include', '/shop');

    // Filter products
    cy.get('[data-cy="filter-category"]').select('Marine Fish');
    cy.get('[data-cy="products-grid"]').should('be.visible');

    // View product details
    cy.get('[data-cy="product-item"]').first().click();
    cy.get('[data-cy="product-title"]').should('be.visible');

    // Add to cart (if WooCommerce active)
    cy.get('body').then($body => {
      if ($body.find('[data-cy="add-to-cart"]').length > 0) {
        cy.get('[data-cy="add-to-cart"]').click();
        cy.get('[data-cy="cart-count"]').should('contain', '1');
      }
    });
  });

  it('should be accessible via keyboard navigation', () => {
    // Test skip links
    cy.get('body').tab();
    cy.focused().should('contain', 'Skip to main content');

    // Test menu navigation
    cy.get('[data-cy="main-menu"]').within(() => {
      cy.get('a').first().focus();
      cy.focused().type('{downarrow}');
      cy.focused().should('not.be', cy.get('a').first());
    });
  });

  it('should work on mobile devices', () => {
    cy.viewport('iphone-x');
    
    // Test mobile menu
    cy.get('[data-cy="mobile-menu-toggle"]').click();
    cy.get('[data-cy="mobile-menu"]').should('be.visible');

    // Test responsive design
    cy.get('[data-cy="hero-section"]').should('be.visible');
    cy.get('[data-cy="products-grid"]').should('have.class', 'grid-cols-1');
  });
});
```

`tests/e2e/specs/accessibility.cy.js`:
```javascript
describe('Accessibility', () => {
  beforeEach(() => {
    cy.visit('/');
    cy.injectAxe();
  });

  it('should meet WCAG 2.1 AA standards', () => {
    cy.checkA11y(null, {
      runOnly: {
        type: 'tag',
        values: ['wcag2a', 'wcag2aa']
      }
    });
  });

  it('should have proper heading hierarchy', () => {
    cy.get('h1').should('have.length', 1);
    cy.get('h1').should('be.visible');
    
    // Check heading order
    cy.get('h1, h2, h3, h4, h5, h6').then($headings => {
      const levels = Array.from($headings).map(h => parseInt(h.tagName.slice(1)));
      
      // Verify no levels are skipped
      for (let i = 1; i < levels.length; i++) {
        expect(levels[i] - levels[i-1]).to.be.at.most(1);
      }
    });
  });

  it('should have proper color contrast', () => {
    cy.checkA11y(null, {
      runOnly: {
        type: 'rule',
        values: ['color-contrast']
      }
    });
  });
});
```

## ⚡ Performance Tests

### Lighthouse Automation

`tests/performance/lighthouse.test.js`:
```javascript
const lighthouse = require('lighthouse');
const chromeLauncher = require('chrome-launcher');

describe('Performance Tests', () => {
  let chrome;
  
  beforeAll(async () => {
    chrome = await chromeLauncher.launch({chromeFlags: ['--headless']});
  });
  
  afterAll(async () => {
    await chrome.kill();
  });

  test('should meet performance benchmarks', async () => {
    const options = {
      logLevel: 'info',
      output: 'json',
      onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'],
      port: chrome.port,
    };
    
    const runnerResult = await lighthouse('http://localhost:8080', options);
    const scores = runnerResult.lhr.categories;
    
    expect(scores.performance.score).toBeGreaterThanOrEqual(0.9);
    expect(scores.accessibility.score).toBeGreaterThanOrEqual(0.95);
    expect(scores['best-practices'].score).toBeGreaterThanOrEqual(0.9);
    expect(scores.seo.score).toBeGreaterThanOrEqual(0.95);
  });

  test('should have good Core Web Vitals', async () => {
    const options = {
      logLevel: 'info',
      output: 'json',
      onlyAudits: [
        'largest-contentful-paint',
        'first-input-delay',
        'cumulative-layout-shift'
      ],
      port: chrome.port,
    };
    
    const runnerResult = await lighthouse('http://localhost:8080', options);
    const audits = runnerResult.lhr.audits;
    
    // LCP should be under 2.5s
    expect(audits['largest-contentful-paint'].numericValue).toBeLessThan(2500);
    
    // CLS should be under 0.1
    expect(audits['cumulative-layout-shift'].numericValue).toBeLessThan(0.1);
  });
});
```

### Load Testing

`tests/performance/load.test.js`:
```javascript
const axios = require('axios');

describe('Load Testing', () => {
  test('should handle concurrent requests', async () => {
    const concurrent = 50;
    const requests = [];
    
    for (let i = 0; i < concurrent; i++) {
      requests.push(axios.get('http://localhost:8080'));
    }
    
    const responses = await Promise.all(requests);
    
    responses.forEach(response => {
      expect(response.status).toBe(200);
      expect(response.headers['content-type']).toContain('text/html');
    });
  });

  test('should respond within acceptable time limits', async () => {
    const start = Date.now();
    const response = await axios.get('http://localhost:8080');
    const duration = Date.now() - start;
    
    expect(response.status).toBe(200);
    expect(duration).toBeLessThan(2000); // Under 2 seconds
  });
});
```

## 🛡️ Security Tests

### Vulnerability Scanning

`tests/security/vulnerabilities.test.js`:
```javascript
const axios = require('axios');

describe('Security Tests', () => {
  test('should have security headers', async () => {
    const response = await axios.get('http://localhost:8080');
    
    expect(response.headers['x-frame-options']).toBe('SAMEORIGIN');
    expect(response.headers['x-content-type-options']).toBe('nosniff');
    expect(response.headers['x-xss-protection']).toBe('1; mode=block');
    expect(response.headers['strict-transport-security']).toBeDefined();
  });

  test('should prevent XSS attacks', async () => {
    const maliciousPayload = '<script>alert("xss")</script>';
    
    try {
      const response = await axios.post('http://localhost:8080/wp-admin/admin-ajax.php', {
        action: 'aqualuxe_contact',
        message: maliciousPayload
      });
      
      expect(response.data).not.toContain('<script>');
    } catch (error) {
      // Should be blocked by security measures
      expect(error.response.status).toBeGreaterThanOrEqual(400);
    }
  });

  test('should prevent SQL injection', async () => {
    const sqlPayload = "'; DROP TABLE wp_posts; --";
    
    try {
      const response = await axios.get(`http://localhost:8080/?s=${encodeURIComponent(sqlPayload)}`);
      expect(response.status).toBe(200);
      expect(response.data).not.toContain('SQL syntax');
    } catch (error) {
      expect(error.response.status).toBeGreaterThanOrEqual(400);
    }
  });
});
```

## 📱 Mobile & Browser Tests

### Cross-browser Testing

`tests/browser/compatibility.test.js`:
```javascript
const puppeteer = require('puppeteer');

describe('Browser Compatibility', () => {
  const browsers = ['chrome', 'firefox', 'safari', 'edge'];
  
  browsers.forEach(browserName => {
    test(`should work in ${browserName}`, async () => {
      const browser = await puppeteer.launch({
        product: browserName === 'firefox' ? 'firefox' : 'chrome'
      });
      
      const page = await browser.newPage();
      await page.goto('http://localhost:8080');
      
      // Test basic functionality
      const title = await page.title();
      expect(title).toContain('AquaLuxe');
      
      // Test JavaScript execution
      const jsWorks = await page.evaluate(() => {
        return typeof window.AquaLuxe !== 'undefined';
      });
      expect(jsWorks).toBe(true);
      
      await browser.close();
    });
  });
});
```

## 🎯 Test Execution

### Running Tests

```bash
# All tests
npm test

# Unit tests only
npm run test:unit

# Integration tests
npm run test:integration

# E2E tests
npm run test:e2e

# Performance tests
npm run test:performance

# Security tests
npm run test:security

# Accessibility tests
npm run test:a11y

# Coverage report
npm run test:coverage
```

### Package.json Scripts

```json
{
  "scripts": {
    "test": "jest && cypress run",
    "test:unit": "jest tests/unit",
    "test:integration": "jest tests/integration",
    "test:e2e": "cypress run",
    "test:e2e:open": "cypress open",
    "test:performance": "jest tests/performance",
    "test:security": "jest tests/security",
    "test:a11y": "pa11y-ci --sitemap http://localhost:8080/sitemap.xml",
    "test:coverage": "jest --coverage",
    "test:watch": "jest --watch"
  }
}
```

## 📊 Test Reporting

### Coverage Reports

The test suite generates comprehensive coverage reports:

- **HTML Report**: `coverage/lcov-report/index.html`
- **JSON Report**: `coverage/coverage-final.json`
- **Text Summary**: Console output during test runs

### CI Integration

`.github/workflows/test.yml`:
```yaml
name: Test Suite

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v4
    
    - name: Setup Node.js
      uses: actions/setup-node@v4
      with:
        node-version: '18'
    
    - name: Install dependencies
      run: npm ci
    
    - name: Run unit tests
      run: npm run test:unit
    
    - name: Run security tests
      run: npm run test:security
    
    - name: Upload coverage
      uses: codecov/codecov-action@v3
      with:
        file: ./coverage/lcov.info
```

## ✅ Quality Gates

### Test Requirements

Before deployment, all tests must pass:

- **Unit Test Coverage**: ≥80%
- **Integration Tests**: All passing
- **E2E Tests**: All critical paths passing
- **Performance Score**: ≥90 Lighthouse
- **Accessibility Score**: ≥95 Lighthouse
- **Security Scan**: No high/critical vulnerabilities

### Continuous Monitoring

Set up monitoring for:
- **Performance regression detection**
- **Accessibility compliance monitoring**
- **Security vulnerability scanning**
- **Error rate tracking**
- **User experience metrics**

---

**🧪 Comprehensive testing ensures AquaLuxe delivers exceptional quality and reliability**