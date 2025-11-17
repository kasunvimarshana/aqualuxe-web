#!/usr/bin/env node

/**
 * AquaLuxe Theme Validator
 * 
 * Validates the theme structure, code quality, and WordPress compliance.
 */

const fs = require('fs');
const path = require('path');

class ThemeValidator {
    constructor() {
        this.themeDir = path.resolve(__dirname, '..');
        this.errors = [];
        this.warnings = [];
        this.info = [];
    }

    async validate() {
        console.log('🔍 Validating AquaLuxe theme...');
        
        try {
            await this.validateFileStructure();
            await this.validateStyleCSS();
            await this.validateFunctionsPhp();
            await this.validateTemplateFiles();
            await this.validateAssets();
            await this.validateSecurity();
            await this.validateAccessibility();
            await this.validatePerformance();
            
            this.generateReport();
            
        } catch (error) {
            console.error('❌ Validation failed:', error);
            process.exit(1);
        }
    }

    async validateFileStructure() {
        console.log('📁 Validating file structure...');
        
        const requiredFiles = [
            'style.css',
            'index.php',
            'functions.php',
            'screenshot.png'
        ];
        
        const recommendedFiles = [
            'header.php',
            'footer.php',
            'single.php',
            'page.php',
            'archive.php',
            '404.php',
            'search.php',
            'comments.php'
        ];
        
        // Check required files
        requiredFiles.forEach(file => {
            const filePath = path.join(this.themeDir, file);
            if (!fs.existsSync(filePath)) {
                this.errors.push(`Required file missing: ${file}`);
            } else {
                this.info.push(`✓ Required file found: ${file}`);
            }
        });
        
        // Check recommended files
        recommendedFiles.forEach(file => {
            const filePath = path.join(this.themeDir, file);
            if (!fs.existsSync(filePath)) {
                this.warnings.push(`Recommended file missing: ${file}`);
            } else {
                this.info.push(`✓ Recommended file found: ${file}`);
            }
        });
        
        // Check directory structure
        const requiredDirs = ['assets', 'inc', 'template-parts'];
        requiredDirs.forEach(dir => {
            const dirPath = path.join(this.themeDir, dir);
            if (!fs.existsSync(dirPath)) {
                this.warnings.push(`Recommended directory missing: ${dir}`);
            } else {
                this.info.push(`✓ Directory found: ${dir}`);
            }
        });
    }

    async validateStyleCSS() {
        console.log('🎨 Validating style.css...');
        
        const stylePath = path.join(this.themeDir, 'style.css');
        if (!fs.existsSync(stylePath)) {
            this.errors.push('style.css not found');
            return;
        }
        
        const content = fs.readFileSync(stylePath, 'utf8');
        
        // Check theme header
        const requiredHeaders = [
            'Theme Name',
            'Description',
            'Version',
            'Author'
        ];
        
        requiredHeaders.forEach(header => {
            const regex = new RegExp(`${header}:`, 'i');
            if (!regex.test(content)) {
                this.errors.push(`Missing required header in style.css: ${header}`);
            } else {
                this.info.push(`✓ Header found: ${header}`);
            }
        });
        
        // Check for license
        if (!content.includes('License:')) {
            this.warnings.push('License not specified in style.css');
        }
        
        // Check for text domain
        if (!content.includes('Text Domain:')) {
            this.warnings.push('Text Domain not specified in style.css');
        }
    }

