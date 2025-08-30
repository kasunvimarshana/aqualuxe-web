/**
 * AquaLuxe Theme - Responsive Testing Script
 * 
 * This script uses Puppeteer to test the theme's responsiveness across
 * different viewport sizes and generates screenshots for review.
 */

const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

// Create screenshots directory if it doesn't exist
const screenshotsDir = path.join(__dirname, 'screenshots');
if (!fs.existsSync(screenshotsDir)) {
  fs.mkdirSync(screenshotsDir, { recursive: true });
}

// Define viewport sizes to test
const viewports = [
  { name: 'mobile-small', width: 320, height: 568 },
  { name: 'mobile-medium', width: 375, height: 667 },
  { name: 'mobile-large', width: 425, height: 812 },
  { name: 'tablet', width: 768, height: 1024 },
  { name: 'laptop', width: 1024, height: 768 },
  { name: 'desktop', width: 1440, height: 900 },
  { name: 'large-desktop', width: 1920, height: 1080 }
];

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

async function takeScreenshots() {
  const browser = await puppeteer.launch({
    headless: true,
    defaultViewport: null
  });

  console.log('Starting responsive testing...');
  
  for (const viewport of viewports) {
    console.log(`Testing viewport: ${viewport.name} (${viewport.width}x${viewport.height})`);
    
    const page = await browser.newPage();
    await page.setViewport({
      width: viewport.width,
      height: viewport.height
    });
    
    for (const pageConfig of pages) {
      const url = `${baseUrl}${pageConfig.path}`;
      console.log(`  Testing page: ${pageConfig.name} (${url})`);
      
      try {
        await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
        
        // Wait for any lazy-loaded content
        await page.waitForTimeout(2000);
        
        // Take screenshot
        const screenshotPath = path.join(screenshotsDir, `${viewport.name}-${pageConfig.name}.png`);
        await page.screenshot({ path: screenshotPath, fullPage: true });
        
        console.log(`  Screenshot saved: ${screenshotPath}`);
      } catch (error) {
        console.error(`  Error testing ${pageConfig.name} at ${viewport.name}: ${error.message}`);
      }
    }
    
    await page.close();
  }
  
  await browser.close();
  console.log('Responsive testing completed!');
}

takeScreenshots().catch(console.error);