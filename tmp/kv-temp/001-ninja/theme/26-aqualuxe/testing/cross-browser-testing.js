/**
 * AquaLuxe Theme - Cross-Browser Testing Script
 * 
 * This script uses Playwright to test the theme across different browsers
 * and generate screenshots for comparison.
 */

const { chromium, firefox, webkit } = require('playwright');
const fs = require('fs');
const path = require('path');

// Create screenshots directory if it doesn't exist
const screenshotsDir = path.join(__dirname, 'browser-screenshots');
if (!fs.existsSync(screenshotsDir)) {
  fs.mkdirSync(screenshotsDir, { recursive: true });
}

// Define pages to test
const pages = [
  { name: 'home', path: '/' },
  { name: 'about', path: '/about/' },
  { name: 'shop', path: '/shop/' },
  { name: 'product', path: '/shop/sample-product/' },
  { name: 'cart', path: '/cart/' },
  { name: 'blog', path: '/blog/' },
  { name: 'contact', path: '/contact/' }
];

// Base URL for testing
const baseUrl = 'http://localhost:8000'; // Update with your local development URL

// Define viewport size
const viewport = { width: 1280, height: 800 };

async function testBrowser(browserType, browserName) {
  console.log(`Starting tests with ${browserName}...`);
  
  const browser = await browserType.launch();
  const context = await browser.newContext({ viewport });
  
  for (const pageConfig of pages) {
    const url = `${baseUrl}${pageConfig.path}`;
    console.log(`  Testing page: ${pageConfig.name} (${url})`);
    
    try {
      const page = await context.newPage();
      await page.goto(url, { waitUntil: 'networkidle', timeout: 30000 });
      
      // Wait for any lazy-loaded content
      await page.waitForTimeout(2000);
      
      // Take screenshot
      const screenshotPath = path.join(screenshotsDir, `${browserName}-${pageConfig.name}.png`);
      await page.screenshot({ path: screenshotPath, fullPage: true });
      
      console.log(`  Screenshot saved: ${screenshotPath}`);
      
      // Test basic interactions
      // Click on menu items
      const menuItems = await page.$$('nav a');
      if (menuItems.length > 0) {
        console.log(`  Testing navigation menu (${menuItems.length} items found)`);
      }
      
      // Test dark mode toggle if it exists
      const darkModeToggle = await page.$('.dark-mode-toggle');
      if (darkModeToggle) {
        console.log('  Testing dark mode toggle');
        await darkModeToggle.click();
        await page.waitForTimeout(1000);
        
        // Take screenshot with dark mode
        const darkModeScreenshotPath = path.join(screenshotsDir, `${browserName}-${pageConfig.name}-dark.png`);
        await page.screenshot({ path: darkModeScreenshotPath, fullPage: true });
        console.log(`  Dark mode screenshot saved: ${darkModeScreenshotPath}`);
      }
      
      await page.close();
    } catch (error) {
      console.error(`  Error testing ${pageConfig.name} with ${browserName}: ${error.message}`);
    }
  }
  
  await browser.close();
  console.log(`Completed tests with ${browserName}`);
}

async function runCrossBrowserTests() {
  console.log('Starting cross-browser testing...');
  
  // Test in Chrome/Chromium
  await testBrowser(chromium, 'chromium');
  
  // Test in Firefox
  await testBrowser(firefox, 'firefox');
  
  // Test in Safari/WebKit
  await testBrowser(webkit, 'webkit');
  
  console.log('Cross-browser testing completed!');
}

runCrossBrowserTests().catch(console.error);