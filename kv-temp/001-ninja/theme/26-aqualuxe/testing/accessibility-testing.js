/**
 * AquaLuxe Theme - Accessibility Testing Script
 * 
 * This script uses axe-core to test the theme's accessibility
 * and generate reports highlighting any issues.
 */

const puppeteer = require('puppeteer');
const { AxePuppeteer } = require('@axe-core/puppeteer');
const fs = require('fs');
const path = require('path');

// Create reports directory if it doesn't exist
const reportsDir = path.join(__dirname, 'reports');
if (!fs.existsSync(reportsDir)) {
  fs.mkdirSync(reportsDir, { recursive: true });
}

// Define pages to test
const pages = [
  { name: 'home', path: '/' },
  { name: 'about', path: '/about/' },
  { name: 'shop', path: '/shop/' },
  { name: 'product', path: '/shop/sample-product/' },
  { name: 'cart', path: '/cart/' },
  { name: 'checkout', path: '/checkout/' },
  { name: 'blog', path: '/blog/' },
  { name: 'contact', path: '/contact/' }
];

// Base URL for testing
const baseUrl = 'http://localhost:8000'; // Update with your local development URL

async function runAccessibilityTests() {
  const browser = await puppeteer.launch({
    headless: true,
    defaultViewport: { width: 1280, height: 800 }
  });
  
  console.log('Starting accessibility testing...');
  
  for (const pageConfig of pages) {
    const url = `${baseUrl}${pageConfig.path}`;
    console.log(`Testing page: ${pageConfig.name} (${url})`);
    
    try {
      const page = await browser.newPage();
      await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
      
      // Wait for any lazy-loaded content
      await page.waitForTimeout(2000);
      
      // Run axe accessibility tests
      const results = await new AxePuppeteer(page).analyze();
      
      // Save results
      const reportPath = path.join(reportsDir, `${pageConfig.name}-accessibility.json`);
      fs.writeFileSync(reportPath, JSON.stringify(results, null, 2));
      
      // Generate a summary report
      const summaryPath = path.join(reportsDir, `${pageConfig.name}-accessibility-summary.txt`);
      let summary = `Accessibility Report for ${pageConfig.name}\n`;
      summary += `URL: ${url}\n`;
      summary += `Date: ${new Date().toISOString()}\n\n`;
      
      summary += `Violations: ${results.violations.length}\n`;
      summary += `Passes: ${results.passes.length}\n`;
      summary += `Incomplete: ${results.incomplete.length}\n\n`;
      
      if (results.violations.length > 0) {
        summary += 'Violations Summary:\n';
        results.violations.forEach((violation, index) => {
          summary += `${index + 1}. ${violation.id} - ${violation.help}\n`;
          summary += `   Impact: ${violation.impact}\n`;
          summary += `   Description: ${violation.description}\n`;
          summary += `   Elements affected: ${violation.nodes.length}\n`;
          summary += `   Help URL: ${violation.helpUrl}\n\n`;
        });
      } else {
        summary += 'No accessibility violations found!\n';
      }
      
      fs.writeFileSync(summaryPath, summary);
      
      console.log(`Report saved: ${reportPath}`);
      console.log(`Summary saved: ${summaryPath}`);
      console.log(`Violations found: ${results.violations.length}`);
      
      await page.close();
    } catch (error) {
      console.error(`Error testing ${pageConfig.name}: ${error.message}`);
    }
  }
  
  await browser.close();
  console.log('Accessibility testing completed!');
}

runAccessibilityTests().catch(console.error);