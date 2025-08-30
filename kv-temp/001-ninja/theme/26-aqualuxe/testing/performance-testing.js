/**
 * AquaLuxe Theme - Performance Testing Script
 * 
 * This script uses Lighthouse to analyze the theme's performance
 * and generate reports for optimization.
 */

const lighthouse = require('lighthouse');
const chromeLauncher = require('chrome-launcher');
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
  { name: 'blog', path: '/blog/' },
  { name: 'contact', path: '/contact/' }
];

// Base URL for testing
const baseUrl = 'http://localhost:8000'; // Update with your local development URL

async function runLighthouse(url, opts, config = null) {
  return await lighthouse(url, opts, config);
}

async function performanceTest() {
  const chrome = await chromeLauncher.launch({
    chromeFlags: ['--headless', '--disable-gpu', '--no-sandbox']
  });
  
  const opts = {
    logLevel: 'info',
    output: 'html',
    port: chrome.port,
    throttling: {
      cpuSlowdownMultiplier: 4,
      downloadThroughputKbps: 1638.4,
      uploadThroughputKbps: 675.84,
      rttMs: 150
    }
  };
  
  console.log('Starting performance testing...');
  
  for (const page of pages) {
    const url = `${baseUrl}${page.path}`;
    console.log(`Testing page: ${page.name} (${url})`);
    
    try {
      const runnerResult = await runLighthouse(url, opts);
      
      // Save report
      const reportPath = path.join(reportsDir, `${page.name}-performance.html`);
      fs.writeFileSync(reportPath, runnerResult.report);
      
      console.log(`Report saved: ${reportPath}`);
      console.log(`Performance score: ${runnerResult.lhr.categories.performance.score * 100}`);
      console.log(`First Contentful Paint: ${runnerResult.lhr.audits['first-contentful-paint'].displayValue}`);
      console.log(`Largest Contentful Paint: ${runnerResult.lhr.audits['largest-contentful-paint'].displayValue}`);
      console.log(`Total Blocking Time: ${runnerResult.lhr.audits['total-blocking-time'].displayValue}`);
      console.log(`Cumulative Layout Shift: ${runnerResult.lhr.audits['cumulative-layout-shift'].displayValue}`);
      console.log('-----------------------------------');
    } catch (error) {
      console.error(`Error testing ${page.name}: ${error.message}`);
    }
  }
  
  await chrome.kill();
  console.log('Performance testing completed!');
}

performanceTest().catch(console.error);