    async validateFunctionsPhp() {
        console.log('⚙️ Validating functions.php...');
        
        const functionsPath = path.join(this.themeDir, 'functions.php');
        if (!fs.existsSync(functionsPath)) {
            this.errors.push('functions.php not found');
            return;
        }
        
        const content = fs.readFileSync(functionsPath, 'utf8');
        
        // Check for required functions
        const requiredFunctions = [
            'wp_enqueue_scripts',
            'add_theme_support',
            'register_nav_menus'
        ];
        
        requiredFunctions.forEach(func => {
            if (content.includes(func)) {
                this.info.push(`✓ Function used: ${func}`);
            } else {
                this.warnings.push(`Recommended function not found: ${func}`);
            }
        });
        
        // Check for security issues
        const securityIssues = [
            'eval(',
            'exec(',
            'system(',
            'shell_exec(',
            'file_get_contents($_'
        ];
        
        securityIssues.forEach(issue => {
            if (content.includes(issue)) {
                this.errors.push(`Security issue found: ${issue}`);
            }
        });
        
        // Check for proper PHP opening tag
        if (!content.startsWith('<?php')) {
            this.errors.push('functions.php should start with <?php');
        }
        
        // Check for closing PHP tag (should not exist)
        if (content.trim().endsWith('?>')) {
            this.warnings.push('functions.php should not end with ?>');
        }
    }

    async validateTemplateFiles() {
        console.log('📄 Validating template files...');
        
        const templateFiles = [
            'index.php',
            'header.php',
            'footer.php',
            'single.php',
            'page.php'
        ];
        
        templateFiles.forEach(file => {
            const filePath = path.join(this.themeDir, file);
            if (fs.existsSync(filePath)) {
                this.validateTemplateFile(filePath, file);
            }
        });
    }

    validateTemplateFile(filePath, filename) {
        const content = fs.readFileSync(filePath, 'utf8');
        
        // Check for DOCTYPE in index.php
        if (filename === 'index.php' && !content.includes('<!DOCTYPE')) {
            this.warnings.push(`${filename}: Missing DOCTYPE declaration`);
        }
        
        // Check for wp_head() in header.php
        if (filename === 'header.php' && !content.includes('wp_head()')) {
            this.errors.push(`${filename}: Missing wp_head() call`);
        }
        
        // Check for wp_footer() in footer.php
        if (filename === 'footer.php' && !content.includes('wp_footer()')) {
            this.errors.push(`${filename}: Missing wp_footer() call`);
        }
        
        // Check for language_attributes()
        if (filename === 'header.php' && !content.includes('language_attributes()')) {
            this.warnings.push(`${filename}: Missing language_attributes()`);
        }
        
        // Check for body_class()
        if (filename === 'header.php' && !content.includes('body_class()')) {
            this.warnings.push(`${filename}: Missing body_class()`);
        }
        
        // Check for escaped output
        const unescapedPatterns = [
            /echo\s+\$[^;]+;/g,
            /print\s+\$[^;]+;/g
        ];
        
        unescapedPatterns.forEach(pattern => {
            const matches = content.match(pattern);
            if (matches) {
                this.warnings.push(`${filename}: Potential unescaped output found`);
            }
        });
    }

    async validateAssets() {
        console.log('📦 Validating assets...');
        
        const assetsDir = path.join(this.themeDir, 'assets');
        if (!fs.existsSync(assetsDir)) {
            this.warnings.push('Assets directory not found');
            return;
        }
        
        // Check for compiled assets
        const distDir = path.join(assetsDir, 'dist');
        if (fs.existsSync(distDir)) {
            this.info.push('✓ Compiled assets directory found');
            
            // Check for main files
            const mainFiles = ['js/main.js', 'css/main.css'];
            mainFiles.forEach(file => {
                const filePath = path.join(distDir, file);
                if (fs.existsSync(filePath)) {
                    this.info.push(`✓ Main asset found: ${file}`);
                } else {
                    this.warnings.push(`Main asset missing: ${file}`);
                }
            });
        } else {
            this.warnings.push('Compiled assets directory not found');
        }
        
        // Check for source files
        const srcDir = path.join(assetsDir, 'src');
        if (fs.existsSync(srcDir)) {
            this.info.push('✓ Source assets directory found');
        } else {
            this.warnings.push('Source assets directory not found');
        }
        
        // Check screenshot
        const screenshotPath = path.join(this.themeDir, 'screenshot.png');
        if (fs.existsSync(screenshotPath)) {
            const stats = fs.statSync(screenshotPath);
            const fileSizeInBytes = stats.size;
            const fileSizeInMB = fileSizeInBytes / (1024 * 1024);
            
            if (fileSizeInMB > 1) {
                this.warnings.push('Screenshot.png is larger than 1MB');
            } else {
                this.info.push('✓ Screenshot.png size is appropriate');
            }
        }
    }

    async validateSecurity() {
        console.log('🔒 Validating security...');
        
        // Check for direct access protection
        const phpFiles = this.getPHPFiles();
        
        phpFiles.forEach(file => {
            const content = fs.readFileSync(file, 'utf8');
            
            // Check for direct access protection
            if (!content.includes('ABSPATH') && !content.includes('defined(')) {
                this.warnings.push(`${path.basename(file)}: Missing direct access protection`);
            }
            
            // Check for nonce verification in forms
            if (content.includes('$_POST') && !content.includes('wp_verify_nonce')) {
                this.warnings.push(`${path.basename(file)}: POST data without nonce verification`);
            }
            
            // Check for SQL injection prevention
            if (content.includes('$wpdb->query') && !content.includes('prepare')) {
                this.errors.push(`${path.basename(file)}: Possible SQL injection vulnerability`);
            }
        });
    }

    async validateAccessibility() {
        console.log('♿ Validating accessibility...');
        
        const templateFiles = this.getTemplateFiles();
        
        templateFiles.forEach(file => {
            const content = fs.readFileSync(file, 'utf8');
            
            // Check for skip links
            if (file.includes('header.php') && !content.includes('skip-link')) {
                this.warnings.push('header.php: Missing skip navigation links');
            }
            
            // Check for ARIA landmarks
            const landmarks = ['main', 'nav', 'aside', 'footer'];
            landmarks.forEach(landmark => {
                if (content.includes(`<${landmark}`) || content.includes(`role="${landmark}"`)) {
                    this.info.push(`✓ ARIA landmark found: ${landmark}`);
                }
            });
            
            // Check for alt attributes on images
            if (content.includes('<img') && !content.includes('alt=')) {
                this.warnings.push(`${path.basename(file)}: Images without alt attributes`);
            }
        });
    }

    async validatePerformance() {
        console.log('⚡ Validating performance...');
        
        // Check for minified assets
        const distDir = path.join(this.themeDir, 'assets', 'dist');
        if (fs.existsSync(distDir)) {
            const jsFiles = this.getJSFiles(distDir);
            const cssFiles = this.getCSSFiles(distDir);
            
            jsFiles.forEach(file => {
                const content = fs.readFileSync(file, 'utf8');
                if (content.length > 1000 && content.includes('\n')) {
                    this.warnings.push(`${path.basename(file)}: JavaScript file not minified`);
                } else {
                    this.info.push(`✓ JavaScript file appears minified: ${path.basename(file)}`);
                }
            });
            
            cssFiles.forEach(file => {
                const content = fs.readFileSync(file, 'utf8');
                if (content.length > 1000 && content.includes('\n')) {
                    this.warnings.push(`${path.basename(file)}: CSS file not minified`);
                } else {
                    this.info.push(`✓ CSS file appears minified: ${path.basename(file)}`);
                }
            });
        }
        
        // Check functions.php for performance best practices
        const functionsPath = path.join(this.themeDir, 'functions.php');
        if (fs.existsSync(functionsPath)) {
            const content = fs.readFileSync(functionsPath, 'utf8');
            
            if (!content.includes('wp_enqueue_script')) {
                this.warnings.push('Scripts not properly enqueued');
            }
            
            if (!content.includes('wp_enqueue_style')) {
                this.warnings.push('Styles not properly enqueued');
            }
        }
    }

    getPHPFiles(dir = this.themeDir) {
        const files = [];
        const items = fs.readdirSync(dir);
        
        items.forEach(item => {
            const fullPath = path.join(dir, item);
            const stat = fs.statSync(fullPath);
            
            if (stat.isDirectory() && !item.startsWith('.') && item !== 'node_modules') {
                files.push(...this.getPHPFiles(fullPath));
            } else if (stat.isFile() && item.endsWith('.php')) {
                files.push(fullPath);
            }
        });
        
        return files;
    }

    getTemplateFiles() {
        const templateFiles = [
            'index.php',
            'header.php',
            'footer.php',
            'single.php',
            'page.php',
            'archive.php',
            '404.php',
            'search.php'
        ];
        
        return templateFiles
            .map(file => path.join(this.themeDir, file))
            .filter(file => fs.existsSync(file));
    }

    getJSFiles(dir) {
        const files = [];
        if (!fs.existsSync(dir)) return files;
        
        const items = fs.readdirSync(dir);
        items.forEach(item => {
            const fullPath = path.join(dir, item);
            const stat = fs.statSync(fullPath);
            
            if (stat.isDirectory()) {
                files.push(...this.getJSFiles(fullPath));
            } else if (item.endsWith('.js')) {
                files.push(fullPath);
            }
        });
        
        return files;
    }

    getCSSFiles(dir) {
        const files = [];
        if (!fs.existsSync(dir)) return files;
        
        const items = fs.readdirSync(dir);
        items.forEach(item => {
            const fullPath = path.join(dir, item);
            const stat = fs.statSync(fullPath);
            
            if (stat.isDirectory()) {
                files.push(...this.getCSSFiles(fullPath));
            } else if (item.endsWith('.css')) {
                files.push(fullPath);
            }
        });
        
        return files;
    }

    generateReport() {
        console.log('\n' + '='.repeat(60));
        console.log('📊 AQUALUXE THEME VALIDATION REPORT');
        console.log('='.repeat(60));
        
        console.log(`\n🔴 ERRORS: ${this.errors.length}`);
        this.errors.forEach(error => {
            console.log(`  ❌ ${error}`);
        });
        
        console.log(`\n🟡 WARNINGS: ${this.warnings.length}`);
        this.warnings.forEach(warning => {
            console.log(`  ⚠️  ${warning}`);
        });
        
        console.log(`\n🟢 INFO: ${this.info.length}`);
        this.info.forEach(info => {
            console.log(`  ${info}`);
        });
        
        console.log('\n' + '='.repeat(60));
        
        if (this.errors.length === 0) {
            console.log('✅ VALIDATION PASSED - No critical errors found!');
            if (this.warnings.length > 0) {
                console.log(`⚠️  ${this.warnings.length} warnings to consider`);
            }
        } else {
            console.log(`❌ VALIDATION FAILED - ${this.errors.length} errors need to be fixed`);
            process.exit(1);
        }
        
        // Generate detailed report file
        this.saveReportToFile();
    }

    saveReportToFile() {
        const reportContent = `# AquaLuxe Theme Validation Report
Generated: ${new Date().toISOString()}

## Summary
- Errors: ${this.errors.length}
- Warnings: ${this.warnings.length}
- Info: ${this.info.length}

## Errors
${this.errors.map(error => `- ❌ ${error}`).join('\n')}

## Warnings
${this.warnings.map(warning => `- ⚠️ ${warning}`).join('\n')}

## Information
${this.info.map(info => `- ✅ ${info}`).join('\n')}

## Recommendations

### Critical Issues
${this.errors.length > 0 ? 'Fix all errors before deploying to production.' : 'No critical issues found.'}

### Improvements
${this.warnings.length > 0 ? 'Consider addressing warnings for better code quality.' : 'No improvements needed.'}

### Next Steps
1. Review and fix any errors
2. Consider addressing warnings
3. Run validation again
4. Deploy to staging for testing
`;

        const reportPath = path.join(this.themeDir, 'validation-report.md');
        fs.writeFileSync(reportPath, reportContent);
        console.log(`\n📄 Detailed report saved to: ${reportPath}`);
    }
}

// Run the validator
if (require.main === module) {
    const validator = new ThemeValidator();
    validator.validate();
}

module.exports = ThemeValidator;
